<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    $start = GetInt($json->start);
    $end = GetInt($json->end);

    $arrayObjs = array();
    if ($idDB > 0) {
        require_once('../php-scripts/db/dbSubscriptionPayments.php');
        $dbSubscriptionPayment = new DBSubscriptionPayment($idDB);

        $strWhere = "isDeleted=0";
        $arrayObjs = $dbSubscriptionPayment->GetArrayRows($strWhere);
    }

    $strJson = "";
    foreach ($arrayObjs as $obj) {
        if (strlen($strJson) > 0)   $strJson .= ",";
        //$strJson .= $obj->ToJson();
        $strJson .= '{"age":"'.$obj->agePayment.'","summa":"'.$obj->summaPayment.'","bonus":"'.$obj->summaBonus.'","comment":"'.$obj->strDescription.'"}';
    }
    
    echo GetOutJson('"payments":['.$strJson.']');
?>