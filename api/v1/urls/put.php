<?php
    chdir('../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';


    //  Только проверить доступность ссылки. Ничего не перезаписывать
    $isOnlyCheck = GetInt($json->isCheck);

    
    $idSalon = GetInt($json->salon);
    //if ($idSalon < 1)  ExitError(101, "Отсутствуют данные о салоне");

    $idEmployee = GetInt($json->employee);
    //if ($idEmployee < 1)  ExitError(102, "Отсутствуют данные о сотруднике");

    $strUrl = "";
    if (!empty($json->urlNamed)) $strUrl = GetCleanString($json->urlNamed);
    if (empty($strUrl))  ExitError(103, "Отсутствует ссылка на онлайн-запись");

    require_once('../php-scripts/db/dbUrlAppointments.php');
    $dbUrlAppointment = new DBUrlAppointment();
    
    //  Найдём текущую row ссылки
    $sqlWhere = "idDB=".$idDB." AND idEmployee=".$idEmployee." AND idSalon=".$idSalon." AND isDeleted=0";
    $urlAppointment = $dbUrlAppointment->GetRowBySql($sqlWhere);
    if ($urlAppointment instanceof UrlAppointment) {
        
        //  Проверим, существует ли уже такая же ссылка
        $urlAppointmentExist = $dbUrlAppointment->GetByUrl($strUrl);
        if ($urlAppointmentExist->id > 0) {
            if ($urlAppointmentExist->id == $urlAppointment->id) {
                //  Пересохраняют неизменённую ссылку
                //  Ссылка существует и совпадает с присланной
            }
            else {
                //  Занята
                ExitError(104, "Ссылка запишись.онлайн/<b>".$strUrl."</b> уже занята. Измените набор слов или регистр букв.");
            }
        }
        else {
            //  изменяем текущую ссылку на присланную ссылку
            //$urlAppointment->strUrlNamed = $strUrl;
            //if ($isOnlyCheck != 1)
            //    $dbUrlAppointment->Save($urlAppointment);
        }
    }
    else {
        //  Создаём новую ссылку
        //if ($isOnlyCheck != 1)
        //    $urlAppointment = $dbUrlAppointment->AddNewUrl($idDB, $idSalon, $idEmployee, $strUrl, $strToken);
    }





    if ($isOnlyCheck != 1) {
        $urlAppointment->strUrlNamed = GetCleanString($json->urlNamed);
        $urlAppointment->idDB = $idDB;
        $urlAppointment->idSalon = $idSalon;
        $urlAppointment->idEmployee = $idEmployee;
        
        $urlAppointment->isUse = GetInt($json->isUse);
        $urlAppointment->isCanUseAuthorisation = GetInt($json->isCanUseAuthorisation);
        $urlAppointment->isForecastOptions = GetInt($json->isForecastOptions);
        $urlAppointment->isShowReminders = GetInt($json->isShowReminders);
        $urlAppointment->isUseAudio = GetInt($json->isUseAudio);
        $urlAppointment->isShowAdress = GetInt($json->isShowAdress);
        $urlAppointment->isGenerateQRCode = GetInt($json->isGenerateQRCode);
        $urlAppointment->isRequirePhone = GetInt($json->isRequirePhone);
        $urlAppointment->isRequireName = GetInt($json->isRequireName);
        $urlAppointment->isShowCommentField = GetInt($json->isShowCommentField);
        $urlAppointment->isSendedNotification = GetInt($json->isSendedNotification);
        
        $urlAppointment->intPeriodMinutes = GetInt($json->period);
        $urlAppointment->intBetweenMinutes = GetInt($json->between);
        $urlAppointment->intBeforeMinutes = GetInt($json->before);
        $urlAppointment->intMinutesWaiting = GetInt($json->wait);
    
        $urlAppointment->id = $dbUrlAppointment->Save($urlAppointment);
    }


    require_once('out.php');
?>