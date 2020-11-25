<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/db/dbSettings.php');

    require_once 'one.php';
    EndResponsePureData(GetJsonOne($idDB));
?>