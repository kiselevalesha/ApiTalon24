<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    require_once('../php-scripts/db/dbPricelists.php');
    $dbPricelist = new DBPricelist($idDB);
    $countPricelists = $dbPricelist->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonPricelists = '"pricelists":'.$countPricelists;
    

    require_once('../php-scripts/db/dbTaxes.php');
    $dbTax = new DBTax($idDB);
    $countTaxes = $dbTax->GetCountRows("isDeleted=0 AND isNew=0");
    $strJsonTaxes = '"taxes":'.$countTaxes;

    
    $strJsonRashod = '"rashod":0';
    
    $strJsonPrihod = '"prihod":0';


    $strJson = $strJsonPricelists.','
    .$strJsonTaxes.','
    .$strJsonRashod.','
    .$strJsonPrihod;

    EndResponseData("menu", $strJson.',"last":0');
?>