<?php
    function MarkServicesDeleted($idDB, $arrayIdDeletes) {
        global $idEssential, $strGlobalJsonReturnRows, $strGlobalJsonCommon, $strJsonRows, $strJsonHistories;
        
        //  сначала удалим услуги
        $dbProduct = new DBProduct($idDB);
    
        SetDeletedByArrayIds($arrayIdDeletes, $dbProduct, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon = '{"name":"'.$dbProduct->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
        $strGlobalJsonReturnRows = $strJsonRows;

        //  потом удалим связи услуг со всеми прайслистами
        $dbPricelistContent = new DBPricelistContent($idDB);
    
        $arraySqlWheres = array();
        foreach($arrayIdDeletes as $id)
            array_push($arraySqlWheres, "idProduct=".$id);

        SetDeletedByArraySqlWheres($arraySqlWheres, $dbPricelistContent, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon .= ',{"name":"'.$dbPricelistContent->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
    }
?>