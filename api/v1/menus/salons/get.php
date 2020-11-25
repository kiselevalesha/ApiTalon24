<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    require_once('../php-scripts/db/dbSalons.php');
    $dbSalon = new DBSalon($idDB);
    $countSalons = $dbSalon->GetCountRows("id>1 AND isDeleted=0 AND isNew=0");
    $strJsonSalons = '"salons":'.$countSalons;


    require_once('../php-scripts/db/dbEmployee.php');
    $dbEmployee = new DBEmployee($idDB);
    $countEmployee = $dbEmployee->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonEmployee = '"employee":'.$countEmployee;


    $analytics = "Нет данных";
    $strJsonAnalytics = '"analytics":"'.$analytics.'"';

    $orders = "Приказов нет";
    $strJsonOrders = '"orders":"'.$orders.'"';

    $supply = "Бесплатный сервис";
    $strJsonSupply = '"supply":"'.$supply.'"';


    $strJson = $strJsonSalons.','
    .$strJsonEmployee.','
    .$strJsonAnalytics.','
    .$strJsonOrders.','
    .$strJsonSupply;

    EndResponseData("menu", $strJson.',"last":0');
?>