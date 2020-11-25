<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {
        $dbPayment = new DBPayment($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbPayment->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>