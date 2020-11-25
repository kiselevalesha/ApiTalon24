<?php
    function GetJsonNew($idDB) {
        $dbTotalPayment = new DBTotalPayment($idDB);
        $totalPayment = $dbTotalPayment->New();
        
        //$totalPayment = new TotalPayment();
        return $totalPayment->ToJson();
    }
?>