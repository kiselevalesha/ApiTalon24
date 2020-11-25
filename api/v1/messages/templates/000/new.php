<?php
    function GetJsonNew($idDB) {
        //$dbMessageTemplate = new DBMessageTemplate($idDB);
        //$message = $dbMessageTemplate->New();
        
        $message = new Message();
        return $message->ToJson();
    }
?>