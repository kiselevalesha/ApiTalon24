<?
    require_once('../php-scripts/db/dbSubscriptions.php');
    require_once('../php-scripts/db/dbSubscriptionChanges.php');
    require_once('../php-scripts/db/dbSubscriptionStates.php');
    require_once('../php-scripts/db/dbSubscriptionPayments.php');
    require_once('../php-scripts/db/dbSubscriptionWaste.php');

    function CalcCostSubscriptionsForOneToken($year, $month, $day, $token) {
        $dbSubscriptionWaste = new dbSubscriptionWaste($token->id);
        $ageDay = $year * 10000 + $month * 100 + $day;
        
        $dbSubscription = new DBSubscription();
        if ($dbSubscription->GetCountRows("") == 0)     $dbSubscription->AddServices();

        //  Начисляем плату за все включенные подписки
        $strWhere = "idState=1";
        $dbSubscriptionState = new DBSubscriptionState($token->id);
        $arrayStates = $dbSubscriptionState->GetArrayRows($strWhere);
        foreach ($arrayStates as $state) {
            $idWhat = $dbSubscription->GetIntField("idTypeWhat", "idService=".$state->idService);
            if ($idWhat == EnumSubscriptionWhat::DAY) {     //  Только те услуги, где тарификация за день !
                $cost = $dbSubscription->GetIntField("cost", "idService=".$state->idService);
                $dbSubscriptionWaste->Update($ageDay, $state->idService, $cost);
            }
        }
        

        //  Начисляем плату за все включаемые в течении дня подписки
        //  Это может дублировать включенные подписки в DBSubscriptionState, но ничего страшного
        $strWhere = "idState=1 AND ageDay=".$ageDay;
        $dbSubscriptionChange = new DBSubscriptionChange($token->id);
        $arrayChanges = $dbSubscriptionChange->GetArrayRows($strWhere);
        foreach ($arrayChanges as $change) {
            $cost = $dbSubscription->GetIntField("cost", "idService=".$change->idService);
            $dbSubscriptionWaste->Update($ageDay, $state->idService, $cost);
        }
        
        
        $dbTokenEmployee = new DBTokenEmployee();


        //  Вычисляем общую сумму всех платежей
        $totalPayments = 0;
        $dbSubscriptionPayment = new DBSubscriptionPayment($token->id);
        $strWhere = "isDeleted=0";
        $arrayPayments = $dbSubscriptionPayment->GetArrayRows($strWhere);
        foreach ($arrayPayments as $payment) {
            $totalPayments += $payment->summaPayment + $payment->summaBonus;
        }
        $dbTokenEmployee->UpdateField("summaTotalPayments", $totalPayments, "id=".$token->id);

        
        //  Вычисляем общую сумму всех расходов
        $totalWastes = 0;
        $strWhere = "";
        $arrayWastes = $dbSubscriptionWaste->GetArrayRows($strWhere);
        foreach ($arrayWastes as $waste) {
            $totalWastes += $waste->cost;
        }
        $dbTokenEmployee->UpdateField("summaTotalWastes", $totalWastes, "id=".$token->id);

    
        $strJson = '"token="'.$token->strToken.'", "payments":'.$totalPayments.', "wastes":'.$totalWastes;
        return $strJson;
    }
?>