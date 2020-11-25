<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        require_once('../php-scripts/db/dbReservationTimes.php');
        $dbReservationTime = new DBReservationTime($idDB);

        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($strWhere) > 0)  $strWhere .= " AND ";
        $strWhere .= "isDeleted=0";
        if ($age > 0)    $strWhere .= " AND dateTimeDay=".$age;
        if ($idOwner > 0)    $strWhere .= " AND idOwner=".$idOwner;
        if ($idEssentialOwner > 0)    $strWhere .= " AND idEssentialOwner=".$idEssentialOwner;
        if ($idSalon > 0)    $strWhere .= " AND idSalon=".$idSalon;

        return $dbReservationTime->GetJsonOrderRows($sqlWhere, "idOwner, dateTimeDay", $offset, $maximum);
    }
?>