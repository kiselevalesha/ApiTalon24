<?
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once 'out.php';


    require_once('../php-scripts/db/dbSubscriptions.php');
    $dbSubscription = new DBSubscription($idDB);

    require_once('../php-scripts/db/dbSubscriptionChanges.php');
    $dbSubscriptionChange = new DBSubscriptionChange($idDB);
    
    require_once('../php-scripts/db/dbSubscriptionStates.php');
    $dbSubscriptionState = new DBSubscriptionState($idDB);

    require_once('../php-scripts/db/dbEmployee.php');
    $dbEmployee = new DBEmployee($idDB);
    $idEmployee = $dbEmployee->GetIdDefault();

    foreach ($request->services as $service) {
        $idService = $service->id;
        if ($idService > 0) {
            $dbSubscriptionChange->AddChange($idService, $idEmployee, $service->isSelected);
            $dbSubscriptionState->UpdateState($idService, $idEmployee, $service->isSelected);
        }
    }


    $strJson = LoadSections();
    echo GetOutJson('"sections":['.$strJson.']');
?>