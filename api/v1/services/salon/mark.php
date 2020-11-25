<?php
    function GetJsonMarkDeleted($arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            global $idDB;
            require_once('../php-scripts/db/dbProducts.php');
            $dbProduct = new DBProduct($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbProduct->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>