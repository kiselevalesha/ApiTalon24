<?php
    function MarkMessagesDeleted($idDB, $arrayIdDeletes) {
        global $idEssential, $strGlobalJsonReturnRows, $strGlobalJsonCommon, $strJsonRows, $strJsonHistories;

        //  сначала удалим самих клиентов
        $dbMessage = new DBMessage($idDB);
    
        SetDeletedByArrayIds($arrayIdDeletes, $dbMessage, 1);
        GetJsonsMarked($arrayIdDeletes, $idEssential, 1);
        $strGlobalJsonCommon = '{"name":"'.$dbMessage->strTableNameInitial.'","rows":['.$strJsonHistories.']}';
        $strGlobalJsonReturnRows = $strJsonRows;

    }
?>