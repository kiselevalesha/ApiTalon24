<?php

    function SaveClients($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveClient($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveClient($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbClient = new DBClient($idDB);
        $client = $dbClient->GetClient(GetInt($json->id));
        //$client->idEssential = EnumEssential::CLIENTS;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$client->strName.'"';
            $client->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$client->strName.'"';
        }
        if (isSet($json->patronymic)) {
            $strJsonOld .= ',"patronymic":"'.$client->strPatronymic.'"';
            $client->strPatronymic = GetCleanString($json->patronymic);
            $strJsonNew .= ',"patronymic":"'.$client->strPatronymic.'"';
        }
        if (isSet($json->surname)) {
            $strJsonOld .= ',"surname":"'.$client->strSurName.'"';
            $client->strSurName = GetCleanString($json->surname);
            $strJsonNew .= ',"surname":"'.$client->strSurName.'"';
        }
        if (isSet($json->alias)) {
            $strJsonOld .= ',"alias":"'.$client->strAlias.'"';
            $client->strAlias = GetCleanString($json->alias);
            $strJsonNew .= ',"alias":"'.$client->strAlias.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$client->strDescription.'"';
            $client->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$client->strDescription.'"';
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


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$client->idCategory.'}';
                $client->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }


        //  Сохранить данные о Контактах
        if (isSet($json->contacts))
            $client->strJsonContacts = SaveContacts($idDB, $json->contacts, $client->id, EnumEssential::CLIENTS);


        //  Сохранить данные об Адресе
        if (isSet($json->adresses))
            $client->strJsonAdress = SaveAdresses($idDB, $json->adresses, $client->id, EnumEssential::CLIENTS);



        $client->isNew = 0;
        $client->id = $dbClient->Save($client);

        $strJson = $dbClient->MakeJson($client);
        
        if (! empty($strJsonCategory))
            $strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbClient->UpdateField("strJson", $strJson, "id=".$client->id);
        $client->strJsonUpdate = '{"id":'.$client->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $client;
    }
?>