<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/db/dbAppointments.php');

    require_once 'mark.php';
    $strJsonRows = GetJsonMarkDeleted($idDB, $request->ids);
    EndResponseListData("deleted", $strJsonRows);
?>