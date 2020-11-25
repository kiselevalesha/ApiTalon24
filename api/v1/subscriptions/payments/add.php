<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';

    
    require_once('../php-scripts/db/dbSubscriptionPayments.php');
    $dbSubscriptionPayment = new DBSubscriptionPayment($idDB);

    $payment = new SubscriptionPayment();
    $payment->summaPayment = GetInt($json->summa);
    $payment->strDescription = GetCleanString($json->description);
    $payment->agePayment = $dbSubscriptionPayment->NowLong();

    $payment->id = $dbSubscriptionPayment->Save($payment);


    
    //  Нужно сохранить общую сумму всех платежей. Не пересчитываем всё полностью, а просто прибавляем к текущей.
    $totalPayments = $dbTokenEmployee->GetIntField("summaTotalPayments", "id=".$idDB);
    $totalPayments = $totalPayments + $payment->summaPayment;
    $dbTokenEmployee->UpdateField("summaTotalPayments", $totalPayments, "id=".$idDB);
    

    
    $strJson = '{"age":"'.$obj->agePayment.'","summa":"'.$obj->summaPayment.'","bonus":"'.$obj->summaBonus.'","comment":"'.$obj->strDescription.'"}';
    echo GetOutJson('"payment":'.$strJson);
?>