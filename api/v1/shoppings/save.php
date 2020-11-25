<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbShopping = new DBShopping($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $shopping = $dbShopping->GetShopping($id);
            $shopping->strSurName = GetCleanString($json->surname);
            $shopping->strName = GetCleanString($json->name);
            $shopping->strPatronymic = GetCleanString($json->patronymic);
            $shopping->strAlias = GetCleanString($json->alias);
            $shopping->dateBorn = GetInt($json->born);
            $shopping->idSex = GetInt($json->sex);
            $shopping->idCategory = GetInt($json->category);
            $shopping->strDescription = GetCleanString($json->description);
            $shopping->isNew = 0;
            $shopping->id = $dbShopping->Save($shopping);

            $strJson = $dbShopping->MakeJson($shopping);
            $dbShopping->UpdateField("strJson", $strJson, "id=".$shopping->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>