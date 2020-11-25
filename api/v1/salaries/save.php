<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbClient = new DBClient($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $client = $dbClient->GetClient($id);
            $client->strSurName = GetCleanString($json->surname);
            $client->strName = GetCleanString($json->name);
            $client->strPatronymic = GetCleanString($json->patronymic);
            $client->strAlias = GetCleanString($json->alias);
            $client->dateBorn = GetInt($json->born);
            $client->idSex = GetInt($json->sex);
            $client->idCategory = GetInt($json->category);
            $client->strDescription = GetCleanString($json->description);
            $client->isNew = 0;
            $client->id = $dbClient->Save($client);

            //  Сохранить Контакты
            require_once('../php-scripts/db/dbContacts.php');
            $dbContact = new DBContact($idDB);
            $phone = GetCleanString($json->phone);
            if (!empty($phone))   $dbContact->SavePhoneToClient($phone, $client->id);
            $email = GetEmail($json->email);
            if (!empty($email))   $dbContact->SaveEmailToClient($email, $client->id);

            require_once('api/v1/adresess/update.php');
            $strJsonAdress = UpdateAdress($json->adress, $client->id, EnumEssential::CLIENTS);
            
            $strJson = $dbClient->MakeJson($client);
            if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            $dbClient->UpdateField("strJson", $strJson, "id=".$client->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>