<?php

    $strGlobalJsonUpdate = "";

    function SaveAll($idDB, $arrayJsonObjs) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbProduct = new DBProduct($idDB);
        $dbPricelist = new DBPricelist($idDB);
        $dbPricelistContent = new DBPricelistContent($idDB);
        
        $strGlobalJsonUpdate = '{"name":"'.$dbProduct->strTableNameInitial.'","rows":[';

        
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = $dbProduct->GetProduct(GetInt($json->id));
            $obj->idEssential = EnumEssential::SERVICES;
            
            if (isSet($json->name)) {
                $strJsonOld .= ',"name":"'.$obj->strName.'"';
                $obj->strName = GetCleanString($json->name);
                $strJsonNew .= ',"name":"'.$obj->strName.'"';
            }
            if (isSet($json->description)) {
                $strJsonOld .= ',"description":"'.$obj->strDescription.'"';
                $obj->strDescription = GetCleanString($json->description);
                $strJsonNew .= ',"description":"'.$obj->strDescription.'"';
            }

            
            if (isSet($json->articul)) {
                $strJsonOld .= ',"articul":"'.$obj->strArticul.'"';
                $obj->strArticul = GetCleanString($json->articul);
                $strJsonNew .= ',"articul":"'.$obj->strArticul.'"';
            }
            if (isSet($json->barCode)) {
                $strJsonOld .= ',"barCode":"'.$obj->strBarCode.'"';
                $obj->strBarCode = GetCleanString($json->barCode);
                $strJsonNew .= ',"barCode":"'.$obj->strBarCode.'"';
            }


            if (isSet($json->unitProduct))
                if (isSet($json->unitProduct->id)) {
                    $strJsonOld .= ',"unitProduct":{"id":'.$obj->idUnitProduct.'}';
                    $obj->idUnitProduct = GetInt($json->unitProduct->id);
                    $strJsonNew .= ',"unitProduct":{"id":'.$obj->idUnitProduct.'}';
                }
            if (isSet($json->unitContent))
                if (isSet($json->unitContent->id)) {
                    $strJsonOld .= ',"unitContent":{"id":'.$obj->idUnitContent.'}';
                    $obj->idUnitContent = GetInt($json->unitContent->id);
                    $strJsonNew .= ',"unitContent":{"id":'.$obj->idUnitContent.'}';
                }



            if (isSet($json->quantityBrutto)) {
                $strJsonOld .= ',"quantityBrutto":'.($obj->intQuantityBrutto + 0);
                $obj->intQuantityBrutto = GetInt($json->quantityBrutto);
                $strJsonNew .= ',"quantityBrutto":'.($obj->intQuantityBrutto + 0);
            }
            if (isSet($json->quantityNetto)) {
                $strJsonOld .= ',"quantityNetto":'.($obj->intQuantityNetto + 0);
                $obj->intQuantityNetto = GetInt($json->quantityNetto);
                $strJsonNew .= ',"quantityNetto":'.($obj->intQuantityNetto + 0);
            }


            if (isSet($json->isForWoman)) {
                $strJsonOld .= ',"isForWoman":'.($obj->isForWoman + 0);
                $obj->isForWoman = GetInt($json->isForWoman);
                $strJsonNew .= ',"isForWoman":'.($obj->isForWoman + 0);
            }
            if (isSet($json->isForMan)) {
                $strJsonOld .= ',"isForMan":'.($obj->isForMan + 0);
                $obj->isForMan = GetInt($json->isForMan);
                $strJsonNew .= ',"isForMan":'.($obj->isForMan + 0);
            }
            if (isSet($json->isForChildren)) {
                $strJsonOld .= ',"isForChildren":'.($obj->isForChildren + 0);
                $obj->isForChildren = GetInt($json->isForChildren);
                $strJsonNew .= ',"isForChildren":'.($obj->isForChildren + 0);
            }


            if (isSet($json->isForSale)) {
                $strJsonOld .= ',"isForSale":'.($obj->isForSale + 0);
                $obj->isForSale = GetInt($json->isForSale);
                $strJsonNew .= ',"isForSale":'.($obj->isForSale + 0);
            }
            if (isSet($json->isShowOnline)) {
                $strJsonOld .= ',"isShowOnline":'.($obj->isShowOnline + 0);
                $obj->isShowOnline = GetInt($json->isShowOnline);
                $strJsonNew .= ',"isShowOnline":'.($obj->isShowOnline + 0);
            }
            if (isSet($json->isUse)) {
                $strJsonOld .= ',"isUse":'.($obj->isUse + 0);
                $obj->isUse = GetInt($json->isUse);
                $strJsonNew .= ',"isUse":'.($obj->isUse + 0);
            }

            $obj->isNew = 0;

            $obj->id = $dbProduct->SaveUpdate($obj);

            $strJson = $dbProduct->MakeJson($obj);
            $dbProduct->UpdateField("strJson", $strJson, "id=".$obj->id);


            //  Теперь заносим данные в содержимое прайслиста

            $idPricelist = GetInt($json->pricelist->id);
            if ($idPricelist < 1)   $idPricelist = $dbPricelist->GetIdDefault();
            
            $idContent = $dbPricelistContent->GetId($obj->id, $idPricelist);
            $content = $dbPricelistContent->GetContent($idContent);

            $strJsonContentNew = "";
            $strJsonContentOld = "";


            if (isSet($json->cost)) {
                $strJsonOld .= ',"cost":'.($content->costForSale+0);
                $content->costForSale = GetInt($json->cost);
                $strJsonNew .= ',"cost":'.($content->costForSale+0);
            }
            if (isSet($json->duration)) {
                $strJsonOld .= ',"duration":'.($content->intDurationMinutes+0);
                $content->intDurationMinutes = GetInt($json->duration);
                $strJsonNew .= ',"duration":'.($content->intDurationMinutes+0);
            }

            $content->idPricelist = $idPricelist;
            $content->idProduct = $obj->id;
            $content->idEssential = EnumEssential::SERVICES;
            
            $content->id = $dbPricelistContent->SaveUpdate($content);

            $strJsonContent = $dbPricelistContent->MakeJson($content);
            $dbPricelistContent->UpdateField("strJson", $strJsonContent, "id=".$content->id);


            //  если есть изменения по содержимому прайслиста
            if (! empty($strJsonContentNew)) {
                if (! empty($strJsonNew))   $strJsonNew .= ',';
                $strJsonNew .= '"pricelist":{'.substr($strJsonContentNew, 1).'}';
            }
            if (! empty($strJsonContentOld)) {
                if (! empty($strJsonOld))   $strJsonOld .= ',';
                $strJsonOld .= '"pricelist":{'.substr($strJsonContentOld, 1).'}';
            }

            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson.',"pricelist":{'.$strJsonContent.'},"essential":'.EnumEssential::SERVICES.'}';
            $comma = ",";
        }
        
        $strGlobalJsonUpdate .= ']}';
        return $strJsonRows;
    }
?>