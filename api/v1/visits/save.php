<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbAppointment = new DBAppointment($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $review = $dbAppointment->GetAppointment($id);
            $review->strSurName = GetCleanString($json->surname);
            $review->strName = GetCleanString($json->name);
            $review->strPatronymic = GetCleanString($json->patronymic);
            $review->strAlias = GetCleanString($json->alias);
            $review->dateBorn = GetInt($json->born);
            $review->idSex = GetInt($json->sex);
            $review->idCategory = GetInt($json->category);
            $review->strDescription = GetCleanString($json->description);
            $review->isNew = 0;
            $review->id = $dbAppointment->Save($review);

            $strJson = $dbAppointment->MakeJson($review);
            $dbAppointment->UpdateField("strJson", $strJson, "id=".$review->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>