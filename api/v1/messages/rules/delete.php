<?php
    require_once 'mark.php';
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbMessageRules.php');

    $idEssential = EnumEssential::MESSAGERULES;
    $strGlobalJsonReturnRows = "";
    $strGlobalJsonCommon = "";

    $arrayIdDeletes = GetArrayIds($request->ids);

    MarkMessageRulesDeleted($idDB, $arrayIdDeletes);

    $dbHistory->Add(EnumTypeActions::ActionDelete, $idEssential, $strGlobalJsonCommon);

    EndResponsePureData('"objects":['.$strGlobalJsonReturnRows.'],"action":"delete"');
?>