<?php
    function GetJsonList($idDB) {
        $dbProduct = new DBProduct($idDB);

        $sqlWhere = "isDeleted=0 AND idEssential=".EnumEssential::SERVICES;
        return $dbProduct->GetJsonRows($sqlWhere, 0, 0);
    }
?>