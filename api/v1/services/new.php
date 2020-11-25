<?php
    function GetJsonNew() {
        global $idDB;
        $dbProduct = new DBProduct($idDB);
        $obj = $dbProduct->New(EnumEssential::SERVICES);
        return $obj->ToJson();
    }
?>