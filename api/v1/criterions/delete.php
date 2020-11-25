<?php
    require_once 'mark.php';
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbCriterions.php');

    $idEssential = EnumEssential::CRITERIONS;
    $strGlobalJsonReturnRows = "";
    $strGlobalJsonCommon = "";

    $arrayIdDeletes = GetArrayIds($request->ids);

    MarkCriterionsDeleted($idDB, $arrayIdDeletes);

    $dbHistory->Add(EnumTypeActions::ActionDelete, $idEssential, $strGlobalJsonCommon);

    EndResponsePureData('"objects":['.$strGlobalJsonReturnRows.'],"action":"delete"');
?>