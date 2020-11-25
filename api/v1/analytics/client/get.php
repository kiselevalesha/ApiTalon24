<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    
    

    //  Проверяем наличие подписки
    require_once('../php-scripts/utils/checkSubscription.php');
    if (! checkSubscriptionAndBalance(EnumTypeServices::CLIENTBASE, $idDB)) {
        ExitError(999, "Включите подписку на услугу <b>Функционал по ведению базы клиентов</b> и проверьте, что <b>баланс</b> счёта положителен.");
    }

    //  Проверяем наличие подписки
    require_once('../php-scripts/utils/checkSubscription.php');
    if (! checkSubscriptionAndBalance(EnumTypeServices::ANALYTICS, $idDB)) {
        ExitError(999, "Включите подписку на услугу <b>Аналитика ключевых показателей</b> и проверьте, что <b>баланс</b> счёта положителен.");
    }




    $idClient = GetInt($json->client);
    $start = GetInt($json->start);
    $end = GetInt($json->end);
    
    
    $strJsonClients = "";
    require_once('../php-scripts/db/dbClients.php');
    $dbClient = new DBClient($idDB);
    
    $sqlWhere = "isDeleted=0 AND isNew=0 AND id=".$idClient;
    $arrayClients = $dbClient->GetArrayRows($sqlWhere);
    foreach ($arrayClients as $client) {

        $strJsonAppointments = "";
        require_once('../php-scripts/db/dbAppointments.php');
        $dbAppointment = new DBAppointment($idDB);
        
        $sqlWhere = "(ageOrderStart>=".$start." AND ageOrderStart<=".$end.") AND idClient=".$client->id." AND isDeleted=0 AND isNew=0";
        $arrayAppointments = $dbAppointment->GetArrayRows($sqlWhere);
        foreach ($arrayAppointments as $appointment) {
            if (strlen($strJsonAppointments) > 0)   $strJsonAppointments .= ",";
            //$strJsonAppointments .= '{"r":'.$appointment->intRatingByClient.',"o":'.$appointment->ageOrderStart.'}';
            $strJsonAppointments .= $appointment->ToJson();
        }

        if (strlen($strJsonClients) > 0)   $strJsonClients .= ",";
        $strJsonClients .= '{"s":'.$client->idSex.',"b":'.$client->dateBorn.',"c":'.$client->ageCreated.',"a":['.$strJsonAppointments.']}';

    }

    EndResponsePureData('"clients":['.$strJsonClients.']');
?>