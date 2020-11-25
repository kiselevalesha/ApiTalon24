<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';

    $flagCreateNew = false;
    if (isSet($request->ids))
        if (sizeOf($request->ids) == 1)
            if ($request->ids[0] == 0)
                $flagCreateNew = true;


    if ($flagCreateNew) {
        require_once 'new.php';
        EndResponseListData("taxes", GetJsonNew());
    }
    else {
        $offset = 0;
        $maximum = 0;
        if (isSet($request->limit)) {
            if (isSet($request->limit->offset)) $offset = GetInt($request->limit->offset);
            if (isSet($request->limit->maximum)) $maximum = GetInt($request->limit->maximum);
        }
        
        require_once 'list.php';
        EndResponseListData("taxes", GetJsonList($request->ids, $offset, $maximum));
    }
?>