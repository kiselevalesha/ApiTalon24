<?php
    switch($action) {
        case "create":
            $strJson = getUrlFile("https://talon24.ru/api/v1/services/pricelist/set.php", json_encode($request));
            break;
        case "edit":
            $strJson = getUrlFile("https://talon24.ru/api/v1/services/pricelist/set.php", json_encode($request));
            break;
        case "delete":
            $strJson = getUrlFile("https://talon24.ru/api/v1/services/pricelist/delete.php", json_encode($request));
            break;
        default:
            ExitError(152, "Not understand of Action-clause.");
    }
?>