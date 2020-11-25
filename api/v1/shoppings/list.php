<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        $dbShopping = new DBShopping($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbShopping->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>