<?php
    function GetJsonNew($idDB) {
        $dbShopping = new DBShopping($idDB);
        $shopping = $dbShopping->New();
        return $shopping->ToJson();
    }
?>