<?php
    function GetJsonMarkDeleted($arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            global $idDB;
            require_once('../php-scripts/db/dbResources.php');
            $dbResource = new DBResource($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbResource->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>