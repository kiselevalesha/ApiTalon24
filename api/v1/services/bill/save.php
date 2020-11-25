<?php

    function SaveAll($idDB, $arrayJsonObjs) {
        $dbProduct = new DBProduct($idDB);
        //$comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = new Product();
            
            $obj->id = $json->id;
            $obj->idEssential = EnumEssential::SERVICES;
            $obj->strName = $json->name;
            $obj->strDescription = $json->description;
        
            $obj->strArticul = $json->articul;
            $obj->strBarCode = $json->barCode;
        
            $obj->idUnitProduct = $json->unitProduct;
            $obj->idUnitContent = $json->unitContent;
            $obj->intQuantityBrutto = $json->quantityBrutto;
            $obj->intQuantityNetto = $json->quantityNetto;
        
            $obj->isForWoman = $json->isForWoman;
            $obj->isForMan = $json->isForMan;
            $obj->isForChildren = $json->isForChildren;
            $obj->isForSale = $json->isForSale;
            $obj->isShowOnline = $json->isShowOnline;
            $obj->isUse = $json->isUse;
            $obj->isNew = 0;
            
            /*$obj->idCategory = $json->idCategory;
            $obj->idNotificationRule = $json->idNotificationRule;
            $obj->idTax = $json->idTax;
            $obj->idUnitProduct = $json->idUnitProduct;
            $obj->idItemWizard = $json->idItemWizard;
            $obj->isForWoman = $json->isForWoman;
            $obj->isForMan = $json->isForMan;
            $obj->isForChildren = $json->isForChildren;
            $obj->isForSale = $json->isForSale;
            $obj->isUse = $json->isUse;
            $obj->isShowOnline = $json->isShowOnline;
            $obj->isNew = $json->isNew;
            $obj->idGlobal = $json->idGlobal;
            $obj->strKeyAuthor = $json->strKeyAuthor;
            $obj->dateTimeGlobalChanged = $json->dateTimeGlobalChanged;*/
        
            $obj->id = $dbProduct->SaveUpdate($obj);

            $dbProduct->UpdateField("strJson", $dbProduct->MakeJson($obj), "id=".$obj->id);
            //$strJsonRows .= $comma . $obj->ToJson();
            //$comma = ",";
        }
        
        //return $strJsonRows;
    }
?>