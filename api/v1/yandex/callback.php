<?
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';

    $strToken = "";
    require_once '../php-scripts/utils/utils.php';
    
    $to = 'beautymastersapp@gmail.com';
    //$subject = 'В техподдержку от '.$strToken;
    $subject = 'Пополнение счёта !!!';
    //$body = '<div>Токен: <b>'.$strToken.'</b> idDB: <b>'.$idDB.'</b><br><br>'.$strMessage.
    //'<br><br><a href="https://записи.онлайн/board/editToken.php?talk='.$idSupportTalk.'&token='.$strToken.'">Перейти в чат</a><br>'.'</div>';
    $body = '<div><b>Прикинь, мне заплатили деньги !!!</b></div>';
    SendUniversalEmail($to, $subject, $body);

?>