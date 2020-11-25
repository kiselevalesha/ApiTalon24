<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';

    
    require_once('../php-scripts/db/dbMessageTemplates.php');
    $dbMessageTemplate = new DBMessageTemplate($idDB);
    
    $id = GetInt($json->id);
    $template = $dbMessageTemplate->Get($id);
    
    $template->strName = GetCleanString($json->name);
    $template->strDescription = GetCleanString($json->description);
    $template->strBody = GetCleanString($json->body);

    $template->idTypeRecepient = GetInt($json->typeRecepient);
    $template->idTypeDate = GetInt($json->typeDate);
    $template->ageCustom = GetInt($json->ageCustom);
    $template->idTypeTime = GetInt($json->typeTime);
    $template->intTimeCustom = GetInt($json->timeCustom);
    $template->intTimeShift = GetInt($json->timeShift);
    $template->idTypeDelivery = GetInt($json->typeDelivery);
    $template->idTypeRepeat = GetInt($json->typeRepeat);
    $template->intRepeatCustom = GetInt($json->repeatCustom);

    $template->idCategoryClients = GetInt($json->categoryClients->id);
    $template->idCategoryEmployee = GetInt($json->categoryEmployee->id);
    $template->idCategoryProducts = GetInt($json->categoryProducts->id);
    $template->idCategoryServices = GetInt($json->categoryServices->id);
    $template->idCategoryPlaces = GetInt($json->categoryPlaces->id);
    
    $template->intRateByMaster = GetInt($json->rateByMaster);
    $template->intRateByClient = GetInt($json->rateByClient);

    $template->idReport = GetInt($json->report);
    $template->isUse = GetInt($json->isUse);
    $template->isNew = 0;
    $template->id = $dbMessageTemplate->Save($template);
    

    $strJson = '{'.$template->MakeJson().'}';

    echo GetOutJson('"template":'.$strJson);
?>