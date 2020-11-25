<?php
    function GetJsonList($arrayIds, $offset, $maximum) {

        require_once('../php-scripts/db/dbTokenEmployee.php');
        $dbTokenEmployee = new DBTokenEmployee();
        
        $sqlWhere = "";
        if ($id > 0)    $sqlWhere = "id=".$id;
        if ($maximum > 0)
            if ($offset >= 0)
                $sqlWhere = " LIMIT ".$offset.",".$maximum;
        $arrayObjs = $dbTokenEmployee->GetArrayOrderRows($sqlWhere, "id DESC");
        
        $strJson = "";
        $comma = "";
        foreach ($arrayObjs as $obj) {
            $strJson .= $comma.'{'.$obj->MakeJson().'}';
            $comma = ",";
        }

        return $strJson;
    }
?>