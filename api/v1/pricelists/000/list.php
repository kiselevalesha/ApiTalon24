<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        $dbPricelist = new DBPricelist($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbPricelist->GetJsonRows($sqlWhere, $offset, $maximum);
    }
?>