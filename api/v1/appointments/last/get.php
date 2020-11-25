<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';
    
    
    
    require_once('../php-scripts/db/dbAppointments.php');
    $dbAppointment = new DBAppointment($idDB);

    $idSalon = 1;
    $idEmployee = 1;
    
    $ageLast = $request->last;
    $sqlWhere = "ageCreated>=".$ageLast." AND idSalon=".$idSalon.
            " AND (idMaster1=".$idEmployee." OR idMaster2=".$idEmployee.
            " OR idAssistent1=".$idEmployee." OR idAssistent2=".$idEmployee.") AND isDeleted=0 AND isNew=0";
    $countCreatedAppointments = $dbAppointment->GetCountRows($sqlWhere);


    $sqlWhere = "ageCreated<".$ageLast." AND ageChanged>=".$ageLast." AND idSalon=".$idSalon.
            " AND (idMaster1=".$idEmployee." OR idMaster2=".$idEmployee.
            " OR idAssistent1=".$idEmployee." OR idAssistent2=".$idEmployee.") AND isDeleted=0 AND isNew=0";
    $countChangedAppointments = $dbAppointment->GetCountRows($sqlWhere);


    $sqlWhere = "ageChanged>=".$ageLast." AND isCanceled=1 AND ageCanceled>=".$ageLast." AND idSalon=".$idSalon.
            " AND (idMaster1=".$idEmployee." OR idMaster2=".$idEmployee.
            " OR idAssistent1=".$idEmployee." OR idAssistent2=".$idEmployee.") AND isDeleted=0 AND isNew=0";
    //$countCanceledAppointments = $dbAppointment->GetCountRows($sqlWhere);
    $countCanceledAppointments = 0;


    $strJson = '"created":'.$countCreatedAppointments.',"changed":'.$countChangedAppointments.',"canceled":'.$countCanceledAppointments;
    EndResponseData("last", $strJson);
?>