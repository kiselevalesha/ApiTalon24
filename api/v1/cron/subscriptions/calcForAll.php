<?
    require_once('../php-scripts/db/dbTokenEmployee.php');
    
    function CalcCostSubscriptionsForAllTokens($year, $month, $day) {
        global $idDB;
        $dbTokenEmployee = new DBTokenEmployee();
        $strWhere = "isTokenActive=1";
        $arrayTokens = $dbTokenEmployee->GetArrayRows($strWhere);
        $strBody = "";
        foreach ($arrayTokens as $token) {
            
            //  Проверим, что текущий баланс у токена положительный
            $summaTotalPayments = $dbTokenEmployee->GetIntField("summaTotalPayments", "id=".$token->id);
            $summaTotalWastes = $dbTokenEmployee->GetIntField("summaTotalWastes", "id=".$token->id);
            $balance = $summaTotalPayments - $summaTotalWastes;
            if ($balance > 0) {
                $strJson = CalcCostSubscriptionsForOneToken($year, $month, $day, $token);
                echo "<br>".$strJson;
                $strBody .= '<br>'.$strJson;
            }
        }
        return $strBody;
    }
?>