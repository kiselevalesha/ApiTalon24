<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';


    switch ($_SERVER['REQUEST_METHOD']) {
        case "OPTIONS":
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
            header("Content-Length: 0");
            exit(0);
            break;
        case "GET":
        case "POST":
            header('Access-Control-Allow-Origin: *');
            break;
    }
    header('Content-Type: application/json; charset="UTF-8"');


    $body = file_get_contents('php://input');
    $request = json_decode($body, false, 32);
    

        

    $flagOne = false;
    if (isSet($request->ids))
        if (sizeOf($request->ids) == 1)
            if ($request->ids[0] == 0)
                $flagOne = true;


    if (!isSet($request->token)) {
        require_once 'one.php';
        EndResponsePureData(GetJsonOne($request));
    }
    else {
        $offset = 0;
        $maximum = 0;
        if (isSet($request->limit)) {
            if (isSet($request->limit->offset)) $offset = GetInt($request->limit->offset);
            if (isSet($request->limit->maximum)) $maximum = GetInt($request->limit->maximum);
        }

        require_once 'list.php';
        EndResponseListData("tokens", GetJsonList($request->ids, $offset, $maximum));
    }
?>