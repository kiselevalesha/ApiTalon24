<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/db/dbPayments.php');
    
    require_once 'save.php';
    $strJsonRows = SaveAll($idDB, $request->objects);
    EndResponseListData("objects", $strJsonRows);
?>