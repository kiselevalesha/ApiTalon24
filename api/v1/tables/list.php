<?php
    function GetJsonList() {
        global $idDB;
        require_once('../php-scripts/db/dbBase.php');
        $dbBase = new DBBase();
        
        AddOtherTables();
        array_push($arrayTables, "Settings");
        array_push($arrayTables, "Log");
        // ... добавь все остальные таблицы потом
        
        $strJson = "";
        for ($i = 0; $i < sizeOf($arrayTables); $i++) {
            $table = $arrayTables[$i];
    
            $dbBase->SetLocalTableName($table, $idDB);
            
            if ($dbBase->IsExistTable()) {
                $countRows = $dbBase->GetCountRows("");
                if (strlen($strJson) > 0)   $strJson .= ",";
                $strJson .= '{"name":"'.$table.'","source":"'.$table.'_'.$idDB.'","rows":'.$countRows.'}';
            }
        }
    
        return $strJson;
    }
?>