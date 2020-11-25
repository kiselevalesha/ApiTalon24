<?php
    function GetJsonNew() {
        global $idDB;
        require_once('../php-scripts/db/dbTaxes.php');
        $dbTax = new DBTax($idDB);
        $obj = $dbTax->New();
        return $obj->ToJson();
    }
?>