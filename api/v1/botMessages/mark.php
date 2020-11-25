<?php
    function GetJsonMarkDeleted($idDB, $arrayIds) {
        $strJsonRows = "";
        if (isSet($arrayIds)) {
            $dbBotMessage = new DBBotMessage($idDB);
            $comma = "";
            foreach($arrayIds as $idDelete) {
                $id = GetInt($idDelete);
                if ($id > 0) {
                    $dbBotMessage->Delete($id);
                    $strJsonRows .= $comma . $id;
                    $comma = ",";
                }
            }
        }
        return $strJsonRows;
    }
?>