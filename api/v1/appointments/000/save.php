<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbAppointment = new DBAppointment($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {




    $idDBExternal = GetInt($json->db);
    if ($idDBExternal > 0)  $idDB = $idDBExternal;

    $idAppointment = 0;
    $idSalon = GetInt($json->salon->id);
    $idEmployee = GetInt($json->employee->id);
    
    
    require_once('../php-scripts/db/dbCodeAppointments.php');
    $dbCodeAppointment = new DBCodeAppointment();
    
    $strAppointmentCode = GetCleanString($json->code);
    if (! empty($strAppointmentCode)) {
        $codeAppointment = $dbCodeAppointment->GetByCode($strAppointmentCode);
        $idDB = $codeAppointment->idDB;
        $idAppointment = $codeAppointment->idAppointment;
    }

    $dbAppointment = new DBAppointment($idDB);
    $appointment = $dbAppointment->GetAppointment($idAppointment);  //  Создаём или загружаем



    //  Сохраним клиента, если он новый
    $strName = GetCleanString($json->client->name);
    $strPatronymic = GetCleanString($json->client->patronymic);
    $strSurname = GetCleanString($json->client->surname);
    $idClient = GetInt($json->client->id);
    $idSex = GetInt($json->client->sex);

    $phone = GetCleanString($json->client->phone);
    $email = GetCleanString($json->client->email);
    $idVK = GetCleanString($json->client->vk);


    require_once('../php-scripts/db/dbClients.php');
    $dbClient = new DBClient($idDB);

    require_once('../php-scripts/db/dbContacts.php');
    $dbContact = new DBContact($idDB);
    
    
    
    //  Попытаемся найти клиента по номеру телефона. Вдруг он уже есть в базе
    if (empty($idClient))
        if (! empty($phone))
            $idClient = $dbContact->GetIdClientByPhone($phone);
    
    if (empty($idClient))
        if (! empty($email))
            $idClient = $dbContact->GetIdClientByEmail($email);
    
    if (empty($idClient))
        if (! empty($idVK))
            $idClient = $dbContact->GetIdClientByVK($idVK);
    

    //  создаём или загружаем клиента
    $client = $dbClient->GetClient($idClient);
    if (! empty($strName))          $client->strName = $strName;
    if (! empty($strPatronymic))    $client->strPatronymic = $strPatronymic;
    if (! empty($strSurname))       $client->strSurname = $strSurname;
    if (! empty($idSex))            $client->idSex = $idSex;
    $idClient = $dbClient->Save($client);

    //  Сохраним или пересохраним номер телефона у клиента
    if (!empty($phone))
        $dbContact->SavePhoneToClient($phone, $idClient);
    
    if (!empty($email))
        $dbContact->SaveEmailToClient($email, $idClient);
    
    if (!empty($idVK))
        $dbContact->SaveVKToClient($idVK, $idClient);



    
    
    $appointment->idClient = $idClient;
    //$appointment->idSalon = GetInt($json->salon->id); //  салон указывается в самом начале и потом никогда не меняется!
    $appointment->idPlace = GetInt($json->place->id);
    $appointment->idCourse = GetInt($json->course);

    $appointment->ageOrderStart = GetInt($json->age->start);
    $appointment->intMinutesDuration = GetInt($json->duration->minutes);
    $appointment->isAutoCalculationDuration = GetInt($json->duration->isAuto);
    if ($appointment->ageCreated == 0)     $appointment->ageCreated = $dbAppointment->NowLong();

    $appointment->ageAcceptedByMaster = GetInt($json->informedByMaster->ageAccepted);

    $appointment->strDescription = GetCleanString($json->description);


    switch(GetInt($json->finishedStep)) {
        case 1:
            $appointment->isFinishedStep1 = 1;
            break;
        case 2:
            $appointment->isFinishedStep1 = 1;
            $appointment->isFinishedStep2 = 1;
            break;
        case 3:
            $appointment->isFinishedStep1 = 1;
            $appointment->isFinishedStep2 = 1;
            $appointment->isFinishedStep3 = 1;
            break;
        case 4:
            $appointment->isFinishedStep1 = 1;
            $appointment->isFinishedStep2 = 1;
            $appointment->isFinishedStep3 = 1;
            $appointment->isFinishedStep4 = 1;
            break;
    }


    $idPricelist = GetInt($json->pricelist->id);
    if (empty($idPricelist)) {
        require_once('../php-scripts/db/dbPricelists.php');
        $dbPricelist = new DBPricelist($idDB);
        $idPricelist = $dbPricelist->GetIdDefault();
    }
    $appointment->idPricelist = $idPricelist;
    $appointment->costOrder = GetInt($json->cost->summa);
    $appointment->isAutoCalcCostOrder = GetInt($json->cost->isAuto);

    ///$appointment->costVisit = $appointment->costOrder;      //  Здесь нужно ещё думать, как правильно сохранять и какие условия проверять !!!
    $appointment->strTokenAdministratorOrder = $strToken;
    $appointment->isNew = 0;

    if (empty($appointment->longCode)) {
        $codeAppointment = $dbCodeAppointment->Add($idDB, $idSalon, $idEmployee);
        $appointment->longCode = $codeAppointment->longCode;
    }

    $dbAppointment->Save($appointment);



            $strJson = $dbAppointment->MakeJson($appointment);
            $dbAppointment->UpdateField("strJson", $strJson, "id=".$appointment->id);
            $strJsonRows .= $comma . '{'.$strJson.',"essential":'.EnumEssential::APPOINTMENTS.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>