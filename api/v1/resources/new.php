<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbResource = new DBResource($idDB);
        $obj = $dbResource->New();
        return $obj->ToJson();
    }
?>