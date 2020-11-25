<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbMessage = new DBMessage($idDB);
        $obj = $dbMessage->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>