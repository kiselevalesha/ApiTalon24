<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbHistory = new DBHistory($idDB);
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
            $client->id = $dbHistory->Save($client);

            $strJson = $dbClient->MakeJson($client);
            //if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            ///$dbHistory->UpdateField("strJson", $strJson, "id=".$client->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>