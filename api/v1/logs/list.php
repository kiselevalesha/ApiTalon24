<?php
    function GetJsonList() {
        global $idDB;

        $strJson = "";
        if ($idDB > 0) {
            require_once('../php-scripts/db/dbLogs.php');
            $dbLog = new DBLog($idDB);
    
            $sqlWhere = "";
            $arrayObjs = $dbLog->GetArrayRows($sqlWhere);
    
            foreach ($arrayObjs as $obj) {
                if (strlen($strJson) > 0)   $strJson .= ",";
                $strJson .= '{'.$obj->MakeJson().'}';
            }
        }
    
        return $strJson;
    }
?>