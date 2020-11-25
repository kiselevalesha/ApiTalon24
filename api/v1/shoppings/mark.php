<?php
    function GetJsonMarkDeleted($idDB, $arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            $dbShopping = new DBShopping($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbShopping->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>