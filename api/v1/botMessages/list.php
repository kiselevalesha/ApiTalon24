<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        $dbBotMessage = new DBBotMessage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbBotMessage->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>