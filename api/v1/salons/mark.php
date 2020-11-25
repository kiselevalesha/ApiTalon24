<?php
    function MarkSalonsDeleted($idDB, $arrayIdDeletes) {
        global $idEssential, $strGlobalJsonReturnRows, $strGlobalJsonCommon, $strJsonRows, $strJsonHistories;

        //  сначала удалим самих клиентов
        $dbSalon = new DBSalon($idDB);
    
        SetDeletedByArrayIds($arrayIdDeletes, $dbSalon, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon = '{"name":"'.$dbSalon->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
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