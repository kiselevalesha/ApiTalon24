<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbPricelist = new DBPricelist($idDB);
        $obj = $dbPricelist->New();
        return '{'.$obj->MakeJson().'}';
    }
?>