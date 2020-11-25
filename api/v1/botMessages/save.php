<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbBotMessage = new DBBotMessage($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $botMessage = $dbBotMessage->GetBotMessage($id);
            $botMessage->strMessage = GetCleanString($json->message);
            $botMessage->strJson = GetCleanString($json->json);
            $botMessage->id = $dbBotMessage->Save($botMessage);

            $strJson = $dbBotMessage->MakeJson($botMessage);
            $strJsonRows .= $comma . '{'.$strJson.',"essential":'.EnumEssential::BOTMESSAGES.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>