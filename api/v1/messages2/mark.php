<?php
    function GetJsonMarkDeleted($idDB, $arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            $dbMessage = new DBMessage($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbMessage->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>