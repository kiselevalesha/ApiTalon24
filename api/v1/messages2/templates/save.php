<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbMessageTemplate = new DBMessageTemplate($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $client = $dbMessageTemplate->GetClient($id);
            $client->strSurName = GetCleanString($json->surname);
            $client->strName = GetCleanString($json->name);
            $client->strPatronymic = GetCleanString($json->patronymic);
            $client->strAlias = GetCleanString($json->alias);
            $client->dateBorn = GetInt($json->born);
            $client->idSex = GetInt($json->sex);
            $client->idCategory = GetInt($json->category);
            $client->strDescription = GetCleanString($json->description);
            $client->isNew = 0;
            $client->id = $dbMessageTemplate->Save($client);

            $strJson = $dbMessageTemplate->MakeJson($client);
            //if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            $dbMessageTemplate->UpdateField("strJson", $strJson, "id=".$client->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>