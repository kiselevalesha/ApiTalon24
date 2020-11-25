<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    
    require_once 'mark.php';
    $strJsonRows = GetJsonMarkDeleted($request->ids);
    EndResponseListData("deleted", $strJsonRows);
?>