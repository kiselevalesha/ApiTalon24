<?php
    function GetJsonNewObject() {
        global $idDB;
        require_once('../php-scripts/db/dbCriterions.php');
        $dbCriterion = new DBCriterion($idDB);
        $obj = $dbCriterion->New();
        return $obj->ToJson();
    }
?>
