<?php
    require_once 'save.php';
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    $arrayObjs = SaveEmployees($idDB, $request->objects);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    $dbEmployee = new DBEmployee($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbEmployee->strTableNameInitial);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::EMPLOYEE, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>