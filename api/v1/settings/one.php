<?php
    function GetJsonOne($idDB) {
        $dbSettings = new DBSettings($idDB);
        $settings = $dbSettings->GetDefault();
    
        if ($settings instanceof Settings)  $strJson = $settings->ToJson();
        else
            $strJson = '{"isShowTips":1,"isUseAudio":1,"isUseOnlineAppointment":1,"isChoosePlace":0,"isChooseMasters":0,"isChooseResources":0,"countDaysForAppointments":7,"startTime":900,"endTime":2100,"period":30,"between":0,"before":120}';
    
        return '"settings":'.$strJson;
    }
?>