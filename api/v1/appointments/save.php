<?php

    $strGlobalJsonUpdate = "";
    
    //$idDBExternal = GetInt($json->db);
    //if ($idDBExternal > 0)  $idDB = $idDBExternal;

    function SaveAppointments($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveAppointment($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }

    function SaveAppointment($idDB, $json) {
        $dbAppointment = new DBAppointment($idDB);
        $appointment = $dbAppointment->GetAppointment(GetInt($json->id));
        return $appointment;
    }

    function SaveAllAppointments($idDB, $arrayJsonObjs) {

        $strJsonRows = "";
        $dbAppointment = new DBAppointment($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {
            SaveOneAppointment($idDB, $json);
        }




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


/*
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
*/
    }
?>



<?php
    function SaveOneAppointment($idDB, $json) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbAppointment = new DBAppointment($idDB);

        $strGlobalJsonUpdate = '{"name":"'.$dbAppointment->strTableNameInitial.'","rows":[';


        $obj = $dbAppointment->GetAppointment(GetInt($json->id));
        $obj->idEssential = EnumEssential::APPOINTMENTS;

        
        if (isSet($json->code)) {
            $strJsonOld .= ',"code":"'.$obj->longCode.'"';
            $obj->longCode = GetCleanString($json->code);
            $strJsonNew .= ',"code":"'.$obj->longCode.'"';
        }
        
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$obj->strDescription.'"';
            $obj->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$obj->strDescription.'"';
        }

        //  Сохраним данные о клиенте, если они были переданы
        if (isSet($json->client)) {
            $appointment->client = SaveClient($idDB, $json->client);
        }

        //  Сохраним данные о салоне
        if (isSet($json->salon)) {
            $strJsonOld .= ',"salon":'.($obj->idSalon + 0);
            $dbSalon = new DBSalon($idDB);
            $obj->idSalon = $dbSalon->SaveObject($json->salon);
            $strJsonNew .= ',"salon":'.($obj->idSalon + 0);
        }

        //  Сохраним данные о мастерах
        if (isSet($json->masters)) {
            $dbSalonEmployee = new DBSalonEmployee($idDB);
            foreach($json->masters as $master) {
                $dbSalonEmployee->SaveUpdate($json->salon->id, $master->id, $obj->id);
            }
        }

        //  Сохраним данные об услугах
        if (isSet($json->services)) {
            $dbServiceApppointment = new DBServiceApppointment($idDB);
            foreach($json->services as $service) {
                $dbServiceApppointment->SaveUpdate($service->id, $obj->id);
            }
        }

        //  Сохранить данные о Контактах
        if (isSet($json->contacts)) {
            require_once('../php-scripts/db/dbContacts.php');
            $dbContact = new DBContact($idDB);
            $obj->strJsonContacts = $dbContact->SaveContacts($json->contacts);
        }
        // $strJsonContacts->new  $strJsonContacts->old

        //  Сохранить данные об Адресе
        if (isSet($json->adress)) {
            require_once('api/v1/adresess/update.php');
            $obj->strJsonAdress = UpdateAdress($json->adress, $obj->id, EnumEssential::APPOINTMENTS);
        }
        // $strJsonAdress->new  $strJsonAdress->old

        if (isSet($json->cost)) {
            $strJsonOld .= ',"cost":{';
            if (isSet($json->cost->isAuto)) {
                $strJsonOld .= '"isAuto":'.($obj->isAutoCalcCostOrder + 0);
                $obj->isAutoCalcCostOrder = GetInt($json->cost->isAuto);
                $strJsonNew .= '"isAuto":'.($obj->isAutoCalcCostOrder + 0);
            }
            if (isSet($json->cost->summa)) {
                $strJsonOld .= ',"summa":'.($obj->costOrder + 0);
                $obj->costOrder = GetInt($json->cost->summa);
                $strJsonNew .= ',"summa":'.($obj->costOrder + 0);
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->duration)) {
            $strJsonOld .= ',"duration":{';
            if (isSet($json->duration->isAuto)) {
                $strJsonOld .= '"isAuto":'.($obj->isAutoCalculationDuration + 0);
                $obj->isAutoCalculationDuration = GetInt($json->duration->isAuto);
                $strJsonNew .= '"isAuto":'.($obj->isAutoCalculationDuration + 0);
            }
            if (isSet($json->duration->minutes)) {
                $strJsonOld .= ',"minutes":'.($obj->intMinutesDuration + 0);
                $obj->intMinutesDuration = GetInt($json->duration->minutes);
                $strJsonNew .= ',"minutes":'.($obj->intMinutesDuration + 0);
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->age)) {
            $strJsonOld .= ',"age":{';
            if (isSet($json->age->created)) {
                $strJsonOld .= '"ageCreated":'.($obj->ageCreated + 0);
                $obj->ageCreated = GetInt($json->age->created);
                $strJsonNew .= '"ageCreated":'.($obj->ageCreated + 0);
            }
            if (isSet($json->age->changed)) {
                $strJsonOld .= '"changed":'.($obj->ageCreated + 0);
                $obj->ageCreated = GetInt($json->age->changed);
                $strJsonNew .= '"changed":'.($obj->ageCreated + 0);
            }
            if (isSet($json->age->start)) {
                $strJsonOld .= '"start":'.($obj->ageOrderStart + 0);
                $obj->ageOrderStart = GetInt($json->age->start);
                $strJsonNew .= '"start":'.($obj->ageOrderStart + 0);
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->administrator)) {
            $strJsonOld .= ',"administrator":{';
            if (isSet($json->administrator->order)) {
                $strJsonOld .= ',"order":"'.$obj->strTokenAdministratorOrder.'"';
                $obj->strTokenAdministratorOrder = GetCleanString($json->order);
                $strJsonNew .= ',"order":"'.$obj->strTokenAdministratorOrder.'"';
            }
            if (isSet($json->administrator->visit)) {
                $strJsonOld .= ',"visit":"'.$obj->strTokenAdministratorVisit.'"';
                $obj->strTokenAdministratorVisit = GetCleanString($json->visit);
                $strJsonNew .= ',"visit":"'.$obj->strTokenAdministratorVisit.'"';
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->photo)) {
            $strJsonOld .= ',"photo":{';
            if (isSet($json->photo->id)) {
                $strJsonOld .= ',"id":"'.$obj->idPhoto.'"';
                $obj->idPhoto = GetCleanString($json->photo->id);
                $strJsonNew .= ',"id":"'.$obj->idPhoto.'"';
            }
            if (isSet($json->photo->url)) {
                $strJsonOld .= ',"url":"'.$obj->strUrlPhoto.'"';
                $obj->strUrlPhoto = GetCleanString($json->photo->url);
                $strJsonNew .= ',"url":"'.$obj->strUrlPhoto.'"';
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->creator)) {
            $strJsonOld .= ',"creator":{';
            if (isSet($json->creator->type)) {
                $strJsonOld .= ',"id":'.$obj->idEssentialCreator;
                $obj->idEssentialCreator = GetInt($json->creator->type);
                $strJsonNew .= ',"id":'.$obj->idEssentialCreator;
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->informedByClient)) {
            $strJsonOld .= ',"informedByClient":{';
            if (isSet($json->informedByClient->ageSended)) {
                $strJsonOld .= '"ageCreated":'.($obj->ageSendedInfoToClient + 0);
                $obj->ageSendedInfoToClient = GetInt($json->informedByClient->ageSended);
                $strJsonNew .= '"ageCreated":'.($obj->ageSendedInfoToClient + 0);
            }
            if (isSet($json->informedByClient->ageConfirmed)) {
                $strJsonOld .= '"ageConfirmed":'.($obj->ageConfirmedByClient + 0);
                $obj->ageConfirmedByClient = GetInt($json->informedByClient->ageConfirmed);
                $strJsonNew .= '"ageConfirmed":'.($obj->ageConfirmedByClient + 0);
            }
            if (isSet($json->informedByClient->type)) {
                $strJsonOld .= '"type":'.($obj->idTypeConfirmed + 0);
                $obj->idTypeConfirmed = GetInt($json->informedByClient->type);
                $strJsonNew .= '"type":'.($obj->idTypeConfirmed + 0);
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->informedByMaster)) {
            $strJsonOld .= ',"informedByMaster":{';
            if (isSet($json->informedByMaster->ageSended)) {
                $strJsonOld .= '"ageCreated":'.($obj->ageSendedInfoToMaster + 0);
                $obj->ageSendedInfoToMaster = GetInt($json->informedByMaster->ageSended);
                $strJsonNew .= '"ageCreated":'.($obj->ageSendedInfoToMaster + 0);
            }
            if (isSet($json->informedByMaster->ageAccepted)) {
                $strJsonOld .= '"ageAccepted":'.($obj->ageAcceptedByMaster + 0);
                $obj->ageAcceptedByMaster = GetInt($json->informedByMaster->ageAccepted);
                $strJsonNew .= '"ageAccepted":'.($obj->ageAcceptedByMaster + 0);
            }
            $strJsonOld .= '}';
        }

        if (isSet($json->status)) {
            $strJsonOld .= ',"status":'.($obj->idStatus + 0);
            $obj->idStatus = GetInt($json->status);
            $strJsonNew .= ',"status":'.($obj->idStatus + 0);
        }
        
        if (isSet($json->isFinished)) {
            $strJsonOld .= ',"isFinished":'.($obj->isFinished + 0);
            $obj->isFinished = GetInt($json->isFinished);
            $strJsonNew .= ',"isFinished":'.($obj->isFinished + 0);
        }



        //  Сохраним данные о прайслист
        if (isSet($json->pricelist)) {
            $strJsonOld .= ',"salon":'.($obj->idSalon + 0);
            $dbSalon = new DBSalon($idDB);
            $obj->idSalon = $dbSalon->SaveObject($json->salon);
            $strJsonNew .= ',"salon":'.($obj->idSalon + 0);
        }
        if (isSet($json->pricelist)) {
            
            $dbPricelist = new DBPricelist($idDB);
            //$idSalon = $dbPricelist->SaveObject($json->pricelist);

            $strJsonOld .= ',"pricelist":{';
            if (isSet($json->pricelist->id)) {
                $strJsonOld .= '"id":'.($obj->id + 0);
                $obj->id = GetInt($json->pricelist->id);
                $strJsonNew .= '"id":'.($obj->id + 0);
            }
            if (isSet($json->pricelist->name)) {
                $strJsonOld .= '"name":"'.$obj->ageAcceptedByMaster.'"';
                $obj->ageAcceptedByMaster = GetCleanString($json->pricelist->name);
                $strJsonNew .= '"name":"'.$obj->ageAcceptedByMaster.'"';
            }
            $strJsonOld .= '}';
        }

    //$appointment->idPlace = GetInt($json->place->id);
    //$appointment->idCourse = GetInt($json->course);









    $appointment->ageOrderStart = GetInt($json->start);
    if ($appointment->ageCreated == 0)  $appointment->ageCreated = $dbAppointment->NowLong();
    
    //  Если продолжительность == 0 (т.е. нет выбранных услуг), то поставим продолжительность = минимальному периоду.
    if ($appointment->intMinutesDuration == 0) {
        require_once('../php-scripts/db/dbSettings.php');
        $dbSettings = new DBSettings($idDB);
        $settings = $dbSettings->GetDefault();
        $appointment->intMinutesDuration = $settings->intPeriodMinutes;
    }




    $dbCodeAppointment->UpdateField("isFinishedStep4", 1, "id=".$codeAppointment->id);

    require_once('api/v1/appointments/finish/sendmessages.php');


    echo GetOutJson('"appointment":{'.$appointment->MakeJson().'}');
    
    require_once('../php-scripts/db/dbLast.php');
    $dbLast = new DBLast($idDB);
    $dbLast->SetLastChanged(EnumIdTables::Appointments, $appointment->idSalon);





            $obj->isNew = 0;

            //  Теперь сформируем итоговый  json, описывающий клиента
            $appointment->strJson = $dbAppointment->MakeJson();

            if (! empty($appointment->client->strJson))
                $appointment->strJsonClient = $appointment->client->strJson;
            if (! empty($appointment->strJsonClient))
                $appointment->strJson .= ',"client":{'.$appointment->strJsonClient.'}';

            /*if (! empty($client->strJsonAdress))
                $client->strJson .= ',"adress":{'.$client->strJsonAdress.'}';
            if (! empty($client->strJsonCategory))
                $client->strJson .= ',"category":{'.$client->strJsonCategory.'}';
            if (! empty($client->strJsonGroups))
                $client->strJson .= ',"groups":['.$client->strJsonGroups.']';*/


            $dbAppointment->Save($appointment);






            //$obj->id = $dbAppointment->Save($obj);

            //$strJson = $dbAppointment->MakeJson($obj);
            //$dbAppointment->UpdateField("strJson", $strJson, "id=".$obj->id);

            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson;
            
            //if (!empty($strJsonAdress))
                //$strJsonRows .= ','.$strJsonAdress;
            
            $strJsonRows .= ',"essential":'.$obj->idEssential.'}';
            //$comma = ",";
        //}
        
        //$strGlobalJsonUpdate .= ']}';
        //return $strJsonRows;
        return $appointment;
    }
?>