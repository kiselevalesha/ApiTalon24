<?php

    $strGlobalJsonUpdate = "";

    function SaveAll($idDB, $arrayJsonObjs) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbAppointment = new DBAppointment($idDB);

        $strGlobalJsonUpdate = '{"name":"'.$dbAppointment->strTableNameInitial.'","rows":[';



        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = $dbAppointment->GetAppointment(GetInt($json->id));

            //  "code":"'.$this->longCode

            if (isSet($json->rate->client->stars)) {
                $strJsonOld .= ',"status":'.($obj->intRatingByClient + 0);
                $obj->intRatingByClient = GetInt($json->rate->client->stars);
                $strJsonNew .= ',"status":'.($obj->intRatingByClient + 0);
            }
            if (isSet($json->rate->client->description)) {
                $strJsonOld .= ',"description":"'.$obj->strReviewByClient.'"';
                $obj->strReviewByClient = GetCleanString($json->rate->client->description);
                $strJsonNew .= ',"description":"'.$obj->strReviewByClient.'"';
            }

            if (isSet($json->rate->master->stars)) {
                $strJsonOld .= ',"status":'.($obj->intRatingByMaster + 0);
                $obj->intRatingByMaster = GetInt($json->rate->master->stars);
                $strJsonNew .= ',"status":'.($obj->intRatingByMaster + 0);
            }
            if (isSet($json->rate->master->description)) {
                $strJsonOld .= ',"description":"'.$obj->strReviewByMaster.'"';
                $obj->strReviewByMaster = GetCleanString($json->rate->master->description);
                $strJsonNew .= ',"description":"'.$obj->strReviewByMaster.'"';
            }

            if (isSet($json->status)) {
                $strJsonOld .= ',"status":'.($obj->idStatus + 0);
                $obj->idStatus = GetInt($json->status);
                $strJsonNew .= ',"status":'.($obj->idStatus + 0);
            }
            if (isSet($json->isFinished)) {
                $strJsonOld .= ',"isFinished":'.($obj->isFinished + 0);
                $obj->isFinished = GetInt($json->isFinished);
                $strJsonNew .= ',"isFinished":'.($obj->isFinished + 0);
            }
            if (isSet($json->age->review)) {
                $strJsonOld .= ',"ageReview":'.($obj->ageReview + 0);
                $obj->ageReview = GetInt($json->age->review);
                $strJsonNew .= ',"ageReview":'.($obj->ageReview + 0);
            }

            $obj->isNew = 0;
            
            //  проверяем, что такого ФИО уже не существует в базе.
            if ($obj->id == 0)
                $obj->id = $dbAppointment->GetIdByCode($obj);

            $obj->id = $dbAppointment->Save($obj);

            $strJson = $dbAppointment->MakeJson($obj);
            $dbAppointment->UpdateField("strJson", $strJson, "id=".$obj->id);


/*
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
*/

            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson;
            
            //if (!empty($strJsonAdress))
                //$strJsonRows .= ','.$strJsonAdress;

            $strJsonRows .= ',"essential":'.EnumEssential::APPOINTMENTS.'}';
            $comma = ",";
        }
        
        $strGlobalJsonUpdate .= ']}';
        return $strJsonRows;
    }
?>