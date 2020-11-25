<?php
    chdir('../../..');
    require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';
    require_once '../talon24.ru/api/v1/adresses/save.php';
    require_once '../talon24.ru/api/v1/bankaccounts/save.php';
    require_once '../php-scripts/db/dbSalons.php';
    require_once '../php-scripts/db/dbCategories.php';


    function SaveSalons($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveSalon($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveSalon($idDB, $json) {

        $strJsonOld = "";
        $strJsonNew = "";

        $dbSalon = new DBSalon($idDB);
        $salon = $dbSalon->GetSalon(GetInt($json->id));
        //$salon->idEssential = EnumEssential::SALONS;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$salon->strName.'"';
            $salon->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$salon->strName.'"';
        }
        /*if (isSet($json->patronymic)) {
            $strJsonOld .= ',"patronymic":"'.$salon->strPatronymic.'"';
            $salon->strPatronymic = GetCleanString($json->patronymic);
            $strJsonNew .= ',"patronymic":"'.$salon->strPatronymic.'"';
        }
        if (isSet($json->surname)) {
            $strJsonOld .= ',"surname":"'.$salon->strSurName.'"';
            $salon->strSurName = GetCleanString($json->surname);
            $strJsonNew .= ',"surname":"'.$salon->strSurName.'"';
        }*/
        if (isSet($json->alias)) {
            $strJsonOld .= ',"alias":"'.$salon->strAlias.'"';
            $salon->strAlias = GetCleanString($json->alias);
            $strJsonNew .= ',"alias":"'.$salon->strAlias.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$salon->strDescription.'"';
            $salon->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$salon->strDescription.'"';
        }


        /*if (isSet($json->sex)) {
            $strJsonOld .= ',"sex":'.($salon->idSex + 0);
            $salon->idSex = GetInt($json->sex);
            $strJsonNew .= ',"sex":'.($salon->idSex + 0);
        }
        if (isSet($json->born)) {
            $strJsonOld .= ',"born":'.($salon->dateBorn + 0);
            $salon->dateBorn = GetInt($json->born);
            $strJsonNew .= ',"born":'.($salon->dateBorn + 0);
        }*/


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$salon->idCategory.'}';
                $salon->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }


        //  Сохранить данные о Контактах
        if (isSet($json->contacts))
            $salon->strJsonContacts = SaveContacts($idDB, $json->contacts, $salon->id, EnumEssential::SALONS);


        //  Сохранить данные об Адресе
        if (isSet($json->adresses))
            $salon->strJsonAdress = SaveAdresses($idDB, $json->adresses, $salon->id, EnumEssential::SALONS);


        //  Сохранить данные о Банковских счетах
        if (isSet($json->bankaccounts))
            $salon->strJsonBankAccounts = SaveBankAccounts($idDB, $json->bankaccounts, $salon->id, EnumEssential::SALONS);



        $salon->isNew = 0;
        $salon->id = $dbSalon->Save($salon);

        $strJson = $dbSalon->MakeJson($salon);
        
        if (! empty($strJsonCategory))
            $strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbSalon->UpdateField("strJson", $strJson, "id=".$salon->id);
        $salon->strJsonUpdate = '{"id":'.$salon->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $salon;
    }
?>
