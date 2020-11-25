<?php
    function GetJsonNew($idDB) {
        $dbProduct = new DBProduct($idDB);
        $obj = $dbProduct->New(EnumEssential::SERVICES);
        return $obj->ToJson();
    }
?>