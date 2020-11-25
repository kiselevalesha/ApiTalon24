<?php
    require_once '../php-scripts/db/dbBankAccounts.php';
    require_once '../php-scripts/models/essential.php';
    
    $strGlobalJsonUpdate = "";

    function SaveBankAccounts($idDB, $arrayJsonObjs, $idOwner, $idTypeOwner) {
        $strJson = '';

        foreach($arrayJsonObjs as $json) {
            $bankaccount = SaveBankAccount($idDB, $json, $idOwner, $idTypeOwner);
            if (! empty($bankaccount->strJson))
                if (! empty($strJson))
                    $strJson .= ',';
            $strJson .= $bankaccount->strJson;
        }

        $strJson = '"bankaccounts":['.$strJson.']';
        return $strJson;
    }
    
    function SaveBankAccount($idDB, $json, $idOwner, $idEssential) {
        global $strGlobalJsonUpdate;
        
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";


        $dbBankAccount = new DBBankAccount($idDB);
        $bankaccount = $dbBankAccount->GetBankAccount($json->id);
        //$bankaccount->idEssential = EnumEssential::BANKACCOUNTS;



        $bankaccount->idEssential = $idEssential;
        $bankaccount->idOwner = $idOwner;

        $isDefault = GetInt($json->isDefault);
        if (isSet($isDefault)) {
            $strJsonOld .= ',"isDefault":"'.$bankaccount->isDefault.'"';
            $bankaccount->isDefault = $isDefault;
            $strJsonNew .= ',"isDefault":"'.$bankaccount->isDefault.'"';
        }

        $strBIK = GetCleanString($json->BIK);
        if (isSet($strBIK)) {
            $strJsonOld .= ',"BIK":"'.$bankaccount->strBIK.'"';
            $bankaccount->strBIK = $strBIK;
            $strJsonNew .= ',"BIK":"'.$bankaccount->strBIK.'"';
        }

        $strName = GetCleanString($json->name);
        if (isSet($strName)) {
            $strJsonOld .= ',"name":"'.$bankaccount->strName.'"';
            $bankaccount->strName = $strName;
            $strJsonNew .= ',"name":"'.$bankaccount->strName.'"';
        }

        $strKorAccount = GetCleanString($json->KorAccount);
        if (isSet($strKorAccount)) {
            $strJsonOld .= ',"KorAccount":"'.$bankaccount->strKorAccount.'"';
            $bankaccount->strKorAccount = $strKorAccount;
            $strJsonNew .= ',"KorAccount":"'.$bankaccount->strKorAccount.'"';
        }

        $strRasAccount = GetCleanString($json->rasAccount);
        if (isSet($strRasAccount)) {
            $strJsonOld .= ',"rasAccount":"'.$bankaccount->strRasAccount.'"';
            $bankaccount->strRasAccount = $strRasAccount;
            $strJsonNew .= ',"rasAccount":"'.$bankaccount->strRasAccount.'"';
        }

        $description = GetCleanString($json->description);
        if (isSet($description)) {
            $strJsonOld .= ',"description":"'.$bankaccount->strDescription.'"';
            $bankaccount->strDescription = $description;
            $strJsonNew .= ',"description":"'.$bankaccount->strDescription.'"';
        }

        $bankaccount->id = $dbBankAccount->Save($bankaccount);
        $bankaccount->strJson = $dbBankAccount->MakeJson($bankaccount);
        $dbBankAccount->UpdateField("strJson", $bankaccount->strJson, "id=".$bankaccount->id);


        $strGlobalJsonUpdate .= '{"id":'.$bankaccount->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
        $strJsonRows .= '{'.$bankaccount->strJson.',"essential":'.$bankaccount->idEssential.'}';

        return $bankaccount;
    }
?>