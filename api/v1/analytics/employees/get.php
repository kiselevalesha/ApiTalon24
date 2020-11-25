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
    
    $sqlWhere = "(ageOrderStart>=".$start." AND ageOrderStart<=".$end.") AND isDeleted=0 AND isNew=0";    //  и не отменён!!! проверять статус, что выполнен!!!
    $arrayAppointments = $dbAppointment->GetArrayRows($sqlWhere);
    
    foreach ($arrayAppointments as $appointment) {
        if (strlen($strJsonAppointments) > 0)   $strJsonAppointments .= ",";
        $strJsonAppointments .= '{"m1":'.$appointment->idMaster1.',"sm1":'.$appointment->summaMaster1.
        ',"m2":'.$appointment->idMaster2.',"sm2":'.$appointment->summaMaster2.
        ',"a1":'.$appointment->idAssistent1.',"sa1":'.$appointment->summaAssistent1.
        ',"a2":'.$appointment->idAssistent2.',"sa2":'.$appointment->summaAssistent2.
        ',"r":'.$appointment->intRatingByClient.',"o":'.$appointment->ageOrderStart.',"c":'.$appointment->idEssentialCreator.
        ',"s":'.$appointment->costOrder.',"d":'.$appointment->intMinutesDuration.'}';
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



    EndResponsePureData('"employees":['.$strJsonEmployees.'],"clients":['.$strJsonClients.
        '],"appointments":['.$strJsonAppointments.'],"schedule":['.$strJsonSchedule.'],"settings":'.$strJsonSettings);
?>