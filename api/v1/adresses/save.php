<?php
    require_once '../php-scripts/db/dbAdresses.php';
    require_once '../php-scripts/models/essential.php';
    
    $strGlobalJsonUpdate = "";

    function SaveAdresses($idDB, $arrayJsonObjs, $idOwner, $idTypeOwner) {
        $strJson = '';

        foreach($arrayJsonObjs as $json) {
            $adress = SaveAdress($idDB, $json, $idOwner, $idTypeOwner);
            if (! empty($strJson)) $strJson .= ',';
            $strJson .= $adress->strJson;
        }

        $strJson = '"adresses":['.$strJson.']';
        return $strJson;
    }
    
    function SaveAdress($idDB, $json, $idOwner, $idEssential) {
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";


        $dbAdress = new DBAdress($idDB);
        $adress = $dbAdress->GetAdress($json->id);
        //$adress->idEssential = EnumEssential::ADRESS;
        
        $latitude = GetInt($json->latitude);
        $longitude = GetInt($json->longitude);

        $index = GetCleanString($json->index);
        $city = GetCleanString($json->city);
        $street = GetCleanString($json->street);
        $house = GetCleanString($json->house);
        $corpus = GetCleanString($json->corpus);
        $appartment = GetCleanString($json->appartment);
        $metro1 = GetCleanString($json->metro1);
        $metro2 = GetCleanString($json->metro2);
        $metro3 = GetCleanString($json->metro3);
        $description = GetCleanString($json->description);


        if ((!empty($index)) || (!empty($city)) || (!empty($street)) || 
            (!empty($house)) || (!empty($corpus)) || (!empty($appartment)) ||
            (!empty($metro1)) || (!empty($metro2)) || (!empty($metro3)) || (!empty($description))) {

            $adress->idEssential = $idEssential;
            $adress->idOwner = $idOwner;


            if (isSet($index)) {
                $strJsonOld .= ',"index":"'.$adress->strPostIndex.'"';
                $adress->strPostIndex = $index;
                $strJsonNew .= ',"index":"'.$adress->strPostIndex.'"';
            }

            if (isSet($city)) {
                $strJsonOld .= ',"city":"'.$adress->strCity.'"';
                $adress->strCity = $city;
                $strJsonNew .= ',"city":"'.$adress->strCity.'"';
            }

            if (isSet($street)) {
                $strJsonOld .= ',"street":"'.$adress->strStreet.'"';
                $adress->strStreet = $street;
                $strJsonNew .= ',"street":"'.$adress->strStreet.'"';
            }

            if (isSet($house)) {
                $strJsonOld .= ',"house":"'.$adress->strHouse.'"';
                $adress->strHouse = $house;
                $strJsonNew .= ',"house":"'.$adress->strHouse.'"';
            }

            if (isSet($corpus)) {
                $strJsonOld .= ',"corpus":"'.$adress->strCorpus.'"';
                $adress->strCorpus = $corpus;
                $strJsonNew .= ',"corpus":"'.$adress->strCorpus.'"';
            }

            if (isSet($appartment)) {
                $strJsonOld .= ',"appartment":"'.$adress->strAppartment.'"';
                $adress->strAppartment = $appartment;
                $strJsonNew .= ',"appartment":"'.$adress->strAppartment.'"';
            }

            if (isSet($metro1)) {
                $strJsonOld .= ',"metro1":"'.$adress->strMetro1.'"';
                $adress->strMetro1 = $metro1;
                $strJsonNew .= ',"metro1":"'.$adress->strMetro1.'"';
            }

            if (isSet($metro2)) {
                $strJsonOld .= ',"metro2":"'.$adress->strMetro2.'"';
                $adress->strMetro2 = $metro2;
                $strJsonNew .= ',"metro2":"'.$adress->strMetro2.'"';
            }

            if (isSet($metro3)) {
                $strJsonOld .= ',"metro3":"'.$adress->strMetro3.'"';
                $adress->strMetro3 = $metro3;
                $strJsonNew .= ',"metro3":"'.$adress->strMetro3.'"';
            }

            if (isSet($description)) {
                $strJsonOld .= ',"description":"'.$adress->strDescription.'"';
                $adress->strDescription = $description;
                $strJsonNew .= ',"description":"'.$adress->strDescription.'"';
            }

            $adress->id = $dbAdress->Save($adress);

            $strJson = $dbAdress->MakeJson($adress);

            $dbAdress->UpdateField("strJson", $strJson, "id=".$adress->id);
            $adress->strJson = '"adress":{'.$strJson.'}';
        }
        else {
            $dbAdress->SetDelete($adress->id);
        }


        $strGlobalJsonUpdate .= '{"id":'.$adress->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
        $strJsonRows .= '{'.$adress->strJson.',"essential":'.$adress->idEssential.'}';

        return $adress;
    }
?>