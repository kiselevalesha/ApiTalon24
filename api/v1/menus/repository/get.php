<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    require_once('../php-scripts/models/essential.php');
    require_once('../php-scripts/db/dbProducts.php');
    $dbService = new DBProduct($idDB);
    $countServices = $dbService->GetCountRows("isDeleted=0 AND isNew=0 AND idEssential=".EnumEssential::SERVICES);
    $strJsonServices = '"services":'.$countServices;
    

    require_once('../php-scripts/db/dbProducts.php');
    $dbProduct = new DBProduct($idDB);
    $countProducts = $dbProduct->GetCountRows("isDeleted=0 AND isNew=0 AND idEssential=".EnumEssential::PRODUCTS);
    $strJsonProducts = '"products":'.$countProducts;


    require_once('../php-scripts/db/dbResources.php');
    $dbResource = new DBResource($idDB);
    $countResources = $dbResource->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonResources = '"resources":'.$countResources;


    require_once('../php-scripts/db/dbImages.php');
    $dbImage = new DBImage($idDB);
    $countImages = $dbImage->GetCountRows("");
    $strJsonImages = '"images":'.$countImages;


    require_once('../php-scripts/db/dbCriterions.php');
    $dbCriterion = new DBCriterion($idDB);
    $countCriterions = $dbCriterion->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonCriterions = '"criterions":'.$countCriterions;


    $strJson = $strJsonServices.','
    .$strJsonProducts.','
    .$strJsonResources.','
    .$strJsonImages.','
    .$strJsonCriterions;

    EndResponseData("menu", $strJson.',"last":0');
?>