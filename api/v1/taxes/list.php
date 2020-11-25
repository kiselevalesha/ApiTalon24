<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        require_once('../php-scripts/db/dbTaxes.php');
        $dbTax = new DBTax($idDB);
        return $dbTax->GetJsonRows(GetSQLSetOfIds($arrayIds), $offset, $maximum);
    }
?>