<?php
    require_once 'save.php';
    chdir('../../..');

    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    $strJsonRows = SaveMessages($idDB, $request);
    //$arrayObjs = SaveMessages($idDB, $request);    //  $request->objects
    //$strJsonRows = GetJsonObjectRows($arrayObjs);

    //$dbMessage = new DBMessage($idDB);
    //$strJsonUpdate = GetJsonUpdate($arrayObjs, $dbMessage->strTableNameInitial);
    $strJsonUpdate = "";

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::MESSAGES, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>