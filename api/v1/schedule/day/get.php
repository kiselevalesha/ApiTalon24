<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/utils.php';

    require_once 'one.php';
    EndResponseListData("days", GetJsonOne($idDB));
?>