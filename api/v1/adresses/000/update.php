<?php

    require_once('../php-scripts/models/essential.php');
    function UpdateAdress($jsonAdress, $idOwner, $idEssential) {
        $strJsonAdress = "";
        if (isSet($jsonAdress)) {
            global $idDB;
            require_once('../php-scripts/db/dbAdresses.php');
            $dbAdress = new DBAdress($idDB);
            $adress = $dbAdress->GetAdress($jsonAdress->id);

            if ((!empty($jsonAdress->index)) || (!empty($jsonAdress->city)) || (!empty($jsonAdress->street)) || 
                (!empty($jsonAdress->house)) || (!empty($jsonAdress->corpus)) || (!empty($jsonAdress->appartment))) {
                //$adress->id = $jsonAdress->id;
                $adress->idEssential = $idEssential;
                $adress->idOwner = $idOwner;
                $adress->strPostIndex = $jsonAdress->index;
                //$adress->strCountry = $jsonAdress->country;
                //$adress->strRegion = $jsonAdress->region;
                $adress->strCity = $jsonAdress->city;
                $adress->strStreet = $jsonAdress->street;
                $adress->strHouse = $jsonAdress->house;
                $adress->strCorpus = $jsonAdress->corpus;
                $adress->strAppartment = $jsonAdress->appartment;
                $adress->strDescription = $jsonAdress->description;
                $adress->id = $dbAdress->Save($adress);
                
                if ($adress instanceof Adress) {
                    $strJsonAdress = $adress->MakeJson();
                    $dbAdress->UpdateField("strJson", $strJsonAdress, "id=".$adress->id);
                    $strJsonAdress = '"adress":{'.$strJsonAdress.'}';
                }
            }
            else {
                $dbAdress->DeleteRow($adress->id);
            }
        }
        return $strJsonAdress;
    }
?>