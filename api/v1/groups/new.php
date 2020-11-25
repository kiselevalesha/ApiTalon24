<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbGroup = new DBGroup($idDB);
        $obj = $dbGroup->New();
        return '{'.$obj->MakeJson().'}';
    }
?>