<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        require_once('../php-scripts/db/dbSchedule.php');
        $dbSchedule = new DBSchedule($idDB);
        return $dbSchedule->GetJsonRows(GetSQLSetOfIds($arrayIds, "isDeleted=0"), $offset, $maximum);
    }
?>