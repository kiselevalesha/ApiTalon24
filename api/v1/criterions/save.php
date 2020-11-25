<?php

    $strGlobalJsonUpdate = "";

    function SaveAll($idDB, $arrayJsonObjs) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbCriterion = new DBCriterion($idDB);

        $strGlobalJsonUpdate = '{"name":"'.$dbCriterion->strTableNameInitial.'","rows":[';

        
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = $dbCriterion->GetCriterion(GetInt($json->id));
            $obj->idEssential = EnumEssential::CRITERIONS;
            
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
            //if ($obj->id == 0)
                //$obj->id = $dbClient->GetId($obj);

            $obj->id = $dbCriterion->Save($obj);

            $strJson = $dbCriterion->MakeJson($obj);
            $dbCriterion->UpdateField("strJson", $strJson, "id=".$obj->id);


            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson;

            $strJsonRows .= ',"essential":'.EnumEssential::CRITERIONS.'}';
            $comma = ",";
        }
        
        $strGlobalJsonUpdate .= ']}';
        return $strJsonRows;
    }
?>