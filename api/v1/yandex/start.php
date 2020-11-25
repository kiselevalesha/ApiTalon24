<?
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';

    //require_once '../php-scripts/utils/api.php';
    require_once '../php-scripts/utils/utils.php';
    
    $summa = GetInt($request->summa);
    $idEmployee = GetInt($request->employee);
    
    require_once('../php-scripts/db/dbYandexMoney.php');
    $dbYandexMoney = new DBYandexMoney();
    $yandexMoney = $dbYandexMoney->New($strToken, $idDB, $idEmployee, $summa);


    chdir('../php-scripts/yandex-money');
    require 'autoload.php'; 

    use YandexCheckout\Client;


    $client = new Client();
    $client->setAuth('634107', 'live_pFJSv5SCXcOVyYkEj9UjvI815BZ8LTIHcJxkmo5vM7c');   //  Рабочий аккаунт
    //$client->setAuth('636163', 'test_bgNJKmd_gEQdklgqAgfbdq0f_JrDKiARTxRSdnWN_vk');     //  Тестовый аккаунт
    
    if ($yandexMoney->summaRequest > 0) {
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $yandexMoney->summaRequest.'.0',
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://www.записи.онлайн/yandex.php?id='.$yandexMoney->id,
                ),
                'capture' => true,
                'description' => $yandexMoney->strDescription,
            ),
            $yandexMoney->strUniqKey
        );
    
        $dbYandexMoney->SaveCode($payment->id, $yandexMoney->id);
        
        $strUrl = "";
        if (strcmp ($payment->status, "pending") == 0)
            $strUrl = $payment->confirmation->_confirmationUrl;
    }
    
    echo GetOutJson('"url":"'.$strUrl.'"');

?>