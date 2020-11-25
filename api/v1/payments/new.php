<?php
    function GetJsonNew($idDB) {
        //$dbPayment = new DBPayment($idDB);
        //$payment = $dbPayment->New();
        
        $payment = new Payment();
        return $payment->ToJson();
    }
?>