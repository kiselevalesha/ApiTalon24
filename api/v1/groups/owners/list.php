<?php
    function GetJsonList($idDB, $idEssential, $idOwner, $idType, $offset=0, $limit=0) {

        $dbGroup = new DBGroup($idDB);
        $dbOwnerGroup = new DBOwnerGroup($idDB);

        $strJson = "";
        $arrayRows = $dbOwnerGroup->GetArrayGroups($idOwner, $idEssential, $idType);
        foreach($arrayRows as $row) {

            $str = $dbGroup->MakeJson($row);
            $isSelected = 0;
            if ($row->isChecked)    $isSelected = 1;
            else                    $isSelected = 0;
            $str .= ',"isSelected":'.$isSelected;
            
            if (!empty($strJson))
                $strJson .= ',';
            $strJson .= '{'.$str.'}';
        }

        return $strJson;
    }
?>