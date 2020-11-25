<?php
    function GetJsonNew() {
        global $idDB;
        $dbPricelist = new DBPricelist($idDB);
        $obj = $dbPricelist->New();
        return $obj->ToJson();
    }
?>