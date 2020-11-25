<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {
        $dbTotalPayment = new DBTotalPayment($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbTotalPayment->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>