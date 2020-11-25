<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        
        //  Проверяем наличие подписки
        /*require_once('../php-scripts/utils/checkSubscription.php');
        if (! checkSubscriptionAndBalance(EnumTypeServices::CLIENTBASE, $idDB)) {
            ExitError(999, "Включите подписку на услугу <b>Функционал по ведению базы клиентов</b> и проверьте, что <b>баланс</b> счёта положителен.");
        }*/

        require_once('../php-scripts/db/dbClients.php');
        $dbClient = new DBClient($idDB);
        //return $dbClient->GetJsonArray(GetSQLSetOfIds($arrayIds), $offset, $maximum);
        return $dbClient->GetJsonArray(GetSQLSetOfIds($arrayIds), $offset, $maximum);
    }
?>