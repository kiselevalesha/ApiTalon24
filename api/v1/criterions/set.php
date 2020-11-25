<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbCriterions.php');

    require_once 'save.php';
    $strJsonRows = SaveAll($idDB, $request->objects);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::CRITERIONS, $strGlobalJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>