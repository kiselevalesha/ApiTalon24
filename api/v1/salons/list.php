<?php
    function GetJsonList($arrayIds, $offset=0, $limit=0) {
        global $idDB;
        $dbSalon = new DBSalon($idDB);
        $dbAdress = new DBAdress($idDB);
        $dbImage = new DBImage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        $sqlWhere .= " AND isDeleted=0";

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbSalon->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY strName, strAlias';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbSalon->ExecuteQuery($query);
        
        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];

                $idAdress = $dbAdress->GetIdAdress($id, EnumEssential::SALONS);
                $strJsonAdress = "";
                if ($idAdress > 0)
                    $strJsonAdress = $dbAdress->GetStringField("strJson", "id=".$idAdress);

                //  Найти картинку
                $strJsonImages = $dbImage->GetJsonPhotos($id, EnumEssential::SALONS);
                if (!empty($strJsonImages))
                    $strJsonImages = ',"images":['.$strJsonImages.']';

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson;
                    if (!empty($strJsonAdress))
                        $str .= ',"adresses":[{'.$strJsonAdress.'}]';
                    if (!empty($strJsonImages))
                        $str .= $strJsonImages;
                    $str .= '}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>