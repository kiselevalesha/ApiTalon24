<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    
    //  Запрос url по-умолчанию для зарегистрировавшегося мастера


    require_once('../php-scripts/db/dbUrlAppointments.php');
    $dbUrlAppointment = new DBUrlAppointment();
    $urlAppointment = $dbUrlAppointment->GetDefault($idDB);

    
    if (empty($urlAppointment->strUrlNamed)) {
        
        $idEmployee = 1;
        require_once('../php-scripts/db/dbEmployee.php');
        $dbEmployee = new DBEmployee($idDB);
        $strWhere = "isDeleted=0 AND id=".$idEmployee;
        $employee = $dbEmployee->GetRowBySql($strWhere);
        if ($employee instanceof Employee) {
            if ((!empty($employee->strName)) && (!empty($employee->strSurName))) {

                $urlAppointment->strUrlNamed = $dbUrlAppointment->GenerateUrl($employee->strName, $employee->strSurName);
                $urlAppointment->idDB = $idDB;
                $urlAppointment->idSalon = 1;
                $urlAppointment->idDepartment = 0;
                $urlAppointment->idEmployee = $idEmployee;
                $urlAppointment->isSendedNotification = 1;

                $urlAppointment = $dbUrlAppointment->CreateNewUniqueUrlAppointment($urlAppointment);
            }
        }


        //  Инициализация начальных нужных таблиц
        require_once('../php-scripts/api/v1/initialization/loadtables.php');
        //  Отсылка сообщений себе и новозарегистрировавшемуся о создании онлайн-записи
        require_once('../php-scripts/api/v1/initialization/sendMessages.php');
    }

    require_once('out.php');
?>