<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';



    //  ЗДЕСЬ ПРОИСХОДИТ ИНИЦИАЛИЗАЦИЯ ПЕРВОНАЧАЛЬНЫМИ ДАННЫМИ СОЗДАННОЙ НОВОЙ DB
    //require_once('api/v1/initialization/loadtables.php');



    /*
    require_once('../php-scripts/db/dbAssistentTips.php');
    $dbAssistentTip = new DBAssistentTip($idDB);
    $countTips = $dbAssistentTip->GetCountRows("isViewed=0");
    if ($countTips == 0) {
        $str = 'Советов нет';
    } else {
        $str = $countTips.' совет'.GetLastSlog(GetLastDigit($countTips), '', 'а', 'ов');
    }*/
    $strJsonTips = '"tips":"'.$str.'"';
    
    require_once('../php-scripts/db/dbAppointments.php');
    $dbAppointment = new DBAppointment($idDB);
    $now = $dbAppointment->NowLong();
    $dateTimeOrderStart = substr($now, 0, 8)."000000";
    $countTotalAppointments = $dbAppointment->GetCountRows("isDeleted=0 AND isNew=0 AND ageOrderStart>=".$dateTimeOrderStart);

    $ageLastShow = GetInt($request->ageLastShow);
    $countNewAppointments = $dbAppointment->GetCountRows("isDeleted=0 AND isNew=0 AND ageCreated>=".$ageLastShow);
    $strJsonAppointments = '"appointments":{"total":'.$countTotalAppointments.',"new":'.$countNewAppointments.'}';


    require_once('../php-scripts/db/dbSettings.php');
    $dbSettings = new DBSettings($idDB);


    $ageDay = date("Y") * 10000 + date("n") * 100 + date("d");

    require_once('../php-scripts/db/dbSchedule.php');
    $dbSchedule = new DBSchedule($idDB);
    $schedule = $dbSchedule->Get("isDeleted=0 AND isNew=0 AND ageDay=".$ageDay);
    if ($schedule instanceof Schedule) {
        $strJsonSchedule = '"schedule":{"start":'.$schedule->intTimeStart.',"end":'.$schedule->intTimeEnd.'}';
    }
    else {
        $settings = $dbSettings->GetDefault();
        $strJsonSchedule = '"schedule":{"start":'.$settings->intTimeStart.',"end":'.$settings->intTimeEnd.'}';
    }
    
    

    require_once('../php-scripts/db/dbClients.php');
    $dbClient = new DBClient($idDB);
    $countClients = $dbClient->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonClients = '"clients":'.$countClients;

    $ageLastShowReviews = GetInt($request->ageLastShowReviews);
    $sqlWhere = "isDeleted=0 AND isNew=0 AND ageReview>".$ageLastShowReviews;
    $countReviews = $dbAppointment->GetCountRows($sqlWhere);
    /*if ($countReviews > 0)
        $strJsonClients .= ',"reviews":'.$countReviews;
    else {
        if ($ageLastShowReviews == 0)
            $strJsonClients .= ',"reviews":1';
    }*/
    


    require_once('../php-scripts/db/dbSalons.php');
    $dbSalon = new DBSalon($idDB);
    $countSalons = $dbSalon->GetCountRows("id>1 AND isDeleted=0 AND isNew=0");
    $strJsonSalons = '"salons":'.$countSalons;



    $arraySalons = $dbSalon->GetArrayField("strFirebase", "isDeleted=0 AND isNew=0", 0, 0);
    //GetArrayRows("isDeleted=0 AND isNew=0");

    $strJsonHooks = "";
    foreach($arraySalons as $uid)
        if (!empty($uid)) {
            if (!empty($strJsonHooks))   $strJsonHooks .= ',';
            $strJsonHooks .= '{"uid":"'.$uid.'"}';
        }
    $strJsonHooks = '"ahooks":['.$strJsonHooks.'],"mhooks":['.$strJsonHooks.']'; 
    
    /*$uidTokenFirebase = $dbTokenEmployee->GetStringField("strFirebase", "id=".$idDB);
    if (!empty($uidTokenFirebase))
        $strJsonHooks .= ',"mhooks":[{"uid":"'.$uidTokenFirebase.'"}]';
    */





    $strJsonMoney = '"money":0';
    
    $strJsonFirstTime = "";
    if (($countTotalAppointments <= 1) && ($ageLastShow == 0))   $strJsonFirstTime = '"isFirstTime":1,';


    $strJsonHaveEmail = '';
    require_once('../php-scripts/db/dbContacts.php');
    $dbContact = new DBContact($idDB);
    $email = $dbContact->GetEmailEmployee(1);
    if (empty($email))     $strJsonHaveEmail = '"isHaveEmail":0,';
    
    

    
    $summaTotalPayments = $dbTokenEmployee->GetIntField("summaTotalPayments", "id=".$idDB);
    $summaTotalWastes = $dbTokenEmployee->GetIntField("summaTotalWastes", "id=".$idDB);
    $balance = ($summaTotalPayments - $summaTotalWastes) / 100;

    require_once('../php-scripts/db/dbSubscriptionWaste.php');
    $dbSubscriptionWaste = new DBSubscriptionWaste($idDB);
    $waste = $dbSubscriptionWaste->GetWasteForDay($ageDay) / 100;

    $strJsonBalance = '"balance":'.$balance.',"waste":'.$waste;
    



    $ageLastShowSupport = GetInt($request->ageLastShowSupport);
    
    //  Посчитаем количество новых сообщений от техподдержки с момента последнего захода.
    $countMessages = 0;
    require_once('../php-scripts/models/essential.php');
    require_once('../php-scripts/db/dbTalks.php');
    $dbTalk = new DBTalk($idDB);
    $idTalk = $dbTalk->GetIdField("idEssential1=".EnumEssential::SUPPORT." OR idEssential2=".EnumEssential::SUPPORT);
    if ($idTalk > 0) {
        require_once('../php-scripts/db/dbMessages.php');
        $dbMessage = new DBMessage($idDB);
        $countMessages = $dbMessage->GetCountRows("idTalk=".$idTalk." AND idEssential=".EnumEssential::SUPPORT." AND ageChanged>=".$ageLastShowSupport);
    }

    //1 = "Низкая загруженность";
    //2 = "Высокая загруженность";
    $strJsonSupport = '"support":{"status":1,"messages":'.$countMessages.'}';


    
    
    $idTypeConfiguration = $dbSettings->GetIntField("idTypeConfiguration", "id=1");
    $strJsonSettings = '"settings":'.$idTypeConfiguration;
    
    $strJson = $strJsonTips.','
    .$strJsonAppointments.','
    .$strJsonSchedule.','
    .$strJsonMoney.','
    .$strJsonClients.','
    .$strJsonSalons.','
    .$strJsonHooks.','
    .$strJsonBalance.','
    .$strJsonSupport.','
    .$strJsonHaveEmail
    .$strJsonFirstTime
    .$strJsonSettings;

    EndResponseData("menu", $strJson.',"last":'.$dbSettings->NowLong());
?>