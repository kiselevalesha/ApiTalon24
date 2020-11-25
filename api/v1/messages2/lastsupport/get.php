<?
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    require_once '../php-scripts/models/essential.php';
    
    $age = GetInt($request->last);


    //  Проверить существует ли таблица Talks
    require_once('../php-scripts/db/dbTalks.php');
    $dbTalk = new DBTalk($idDB);
    $arrayTalks = $dbTalk->GetArrayRows("idEssential1=".EnumEssential::SUPPORT." AND isDeleted=0 AND ageChanged>=".$age);

    require_once('../php-scripts/db/dbMessages.php');
    $dbMessage = new DBMessage($idDB);
    
    $countSupportMessages = 0;
    foreach($arrayTalks as $talk) {
        $countSupportMessages += $dbMessage->GetCountRows("idTalk=".$talk->id." AND idEssential=".EnumEssential::SUPPORT." AND isDeleted=0 AND ageChanged>=".$age);
    }
    
    EndResponseData("messages", '"count":'.$countSupportMessages.',"last":'.$dbMessage->NowLong());
?>
