<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/getUrlFile.php';


    $vkToken = '';
    $user = '';
    if ((isSet($request->vk)) && (isSet($request->user))) {

        $vkToken = GetCleanString($request->vk);
        $vkUser = GetInt($request->user);

        $strAppointment = GetCleanString($request->appointment);
        if (empty($strAppointment))   ExitEmptyError("Appointment is empty!");
    
        require_once('../php-scripts/db/dbCodeAppointments.php');
        $dbCodeAppointment = new DBCodeAppointment();
        $codeAppointment = $dbCodeAppointment->GetByCode($strAppointment);
        $idDB = $codeAppointment->idDB;
        $idAppointment = $codeAppointment->idAppointment;
        
        require_once('../php-scripts/db/dbClients.php');
        $dbClient = new DBClient($idDB);
        
        $idClient = 0;
        $flagFindedVK = false;
        //  По email поискать клиента. Если найден, то найти idClient и вписать ему информацию.
        if (isSet($vkUser)) {
            require_once('../php-scripts/db/dbContacts.php');
            $dbContact = new DBContact($idDB);
            $idClient = $dbContact->GetIdClientByVK($vkUser);
            
            if ($idClient > 0)  $flagFindedVK = true;
        }
        
        $strName = urldecode(GetCleanString($request->name));
        $strSurname = urldecode(GetCleanString($request->surname));
        if ($idClient == 0) {
            $query = "isDeleted=0 AND strName LIKE '".$strName."' AND strSurname LIKE '".$strSurname."'";
            $idClient = $dbClient->GetIdField($query);
        }
        
        if ($idClient > 0) {
            $client = $dbClient->Get($idClient);
        }
        else {
            //  Создаём нового клиента
            $client = $dbClient->New();
        }


        //  Сохраним данные клиента
        if (isSet($request->sex)) {
            $client->idSex = $request->sex;
        }

        if (isSet($strName))
            $client->strName = $strName;

        if (isSet($strSurname))
            $client->strSurName = $strSurname;

        if (isSet($request->born))
            $client->dateBorn = $request->born * 1;

        
        $client->strVKToken = $vkToken;
        $client->id = $dbClient->Save($client);



        //  Берём фотку клиента, если она у него есть в профиле
        $strClientImage = "";
        $idImage = 0;
        if (isSet($request->photo))  $strClientImage = GetCleanString($request->photo);
        if (isSet($strClientImage)) {
            require_once('../php-scripts/db/dbImages.php');
            $dbImage = new DBImage($idDB);

            //  Проверим нет ли её уже в базе
            $sql = "idOwner=".$client->id." AND idEssential=".EnumEssential::CLIENTS." AND strUrl LIKE '".$strClientImage."' AND isDeleted=0";
            $idImage = $dbImage->GetIdField($sql);

            if ($idImage < 1) {
                $image = $dbImage->AddNewImageFromSite(EnumEssential::CLIENTS, $client->id, $strClientImage, 1, 0);
                $idImage = $image->id;
            }
            $dbClient->UpdateField("idMainPhoto", $idImage, "id=".$client->id);
        }



        
        //  Записать клиенту vk-id
        if (!$flagFindedVK)
            $dbContact->SaveVKToClient($vkUser, $client->id);
                
        //  Записать клиента в appointment
        if (($idAppointment > 0) && ($client->id > 0)) {
            require_once('../php-scripts/db/dbAppointments.php');
            $dbAppointment = new DBAppointment($idDB);
            $appointment = $dbAppointment->Get($idAppointment);
            $appointment->idClient = $client->id;
            $appointment->isFinishedStep4 = 1;
            $dbAppointment->Save($appointment);
        }
        
        $dbCodeAppointment->UpdateField("isFinishedStep4", 1, "id=".$codeAppointment->id);

        EndResponsePureData('"client":{"id":'.$client->id.'}');
    }
?>