<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        require_once('../php-scripts/db/dbTaxes.php');
        $dbTax = new DBTax($idDB);
        
        $strWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($strWhere) > 0)  $strWhere .= " AND ";
        $strWhere .= "isDeleted=0 AND isNew=0";
        return $dbTax->GetJsonRows($strWhere, $offset, $maximum);
    }
?>