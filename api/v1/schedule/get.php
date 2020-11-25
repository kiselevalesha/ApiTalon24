<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/models/essential.php');

    $flagCreateNew = false;
    if (isSet($request->ids))
        if (sizeOf($request->ids) == 1)
            if ($request->ids[0] == 0)
                $flagCreateNew = true;


    $age = $request->age;
    if (empty($age))    $age = 0;
    $idEssentialOwner = $request->essential;
    if (empty($idEssentialOwner))    $idEssentialOwner = 0;
    $idOwner = $request->owner;
    if (empty($idOwner))    $idOwner = 0;
    $idSalon = $request->salon;
    if (empty($idSalon))    $idSalon = 0;
    $idPlace = $request->place;
    if (empty($idPlace))    $idPlace = 0;


    if ($flagCreateNew) {
        require_once 'new.php';
        EndResponseListData("schedule", GetJsonNew($age, $idEssentialOwner, $idOwner, $idSalon, $idPlace));
    }
    else {
        $offset = 0;
        $maximum = 0;
        if (isSet($request->limit)) {
            if (isSet($request->limit->offset)) $offset = GetInt($request->limit->offset);
            if (isSet($request->limit->maximum)) $maximum = GetInt($request->limit->maximum);
        }
        
        require_once 'list.php';
        EndResponseListData("schedule", GetJsonList($request->ids, $offset, $maximum));
    }
?>