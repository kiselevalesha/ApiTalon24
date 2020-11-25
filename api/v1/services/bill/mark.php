<?php
    function GetJsonMarkDeleted($idDB, $arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
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