<?php
    function GetJsonNewObject() {
        global $idDB;
        $dbProduct = new DBProduct($idDB);
        $obj = $dbProduct->New(EnumEssential::PRODUCTS);
        return $obj->ToJson();
    }
?>