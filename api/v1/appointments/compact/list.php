<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        $dbAppointment = new DBAppointment($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";

        return $dbAppointment->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>