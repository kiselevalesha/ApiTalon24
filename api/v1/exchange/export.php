<?php
    chdir('../../..');
    require_once '../php-scripts/utils/api.php';
    require_once '../php-scripts/utils/exchange.php';

    echo GetOutJson('"url":"'.ExportDB($idDB).'"');
?>