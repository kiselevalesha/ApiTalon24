<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    
    require_once 'save.php';
    $strJsonRows = SaveAll($request->services);

    require_once 'list.php';
    EndResponseListData("objects", GetJsonList());
?>