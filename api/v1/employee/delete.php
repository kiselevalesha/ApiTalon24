<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once('../php-scripts/db/dbEmployee.php');

    $idEssential = EnumEssential::EMPLOYEE;
    $strGlobalJsonReturnRows = "";
    $strGlobalJsonCommon = "";

    $arrayIdDeletes = GetArrayIds($request->objects);

    require_once 'mark.php';
    MarkEmployeeDeleted($idDB, $arrayIdDeletes);

    $dbHistory->Add(EnumTypeActions::ActionDelete, $idEssential, $strGlobalJsonCommon);

    EndResponsePureData('"objects":['.$strGlobalJsonReturnRows.'],"action":"delete"');
?>