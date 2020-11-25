<?php
    chdir("../../..");

    $strToken = $_GET["token"];
    require_once '../php-scripts/utils/api.php';


    require_once('../php-scripts/db/dbSupportTalks.php');
    $dbSupportTalk = new DBSupportTalk();

    $idSupportTalk = GetInt($_GET["support"]);
    if ($idSupportTalk > 0) {
        $idDB = $dbSupportTalk->GetIntField("idDB","id=".$idSupportTalk);
    }






    //  Непосредственно переносим файл в папку-токен юзера
    $strFolderUpload = "uploads/";
    $imgFileName = "";
    $imgFileNamePath = "";
    if ($_FILES['file']['error'] > 0) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $imgFileName = $_FILES['file']['name'];
        $imgFilePath = $strFolderUpload . $idDB;
        
        if (!file_exists($imgFilePath))     mkdir($imgFilePath);
        
        $imgFileNamePath = $imgFilePath .'/'. $imgFileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $imgFileNamePath);
    }
    




 

    $idAppointment = 0;
    $strAppointment = GetCleanString($_GET["appointment"]);
    if (!empty($strAppointment)) {
        require_once('../php-scripts/db/dbCodeAppointments.php');
        
        $codeAppointment = new CodeAppointment();
        $idCodeAppointment = $codeAppointment->GetIdByCode($strAppointment);
        
        if ($idCodeAppointment > 0) {
            $dbCodeAppointment = new DBCodeAppointment();
            $idAppointment = $dbCodeAppointment->GetIntField("idAppointment", "id=".$idCodeAppointment);
        }
    }



    $sender = $_GET["sender"] * 1;
    $essentialSender = $_GET["typeSender"] * 1;
    if (empty($essentialSender))   ExitError(101, "Side1 not defined!");

    $receiver = $_GET["receiver"] * 1;
    $essentialReceiver = $_GET["typeReceiver"] * 1;
    if (empty($essentialReceiver))    ExitError(102, "Side2 not defined!");


    //  Проверить существует ли таблица Talks
    require_once('../php-scripts/db/dbTalks.php');
    $dbTalk = new DBTalk($idDB);


    $idTalk = GetInt($_GET["talk"]);
    if ($idTalk < 1)
        $idTalk = $dbTalk->CreateUpdateIdTalk($essentialSender, $sender, $essentialReceiver, $receiver);
    $talk = $dbTalk->Get($idTalk);



        require_once('../php-scripts/db/dbMessages.php');
        $dbMessage = new DBMessage($idDB);

        $idMessage = -1;
        if (strlen($strToken) == 0)     ExitError(104, "User not defined!");
        $strMessage = $idDB."/".$imgFileName;
        //$idChannel = GetInt($jsonMessage->channel->id);
        //$strAdress = GetCleanString($jsonMessage->channel->adress);
        //$ageWill = GetInt($jsonMessage->age->will);
        
        $message = new Message();
        $message->idTalk = $talk->id;
        $message->idAppointment = $idAppointment;

        $message->idUser = $sender;
        $message->idEssential = $essentialSender;

        $message->strBody = $strMessage;
        $message->strUidUser = $strToken;
        $message->idEssential = $essentialSender;
        $message->idTypeChannel = $idChannel;
        $message->strAdress = $strAdress;
        $message->idTypeContent = EnumTypeContents::TypeContentImage;
        $message->idTypeMessage = EnumTypeMessage::TypeMessageMedia;
        
        $message->ageWillSend = $dbMessage->NowLong();
        $message->ageWasSended = $message->ageWillSend;
        $dbMessage->Save($message);


        $talk->intCountMessages++;
        $dbTalk->Save($talk);


        
        
        //  Если это обращение в службу техподдержки, отошлём себе письмо
        if ($idMessage < 0) {
            require_once('../php-scripts/models/essential.php');
            if ($essentialReceiver == EnumEssential::SUPPORT) {
                
                //  Если это не ответ от техподдержки
                $idSupportTalk = $dbSupportTalk->SaveUpdate($idDB, $talk->id, $essentialSender);
            
    
                require_once '../php-scripts/utils/utils.php';
                $to = 'beautymastersapp@gmail.com';
                $subject = 'В техподдержку от '.$strToken;
                $body = '<div>Токен: <b>'.$strToken.'</b> idDB: <b>'.$idDB.'</b><br><br>'.$strMessage.
                '<br><br><a href="https://записи.онлайн/board/editToken.php?talk='.$idSupportTalk.'&token='.$strToken.'">Перейти в чат</a><br>'.'</div>';
                SendUniversalEmail($to, $subject, $body);
            }
        }



    
    $strJsonUser = '"user":"'.$strToken.'","talk":'.$talk->id.',"support":'.$idSupportTalk;
    $srJsonMessages = ',"messages":['.$dbMessage->GetJsonMessages($idTalk, $idAppointment).']';

    echo GetOutJson($strJsonUser.$srJsonMessages);
?>
