<?php
    function GetJsonMarkDeleted($idDB, $arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            $dbMessageTemplate = new DBMessageTemplate($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbMessageTemplate->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>