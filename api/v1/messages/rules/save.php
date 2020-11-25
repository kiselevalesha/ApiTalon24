<?php

    function SaveMessageRules($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveMessageRule($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveMessageRule($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbMessageRule = new DBMessageRule($idDB);
        $messageRule = $dbMessageRule->GetMessageRule(GetInt($json->id));
        //$messageRule->idEssential = EnumEssential::MESSAGERULES;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$messageRule->strName.'"';
            $messageRule->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$messageRule->strName.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$messageRule->strDescription.'"';
            $messageRule->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$messageRule->strDescription.'"';
        }
        if (isSet($json->body)) {
            $strJsonOld .= ',"body":"'.$messageRule->strBody.'"';
            $messageRule->strBody = GetCleanString($json->body);
            $strJsonNew .= ',"body":"'.$messageRule->strBody.'"';
        }


        /*if (isSet($json->sex)) {
            $strJsonOld .= ',"sex":'.($messageRule->idSex + 0);
            $messageRule->idSex = GetInt($json->sex);
            $strJsonNew .= ',"sex":'.($messageRule->idSex + 0);
        }
        if (isSet($json->born)) {
            $strJsonOld .= ',"born":'.($messageRule->dateBorn + 0);
            $messageRule->dateBorn = GetInt($json->born);
            $strJsonNew .= ',"born":'.($messageRule->dateBorn + 0);
        }


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$messageRule->idCategory.'}';
                $messageRule->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }*/


        $messageRule->isNew = 0;
        $messageRule->id = $dbMessageRule->Save($messageRule);

        $strJson = $dbMessageRule->MakeJson($messageRule);
        
        if (! empty($strJsonCategory))
            $strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbMessageRule->UpdateField("strJson", $strJson, "id=".$messageRule->id);
        $messageRule->strJsonUpdate = '{"id":'.$messageRule->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $messageRule;
    }
?>