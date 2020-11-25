<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbVendor = new DBVendor($idDB);
        $obj = $dbVendor->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>