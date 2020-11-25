<?php
    require_once 'save.php';
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';
    
    require_once '../php-scripts/db/dbGroups.php';
    require_once '../php-scripts/db/dbOwnerGroup.php';
    
    $idEssential = GetInt($request->essential);
    $idOwner = GetInt($request->owner);

    $arrayObjs = SaveGroups($idDB, $request->objects, $idOwner, $idEssential);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    /*require_once '../php-scripts/db/dbGroups.php';
    $dbGroup = new DBGroup($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbGroup->strTableNameInitial);

    require_once('../php-scripts/db/dbOwnerGroup.php');
    $dbOwnerGroup = new DBOwnerGroup($idDB);*/

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::GROUPS, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>