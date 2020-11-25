<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbProducts.php');
    require_once('../php-scripts/db/dbPricelists.php');
    require_once('../php-scripts/db/dbPricelistContents.php');

    require_once 'save.php';
    $strJsonRows = SaveAll($idDB, $request->objects);

    $dbHistory->Add($idTypeAction, EnumEssential::SERVICES, $strGlobalJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>