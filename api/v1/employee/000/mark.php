<?php
    function GetJsonMarkDeleted($arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            global $idDB;
            require_once('../php-scripts/db/dbClients.php');
            $dbClient = new DBClient($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbClient->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>