<?php
    function SaveAll($arrayJsonObjs) {
        $strJsonRows = "";
        if (isSet($request->objects)) {
            global $idDB;
            require_once('../php-scripts/db/dbTaxes.php');
            $dbTax = new DBTax($idDB);
            $comma = "";
            foreach($arrayJsonObjs as $json) {
                $obj = new Tax();
                $obj->id = GetNumeric($json->id);
                $obj->strName = GetCleanString($json->name);
                $obj->strDescription = GetCleanString($json->description);
                $obj->idEssential = GetInt($json->essential);
                $obj->idType = GetInt($json->type);
                $obj->isNew = 0;
                $obj->id = $dbTax->Save($obj);
                
                $dbTax->UpdateField("strJson", $dbTax->MakeJson($obj), "id=".$obj->id);
                $strJsonRows .= $comma . $obj->ToJson();
                $comma = ",";
            }
        }
        return $strJsonRows;
    }
?>