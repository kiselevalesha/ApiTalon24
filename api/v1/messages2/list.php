<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        $dbMessage = new DBMessage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbMessage->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>