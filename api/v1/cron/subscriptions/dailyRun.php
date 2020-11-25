<?
    chdir('../../../..');
    
    require_once '../xn--80anea7an.xn--80asehdb/api/v1/cron/subscriptions/calcForOne.php';
    require_once '../xn--80anea7an.xn--80asehdb/api/v1/cron/subscriptions/calcForAll.php';
    
    require_once '../php-scripts/utils/utils.php';

    $year = $_GET['year'];
    if (empty($year))     $year = date("Y");
    $month = $_GET['month'];
    if (empty($month))     $month = date("n");
    $day = $_GET['day'];
    if (empty($day))     $day = date("d");
    
    $title =  "Start cron on ".GetTwoNumbers($day).".".$month.".".$year;
    echo $title;
    CalcCostSubscriptionsForAllTokens($year, $month, $day);
?>