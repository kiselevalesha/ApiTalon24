<?php
    function GetJsonNew($idDB) {
        $dbMessage = new DBMessage($idDB);
        
        //$message = $dbMessage->New();
        $message = new Message();
        return $message->ToJson();
    }
?>