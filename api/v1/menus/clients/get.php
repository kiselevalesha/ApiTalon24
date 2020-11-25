<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    require_once('../php-scripts/db/dbClients.php');
    $dbClient = new DBClient($idDB);
    $countClients = $dbClient->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonClients = '"clients":'.$countClients;



    $ageLastShowReviews = GetInt($json->ageLastShowReviews);
    require_once('../php-scripts/db/dbAppointments.php');
    $dbAppointment = new DBAppointment($idDB);
    $sqlWhere = "isDeleted=0 AND isNew=0 AND ageReview>0 AND ageReview>=".$ageLastShowReviews;
    $countReviews = $dbAppointment->GetCountRows($sqlWhere);
    $strJsonReviews = '"reviews":'.$countReviews;   


    $analytics = "Нет данных";
    $strJsonAnalytics = '"analytics":"'.$analytics.'"';

    $sendings = "Нет данных";
    $strJsonSendings = '"sendings":"'.$sendings.'"';

    $supply = "Выгодные предложения";
    $strJsonSupply = '"supply":"'.$supply.'"';


    $strJson = $strJsonClients.','
    .$strJsonAnalytics.','
    .$strJsonReviews.','
    .$strJsonSendings.','
    .$strJsonSupply;

    EndResponseData("menu", $strJson.',"last":0');
?>