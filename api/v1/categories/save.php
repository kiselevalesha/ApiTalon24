<?php

    function SaveCategories($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveCategory($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveCategory($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbCategory = new DBCategory($idDB);
        $category = $dbCategory->GetCategory(GetInt($json->id));

        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$category->strName.'"';
            $category->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$category->strName.'"';
        }

        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$category->strDescription.'"';
            $category->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$category->strDescription.'"';
        }

        if (isSet($json->essential)) {
            $strJsonOld .= ',"essential":'.($category->idEssential + 0);
            $category->idEssential = GetInt($json->essential);
            $strJsonNew .= ',"essential":'.($category->idEssential + 0);
        }

        if (isSet($json->owner)) {
            $strJsonOld .= ',"owner":'.($category->idOwner + 0);
            $category->idOwner = GetInt($json->owner);
            $strJsonNew .= ',"owner":'.($category->idOwner + 0);
        }

        if (isSet($json->category)) {
            $strJsonOld .= ',"category":'.($category->idCategory + 0);
            $category->idCategory = GetInt($json->category);
            $strJsonNew .= ',"category":'.($category->idCategory + 0);
        }

        if (isSet($json->parent)) {
            $strJsonOld .= ',"parent":'.($category->idParent + 0);
            $category->idParent = GetInt($json->parent);
            $strJsonNew .= ',"parent":'.($category->idParent + 0);
        }
        
        $category->isNew = 0;

        $category->id = $dbCategory->Save($category);

        $category->strJson = $dbCategory->MakeJson($category);
        $dbCategory->UpdateField("strJson", $category->strJson, "id=".$category->id);
        $category->strJsonUpdate = '{"id":'.$category->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $category;
    }
?>