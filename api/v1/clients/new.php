<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbClient = new DBClient($idDB);
        $obj = $dbClient->New();
        return '{'.$obj->MakeJson().',"contacts":[],"adresses":[]}';
    }
?>