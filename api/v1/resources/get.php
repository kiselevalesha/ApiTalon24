<?php
    require_once 'new.php';
    require_once 'list.php';
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbResources.php';
    require_once '../php-scripts/db/dbAdresses.php';
    require_once '../php-scripts/db/dbImages.php';

    if (IsRequestForNewObject($request)) {
        EndResponseListObjects(GetJsonNewObject());
    }
    else {
        $arrayIds = GetArrayIds($request->ids);
        EndResponseListObjects(GetJsonList($arrayIds, GetLimitOffset($request), GetLimitMaximum($request)));
    }
?>