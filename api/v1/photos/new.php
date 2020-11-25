<?php
    function GetJsonNew($idDB) {
        $dbImage = new DBImage($idDB);
        
        $photo = $dbImage->New();
        return $photo->ToJson();
    }
?>