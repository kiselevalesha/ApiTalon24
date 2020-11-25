<?php
    require_once '../php-scripts/models/essential.php';
    require_once '../php-scripts/db/dbProducts.php';
    require_once '../php-scripts/db/dbProductsAppointments.php';
    require_once '../php-scripts/db/dbPricelistContents.php';
    require_once '../php-scripts/db/dbPricelists.php';

    function SaveAllServices($idDB, $appointment) {

        $dbProductsAppointment = new DBProductsAppointment($idDB, EnumProductRelationTables::NameTableOrder);
        $dbPricelistContent = new DBPricelistContent($idDB);
        $dbPricelist = new DBPricelist($idDB);
        $dbProduct = new DBProduct($idDB);
        
        //  Определимся с прайслистом, по которому будем определять цену и продолжительность услуг.
        if ($appointment->idPricelist == 0)
            $appointment->idPricelist = $dbPricelist->GetIdDefault();
    
        //  Проходимся по всем переданным услугам и сохраняем их как выбранные или как не выбранные
        foreach ($appointment->services as $service) {
            
            $relation = new ProductAppointment();
            $relation->idOwner = $appointment->id;
            $relation->idEssential = EnumEssential::SERVICES;
            $relation->idProduct = GetInt($service->id);
            $relation->intQuantity = 1;
            $relation->isDeleted = 0;
    
            if (($service->isSelected == 1) || (strcmp($service->isSelected,"true") == 0))
                $relation->isChecked = 1;
            else
                $relation->isChecked = 0;
    
            //  Отрабатываем случай, когда услуга сначала была выбрана, а потом убрали с неё выделенность.
            $flagSkipSave = false;
            if ($relation->isChecked == 0) {
                
                //  Возможно, что такая запись ещё не существует, то и сохранять её нет смысла
                $relation->id = $dbProductsAppointment->GetId($relation);
                if ($relation->id > 0)
                    $flagSkipSave = true;
            }
            
            if (! $flagSkipSave) {
    
                //  выясняем цену и продолжительность услуги в прайслисте
                $pricelistContent = $dbPricelistContent->GetRowBySql("idPricelist=".$appointment->idPricelist." AND idProduct=".$relation->idProduct." AND isDeleted=0 AND idEssential=".$relation->idEssential);
                if ($pricelistContent->id > 0) {
                    $relation->cost = $pricelistContent->costForSale;
                    $relation->summaTotal = $relation->cost * $relation->intQuantity;    //  цену умножить на количество, которое по умолчанию = 1.
                    $relation->intMinutesDuration = $pricelistContent->intDurationMinutes;
                }
                
                $dbProductsAppointment->SaveUpdate($relation);
            }
        }
    
        //  Теперь проходимся по списку сохранённых принадлежащих Записи услуг (сохранённых ранее или сейчас), чтобы посчитать общее время и общую стоимость
        $duraion = 0;
        $cost = 0;
        $strJsonRows = "";
        $arrayProductsAppointment = $dbProductsAppointment->GetArrayRows("idOwner=".$appointment->id." AND isChecked=1 AND idEssential=".EnumEssential::SERVICES);
        foreach ($arrayProductsAppointment as $serviceRelation) {
            $duraion += $serviceRelation->intMinutesDuration;
            $cost += $serviceRelation->summaTotal;
            
            //  тут нужно формировать json по услугам и определять название услуги
            $product = $dbProduct->GetRowBySql("idProduct=".$relation->idProduct." AND isDeleted=0 AND idEssential=".$relation->idEssential);
            if ($product->id > 0) {
                if (! empty($strJsonRows))  $strJsonRows .= ',';
                $strJsonRows .= '{"id":'.$product->id.',"name":"'.$product->strName.'"'
                    .',"isSelected":'.$serviceRelation->isChecked
                    .',"cost":'.$serviceRelation->summaTotal
                    .',"duration":'.$serviceRelation->intMinutesDuration
                    .',"quantity":'.$serviceRelation->intQuantity
                    .'}';
            }
        }
        $appointment->costOrder = $cost;
        $appointment->intMinutesDuration = $duraion;
        $appointment->strJsonServices = $strJsonRows;

        //  возвращаем данные в той же структуре, в которой получили
        return $appointment;
    }
?>