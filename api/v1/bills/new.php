<?php
    function GetJsonNew($idDB) {
        $dbBill = new DBBill($idDB);
        
        $bill = $dbBill->New();
        return $bill->ToJson();
    }
?>