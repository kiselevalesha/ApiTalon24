<?php
    require_once 'save.php';
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    $arrayObjs = SavePricelists($idDB, $request->objects);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    $dbPricelist = new DBPricelist($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbPricelist->strTableNameInitial);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::PRICELISTS, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>