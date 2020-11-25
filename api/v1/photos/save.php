<?php

    $strGlobalJsonUpdate = "";

    function SaveAll($idDB, $arrayJsonObjs) {
        global $strGlobalJsonUpdate;
        $strJsonRows = "";
        $strJsonOld = "";
        $strJsonNew = "";

        $dbImage = new DBImage($idDB);

        $strGlobalJsonUpdate = '{"name":"'.$dbImage->strTableNameInitial.'","rows":[';

        
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $obj = $dbImage->GetImage(GetInt($json->id));
            $obj->idEssential = EnumEssential::PHOTOS;

            if (isSet($json->site)) {
                $strJsonOld .= ',"site":'.($obj->idSite + 0);
                $obj->idSite = GetInt($json->site);
                $strJsonNew .= ',"site":'.($obj->idSite + 0);
            }
            if (isSet($json->essential)) {
                $strJsonOld .= ',"essential":'.($obj->idEssential + 0);
                $obj->idEssential = GetInt($json->essential);
                $strJsonNew .= ',"essential":'.($obj->idEssential + 0);
            }
            if (isSet($json->owner)) {
                $strJsonOld .= ',"owner":'.($obj->idOwner + 0);
                $obj->idOwner = GetInt($json->owner);
                $strJsonNew .= ',"owner":'.($obj->idOwner + 0);
            }

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
            if (isSet($json->descriptionOnline)) {
                $strJsonOld .= ',"descriptionOnline":"'.$obj->strDescriptionOnline.'"';
                $obj->strDescriptionOnline = GetCleanString($json->descriptionOnline);
                $strJsonNew .= ',"descriptionOnline":"'.$obj->strDescriptionOnline.'"';
            }
            if (isSet($json->url)) {
                $strJsonOld .= ',"url":"'.$obj->strUrl.'"';
                $obj->strUrl = GetCleanString($json->url);
                $strJsonNew .= ',"url":"'.$obj->strUrl.'"';
            }

            if (isSet($json->fileSize)) {
                $strJsonOld .= ',"fileSize":'.($obj->intFileSize + 0);
                $obj->intFileSize = GetInt(fileSize);
                $strJsonNew .= ',"fileSize":'.($obj->intFileSize + 0);
            }
            
            if (isSet($json->isPublish)) {
                $strJsonOld .= ',"isPublish":'.($obj->isPublish + 0);
                $obj->isPublish = GetInt($json->isPublish);
                $strJsonNew .= ',"isPublish":'.($obj->isPublish + 0);
            }
            if (isSet($json->isShowOnline)) {
                $strJsonOld .= ',"isShowOnline":'.($obj->isShowOnline + 0);
                $obj->isShowOnline = GetInt($json->isShowOnline);
                $strJsonNew .= ',"isShowOnline":'.($obj->isShowOnline + 0);
            }
            if (isSet($json->isMainInSequence)) {
                $strJsonOld .= ',"isMainInSequence":'.($obj->isMainInSequence + 0);
                $obj->isMainInSequence = GetInt($json->isMainInSequence);
                $strJsonNew .= ',"isMainInSequence":'.($obj->isMainInSequence + 0);
            }
            if (isSet($json->isUseInPortfolio)) {
                $strJsonOld .= ',"isUseInPortfolio":'.($obj->isUseInPortfolio + 0);
                $obj->isUseInPortfolio = GetInt($json->isUseInPortfolio);
                $strJsonNew .= ',"isUseInPortfolio":'.($obj->isUseInPortfolio + 0);
            }

            if (isSet($json->essentialAuthor)) {
                $strJsonOld .= ',"essentialAuthor":'.($obj->idEssentialAuthor + 0);
                $obj->idEssentialAuthor = GetInt($json->essentialAuthor);
                $strJsonNew .= ',"essentialAuthor":'.($obj->idEssentialAuthor + 0);
            }

            if (isSet($json->author)) {
                $strJsonOld .= ',"author":'.($obj->idAuthor + 0);
                $obj->idAuthor = GetInt($json->author);
                $strJsonNew .= ',"author":'.($obj->idAuthor + 0);
            }

            if (isSet($json->ageChanged)) {
                $strJsonOld .= ',"ageChanged":'.($obj->ageChanged + 0);
                $obj->ageChanged = GetInt($json->ageChanged);
                $strJsonNew .= ',"ageChanged":'.($obj->ageChanged + 0);
            }

            $obj->isNew = 0;
            
            //  проверяем, что такого файла уже не существует в базе.
            if ($obj->id == 0)
                $obj->id = $dbImage->GetId($obj);

            $obj->id = $dbImage->Save($obj);

            $strJson = $dbImage->MakeJson($obj);
            $dbImage->UpdateField("strJson", $strJson, "id=".$obj->id);


            $strGlobalJsonUpdate .= $comma.'{"id":'.$obj->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';
            $strJsonRows .= $comma.'{'.$strJson.'}';
            $comma = ",";
        }
        
        $strGlobalJsonUpdate .= ']}';
        return $strJsonRows;
    }
?>