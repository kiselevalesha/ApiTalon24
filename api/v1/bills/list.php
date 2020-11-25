<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {
        $dbBill = new DBBill($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbBill->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>