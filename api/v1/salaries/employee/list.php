<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        //  Проверяем наличие подписки
        require_once('../php-scripts/utils/checkSubscription.php');
        if (! checkSubscriptionAndBalance(EnumTypeServices::CLIENTBASE, $idDB)) {
            ExitError(999, "Включите подписку на услугу <b>Функционал по ведению базы клиентов</b> и проверьте, что <b>баланс</b> счёта положителен.");
        }

        $dbClient = new DBClient($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbClient->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>