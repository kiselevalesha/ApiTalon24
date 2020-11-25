<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbProducts.php');
    //require_once('../php-scripts/db/dbPricelists.php');
    //require_once('../php-scripts/db/dbPricelistContents.php');

    $dbProduct = new DBProduct($idDB);

    if (IsRequestForNewObject($request)) {
        EndResponseListObjects(GetJsonNew($dbProduct, EnumEssential::SERVICES));
    }
    else {
        $arrayIds = GetArrayIds($request->objects);
        EndResponseListObjects(GetJsonListRows($arrayIds, $dbProduct, "isDeleted=0", GetLimitOffset($request), GetLimitMaximum($request)));
    }
?>