<?php
    require_once 'new.php';
    require_once 'list.php';
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbCategories.php';
    $dbCategory = new DBCategory($idDB);
    
    $idEssential = GetInt($request->essential);
    if (empty($idEssential))    $idEssential = 0;
    $strJsonCategories = $dbCategory->GetJsonCategories($idEssential);

    if (IsRequestForNewObject($request)) {
        $str = '"objects":['.GetJsonNewObject($idEssential).']';
        if (! empty($strJsonCategories))
            $str .= ',"categories":['.$strJsonCategories.']';
        EndResponsePureData($str);
    }
    else {
        $arrayIds = GetArrayIds($request->ids);
        $str = '"objects":['.GetJsonList($arrayIds, $idEssential, GetLimitOffset($request), GetLimitMaximum($request)).']';
        if (! empty($strJsonCategories))
            $str .= ',"categories":['.$strJsonCategories.']';
        EndResponsePureData($str);
    }
?>
