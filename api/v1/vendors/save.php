<?php
    chdir('../../..');
    require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';
    require_once '../talon24.ru/api/v1/adresses/save.php';
    require_once '../talon24.ru/api/v1/bankaccounts/save.php';
    require_once '../php-scripts/db/dbVendors.php';
    require_once '../php-scripts/db/dbCategories.php';


    function SaveVendors($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveVendor($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveVendor($idDB, $json) {

        $strJsonOld = "";
        $strJsonNew = "";

        $dbVendor = new DBVendor($idDB);
        $vendor = $dbVendor->GetVendor(GetInt($json->id));
        //$vendor->idEssential = EnumEssential::VENDORS;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$vendor->strName.'"';
            $vendor->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$vendor->strName.'"';
        }
        /*if (isSet($json->patronymic)) {
            $strJsonOld .= ',"patronymic":"'.$vendor->strPatronymic.'"';
            $vendor->strPatronymic = GetCleanString($json->patronymic);
            $strJsonNew .= ',"patronymic":"'.$vendor->strPatronymic.'"';
        }
        if (isSet($json->surname)) {
            $strJsonOld .= ',"surname":"'.$vendor->strSurName.'"';
            $vendor->strSurName = GetCleanString($json->surname);
            $strJsonNew .= ',"surname":"'.$vendor->strSurName.'"';
        }*/
        if (isSet($json->alias)) {
            $strJsonOld .= ',"alias":"'.$vendor->strAlias.'"';
            $vendor->strAlias = GetCleanString($json->alias);
            $strJsonNew .= ',"alias":"'.$vendor->strAlias.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$vendor->strDescription.'"';
            $vendor->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$vendor->strDescription.'"';
        }


        /*if (isSet($json->sex)) {
            $strJsonOld .= ',"sex":'.($vendor->idSex + 0);
            $vendor->idSex = GetInt($json->sex);
            $strJsonNew .= ',"sex":'.($vendor->idSex + 0);
        }
        if (isSet($json->born)) {
            $strJsonOld .= ',"born":'.($vendor->dateBorn + 0);
            $vendor->dateBorn = GetInt($json->born);
            $strJsonNew .= ',"born":'.($vendor->dateBorn + 0);
        }*/


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$vendor->idCategory.'}';
                $vendor->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }


        //  Сохранить данные о Контактах
        if (isSet($json->contacts))
            $vendor->strJsonContacts = SaveContacts($idDB, $json->contacts, $vendor->id, EnumEssential::VENDORS);


        //  Сохранить данные об Адресе
        if (isSet($json->adresses))
            $vendor->strJsonAdress = SaveAdresses($idDB, $json->adresses, $vendor->id, EnumEssential::VENDORS);


        //  Сохранить данные о Банковских счетах
        if (isSet($json->bankaccounts))
            $vendor->strJsonBankAccounts = SaveBankAccounts($idDB, $json->bankaccounts, $vendor->id, EnumEssential::VENDORS);



        $vendor->isNew = 0;
        $vendor->id = $dbVendor->Save($vendor);

        $strJson = $dbVendor->MakeJson($vendor);
        
        if (! empty($strJsonCategory))
            $strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbVendor->UpdateField("strJson", $strJson, "id=".$vendor->id);
        $vendor->strJsonUpdate = '{"id":'.$vendor->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $vendor;
    }
?>
