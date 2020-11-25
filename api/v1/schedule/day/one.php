<?php
    function GetJsonOne($idDB) {
        $idSalon = GetInt($request->salon);
        if (empty($idSalon))     $idSalon = 1;
        $idEmployee = GetInt($request->employee);
        if (empty($idEmployee))     $idEmployee = 1;
        $age = ''.GetInt($request->year).GetTwoNumbers(GetInt($request->month)).GetTwoNumbers(GetInt($request->day));
    
    
        require_once('../php-scripts/models/essential.php');
        require_once('../php-scripts/db/dbSchedule.php');
        $dbSchedule = new DBSchedule($idDB);
        $sqlWhere = "idEssentialOwner=".EnumEssential::EMPLOYEE." AND idOwner=".$idEmployee." AND age=".$age;
        $shedule = $dbSchedule->GetRowBySql($sqlWhere);
        if ($shedule instanceof Shedule)
            $strJsonShedule = '"start":'.$shedule->intTimeStart.',"end":'.$shedule->intTimeEnd;
    
    
    
        //  Найти все Записи
        require_once '../php-scripts/api/v1/appointments/out.php';
        require_once('../php-scripts/db/dbAppointments.php');
        $dbAppointment = new DBAppointment($idDB);
        $start = $age."000000";
        $end = $age."240000";
        $sqlWhere = "ageOrderStart>=".$start." AND ageOrderStart<=".$end." AND idSalon=".$idSalon.
                " AND (idMaster1=".$idEmployee." OR idMaster2=".$idEmployee.
                " OR idAssistent1=".$idEmployee." OR idAssistent2=".$idEmployee.") AND isDeleted=0 AND isNew=0";
        $arrayAppointments = $dbAppointment->GetArrayOrderRows($sqlWhere, "ageOrderStart");
    
        $strJsonAppointments = "";
        foreach($arrayAppointments as $appointment) {
            if (!empty($strJsonAppointments))   $strJsonAppointments .= ',';
            $strJsonAppointments .= GetJsonAppointment($appointment, $idDB);
        }
        
    
    
        //  Найти все резервирования
        require_once('../php-scripts/db/dbReservationTimes.php');
        $dbReservationTime = new DBReservationTime($idDB);
        $sqlWhere = "idEssentialOwner=".EnumEssential::EMPLOYEE." AND idOwner=".$idEmployee." AND isDeleted=0";
        if (!empty($age))   $sqlWhere .= " AND dateTimeDay=".$age;
        $arrayReservationTimes = $dbReservationTime->GetArrayOrderRows($sqlWhere, "dateTimeDay, intTimeStart");
    
        $strJsonReservationTimes = "";
        foreach($arrayReservationTimes as $reservationTime) {
            if (!empty($strJsonReservationTimes))   $strJsonReservationTimes .= ',';
            $strJsonReservationTimes .= $reservationTime->ToJson();
        }
    
    
        //  Найти settings
        require_once('../php-scripts/db/dbSettings.php');
        $dbSettings = new DBSettings($idDB);
        $settings = $dbSettings->GetDefault();
        $strJsonSettings .= '{"period":'.$settings->intPeriodMinutes.',"between":'.$settings->intBetweenMinutes.',"before":'.$settings->intBeforeMinutes.
                            ',"showDays":'.$settings->intCountDaysForAppointments.',"start":'.$settings->intTimeStart.',"end":'.$settings->intTimeEnd.'}';
    


        $strJson = '"master":"'.$uidMaster.'","salon":"'.$uidSalon.'","year":"'.$year.'","month":"'.$month.'","day":"'.$day.'","start":"'.$shedule->start.'","end":"'.$shedule->end.'"';
        $strJson .= ',"appointments":['.$strJsonAppointments.'],"reservations":['.$strJsonReservationTimes.'],"shedule":{'.$strJsonShedule.'},"settings":'.$strJsonSettings;
        return '{'.$strJson.'}';
    }
?>