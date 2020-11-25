<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';

    
    require_once('../php-scripts/db/dbMessageRules.php');
    $dbMessageRule = new DBMessageRule($idDB);
    
    $id = GetInt($json->id);
    
    if ($id > 0)
        $rule = $dbMessageRule->Get($id);
    else
        $rule = new MessageRule();
    
    $rule->strName = GetCleanString($json->name);
    $rule->strDescription = GetCleanString($json->description);
    $rule->strBody = GetCleanString($json->body);

    $rule->idTypeRecepient = GetInt($json->typeRecepient);
    $rule->idTypeDate = GetInt($json->typeDate);
    $rule->ageCustom = GetInt($json->ageCustom);
    $rule->idTypeTime = GetInt($json->typeTime);
    $rule->intTimeCustom = GetInt($json->timeCustom);
    $rule->intTimeShift = GetInt($json->timeShift);
    $rule->idTypeDelivery = GetInt($json->typeDelivery);
    $rule->idTypeRepeat = GetInt($json->typeRepeat);
    $rule->intRepeatCustom = GetInt($json->repeatCustom);

    $rule->idCategoryClients = GetInt($json->categoryClients->id);
    $rule->idCategoryEmployee = GetInt($json->categoryEmployee->id);
    $rule->idCategoryProducts = GetInt($json->categoryProducts->id);
    $rule->idCategoryServices = GetInt($json->categoryServices->id);
    $rule->idCategoryPlaces = GetInt($json->categoryPlaces->id);
    
    $rule->intRateByMaster = GetInt($json->rateByMaster);
    $rule->intRateByClient = GetInt($json->rateByClient);

    $rule->idReport = GetInt($json->report);
    $rule->isUse = GetInt($json->isUse);
    $rule->isNew = 0;
    $rule->id = $dbMessageRule->Save($rule);
    

    $strJson = '{'.$rule->MakeJson().'}';

    echo GetOutJson('"rule":'.$strJson);
?>