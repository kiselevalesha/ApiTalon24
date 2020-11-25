<?php
    function GetJsonNew($idDB) {
        $dbBotMessage = new DBBotMessage($idDB);
        
        $botMessage = $dbBotMessage->New();
        return $botMessage->ToJson();
    }
?>