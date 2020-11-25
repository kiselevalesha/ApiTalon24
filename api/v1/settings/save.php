<?php
    function Save($idDB, $request) {
        $dbSettings = new DBSettings($idDB);

        $obj = new Settings();
        if ($dbSettings->GetCountRows("") == 0)     $obj->id = 0;
        else                                        $obj->id = 1;
        
        
        $obj->idTypeConfiguration = GetInt($request->typeConfiguration);
        $obj->idTypeFunctionality = GetInt($request->typeFunctionality);
        
        $obj->intTimeStart = GetInt($request->timeStart);
        $obj->intTimeEnd = GetInt($request->timeEnd);

        $obj->intCountDaysForAppointments = GetInt($request->countDaysForAppointments);
        $obj->intPeriodMinutes = GetInt($request->period);
        $obj->intBetweenMinutes = GetInt($request->between);
        $obj->intBeforeMinutes = GetInt($request->before);
        
        $obj->isShowTips = GetInt($request->isShowTips);
        $obj->isUseAudio = GetInt($request->isUseAudio);
    
        $obj->isUseOnlineAppointment = GetInt($request->isUseOnlineAppointment);
        $obj->isChoosePlace = GetInt($request->isChoosePlace);
        $obj->isChooseMasters = GetInt($request->isChooseMasters);
        $obj->isChooseResources = GetInt($request->isChooseResources);
    
        $obj->id = $dbSettings->Save($obj);
    
        return $obj->ToJson();
    }
?>