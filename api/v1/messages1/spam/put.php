<?
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';


    if (strlen($strToken) == 0)     ExitError(104, "User not defined!");

    $idChannel = GetInt($jsonMessage->channel->id);
    $ageWill = GetInt($jsonMessage->age->will);
    $idTypeMedia = GetInt($jsonMessage->media);


    require_once('../php-scripts/db/dbMessages.php');
    $dbMessage = new DBMessage($idDB);

    
    foreach($json->messages as $message) {

        $jsonMessage = $json->message;
        if (!empty($jsonMessage)) {
    
                $message = new Message();
                //$message->idTalk = $idTalk;
                //$message->idAppointment = $idAppointment;
                $message->strUidUser = $strToken;
                
                $message->idUser = GetInt($jsonMessage->client);
                $message->idEssential = EnumEssential::CLIENTS;
                $message->isManualEdited = 1;
                $message->isApproved = 0;
                $message->strBody = GetCleanString($jsonMessage->body);
                $message->idTypeChannel = $idChannel;
                $message->strAdress = GetCleanString($jsonMessage->adress);
                $message->idTypeContent = EnumTypeContents::TypeContentText;    //  $idTypeMedia
                
                if ($message->idTypeChannel == EnumTypeChannels::TypeChannelChat) {
                    if ($ageWill > 0)   $message->ageWillSend = $ageWill;
                    else                $message->ageWillSend = $dbMessage->NowLong();
                    //$message->ageWasSended = $message->ageWillSend;
                }
                else {
                    //$message->ageWillSend = $ageWill;
                }
                $dbMessage->Save($message);
        }
    }

    
    $srJsonMessages = '"messages":[]';
    echo GetOutJson($srJsonMessages);
?>
