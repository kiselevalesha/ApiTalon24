<?php
    chdir('../../..');
    //require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';
    //require_once '../talon24.ru/api/v1/adresses/save.php';
    require_once '../php-scripts/db/dbPricelists.php';
    require_once '../php-scripts/db/dbCategories.php';


    function SavePricelists($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SavePricelist($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SavePricelist($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbPricelist = new DBPricelist($idDB);
        $pricelist = $dbPricelist->GetPricelist(GetInt($json->id));
        //$pricelist->idEssential = EnumEssential::CLIENTS;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$pricelist->strName.'"';
            $pricelist->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$pricelist->strName.'"';
        }
        /*if (isSet($json->patronymic)) {
            $strJsonOld .= ',"patronymic":"'.$pricelist->strPatronymic.'"';
            $pricelist->strPatronymic = GetCleanString($json->patronymic);
            $strJsonNew .= ',"patronymic":"'.$pricelist->strPatronymic.'"';
        }
        if (isSet($json->surname)) {
            $strJsonOld .= ',"surname":"'.$pricelist->strSurName.'"';
            $pricelist->strSurName = GetCleanString($json->surname);
            $strJsonNew .= ',"surname":"'.$pricelist->strSurName.'"';
        }*/
        if (isSet($json->alias)) {
            $strJsonOld .= ',"alias":"'.$pricelist->strAlias.'"';
            $pricelist->strAlias = GetCleanString($json->alias);
            $strJsonNew .= ',"alias":"'.$pricelist->strAlias.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$pricelist->strDescription.'"';
            $pricelist->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$pricelist->strDescription.'"';
        }


        /*if (isSet($json->sex)) {
            $strJsonOld .= ',"sex":'.($pricelist->idSex + 0);
            $pricelist->idSex = GetInt($json->sex);
            $strJsonNew .= ',"sex":'.($pricelist->idSex + 0);
        }
        if (isSet($json->born)) {
            $strJsonOld .= ',"born":'.($pricelist->dateBorn + 0);
            $pricelist->dateBorn = GetInt($json->born);
            $strJsonNew .= ',"born":'.($pricelist->dateBorn + 0);
        }*/


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$pricelist->idCategory.'}';
                $pricelist->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }



        $pricelist->isNew = 0;
        $pricelist->id = $dbPricelist->Save($pricelist);

        $pricelist->strJson = $dbPricelist->MakeJson($pricelist);

        if (! empty($strJsonCategory))
            $pricelist->strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbPricelist->UpdateField("strJson", $pricelist->strJson, "id=".$pricelist->id);
        $pricelist->strJsonUpdate = '{"id":'.$pricelist->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $pricelist;
    }
?>