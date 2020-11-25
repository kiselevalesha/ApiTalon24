<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbPayment = new DBPayment($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $payment = $dbPayment->GetPayment($id);
            $payment->strSurName = GetCleanString($json->surname);
            $payment->strName = GetCleanString($json->name);
            $payment->strPatronymic = GetCleanString($json->patronymic);
            $payment->strAlias = GetCleanString($json->alias);
            $payment->dateBorn = GetInt($json->born);
            $payment->idSex = GetInt($json->sex);
            $payment->idCategory = GetInt($json->category);
            $payment->strDescription = GetCleanString($json->description);
            $payment->isNew = 0;
            $payment->id = $dbPayment->Save($payment);

            $strJson = $dbPayment->MakeJson($payment);
            //if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            $dbPayment->UpdateField("strJson", $strJson, "id=".$payment->id);
            $strJsonRows .= $comma . '{'.$strJson.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>