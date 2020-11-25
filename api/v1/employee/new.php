<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbEmployee = new DBEmployee($idDB);
        $obj = $dbEmployee->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>