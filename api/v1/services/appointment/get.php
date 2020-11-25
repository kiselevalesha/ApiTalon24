<?php
    require_once 'list.php';
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbGroups.php';
    require_once('../php-scripts/db/dbOwnerGroup.php');

    $idEssential = GetInt($request->essential);
    $idOwner = GetInt($request->owner);
    $idType = GetInt($request->type);

    $str = '"objects":['.GetJsonList($idDB, $idEssential, $idOwner, $idType, GetLimitOffset($request), GetLimitMaximum($request)).']';
    EndResponsePureData($str);
?>
