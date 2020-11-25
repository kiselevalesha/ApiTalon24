<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once 'out.php';


    require_once('../php-scripts/db/dbSubscriptions.php');
    $dbSubscription = new DBSubscription();
    if ($dbSubscription->GetCountRows("") == 0)     $dbSubscription->AddServices();

    
    require_once('../php-scripts/db/dbSubscriptionChanges.php');
    $dbSubscriptionChange = new DBSubscriptionChange($idDB);
    
    require_once('../php-scripts/db/dbSubscriptionStates.php');
    $dbSubscriptionState = new DBSubscriptionState($idDB);


    require_once 'api/v1/subscriptions/balance.php';
    $strJsonBalance = GetJsonBalance($idDB);


    require_once('../php-scripts/db/dbEmployee.php');
    $dbEmployee = new DBEmployee($idDB);
    $idEmployee = $dbEmployee->GetIdDefault();      //  $idEmployee нужен для LoadSections()
    
    $strJson = LoadSections();
    echo GetOutJson('"sections":['.$strJson.'],'.$strJsonBalance);
    
?>