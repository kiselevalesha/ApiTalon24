<?php
    function GetJsonNew() {
        global $idDB;
        require_once('../php-scripts/db/dbProducts.php');
        $dbProduct = new DBProduct($idDB);
        $obj = $dbProduct->New(EnumEssential::SERVICES);
        return $obj->ToJson();
    }
?>