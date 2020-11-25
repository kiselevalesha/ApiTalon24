<?php
    function GetJsonNew($idDB) {
        //$dbAppointment = new DBAppointment($idDB);
        //$review = $dbAppointment->New();
        
        $review = new Appointment();
        return $review->ToJson();
    }
?>