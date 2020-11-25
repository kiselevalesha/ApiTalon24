<?php
    function GetJsonNewObject($idEssential=0) {
        global $idDB;
        $dbCategory = new DBCategory($idDB);
        $obj = $dbCategory->New();
        $obj->idEssential = $idEssential;
        return '{'.$obj->MakeJson().'}';
    }
?>