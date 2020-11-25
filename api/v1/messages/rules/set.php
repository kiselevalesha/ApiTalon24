<?php
    require_once 'save.php';
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../php-scripts/db/dbMessageRules.php';
    require_once '../php-scripts/db/dbCategories.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';

    $arrayObjs = SaveMessageRules($idDB, $request->objects);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    $dbMessageRule = new DBMessageRule($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbMessageRule->strTableNameInitial);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::MESSAGERULES, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>