<?php
    function SaveAll($arrayJsonObjs) {
        $strJsonRows = "";
        if (isSet($arrayJsonObjs)) {
            global $idDB;

            require_once('../php-scripts/db/dbSchedule.php');
            $dbSchedule = new DBSchedule($idDB);
            $comma = "";
            foreach($arrayJsonObjs as $json) {

                $obj = new Shedule();
                $obj->id = GetNumeric($json->id);
                $obj->idTypeDay = GetInt($json->type);
            
                $obj->idEssentialOwner = GetInt($json->essential);
                $obj->idOwner = GetInt($json->owner);
        
                $obj->idSalon = GetInt($json->salon);
                $obj->idPlace = GetInt($json->place);
                $obj->idColor = GetInt($json->color);
            
                $obj->age = GetInt($json->age);
                $obj->intTimeStart = GetInt($json->start);
                $obj->intTimeEnd = GetInt($json->end);
                
                //  Если время начала и окончания смены нулевые, то удалим строку, если она есть.
                if (($obj->intTimeStart == 0) && ($obj->intTimeEnd == 0)) {
                    $sqlWhere = "age=".$obj->age." AND idOwner=".$obj->idOwner." AND idEssentialOwner=".$obj->idEssentialOwner." AND idSalon=".$obj->idSalon." AND idPlace=".$obj->idPlace;
                    $dbSchedule->DeleteRowsBySql($sqlWhere);
                }
                else    $obj->id = $dbSchedule->UpdateSave($obj);
                
                
                /*
                require_once('../php-scripts/db/dbSettings.php');
                $dbSettings = new DBSettings($idDB);
                $settings = $dbSettings->GetDefault();
                
                if ($obj->intTimeStart == -1)   $obj->intTimeStart = $settings->intTimeStart;
                if ($obj->intTimeEnd == -1)   $obj->intTimeEnd = $settings->intTimeEnd;
                */

                
            
            
                //  Сохраним в шаблонах. Если такой там уже не присутствует.
                /*require_once('../php-scripts/db/dbTemplateDay.php');
                $dbTemplateDay = new DBTemplateDay($idDB);
                $templateDay = $dbTemplateDay->FindOrCreate($obj->idTypeDay, $obj->intTimeStart, $obj->intTimeEnd, $obj->idColor);
                if ($templateDay->isCanEdit == 1) {
                    $templateDay->strName = GetCleanString($json->name);
                    $templateDay->strDescription = GetCleanString($json->description);
                    $templateDay->id = $dbTemplateDay->Save($templateDay);
                }*/
        

                //$dbTax->UpdateField("strJson", $dbTax->MakeJson($obj), "id=".$obj->id);
                $strJsonRows .= $comma . $obj->ToJson();
                $comma = ",";
            }
        }
        return $strJsonRows;
    }
?>