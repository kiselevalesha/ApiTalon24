<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbReservationTime = new DBReservationTime($idDB);
        $obj = $dbReservationTime->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>