<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/utils/getUrlFile.php';


    $yandex = '';
    if (isSet($request->yandex)) {
        
        $yandex = GetCleanString($request->yandex);
        $response = getUrlFile("https://login.yandex.ru/info?oauth_token=".$yandex, null);
        $jsonYandex = json_decode($response, false, 32);
        
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
        $flagFindedEmail = false;
        $email = GetCleanString($jsonYandex->default_email);
        //  По email поискать клиента. Если найден, то найти idClient и вписать ему информацию.
        if (isSet($email)) {
            require_once('../php-scripts/db/dbContacts.php');
            $dbContact = new DBContact($idDB);
            $idClient = $dbContact->GetIdClientByEmail($email);
            
            if ($idClient > 0)  $flagFindedEmail = true;
        }
        
        $strName = urldecode(GetCleanString($jsonYandex->first_name));
        $strSurname = urldecode(GetCleanString($jsonYandex->last_name));
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
        
        if (isSet($jsonYandex->sex)) {
            if ($jsonYandex->sex == 'male') $client->idSex = 1;
            elseif ($jsonYandex->sex == 'female') $client->idSex = 2;
        }

        if (isSet($strName))
            $client->strName = $strName;

        if (isSet($strSurname))
            $client->strSurName = $strSurname;

        if (isSet($jsonYandex->birthday)) {
            //  Распарсить день рождения
            $array_dates = explode("-", $jsonYandex->birthday);
            if (sizeOf($array_dates) == 3)
                $client->dateBorn = $array_dates[0] * 10000 + $array_dates[1] * 100 + $array_dates[2] * 1;
        }
        
        $client->strYandexToken = $yandex;
        $client->id = $dbClient->Save($client);


        //  Берём фотку клиента, если она у него есть в профиле
        if (isSet($jsonYandex->is_avatar_empty)){
            if (isSet($jsonYandex->default_avatar_id)) {
                $strClientImage = "https://avatars.yandex.net/get-yapic/".GetCleanString($jsonYandex->default_avatar_id)."/islands-200";

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
        }

        
        //  Записать клиенту email
        if (!$flagFindedEmail)
            if (isSet($email))
                $dbContact->SaveEmailToClient($email, $client->id);
                
        //  Записать клиента в appointment
        if ($idAppointment > 0) {
            require_once('../php-scripts/db/dbAppointments.php');
            $dbAppointment = new DBAppointment($idDB);
            $appointment = $dbAppointment->Get($idAppointment);
            $appointment->idClient = $client->id;
            $dbAppointment->Save($appointment);
        }
        
        $dbCodeAppointment->UpdateField("isFinishedStep4", 1, "id=".$codeAppointment->id);

        EndResponsePureData('"client":{"id":'.$client->id.'}');
    }
    
    
    
?>