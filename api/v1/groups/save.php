<?php

    function SaveGroups($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveGroup($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveGroup($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbGroup = new DBGroup($idDB);
        $group = $dbGroup->GetGroup(GetInt($json->id));

        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$group->strName.'"';
            $group->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$group->strName.'"';
        }

        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$group->strDescription.'"';
            $group->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$group->strDescription.'"';
        }

        if (isSet($json->essential)) {
            $strJsonOld .= ',"essential":'.($group->idEssential + 0);
            $group->idEssential = GetInt($json->essential);
            $strJsonNew .= ',"essential":'.($group->idEssential + 0);
        }

        if (isSet($json->type)) {
            $strJsonOld .= ',"type":'.($group->idType + 0);
            $group->idType = GetInt($json->type);
            $strJsonNew .= ',"type":'.($group->idType + 0);
        }

        /*if (isSet($json->owner)) {
            $strJsonOld .= ',"owner":'.($group->idOwner + 0);
            $group->idOwner = GetInt($json->owner);
            $strJsonNew .= ',"owner":'.($group->idOwner + 0);
        }

        if (isSet($json->group)) {
            $strJsonOld .= ',"group":'.($group->idGroup + 0);
            $group->idGroup = GetInt($json->group);
            $strJsonNew .= ',"group":'.($group->idGroup + 0);
        }

        if (isSet($json->parent)) {
            $strJsonOld .= ',"parent":'.($group->idParent + 0);
            $group->idParent = GetInt($json->parent);
            $strJsonNew .= ',"parent":'.($group->idParent + 0);
        }*/
        
        $group->isNew = 0;

        $group->id = $dbGroup->Save($group);

        $group->strJson = $dbGroup->MakeJson($group);
        $dbGroup->UpdateField("strJson", $group->strJson, "id=".$group->id);
        $group->strJsonUpdate = '{"id":'.$group->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $group;
    }
?>