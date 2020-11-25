<?php
    require_once '../php-scripts/db/dbEmployee.php';
    require_once '../php-scripts/db/dbImages.php';
    require_once '../php-scripts/db/dbContacts.php';
    require_once 'api/v1/adresess/update.php';

    function SaveEmployee($idDB, $employee) {

            $dbEmployee = new DBEmployee($idDB);

            if ($employee->id > 0) {
                $employee = $dbEmployee->GetEmployee($employee->id);
            }
            else {
                //  Создадим нового клиента, если передана о нём хоть какая-то информация
                $str = $strPhone.$strEmail.$strVK.GetCleanString($employee->name).GetCleanString($employee->patronymic).GetCleanString($employee->surname);
                if (strlen(trim($str)) > 0)
                    $employee = $dbEmployee->New();
            }
        
            if ($employee->id > 0) {
                if (empty($employee->strName))   $employee->strName = GetCleanString($employee->name);
                if (empty($employee->strPatronymic))   $employee->strPatronymic = GetCleanString($employee->patronymic);
                if (empty($employee->strSurName))   $employee->strSurName = GetCleanString($employee->surname);
                if ($employee->idSex == 0)    $employee->idSex = GetInt($employee->sex);
                if (empty($employee->strToken))   $employee->strToken = GetCleanString($employee->strToken);
                
                $employee->isNew = 0;
                $dbEmployee->Save($employee);


                $dbImage = new DBImage($idDB);
                if ($employee->photo)
                    if (!($employee->photo->id > 0))
                        if (!empty($employee->photo->url)) {
                            $image = $dbImage->SaveNewPhoto($employee->photo->url, EnumEssential::EMPLOYEE);
                            if ($image->id > 0) {
                                $dbEmployee->UpdateField("idMainPhoto", $image->id, "id=".$employee->id);
                                $dbEmployee->idMainPhoto = $image->id;
                            }
                        }


                //  Сохранить данные о Контактах
                if (isSet($employee->contacts)) {
                    $dbContact = new DBContact($idDB);
                    $employee->strJsonContacts = $dbContact->SaveContacts($employee->contacts, EnumEssential::EMPLOYEE);
                }
                // $strJsonContacts->new  $strJsonContacts->old


                //  Сохранить данные об Адресе
                if (isSet($employee->adress)) {
                    $employee->strJsonAdress = UpdateAdress($employee->adress, $employee->id, EnumEssential::EMPLOYEE);
                }
                // $strJsonAdress->new  $strJsonAdress->old

                $employee->strJson = $dbEmployee->MakeJson().',"contacts":['.$employee->strJsonContacts.'],"adress":{'.$employee->strJsonAdress.'}';
            }

        //  возвращаем данные в той же структуре, в которой получили
        return $employee;
    }
?>