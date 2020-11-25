<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbBill = new DBBill($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $client = $dbBill->GetClient($id);
            $client->strSurName = GetCleanString($json->surname);
            $client->strName = GetCleanString($json->name);
            $client->strPatronymic = GetCleanString($json->patronymic);
            $client->strAlias = GetCleanString($json->alias);
            $client->dateBorn = GetInt($json->born);
            $client->idSex = GetInt($json->sex);
            $client->idCategory = GetInt($json->category);
            $client->strDescription = GetCleanString($json->description);
            $client->isNew = 0;
            $client->id = $dbBill->Save($client);

            $strJson = $dbBill->MakeJson($client);
            //if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            $dbBill->UpdateField("strJson", $strJson, "id=".$client->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>