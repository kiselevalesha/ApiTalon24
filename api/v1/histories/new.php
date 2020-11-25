<?php
    function GetJsonNew($idDB) {
        $dbHistory = new DBHistory($idDB);
        
        $history = $dbHistory->New();
        return $history->ToJson();
    }
?>