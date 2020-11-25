<?php
    require_once '../php-scripts/db/dbContacts.php';
    
    $strGlobalJsonUpdate = "";

    function SaveContacts($idDB, $arrayJsonObjs, $idOwner, $idTypeOwner) {
        ///global $strGlobalJsonUpdate;
        ///$strGlobalJsonUpdate = '{"name":"'.$dbContact->strTableNameInitial.'","rows":[';
        $strJson = '"contacts":[';

        foreach($arrayJsonObjs as $json) {
            $contact = SaveContact($idDB, $json, $idOwner, $idTypeOwner);
            $strJson .= $contact->strJson;
        }

        ///$strGlobalJsonUpdate .= ']}';
        $strJson .= ']';
        return $strJson;
    }
    
    function SaveContact($idDB, $json, $idOwner, $idTypeOwner) {
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbContact = new DBContact($idDB);

        $contact = $dbContact->GetContact(GetInt($json->id));
        $contact->idEssential = EnumEssential::CONTACTS;

        $contact = $dbContact->SaveUserContact($idOwner, $idTypeOwner, GetInt($json->type), GetCleanString($json->body));

        $contact->strJson = $dbContact->MakeJson($contact);
        $dbContact->UpdateField("strJson", $contact->strJson, "id=".$contact->id);

        $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
        $strJsonRows .= $comma.'{'.$contact->strJson;

        $strJsonRows .= ',"essential":'.$contact->idEssential.'}';
        $comma = ",";

        //return $strJsonRows;
        return $contact;
    }
?>