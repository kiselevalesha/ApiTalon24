<?php
    require_once '../php-scripts/db/dbClients.php';
    require_once '../php-scripts/db/dbImages.php';
    require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../php-scripts/db/dbCategory.php';
    require_once '../php-scripts/db/dbGroups.php';
    require_once 'api/v1/adresess/update.php';

    function SaveClient($idDB, $json) {

        $dbClient = new DBClient($idDB);

        $strName = GetCleanString($json->name);
        $strPatronymic = GetCleanString($json->patronymic);
        $strSurname = GetCleanString($json->surname);
        $strAlias = GetCleanString($json->alias);
        $strDescription = GetCleanString($json->description);

        if ($json->id > 0) {
            $client = $dbClient->GetClient($json->id);
        }
        else {
            
            //  Создадим нового клиента, если передана о нём хоть какая-то информация
            $str = $strPhone.$strEmail.$strVK.$strName.$strPatronymic.$strSurname.$strAlias.$strDescription;
            if (strlen(trim($str)) > 0)
                $client = $dbClient->New();
        }

        if ($client instanceof Client)
            if ($client->id > 0) {
    
                if (isSet($strName)) {
                    $strJsonOld .= ',"name":"'.$client->strName.'"';
                    $client->strName = GetCleanString($strName);
                    $strJsonNew .= ',"name":"'.$client->strName.'"';
                }
    
                if (isSet($strPatronymic)) {
                    $strJsonOld .= ',"patronymic":"'.$client->strPatronymic.'"';
                    $client->strPatronymic = GetCleanString($strPatronymic);
                    $strJsonNew .= ',"patronymic":"'.$client->strPatronymic.'"';
                }
    
                if (isSet($strSurname)) {
                    $strJsonOld .= ',"surname":"'.$client->strSurName.'"';
                    $client->strSurName = GetCleanString($strSurname);
                    $strJsonNew .= ',"surname":"'.$client->strSurName.'"';
                }
    
                if (isSet($strAlias)) {
                    $strJsonOld .= ',"alias":"'.$client->strAlias.'"';
                    $client->strAlias = GetCleanString($strAlias);
                    $strJsonNew .= ',"alias":"'.$client->strAlias.'"';
                }
        
                if (isSet($strDescription)) {
                    $strJsonOld .= ',"description":"'.$client->strDescription.'"';
                    $client->strDescription = GetCleanString($strDescription);
                    $strJsonNew .= ',"description":"'.$client->strDescription.'"';
                }
        
                if (isSet($json->token)) {
                    $strJsonOld .= ',"token":"'.$client->strToken.'"';
                    $client->strToken = GetCleanString($json->token);
                    $strJsonNew .= ',"token":"'.$client->strToken.'"';
                }
    
    
                if (isSet($json->sex)) {
                    $strJsonOld .= ',"sex":'.($client->idSex + 0);
                    $client->idSex = GetInt($json->sex);
                    $strJsonNew .= ',"sex":'.($client->idSex + 0);
                }
    
                if (isSet($json->born)) {
                    $strJsonOld .= ',"born":'.($client->dateBorn + 0);
                    $client->dateBorn = GetInt($json->born);
                    $strJsonNew .= ',"born":'.($client->dateBorn + 0);
                }
    
    
    
                //  Сохраним фото клиента и сделаем это фото основным
                if ($client->photo)
                    if (! ($client->photo->id > 0))
                        if (! empty($client->photo->url)) {
                            $dbImage = new DBImage($idDB);
                            $image = $dbImage->SaveNewPhoto($client->photo->url, EnumEssential::CLIENTS);
                            if ($image->id > 0) {
                                $dbClient->UpdateField("idMainPhoto", $image->id, "id=".$client->id);
                                $client->idMainPhoto = $image->id;
                                $client->strJsonPhoto = '"photo":{"id":'.$image->id.',"url":"'.$image->strUrl.'"}';
                            }
                        }
    
    
                //  Сохранить данные о Контактах
                if (isSet($client->contacts)) {
                    $client->strJsonContacts = SaveContacts($idDB, $client->contacts, $client->id, EnumEssential::CLIENTS);
                    //$dbContact = new DBContact($idDB);
                    //$client->strJsonContacts = $dbContact->SaveContacts($client->contacts, $client->id, EnumEssential::CLIENTS);
                }
    
    
                //  Сохранить данные об Адресе
                if (isSet($client->adress)) {
                    $client->strJsonAdress = UpdateAdress($client->adress, $client->id, EnumEssential::CLIENTS);
                }
    
    
                //  Сохранить данные о Категории
                if (isSet($client->category)) {
                    $dbCategory = new DBCategory($idDB);
                    $client->strJsonCategory = $dbCategory->SaveCategory($client->category, $client->id, EnumEssential::CLIENTS);
                }
    
    
                //  Сохранить данные о группах
                if (isSet($client->groups)) {
                    $dbGroup = new DBGroup($idDB);
                    $client->strJsonGroups = $dbGroup->SaveGroups($client->groups, $client->id, EnumEssential::CLIENTS);
                }
    
    
                //  Теперь сформируем итоговый  json, описывающий клиента
                $client->strJson = $dbClient->MakeJson();
                if (! empty($client->strJsonContacts))
                    $client->strJson .= ',"contacts":['.$client->strJsonContacts.']';
                if (! empty($client->strJsonAdress))
                    $client->strJson .= ',"adress":{'.$client->strJsonAdress.'}';
                if (! empty($client->strJsonCategory))
                    $client->strJson .= ',"category":{'.$client->strJsonCategory.'}';
                if (! empty($client->strJsonGroups))
                    $client->strJson .= ',"groups":['.$client->strJsonGroups.']';
    
    
                $client->isNew = 0;
                $dbClient->Save($client);
            }
    
        return $client;
    }
?>