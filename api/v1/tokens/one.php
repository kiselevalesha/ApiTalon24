<?php
    function GetJsonOne($request) {
        
        require_once('../php-scripts/models/essential.php');

        $uid = GetCleanString($request->uid);
        $typeUid = GetInt($request->type);    //  EnumEssential::EMPLOYEE или EnumEssential::CLIENTS.
    
        $uidAndroid = GetCleanString($request->android);
    
        $strLogin = GetCleanString($request->login);
        $strPassword = GetCleanString($request->password);
        
        switch($typeUid) {
            case EnumEssential::EMPLOYEE:
                require_once 'employee.php';
                break;
            case EnumEssential::CLIENTS:
                require_once 'client.php';
                break;
            default:
                ExitEmptyError("Type is incorrect!");
        }
    
        return '"token":"'.$token->strToken.'","host":"talon24.ru"';
    }
?>