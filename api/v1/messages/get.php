<?php

    require_once 'new.php';
    require_once 'list.php';
    chdir('../../..');

    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbMessageRules.php';
    require_once '../php-scripts/db/dbSupportTalks.php';
    require_once '../php-scripts/db/dbCodeAppointments.php';
    require_once '../php-scripts/db/dbTalks.php';
    require_once '../php-scripts/db/dbMessages.php';

    if (IsRequestForNewObject($request)) {
        EndResponseListObjects(GetJsonNewObject());
    }
    else {
        EndResponseListObjects(GetJsonList($idDB, $request));
    }
?>