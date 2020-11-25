<?php
    function GetJsonNew($idDB) {
        $dbClient = new DBClient($idDB);
        
        $client = $dbClient->New();
        return $client->ToJson();
    }
?>