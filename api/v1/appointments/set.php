<?php
    require_once 'save.php';
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/dbApi.php';

    require_once '../talon24.ru/api/v1/clients/save.php';

    require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';
    require_once '../talon24.ru/api/v1/adresses/save.php';
    
    require_once '../php-scripts/db/dbAppointments.php';
    require_once '../php-scripts/db/dbClients.php';
    require_once '../php-scripts/db/dbCategories.php';


    $arrayObjs = SaveAppointments($idDB, $request->objects);
    $strJsonRows = GetJsonObjectRows($arrayObjs);
    
    $dbAppointment = new DBAppointment($idDB);
    $strJsonUpdate = GetJsonUpdate($arrayObjs, $dbAppointment->strTableNameInitial);

    $idTypeActionEdit = 0;
    if ($request->action == 'create')   $idTypeAction = EnumTypeActions::ActionCreate;
    elseif ($request->action == 'edit')   $idTypeAction = EnumTypeActions::ActionEdit;
    $dbHistory->Add($idTypeAction, EnumEssential::APPOINTMENTS, $strJsonUpdate);

    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"'.$request->action.'"');
?>