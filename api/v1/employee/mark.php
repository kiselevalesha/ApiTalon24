<?php
    function MarkEmployeeDeleted($idDB, $arrayIdDeletes) {
        global $idEssential, $strGlobalJsonReturnRows, $strGlobalJsonCommon, $strJsonRows, $strJsonHistories;
        
        //  сначала удалим самих сотрудников
        $dbEmployee = new DBEmployee($idDB);
    
        SetDeletedByArrayIds($arrayIdDeletes, $dbEmployee, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon = '{"name":"'.$dbEmployee->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
        $strGlobalJsonReturnRows = $strJsonRows;

        //  потом удалим связи сотрудников с их контактами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    

        //  потом удалим связи сотрудников с их адресами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    

        //  потом удалим связи сотрудников с их фотографиями
        ///$dbPricelistContent = new DBPricelistContent($idDB);


        //  потом удалим связи сотрудников с их группами
        ///$dbPricelistContent = new DBPricelistContent($idDB);
    


        /*$arraySqlWheres = array();
        foreach($arrayIdDeletes as $id)
            array_push($arraySqlWheres, "idProduct=".$id);

        SetDeletedByArraySqlWheres($arraySqlWheres, $dbPricelistContent, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon .= ',{"name":"'.$dbPricelistContent->strTableNameInitial.'","rows":['.$strJsonHistories.']}';*/
    }
?>