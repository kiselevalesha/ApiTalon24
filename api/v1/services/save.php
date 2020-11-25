<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";

        $dbProduct = new DBProduct($idDB);
        $dbPricelist = new DBPricelist($idDB);
        $dbPricelistContent = new DBPricelistContent($idDB);
        
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $service = new Product();
            
            $service->id = GetInt($json->id);
            $service->idEssential = EnumEssential::SERVICES;
            $service->strName = GetCleanString($json->name);
            $service->strDescription = GetCleanString($json->description);
        
            $service->strArticul = GetCleanString($json->articul);
            $service->strBarCode = GetCleanString($json->barCode);
        
            $service->idUnitProduct = GetInt($json->unitProduct);
            $service->idUnitContent = GetInt($json->unitContent);
            $service->intQuantityBrutto = GetInt($json->quantityBrutto);
            $service->intQuantityNetto = GetInt($json->quantityNetto);
        
            $service->isForWoman = GetInt($json->isForWoman);
            $service->isForMan = GetInt($json->isForMan);
            $service->isForChildren = GetInt($json->isForChildren);
            $service->isForSale = GetInt($json->isForSale);
            $service->isShowOnline = GetInt($json->isShowOnline);
            $service->isAutoCalculation = GetInt($json->isAutoCalculation);
            $service->isUse = GetInt($json->isUse);
            $service->isNew = 0;
            
            /*$service->idCategory = $json->idCategory;
            $service->idNotificationRule = $json->idNotificationRule;
            $service->idTax = $json->idTax;
            $service->idUnitProduct = $json->idUnitProduct;
            $service->idItemWizard = $json->idItemWizard;
            $service->isForWoman = $json->isForWoman;
            $service->isForMan = $json->isForMan;
            $service->isForChildren = $json->isForChildren;
            $service->isForSale = $json->isForSale;
            $service->isUse = $json->isUse;
            $service->isShowOnline = $json->isShowOnline;
            $service->isNew = $json->isNew;
            $service->idGlobal = $json->idGlobal;
            $service->strKeyAuthor = $json->strKeyAuthor;
            $service->dateTimeGlobalChanged = $json->dateTimeGlobalChanged;*/
        
            $service->id = $dbProduct->SaveUpdate($service);
            
            $strJson = $dbProduct->MakeJson($service);
            $dbProduct->UpdateField("strJson", $strJson, "id=".$service->id);


            //  Выясним, указан ли прайслист. Если - нет, то возьмём по умолчанию.
            $idPricelist = 0;
            if ($json->pricelist->id > 0)
                $idPricelist = $json->pricelist->id;
            else
                $idPricelist = $dbPricelist->GetIdDefault();
            
            //  Теперь нужно сохранить данные для прайслиста.
            $pricelistContent = new PricelistContent();
            $pricelistContent->idPricelist = $idPricelist;
            $pricelistContent->idProduct = $service->id;
            $pricelistContent->costForSale = GetInt($json->cost);
            $pricelistContent->intDurationMinutes = GetInt($json->duration);
            
            $pricelistContent->id = $dbPricelistContent->SaveUpdate($pricelistContent);
            $strJsonPricelistContent = $dbPricelistContent->MakeJson($pricelistContent);
            $dbPricelistContent->UpdateField("strJson", $strJsonPricelistContent, "id=".$pricelistContent->id);
            
            //$strJsonRows .= $comma . '{'.$strJson.','.$strJsonPricelistContent.',"essential":'.EnumEssential::SERVICES.'}';
            $strJsonRows .= $comma . '{'.$strJson.','.$strJsonPricelistContent.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>