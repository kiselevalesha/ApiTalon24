<?php
    function GetJsonList($idDB, $arrayIds, $offset, $maximum) {
        $dbHistory = new DBHistory($idDB);
        return $dbHistory->GetJsonArray("", $offset, $maximum, "id DESC");
    }
?>