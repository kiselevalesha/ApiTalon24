<?php
    function GetJsonList($arrayIds, $offset=0, $limit=0) {
        global $idDB;
        $dbAppointment = new DBAppointment($idDB);
        $dbAdress = new DBAdress($idDB);
        $dbImage = new DBImage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        $sqlWhere .= " AND isDeleted=0";

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbAppointment->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY ageReview, ageOrderStart';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbAppointment->ExecuteQuery($query);
        
        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];

                $idAdress = $dbAdress->GetIdAdress($id, EnumEssential::CLIENTS);
                $strJsonAdress = "";
                if ($idAdress > 0)
                    $strJsonAdress = $dbAdress->GetStringField("strJson", "id=".$idAdress);
                    
                
                //  Найти картинку
                $strJsonImages = $dbImage->GetJsonPhotos($id, EnumEssential::CLIENTS);
                if (!empty($strJsonImages))
                    $strJsonImages = ',"images":['.$strJsonImages.']';

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson;
                    if (!empty($strJsonAdress))
                        $str .= ',"adress":{'.$strJsonAdress.'}';
                    if (!empty($strJsonImages))
                        $str .= $strJsonImages;
                    $str .= '}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>