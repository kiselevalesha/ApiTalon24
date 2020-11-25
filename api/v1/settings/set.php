<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/db/dbSettings.php');
    
    require_once 'save.php';
    $strJsonRows = Save($idDB, $request->objects[0]);
    EndResponseListData("settings", $strJsonRows);
?>