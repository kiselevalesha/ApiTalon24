<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbSalon = new DBSalon($idDB);
        $obj = $dbSalon->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>