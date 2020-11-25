<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    $start = GetInt($json->start);
    $end = GetInt($json->end);
    
    $strJsonWastes = "";
    require_once('../php-scripts/db/dbSubscriptionWaste.php');
    $dbSubscriptionWaste = new DBSubscriptionWaste($idDB);
    $arrayWastes = $dbSubscriptionWaste->GetArrayRows("ageDay>=".$start." AND ageDay<=".$end);
    foreach ($arrayWastes as $waste) {
        if (strlen($strJsonWastes) > 0)   $strJsonWastes .= ",";
        //$strJsonWastes .= $waste->ToJson();
        $strJsonWastes .= '{"s":'.$waste->idService.',"c":'.$waste->cost.',"a":'.$waste->ageDay.'}';
    }

    
    $strJsonServices = "";
    require_once('../php-scripts/db/dbSubscriptions.php');
    $dbSubscription = new DBSubscription($idDB);
    $arrayServices = $dbSubscription->GetArrayRows("");
    foreach ($arrayServices as $service) {
        if (strlen($strJsonServices) > 0)   $strJsonServices .= ",";
        $strJsonServices .= '{"id":'.$service->idService.',"name":"'.$service->strName.'"}';
    }

    
    $strJsonMessages = "";
    require_once('../php-scripts/db/dbMessages.php');
    $dbMessage = new DBMessage($idDB);
    $arrayMessages = $dbMessage->GetArrayRows("cost>0 AND ageWasSended>=".$start." AND ageWasSended<=".$end);
    foreach ($arrayMessages as $message) {
        if (strlen($strJsonMessages) > 0)   $strJsonMessages .= ",";
        $strJsonMessages .= '{"id":'.$message->id.',"c":'.$message->cost.'}';
    }

    
    echo GetOutJson('"waste":['.$strJsonWastes.'],"services":['.$strJsonServices.'],"messages":['.$strJsonMessages.']');
?>