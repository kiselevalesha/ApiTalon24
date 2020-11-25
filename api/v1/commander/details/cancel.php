<?php

    require_once '../php-scripts/utils/dbApi.php';


    $dbHistory = new DBHistory($idDB);
    $dbBase = new DBBase();

    
    //  взять самую последнюю запись и распарсить её
    $history= $dbHistory->GetRecentRow();
    $json = $dbHistory->GetJsonObjectFromRecentRow($history);

    //  в зависимости от action восстановить по разному.
    switch ($history->idAction) {
        
        case EnumTypeActions::ActionCreate:  //  удалить созданный
        
            //  загрузим в массив все удаляемые айдишники
            $arrayIdDeletes = array();
            foreach($json->tables as $table) {
                $strTable = $table->name;
                if (! empty($strTable))
                    foreach($table->rows as $row)
                        array_push($arrayIdDeletes, $row->id);
            }

            switch ($json->essential) {
                case EnumEssential::SERVICES:
                    require_once 'api/v1/services/pricelist/mark.php';
                    MarkServicesDeleted($arrayIdDeletes);
                    EndResponsePureData('"objects":['.$strJsonReturnRows.'],"action":"delete"');
                    break;
            }
            break;

        case EnumTypeActions::ActionEdit:    //  вернуть изменения
            foreach($json->tables as $table) {
                $strTable = $table->name;
                if (! empty($strTable))
                    foreach($table->rows as $row) {

                        $jsonParameters = $row->old;
                        $jsonParameters->id = $row->id;
                        $arrayJsonObjs = array();
                        array_push($arrayJsonObjs, $jsonParameters);
                        switch ($history->idEssential) {
                            case EnumEssential::SERVICES:
                                require_once('../php-scripts/db/dbProducts.php');
                                require_once('../php-scripts/db/dbPricelists.php');
                                require_once('../php-scripts/db/dbPricelistContents.php');
                                require_once 'api/v1/services/pricelist/save.php';
                                $strJsonRows = SaveAll($idDB, $arrayJsonObjs);
                                EndResponsePureData('"objects":['.$strJsonRows.'],"action":"edit"');
                                break;
                        }
                    }
            }
            break;

        case EnumTypeActions::ActionDelete:  //  вернуть удалённый
            $arrayIds = SetFieldDeleted($json, 0);
            switch ($json->essential) {
                case EnumEssential::SERVICES:
                    $dbProduct = new DBProduct($idDB);
                    $strJsonRows = GetJsonListRows($arrayIds, $dbProduct);
                    EndResponsePureData('"objects":['.$strJsonRows.'],"action":"edit"');
                    break;
            }
            break;
    }
    
    
    function SetFieldDeleted($json, $valueIsDeleted) {
        $arrayIds = array();
        foreach($json->tables as $table) {
            $strTable = $table->name;
            if (! empty($strTable))
                foreach($table->rows as $row) {

                    $dbBase->SetLocalTableName($strTable, $idDB);
                    $query = "UPDATE ".$dbBase->strTableName." SET isDeleted=".$valueIsDeleted." WHERE id=".$row->id;
                    $result = $this->ExecuteQuery($query);
                    array_push($arrayIds, $row->id);
                }
        }
        return $arrayIds;
    }
    
?>