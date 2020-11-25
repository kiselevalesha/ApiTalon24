<?php
    function GetJsonMarkDeleted($arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            global $idDB;
            require_once('../php-scripts/db/dbCategories.php');
            $dbCategory = new DBCategory($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbCategory->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>