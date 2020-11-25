<?php
    function MarkMessageRulesDeleted($idDB, $arrayIdDeletes) {
        global $idEssential, $strGlobalJsonReturnRows, $strGlobalJsonCommon, $strJsonRows, $strJsonHistories;

        //  сначала удалим самих клиентов
        $dbMessageRule = new DBMessageRule($idDB);
    
        SetDeletedByArrayIds($arrayIdDeletes, $dbMessageRule, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon = '{"name":"'.$dbMessageRule->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
        $strGlobalJsonReturnRows = $strJsonRows;

        //  потом удалим связи клиентов с их контактами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    

        //  потом удалим связи клиентов с их адресами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    

        //  потом удалим связи клиентов с их фотографиями
        ///$dbPricelistContent = new DBPricelistContent($idDB);


        //  потом удалим связи клиентов с их группами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    


        /*$arraySqlWheres = array();
        foreach($arrayIdDeletes as $id)
            array_push($arraySqlWheres, "idProduct=".$id);

        SetDeletedByArraySqlWheres($arraySqlWheres, $dbPricelistContent, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon .= ',{"name":"'.$dbPricelistContent->strTableNameInitial.'","rows":['.$strJsonHistories.']}';*/
    }
?>