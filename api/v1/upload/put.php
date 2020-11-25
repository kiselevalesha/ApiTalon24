<?php

    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    
    $strToken = GetCleanString($_GET["token"]);
    if (empty($strToken))   ExitEmptyError("Token is empty!");

    require_once '../php-scripts/utils/headers.php';


    $strFolderUpload = "uploads/";
    $imgFileName = "";
    $imgFileNamePath = "";
    
    
    
    $idEssential = $_GET["essential"] * 1;
    $idOwner = $_GET["owner"] * 1;
    if (empty($idOwner))    $idOwner = 1;



    
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {

        $imgFileName = $_FILES['file']['name'];
        if (! empty($imgFileName)) {
            $imgFilePath = $strFolderUpload . $idDB;
    
            if (!file_exists($strFolderUpload))     mkdir($strFolderUpload);
            if (!file_exists($imgFilePath))     mkdir($imgFilePath);
            
            $imgFileNamePath = $imgFilePath .'/'. $imgFileName;
            move_uploaded_file($_FILES['file']['tmp_name'], $imgFileNamePath);
            

            require_once('../php-scripts/db/dbImages.php');
            $dbImage = new DBImage($idDB);
            $image = $dbImage->AddNewImageFromSite($idEssential, $idOwner, $imgFileNamePath, 1, EnumIdSites::SITE_TALON24_RU);
            
            $strJson = $dbImage->MakeJson($image);
            $dbImage->UpdateField("strJson", $strJson, "id=".$image->id);

    
            require_once('../php-scripts/models/essential.php');
            switch($idEssential) {
                case EnumEssential::CLIENTS:
                    require_once('../php-scripts/db/dbClients.php');
                    $dbClient= new DBClient($idDB);
                    $dbClient->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::EMPLOYEE:
                    require_once('../php-scripts/db/dbEmployee.php');
                    $dbEmployee = new DBEmployee($idDB);
                    $dbEmployee->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::SERVICES:
                    require_once('../php-scripts/db/dbProducts.php');
                    $dbProduct = new DBProduct($idDB);
                    $dbProduct->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::PRODUCTS:
                    require_once('../php-scripts/db/dbProducts.php');
                    $dbProduct = new DBProduct($idDB);
                    $dbProduct->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::RESOURCES:
                    require_once('../php-scripts/db/dbResources.php');
                    $dbResource = new DBResource($idDB);
                    $dbResource->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::SALONS:
                    require_once('../php-scripts/db/dbSalons.php');
                    $dbSalon = new DBSalon($idDB);
                    $dbSalon->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);
                    break;
                case EnumEssential::VISITS:
                    /*require_once('../php-scripts/db/dbResources.php');
                    $dbResource = new DBResource($idDB);
                    $dbResource->UpdateField("idMainPhoto", $image->id, "id=".$idOwner);*/
                    break;
            }
    
            EndResponseListData("objects", $dbImage->GetJsonPhotos($idOwner, $idEssential));
        }
    }

    ExitEmptyError("Error on upload file!");
    
?>
