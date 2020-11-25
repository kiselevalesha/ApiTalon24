<?php
    require_once('../php-scripts/db/dbProducts.php');
    require_once('../php-scripts/models/essential.php');

    function GetJsonList() {
        global $idDB;
        $dbProduct = new DBProduct($idDB);

        $sqlWhere = "isDeleted=0 AND idEssential=".EnumEssential::SERVICES;
        return $dbProduct->GetJsonRows($sqlWhere, 0, 0);
    }
?>