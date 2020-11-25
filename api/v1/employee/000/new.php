<?php
    function GetJsonNew() {
        global $idDB;
        require_once('../php-scripts/db/dbClients.php');
        $dbClient = new DBClient($idDB);
        
        $client = $dbClient->New();
        return $client->ToJson();
    }
?>