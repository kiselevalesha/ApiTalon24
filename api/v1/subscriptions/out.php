<?

function LoadServices($idSection) {
    global $dbSubscription, $dbSubscriptionState, $idEmployee;
    $sqlWhere = "idSection=".$idSection;
    $arrayServices = $dbSubscription->GetArrayRows($sqlWhere);
    $strJsonServices = "";
    foreach($arrayServices as $service) {
        if (!empty($strJsonServices))   $strJsonServices .= ',';
        $idState = $dbSubscriptionState->GetState($service->idService, $idEmployee);
        $strJsonServices .= MakeSubscription($service->idService, $service->strName, $service->strDescription, $service->cost, $service->idTypeWhat, $idState, $service->isAccessable);
    }
    return $strJsonServices;
}

function LoadSections() {
    $YES = 1;
    $strJsonSections = MakeSection(1, "Расширение функциональности сервиса", $YES, LoadServices(1));
    $strJsonSections .= ','.MakeSection(2, "Услуги коммуникаций", $YES, LoadServices(2));
    $strJsonSections .= ','.MakeSection(3, "Услуги калькуляции и аналитики", $YES, LoadServices(3));
    //$strJsonSections .= ','.MakeSection(4, "Услуги контроля качества", $YES, LoadServices(4));
    //$strJsonSections .= ','.MakeSection(5, "Услуги личного администратора", $YES, LoadServices(5));
    return $strJsonSections;
}

function MakeSubscription($id, $name, $description, $cost, $what, $isChecked, $isAccessable) {
    return '{"id":'.$id.', "name":"'.$name.'", "description":"'.$description.'", "cost":'.$cost.', "what":'.$what.', "isAccessable":'.$isAccessable.', "isChecked":'.$isChecked.'}';
}
function MakeSection($id, $name, $isShow, $services) {
    return '{"id":'.$id.', "name":"'.$name.'", "isShow":'.$isShow.', "services":['.$services.']}';
}


?>