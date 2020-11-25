<?php

    require_once '../php-scripts/utils/dbApi.php';


    //$dbHistory = new DBHistory($idDB);
    

    
    //  взять самую последнюю запись и распарсить её
    $history = $dbHistory->GetRecentRow();
    if ($history->id == 0)
        ExitEmptyError("No recent history.");
        
    $json = $dbHistory->GetJsonObjectFromRecentRow($history);

    switch ($history->idEssential) {
        
        case EnumEssential::SERVICES:
            require_once 'api/v1/commander/cancel/services.php';
            break;
        
        case EnumEssential::EMPLOYEE:
            require_once 'api/v1/commander/cancel/employee.php';
            break;
        
        case EnumEssential::CLIENTS:
            require_once 'api/v1/commander/cancel/clients.php';
            break;
    }


    function SetFieldDeleted($json, $valueIsDeleted) {
        global $idDB;
        $arrayIds = array();
        foreach($json->tables as $table) {
            $strTable = $table->name;
            if (! empty($strTable))
                foreach($table->rows as $row) {
                    
                    $dbBase = new DBBase();
                    $dbBase->SetLocalTableName($strTable, $idDB);
                    $query = "UPDATE ".$dbBase->strTableName." SET isDeleted=".$valueIsDeleted." WHERE id=".$row->id;
                    $result = $dbBase->ExecuteQuery($query);
                    array_push($arrayIds, $row->id);
                }
        }
        return $arrayIds;
    }
    
?>