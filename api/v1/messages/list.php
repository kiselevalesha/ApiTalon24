<?php

    function GetJsonList($idDB, $json) {
        $idSupportTalk = GetInt($json->support);
        if ($idSupportTalk > 0) {
            $dbSupportTalk = new DBSupportTalk();
            $idDB = $dbSupportTalk->GetIntField("idDB","id=".$idSupportTalk);
        }
    
    
        $idAppointment = 0;
        $strAppointment = GetCleanString($json->appointment);
        if (!empty($strAppointment)) {
            
            $codeAppointment = new CodeAppointment();
            $idCodeAppointment = $codeAppointment->GetIdByCode($strAppointment);
            
            if ($idCodeAppointment > 0) {
                $dbCodeAppointment = new DBCodeAppointment();
                $idAppointment = $dbCodeAppointment->GetIntField("idAppointment", "id=".$idCodeAppointment);
            }
        }
        
        $idMessage = GetInt($json->message->id);
        $idTalk = GetInt($json->talk);
    
        if ($idMessage == 0) {
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
                //  Проверить существует ли таблица Talks
                $dbTalk = new DBTalk($idDB);

                $idTalk = $dbTalk->CreateUpdateIdTalk($essentialSender, $sender, $essentialReceiver, $receiver);
            }
        }
    
        if ($idTalk < 1)    $idTalk = 0;    //  на всякий случай


        $dbMessage = new DBMessage($idDB);
    
        $idMessage = GetInt($json->message->id);
        if ($idMessage == 0) {
            $srJsonMessages = ',"messages":['.GetJsonMessages($idDB, $idTalk, $idAppointment).']';
        }
        elseif ($idMessage > 0) {
            $message = $dbMessage->Get($idMessage);
            $srJsonMessages = ',"messages":[{'.$message->MakeJsonBody().'}]';
        }
        
        
        $strJsonUser = '"user":"'.$strToken.'","talk":'.$idTalk.',"support":'.$idSupportTalk;
    
        //echo GetOutJson($strJsonUser.$srJsonMessages);
        return '{'.$strJsonUser.$srJsonMessages.'}';
    }

    function GetJsonMessages($idDB, $idTalk, $idAppointment) {
        $dbMessage = new DBMessage($idDB);
        $dbMessageRule = new DBMessageRule($idDB);

        $strJson = "";
        $messages = $dbMessage->GetMessages($idTalk, $idAppointment);
        foreach($messages as $message) {
            $strJsonRule = "";
            if ($message->idMessageRule > 0) {
                $strName = $dbMessageRule->GetStringField("strName", "id=".$message->idMessageRule);
                $strJsonRule = ',"rule":{"id":'.$message->idMessageRule.',"name":"'.$strName.'"}';
            }
            if (!empty($strJson))   $strJson .= ',';
            $strJson .= '{'.$message->MakeJsonBody().$strJsonRule.'}';
        }
        return $strJson;
    }

?>