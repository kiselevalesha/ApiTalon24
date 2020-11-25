<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once('../php-scripts/models/essential.php');
    
    

    //  Проверяем наличие подписки
    require_once('../php-scripts/utils/checkSubscription.php');
    if (! checkSubscriptionAndBalance(EnumTypeServices::ANALYTICS, $idDB)) {
        ExitError(999, "Включите подписку на услугу <b>Аналитика ключевых показателей</b> и проверьте, что <b>баланс</b> счёта положителен.");
    }




    $start = GetInt($json->start);
    $end = GetInt($json->end);
    $idService = GetInt($json->service);
    
    $startDate = substr($start, 0, 8);
    $endDate = substr($end, 0, 8);



    //  Найти settings
    require_once('../php-scripts/db/dbSettings.php');
    $dbSettings = new DBSettings($idDB);
    $settings = $dbSettings->GetDefault();
    $strJsonSettings .= '{"start":'.$settings->intTimeStart.',"end":'.$settings->intTimeEnd.'}';



    $strJsonAppointments = "";
    require_once('../php-scripts/db/dbAppointments.php');
    $dbAppointment = new DBAppointment($idDB);
    
    require_once('../php-scripts/db/dbProductsAppointments.php');
    $dbProductsAppointment = new DBProductsAppointment($idDB, EnumProductRelationTables::NameTableOrder);
    
    $sqlWhere = "(ageOrderStart>=".$start." AND ageOrderStart<=".$end.") AND isDeleted=0 AND isNew=0";    //  и не отменён!!! проверять статус, что выполнен!!!
    $arrayAppointments = $dbAppointment->GetArrayRows($sqlWhere);
    
    foreach ($arrayAppointments as $appointment) {
        
        $strJsonServices = "";
        $strComma = "";
        $sqlWhere = "idOwner=".$appointment->id." AND isDeleted=0";
        $arrayServices = $dbProductsAppointment->GetArrayRows($sqlWhere);
        foreach ($arrayServices as $service) {
            $strJsonServices .= $strComma.$service->ToJson();
            $strComma = ",";
        }


        if (strlen($strJsonAppointments) > 0)   $strJsonAppointments .= ",";
        
        $strJsonAppointments .= 
        '{"m1":'.$appointment->idMaster1.',"sm1":'.$appointment->summaMaster1.
        ',"m2":'.$appointment->idMaster2.',"sm2":'.$appointment->summaMaster2.
        ',"a1":'.$appointment->idAssistent1.',"sa1":'.$appointment->summaAssistent1.
        ',"a2":'.$appointment->idAssistent2.',"sa2":'.$appointment->summaAssistent2.
        ',"r":'.$appointment->intRatingByClient.',"o":'.$appointment->ageOrderStart.',"c":'.$appointment->idEssentialCreator.
        ',"m":'.$appointment->costOrder.',"d":'.$appointment->intMinutesDuration.
        ',"p":'.$appointment->idDepartment.',"n":'.$appointment->idSalon.',"l":'.$appointment->idClient.
        ',"s":['.$strJsonServices.']}';
    }


   
    $strJsonSchedule = "";
    require_once('../php-scripts/db/dbShedule.php');
    $dbShedule = new DBShedule($idDB);
    
    $strJsonEmployees = "";
    require_once('../php-scripts/db/dbEmployee.php');
    $dbEmployee = new DBEmployee($idDB);

    $sqlWhere = "isDeleted=0 AND isNew=0 AND isUse=1";
    $arrayEmployees = $dbEmployee->GetArrayRows($sqlWhere);
    
    foreach ($arrayEmployees as $employee) {
        if (strlen($strJsonEmployees) > 0)   $strJsonEmployees .= ",";
        $strJsonEmployees .= '{"id":'.$employee->id.',"i":"'.$employee->strName.'","o":"'.$employee->strPatronymic.'","f":"'.$employee->strSurname.'"}';

        $strWhere = "idOwner=".$employee->id." AND idEssentialOwner=".EnumEssential::EMPLOYEE.
                    " AND age>=".$startDate." AND age<=".$endDate." AND isDeleted=0";
        $arrayShedules = $dbShedule->GetArrayOrderRows($strWhere, "age");
    
        foreach ($arrayShedules as $shedule) {
            if (strlen($strJsonSchedule) > 0)   $strJsonSchedule .= ",";
            $strJsonSchedule .= '{'.$shedule->MakeMiniJson().'}';
        }
    }



    $strJsonClients = "";
    require_once('../php-scripts/db/dbClients.php');
    $dbClient = new DBClient($idDB);

    $sqlWhere = "isDeleted=0 AND isNew=0";
    $arrayClients = $dbClient->GetArrayRows($sqlWhere);
    foreach ($arrayClients as $client) {
        if (strlen($strJsonClients) > 0)   $strJsonClients .= ",";
        $strJsonClients .= '{"id":'.$client->id.',"s":'.$client->idSex.',"b":'.$client->dateBorn.',"f":'.$client->ageFirstVisit.',"l":'.$client->ageLastVisit.'}';
    }




    $strJsonServices = "";
    require_once('../php-scripts/db/dbProducts.php');
    $dbService = new DBProduct($idDB);

    $sqlWhere = "isDeleted=0 AND isNew=0 AND idEssential=".EnumEssential::SERVICES;
    $arrayServices = $dbService->GetArrayRows($sqlWhere);
    foreach ($arrayServices as $service) {
        if (strlen($strJsonServices) > 0)   $strJsonServices .= ",";
        $strJsonServices .= '{"i":'.$service->id.',"n":"'.$service->strName.'"}';
    }




    $strJsonSalons = "";
    require_once('../php-scripts/db/dbSalons.php');
    $dbSalon = new DBSalon($idDB);

    $sqlWhere = "isDeleted=0 AND isNew=0 AND id>1";
    $arraySalons = $dbSalon->GetArrayRows($sqlWhere);
    foreach ($arraySalons as $salon) {
        if (strlen($strJsonSalons) > 0)   $strJsonSalons .= ",";
        $strJsonSalons .= '{"i":'.$salon->id.',"n":"'.$salon->strName.'"}';
    }



    $strJsonDepartments = "";
    require_once('../php-scripts/db/dbCategories.php');
    $dbCategory = new DBCategory($idDB);
    $strJsonDepartments = $dbCategory->GetJsonCategories(EnumEssential::DEPARTMENTS);






    EndResponsePureData('"employee":['.$strJsonEmployees.'],"clients":['.$strJsonClients.'],"services":['.$strJsonServices.
    '],"salons":['.$strJsonSalons.'],"departments":['.$strJsonDepartments.
    '],"appointments":['.$strJsonAppointments.'],"settings":'.$strJsonSettings);
?>