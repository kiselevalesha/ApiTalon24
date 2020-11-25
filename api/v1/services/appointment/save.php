<?php
    function SaveGroups($idDB, $arrayJsonObjs, $idOwner, $idEssential) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveGroup($idDB, $json, $idOwner, $idEssential);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveGroup($idDB, $json, $idOwner, $idEssential) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbGroup = new DBGroup($idDB);
        $dbOwnerGroup = new DBOwnerGroup($idDB);
        
        $idGroup = GetInt($json->id);
        $group = $dbGroup->GetGroup($idGroup);

        if (isSet($json->type)) {
            $strJsonOld .= ',"type":'.($group->idType + 0);
            $group->idType = GetInt($json->type);
            $strJsonNew .= ',"type":'.($group->idType + 0);
        }

        if (isSet($json->isSelected)) {
            $strJsonOld .= ',"isSelected":'.($group->isChecked + 0);
            $group->isChecked = GetInt($json->isSelected);
            $strJsonNew .= ',"isSelected":'.($group->isChecked + 0);
        }


        $group->idEssential = $idEssential;
        $group->idOwner = $idOwner;
        $group->idGroup = $group->id;

        $group->id = $dbOwnerGroup->GetId($group);
        $group->idRelation = $dbOwnerGroup->Save($group);

        $group->id = $group->idGroup;
        
        $group->strJson = $dbGroup->MakeJson($group).',"isSelected":'.$group->isChecked;
        $dbOwnerGroup->UpdateField("strJson", $group->strJson, "id=".$group->idRelation);
        
        $group->strJsonUpdate = '{"id":'.$group->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $group;
    }
?>