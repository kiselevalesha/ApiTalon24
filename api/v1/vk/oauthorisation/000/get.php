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
        
        $response = getUrlFile("https://api.vk.com/method/users.get?uids=574900243&fields=uid,first_name,last_name,nickname,sex,bdate,city,country,photo,photo_big&v=5.52&access_token=82338216583da2f3bda1d5f79c97f5fec5546165342d2f9afa4a88efc228da86dc30bce181802618dd029", null);
            //"https://api.vk.com/method/users.get?uids=".$vkUser."&fields=uid,first_name,last_name,nickname,sex,bdate,city,country,photo,photo_big&v=5.103&access_token=".$vkToken, null);
        echo $response;
        $jsonVKResponse = json_decode($response, false, 32);
        
        if (isSet($jsonVKResponse->error))    ExitError(1, "Ошибка авторизации.");
        else if (!isSet($jsonVKResponse->response))     ExitError(2, "Ошибка авторизации.");
        
        $jsonVK = $jsonVKResponse->response;
        
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
        
        $strName = urldecode(GetCleanString($jsonVK->first_name));
        $strSurname = urldecode(GetCleanString($jsonVK->last_name));
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
        
        if (isSet($jsonVK->sex)) {  //  У Вконтакте идентификаторы секса противоположные
            if ($jsonVK->sex == 2) $client->idSex = 1;
            elseif ($jsonVK->sex == 1) $client->idSex = 2;
        }

        if (isSet($strName))
            $client->strName = $strName;

        if (isSet($strSurname))
            $client->strSurname = $strSurname;

        if (isSet($jsonVK->bdate)) {
            //  Распарсить день рождения
            //$client->ageBorn = ;
        }
        
        $client->strVKToken = $vkToken;
        $client->id = $dbClient->Save($client);


        //  Берём фотку клиента, если она у него есть в профиле
        $strClientImage = "";
        if (isSet($jsonVK->photo_big))  $strClientImage = GetCleanString($jsonVK->photo_big);
        elseif (isSet($jsonVK->photo))  $strClientImage = GetCleanString($jsonVK->photo);
        if (isSet($strClientImage)) {
            require_once('../php-scripts/db/dbImages.php');
            $dbImage = new DBImage($idDB);
            
            //  Проверим нет ли её уже в базе
            $sql = "idOwner=".$client->id." AND idEssential=".EnumEssential::CLIENTS." AND strUrl LIKE '".$strClientImage."' AND isDeleted=0";
            $idImage = $dbImage->GetIdField($sql);
            
            if ($idImage < 1)
                $dbImage->AddNewImage(EnumEssential::CLIENTS, $client->id, $strClientImage, 1);
        }

        
        //  Записать клиенту vk-id
        if (!$flagFindedVK)
            $dbContact->SaveVKToClient($vkUser, $client->id);
                
        //  Записать клиента в appointment
        if ($idAppointment > 0) {
            require_once('../php-scripts/db/dbAppointments.php');
            $dbAppointment = new DBAppointment($idDB);
            $appointment = $dbAppointment->Get($idAppointment);
            $appointment->idClient = $client->id;
            $dbAppointment->Save($appointment);
        }

        EndResponsePureData('"client":{"id":'.$client->id.'}');
    }
    
    
    
?>