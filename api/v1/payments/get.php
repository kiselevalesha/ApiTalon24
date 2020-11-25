<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/db/dbPayments.php');

    if (IsRequestForNewObject($request)) {
        require_once 'new.php';
        EndResponseListData("objects", GetJsonNew($idDB));
    }
    else {
        $offset = 0;
        $maximum = 0;
        if (isSet($request->limit)) {
            if (isSet($request->limit->offset)) $offset = GetInt($request->limit->offset);
            if (isSet($request->limit->maximum)) $maximum = GetInt($request->limit->maximum);
        }
        
        require_once 'list.php';
        EndResponseListData("objects", GetJsonList($idDB, $request->ids, $offset, $maximum));
    }
?>