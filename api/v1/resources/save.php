<?php
    require_once('../php-scripts/models/essential.php');
    require_once('../php-scripts/db/dbResources.php');

    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbResource = new DBResource($idDB);
        $comma = "";

        foreach($arrayJsonObjs as $json) {

            $obj = new Resource();
            
            $obj->id = GetNumeric($json->id);
            $obj->strName = GetCleanString($json->name);
            $obj->strDescription = GetCleanString($json->description);
        
            //$obj->strArticul = GetCleanString($json->articul);
            //$obj->strBarCode = GetCleanString($json->barCode);
        
            /*
            $obj->intQuantityNetto = $json->quantityNetto;
            */
            $obj->isShowOnline = GetInt($json->isShowOnline);
            $obj->isUse = GetInt($json->isUse);
            $obj->isNew = 0;
            
            $obj->id = $dbResource->SaveUpdate($obj);

            $dbResource->UpdateField("strJson", $dbResource->MakeJson($obj), "id=".$obj->id);
            $strJsonRows .= $comma . $obj->ToJson();
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>