<?php
    chdir('../../..');
    require_once '../talon24.ru/api/v1/contacts/save.php';
    require_once '../talon24.ru/api/v1/groups/save.php';
    require_once '../talon24.ru/api/v1/categories/save.php';
    require_once '../talon24.ru/api/v1/adresses/save.php';
    require_once '../php-scripts/db/dbEmployee.php';
    require_once '../php-scripts/db/dbCategories.php';


    function SaveEmployees($idDB, $arrayJsonObjs) {
        $arrayObjs = array();
        foreach($arrayJsonObjs as $json) {
            $obj = SaveEmployee($idDB, $json);
            array_push($arrayObjs, $obj);
        }
        return $arrayObjs;
    }
    
    function SaveEmployee($idDB, $json) {
        $strJsonOld = "";
        $strJsonNew = "";

        $dbEmployee = new DBEmployee($idDB);
        $employee = $dbEmployee->GetEmployee(GetInt($json->id));
        //$employee->idEssential = EnumEssential::CLIENTS;
        
        if (isSet($json->name)) {
            $strJsonOld .= ',"name":"'.$employee->strName.'"';
            $employee->strName = GetCleanString($json->name);
            $strJsonNew .= ',"name":"'.$employee->strName.'"';
        }
        if (isSet($json->patronymic)) {
            $strJsonOld .= ',"patronymic":"'.$employee->strPatronymic.'"';
            $employee->strPatronymic = GetCleanString($json->patronymic);
            $strJsonNew .= ',"patronymic":"'.$employee->strPatronymic.'"';
        }
        if (isSet($json->surname)) {
            $strJsonOld .= ',"surname":"'.$employee->strSurName.'"';
            $employee->strSurName = GetCleanString($json->surname);
            $strJsonNew .= ',"surname":"'.$employee->strSurName.'"';
        }
        if (isSet($json->alias)) {
            $strJsonOld .= ',"alias":"'.$employee->strAlias.'"';
            $employee->strAlias = GetCleanString($json->alias);
            $strJsonNew .= ',"alias":"'.$employee->strAlias.'"';
        }
        if (isSet($json->description)) {
            $strJsonOld .= ',"description":"'.$employee->strDescription.'"';
            $employee->strDescription = GetCleanString($json->description);
            $strJsonNew .= ',"description":"'.$employee->strDescription.'"';
        }


        if (isSet($json->sex)) {
            $strJsonOld .= ',"sex":'.($employee->idSex + 0);
            $employee->idSex = GetInt($json->sex);
            $strJsonNew .= ',"sex":'.($employee->idSex + 0);
        }
        if (isSet($json->born)) {
            $strJsonOld .= ',"born":'.($employee->dateBorn + 0);
            $employee->dateBorn = GetInt($json->born);
            $strJsonNew .= ',"born":'.($employee->dateBorn + 0);
        }


        $strJsonCategory = "";
        if (isSet($json->category))
            if (isSet($json->category->id)) {
                $strJsonOld .= ',"category":{"id":'.$employee->idCategory.'}';
                $employee->idCategory = GetInt($json->category->id);

                $category = SaveCategory($idDB, $json->category);
                $strJsonCategory = $category->strJson;

                $strJsonNew .= ',"category":{"id":'.$category->id.'}';
            }


        //  Сохранить данные о Контактах
        if (isSet($json->contacts))
            $employee->strJsonContacts = SaveContacts($idDB, $json->contacts, $employee->id, EnumEssential::EMPLOYEE);


        //  Сохранить данные об Адресе
        if (isSet($json->adresses))
            $employee->strJsonAdress = SaveAdresses($idDB, $json->adresses, $employee->id, EnumEssential::EMPLOYEE);



        $employee->isNew = 0;
        $employee->id = $dbEmployee->Save($employee);

        $strJson = $dbEmployee->MakeJson($employee);
        
        if (! empty($strJsonCategory))
            $strJson .= ',"category":{'.$strJsonCategory.'}';
        
        $dbEmployee->UpdateField("strJson", $strJson, "id=".$employee->id);
        $employee->strJsonUpdate = '{"id":'.$employee->id.',"upd":{'.substr($strJsonNew, 1).'},"old":{'.substr($strJsonOld, 1).'}}';

        return $employee;
    }
?>
