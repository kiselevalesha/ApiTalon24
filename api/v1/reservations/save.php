<?php
    function SaveAll($arrayJsonObjs) {
        $strJsonRows = "";
        if (isSet($request->objects)) {
            global $idDB;
            require_once('../php-scripts/db/dbReservationTimes.php');
            $dbReservationTime = new DBReservationTime($idDB);
            $comma = "";
            foreach($arrayJsonObjs as $json) {

                $obj = new ReservationTime();
                $obj->id = GetNumeric($json->id);
            
                $obj->idEssentialOwner = GetInt($json->essential);
                $obj->idOwner = GetInt($json->owner);
            
                $obj->dateTimeDay = GetInt($json->age);
                $obj->intTimeStart = GetInt($json->start);
                $obj->intTimeEnd = GetInt($json->end);
                $obj->intMinutesDuration = GetInt($json->duration);
                
                $obj->strDescription = GetCleanString($json->description);
                $obj->idColor = GetInt($json->color);
            
                $obj->idSalon = GetInt($json->salon);
                $obj->idPlace = GetInt($json->place);
            
                $obj->id = $dbReservationTime->UpdateSave($obj);


                $dbReservationTime->UpdateField("strJson", $dbReservationTime->MakeJson($obj), "id=".$obj->id);
                $strJsonRows .= $comma . $obj->ToJson();
                $comma = ",";
            }
        }
        return $strJsonRows;
    }
?>