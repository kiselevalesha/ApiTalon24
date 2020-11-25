<?php
    require_once 'new.php';
    require_once 'list.php';
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbGroups.php';
    $dbGroup = new DBGroup($idDB);
    
    $idEssential = GetInt($request->essential);

    if (IsRequestForNewObject($request)) {
        $str = '"objects":['.GetJsonNewObject().']';
        EndResponsePureData($str);
    }
    else {
        $arrayIds = GetArrayIds($request->ids);
        $str = '"objects":['.GetJsonList($arrayIds, GetLimitOffset($request), GetLimitMaximum($request)).']';
        EndResponsePureData($str);
    }
?>
