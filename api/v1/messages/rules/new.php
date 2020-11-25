<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbMessageRule = new DBMessageRule($idDB);
        $obj = $dbMessageRule->New();
        return '{'.$obj->MakeJson().'}';
    }
?>