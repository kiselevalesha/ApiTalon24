<?php
    function SaveAll($idDB, $arrayJsonObjs) {
        $strJsonRows = "";
        $dbEmployee = new DBEmployee($idDB);
        $comma = "";
        foreach($arrayJsonObjs as $json) {

            $id = GetInt($json->id);
            $employee = $dbEmployee->GetEmployee($id);
            $employee->strSurName = GetCleanString($json->surname);
            $employee->strName = GetCleanString($json->name);
            $employee->strPatronymic = GetCleanString($json->patronymic);
            $employee->strAlias = GetCleanString($json->alias);
            $employee->dateBorn = GetInt($json->born);
            $employee->idSex = GetInt($json->sex);
            $employee->idCategory = GetInt($json->category);
            $employee->strDescription = GetCleanString($json->description);
            $employee->isNew = 0;
            $employee->id = $dbEmployee->Save($employee);

            //  Сохранить Контакты
            require_once('../php-scripts/db/dbContacts.php');
            $dbContact = new DBContact($idDB);
            $phone = GetCleanString($json->phone);
            if (!empty($phone))   $dbContact->SavePhoneToEmployee($phone, $employee->id);
            $email = GetEmail($json->email);
            if (!empty($email))   $dbContact->SaveEmailToEmployee($email, $employee->id);

            require_once('api/v1/adresess/update.php');
            $strJsonAdress = UpdateAdress($json->adress, $employee->id, EnumEssential::EMPLOYEE);
            
            $strJson = $dbEmployee->MakeJson($employee);
            if (!empty($strJsonAdress)) $strJson .= ','.$strJsonAdress;
            $dbEmployee->UpdateField("strJson", $strJson, "id=".$employee->id);
            $strJsonRows .= $comma . '{'.$strJson.',"essential":'.EnumEssential::EMPLOYEE.'}';
            $comma = ",";
        }
        
        return $strJsonRows;
    }
?>