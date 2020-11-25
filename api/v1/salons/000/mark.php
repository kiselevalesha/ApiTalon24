<?php
    function GetJsonMarkDeleted($arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            global $idDB;
            require_once('../php-scripts/db/dbSalons.php');
            $dbSalon = new DBSalon($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbSalon->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>