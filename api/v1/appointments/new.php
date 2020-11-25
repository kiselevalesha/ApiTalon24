<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbAppointment = new DBAppointment($idDB);
        $obj = $dbAppointment->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>