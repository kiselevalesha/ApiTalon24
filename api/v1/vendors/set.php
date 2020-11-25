<?php
    require_once 'save.php';
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    $arrayObjs = SaveVendors($idDB, $request->objects);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    $dbVendor = new DBVendor($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbVendor->strTableNameInitial);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::VENDORS, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>