<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        $dbProduct = new DBProduct($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0 AND idEssential=".EnumEssential::PRODUCTS;
        return $dbProduct->GetJsonRows($sqlWhere, $offset, $maximum);
    }
?>