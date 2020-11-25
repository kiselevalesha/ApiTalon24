<?php
    function GetJsonNewObject($idDB) {

        $strTargetUrl = GetCleanString($json->target);
        if (empty($strTargetUrl))   ExitEmptyError("Url is empty!");
    
        $strRefererUrl = GetCleanString($json->referer);
        $strAppointment = GetCleanString($json->appointment);

    
        require_once('../php-scripts/db/dbCodeAppointments.php');
        $dbCodeAppointment = new DBCodeAppointment();
    
        if (!empty($strAppointment)) {
            $codeAppointment = $dbCodeAppointment->GetByCode($strAppointment);
        }
        
        if (empty($codeAppointment)) {
    
            //  Находим url онлайн-записи. И по нему находим idDB, idSalon, idEmployee
            if (!empty($strTargetUrl)) {
                
                require_once('../php-scripts/db/dbUrlAppointments.php');
                $dbUrlAppointment = new DBUrlAppointment();
                
                $idUrlAppointment = $dbUrlAppointment->GetIdByUrl($strTargetUrl);
                if ($idUrlAppointment > 0) {
                    $urlAppointment = $dbUrlAppointment->Get($idUrlAppointment);
                    
                    if ($urlAppointment->isUse != 1)
                        ExitError(901, "Онлайн-запись <b>отключена</b> владельцем. Сервис <b>недоступен</b>.");
    
                    $idDB = $urlAppointment->idDB;
    
    
                    //  Находим токен.
                    require_once('../php-scripts/db/dbTokenEmployee.php');
                    $dbTokenEmployee = new DBTokenEmployee();
                    $idToken = $dbTokenEmployee->GetIdField("idMainDB=".$idDB);
    
    
                    //  Устанавливаем флаг, что был переход по ссылке. Это нужно знать, когда запись была предсоздана и нужно учитывать факты перехода по ней.
                    $isUrlInitializationed = $dbTokenEmployee->GetIntField("isUrlInitializationed", "id=".$idToken);
                    if ($isUrlInitializationed == 0) {
                        
                        //  Инициализация начальных таблиц, нужных для формирования сообщений
                        require_once('../php-scripts/api/v1/initialization/loadtables.php');
            
                        $dbTokenEmployee->UpdateField("isUrlInitializationed", 1, "id=".$idToken);
                    }
    
    
                    //  Проверяем не отключена ли онлайн-запись в настройках
                    require_once('../php-scripts/db/dbSettings.php');
                    $dbSettings = new DBSettings($idDB);
                    $settings = $dbSettings->GetDefault();
                    if ($settings->isUseOnlineAppointment != 1)
                        ExitError(901, "Онлайн-запись <b>отключена</b> владельцем. Сервис <b>недоступен</b>.");
                
                
                    
                    //  Проверяем наличие подписки
                    require_once('../php-scripts/utils/checkSubscription.php');
                    if (! checkSubscriptionAndBalance(EnumTypeServices::ONLINEAPPOINTMENTS, $urlAppointment->idDB))
                        ExitError(999, "Онлайн-запись <b>отключена</b> из-за отрицательного баланса на счёте. Сервис <b>недоступен</b>.");
    
    
                    //  Создаём новую запись в CodeAppointments. Генерируем её код.
                    $codeAppointment = $dbCodeAppointment->Add($urlAppointment->idDB, $urlAppointment->idSalon, $urlAppointment->idEmployee,
                        EnumTypeToolCreator::SITE_ZAPISHIS_ONLINE, 1, EnumEssential::CLIENTS, 0);
    
    
                    if ($urlAppointment->idDB > 0) {
                        //  Создаём новую запись в Appointments в локальной DB.
                        require_once('../php-scripts/db/dbAppointments.php');
                        $dbAppointment = new DBAppointment($urlAppointment->idDB);
                        $appointment = $dbAppointment->Add($urlAppointment->idSalon, $urlAppointment->idDepartment, $urlAppointment->idEmployee, $codeAppointment->longCode, 
                                                            EnumTypeToolCreator::SITE_ZAPISI_ONLINE, 1, EnumEssential::CLIENTS, 0, $strRefererUrl);
                        $dbCodeAppointment->UpdateField("idAppointment", $appointment->id, "id=".$codeAppointment->id);
                        if ($appointment->id == 1) {
                            $comment = "Это проверочная запись, сделанная самому(ой) к себе. Теперь её можно удалить.";
                            $dbAppointment->UpdateField("strDescription", $comment, "id=".$appointment->id);
                        }
                    }
        
                    $strAppointment = $codeAppointment->GetCodeFormat();
                }
                else {
                    ExitError(900, "Онлайн-запись не найдена. Проверьте корректность написания ссылки. Строчные и прописные буквы различаются.");
                }
            }
            
        }
    
        $strJson = '';
        if (!empty($strAppointment))
            $strJson = '"appointment":"'.$strAppointment.'"';
            
        if ($codeAppointment instanceof CodeAppointment) 
            $strJson .=',"isFinishedStep1":'.$codeAppointment->isFinishedStep1.
                ',"isFinishedStep2":'.$codeAppointment->isFinishedStep2.
                ',"isFinishedStep3":'.$codeAppointment->isFinishedStep3.
                ',"isFinishedStep4":'.$codeAppointment->isFinishedStep4.
                ',"isFinishedStep5":'.$codeAppointment->isFinishedStep5;
    
        return $strJson;
    }
 ?>