<?php

    $strGlobalJsonUpdate = "";

    function SaveAll($idDB, $arrayJsonObjs) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbClient = new DBClient($idDB);

        $strGlobalJsonUpdate = '{"name":"'.$dbClient->strTableNameInitial.'","rows":[';

        
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = $dbClient->GetClient(GetInt($json->id));
            $obj->idEssential = EnumEssential::CLIENTS;
            
            if (isSet($json->name)) {
                $strJsonOld .= ',"name":"'.$obj->strName.'"';
                $obj->strName = GetCleanString($json->name);
                $strJsonNew .= ',"name":"'.$obj->strName.'"';
            }
            if (isSet($json->patronymic)) {
                $strJsonOld .= ',"patronymic":"'.$obj->strPatronymic.'"';
                $obj->strPatronymic = GetCleanString($json->patronymic);
                $strJsonNew .= ',"patronymic":"'.$obj->strPatronymic.'"';
            }
            if (isSet($json->surname)) {
                $strJsonOld .= ',"surname":"'.$obj->strSurName.'"';
                $obj->strSurName = GetCleanString($json->surname);
                $strJsonNew .= ',"surname":"'.$obj->strSurName.'"';
            }
            if (isSet($json->alias)) {
                $strJsonOld .= ',"alias":"'.$obj->strAlias.'"';
                $obj->strAlias = GetCleanString($json->alias);
                $strJsonNew .= ',"alias":"'.$obj->strAlias.'"';
            }
            if (isSet($json->description)) {
                $strJsonOld .= ',"description":"'.$obj->strDescription.'"';
                $obj->strDescription = GetCleanString($json->description);
                $strJsonNew .= ',"description":"'.$obj->strDescription.'"';
            }


            if (isSet($json->sex)) {
                $strJsonOld .= ',"sex":'.($obj->idSex + 0);
                $obj->idSex = GetInt($json->sex);
                $strJsonNew .= ',"sex":'.($obj->idSex + 0);
            }
            if (isSet($json->born)) {
                $strJsonOld .= ',"born":'.($obj->dateBorn + 0);
                $obj->dateBorn = GetInt($json->born);
                $strJsonNew .= ',"born":'.($obj->dateBorn + 0);
            }
            if (isSet($json->category))
                if (isSet($json->category->id)) {
                    $strJsonOld .= ',"unitProduct":{"id":'.$obj->idCategory.'}';
                    $obj->idCategory = GetInt($json->category->id);
                    $strJsonNew .= ',"unitProduct":{"id":'.$obj->idCategory.'}';
                }


            $obj->isNew = 0;
            
            //  проверяем, что такого ФИО уже не существует в базе.
            if ($obj->id == 0)
                $obj->id = $dbClient->GetId($obj);

            $obj->id = $dbClient->Save($obj);

            $strJson = $dbClient->MakeJson($obj);
            $dbClient->UpdateField("strJson", $strJson, "id=".$obj->id);



            //  Сохранить данные о Контактах
            if (!empty($json->contacts)) {
                require_once('../php-scripts/db/dbContacts.php');
                $dbContact = new DBContact($idDB);
                $strJsonContacts = $dbContact->SaveContacts($json->contacts);
            }
            // $strJsonContacts->new  $strJsonContacts->old


            //  Сохранить данные об Адресе
            require_once('api/v1/adresess/update.php');
            $strJsonAdress = UpdateAdress($json->adress, $obj->id, EnumEssential::CLIENTS);
            // $strJsonAdress->new  $strJsonAdress->old

            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson;
            
            if (!empty($strJsonAdress))
                $strJsonRows .= ','.$strJsonAdress;
            
            $strJsonRows .= ',"essential":'.EnumEssential::CLIENTS.'}';
            $comma = ",";
        }
        
        $strGlobalJsonUpdate .= ']}';
        return $strJsonRows;
    }
?>