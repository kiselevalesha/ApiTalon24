<?php
    require_once('../php-scripts/models/templateDay.php');
    require_once('../php-scripts/models/schedule.php');

    function GetJsonNew($age, $idEssentialOwner, $idOwner, $idSalon, $idPlace) {
        $shedule = new Shedule();
        $shedule->idTypeDay = EnumTypeTemplateDays::TypeDayWorking;
        $shedule->age = $age;
        $shedule->intTimeStart = 900;
        $shedule->intTimeEnd = 2000;
        $shedule->idEssentialOwner = $idEssentialOwner;
        $shedule->idOwner = $idOwner;
        $shedule->idSalon = $idSalon;
        $shedule->idPlace = $idPlace;
        $shedule->idColor = rand(1, 20);
        return $shedule->ToJson();
    }
?>