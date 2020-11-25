<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {

        $dbMessageTemplate = new DBMessageTemplate($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";
        return $dbMessageTemplate->GetJsonArray($sqlWhere, $offset, $maximum);
    }
?>