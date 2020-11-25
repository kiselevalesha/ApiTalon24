<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        //  Проверяем наличие подписки
        /*require_once('../php-scripts/utils/checkSubscription.php');
        if (! checkSubscriptionAndBalance(EnumTypeServices::PHOTOHOSTING, $idDB)) {
            ExitError(999, "Включите подписку на услугу <b>Функционал по хранению фотоматериалов</b> и проверьте, что <b>баланс</b> счёта положителен.");
        }*/

        $dbImage = new DBImage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbImage->GetJsonArray($sqlWhere, $offset, $maximum, "ageCreated DESC");
    }
?>