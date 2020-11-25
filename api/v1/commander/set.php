<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/getUrlFile.php';
    
    $object = $request->object;
    $action = GetCleanString($request->action);
    $what = GetCleanString($request->what);
    $strJson = "";
    
    if ($action == 'cancel') {
        require_once 'api/v1/commander/cancel.php';
    }
    else
        switch($what) {
            case "appointment":
                require_once 'api/v1/commander/details/appointments.php';
                break;
            case "client":
                require_once 'api/v1/commander/details/clients.php';
                break;
            case "employee":
                require_once 'api/v1/commander/details/employee.php';
                break;
            case "service":
                require_once 'api/v1/commander/details/services.php';
                break;
            case "salon":
                require_once 'api/v1/commander/details/salons.php';
                break;
            default:
                ExitError(151, "Not understand of What-clause.");
        }

    //EndResponsePureData($strJson);
    echo $strJson;
?>