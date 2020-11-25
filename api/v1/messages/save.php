<?php

    function SaveMessages($idDB, $json) {
        require_once('../php-scripts/db/dbSupportTalks.php');
        $dbSupportTalk = new DBSupportTalk();
    
        $idSupportTalk = GetInt($json->support);
        if ($idSupportTalk > 0) {
            $idDB = $dbSupportTalk->GetIntField("idDB","id=".$idSupportTalk);
        }
        
    
        $idAppointment = 0;
        $strAppointment = GetCleanString($json->appointment);
        if (!empty($strAppointment)) {
            require_once('../php-scripts/db/dbCodeAppointments.php');
            
            $codeAppointment = new CodeAppointment();
            $idCodeAppointment = $codeAppointment->GetIdByCode($strAppointment);
            
            if ($idCodeAppointment > 0) {
                $dbCodeAppointment = new DBCodeAppointment();
                $idAppointment = $dbCodeAppointment->GetIntField("idAppointment", "id=".$idCodeAppointment);
            }
        }
    
    
        //  Проверить существует ли таблица Talks
        require_once('../php-scripts/db/dbTalks.php');
        $dbTalk = new DBTalk($idDB);
    
        
        $idMessage = GetInt($json->message->id);
        $idTalk = GetInt($json->talk);
    
        if ($idMessage < 1) {
            $sideA = $json->sides[0];
            if (empty($sideA))   ExitError(101, "Side1 not defined!");
            $sender = GetCleanString($sideA->id);
            $essentialSender = GetInt($sideA->type);
            if (empty($essentialSender))   ExitError(101, "Side1 not defined!");
        
            $sideB = $json->sides[1];
            if (empty($sideB))    ExitError(102, "Side2 not defined!");
            $receiver = GetCleanString($sideB->id);
            $essentialReceiver = GetInt($sideB->type);
            if (empty($essentialReceiver))    ExitError(102, "Side2 not defined!");
    
            if ($idTalk < 1) {
                $idTalk = $dbTalk->GetIdTalk($essentialSender, $sender, $essentialReceiver, $receiver);
            }
        }
    
        if ($idTalk < 0)    $idTalk = 0;    //  на всякий случай
        $talk = $dbTalk->Get($idTalk);
    
    
        $jsonMessage = $json->message;
        if (!empty($jsonMessage)) {
            
            
            $strToken = GetCleanString($json->token);
            if (strlen($strToken) == 0)     ExitError(104, "User not defined!");
            
            $strMessage = GetCleanString($jsonMessage->body);
            if (strlen($strMessage) == 0)   ExitError(103, "Message is empty!");
            $idChannel = GetInt($jsonMessage->channel->id);
            $strAdress = GetCleanString($jsonMessage->channel->adress);
            $ageWill = GetInt($jsonMessage->age->will);
    
            
            require_once('../php-scripts/db/dbMessages.php');
            $dbMessage = new DBMessage($idDB);
    
            $idMessage = GetInt($jsonMessage->id);
            if ($idMessage < 0) {
                $message = new Message();
                $message->idTalk = $idTalk;
                $message->idAppointment = $idAppointment;
                $message->idUser = $sender;
                $message->idEssential = $essentialSender;
            }
            elseif ($idMessage > 0) {
                $message = $dbMessage->Get($idMessage);
            }
        
            if ($message instanceof Message) {
                $message->isManualEdited = 1;
                $message->strBody = $strMessage;
                $message->strUidUser = $strToken;
                $message->idEssential = $essentialSender;
                $message->idTypeChannel = $idChannel;
                $message->strAdress = $strAdress;
                $message->idTypeContent = EnumTypeContents::TypeContentText;
                if ($message->idTypeChannel == EnumTypeChannels::TypeChannelChat) {
                    if ($ageWill > 0)   $message->ageWillSend = $ageWill;
                    else                $message->ageWillSend = $dbMessage->NowLong();
                    $message->ageWasSended = $message->ageWillSend;
                }
                else {
                    $message->ageWillSend = $ageWill;
                }
                $dbMessage->Save($message);
                
                if ($idMessage < 1)    $talk->intCountMessages++;
                $dbTalk->Save($talk);
            }
        
    
            
            
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
                else {
                    //  Это ответ из техподдержки !
                        
                    require_once('../php-scripts/db/dbLast.php');
                    $dbLast = new DBLast($idDB);
                    $dbLast->SetLastChangedSupport();   //  Вообще-то тут нужен id-салона, но пока для упрощения его опустим.
                }
            }
        }

        $strJsonUser = '"user":"'.$strToken.'","talk":'.$idTalk.',"support":'.$idSupportTalk;
        $srJsonMessages = ',"messages":['.$dbMessage->GetJsonMessages($idTalk, $idAppointment).']';
    
        //echo GetOutJson($strJsonUser.$srJsonMessages);
        //$message->strJson
        
        return '{'.$strJsonUser.$srJsonMessages.'}';
        //return $message;
    }
?>