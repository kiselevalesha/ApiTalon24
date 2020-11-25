<?php
    function GetJsonList($arrayIds, $offset, $maximum) {
        global $idDB;
        $dbResource = new DBResource($idDB);
        //$dbPricelist = new DBPricelist($idDB);
        //$dbPricelistContent = new DBPricelistContent($idDB);
        $dbImage = new DBImage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        if (strlen($sqlWhere) > 0)  $sqlWhere .= " AND ";
        $sqlWhere .= "isDeleted=0";

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbResource->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY strName';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbResource->ExecuteQuery($query);
        
        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];
                $idPricelist = 0;
                
                //  Найти данные из прайслиста
                $strJsonPricelistContent = "";
                /*if ($idPricelist < 1)
                    $idPricelist = $dbPricelist->GetIdDefault();
                
                $idPricelistContent = $dbPricelistContent->GetId($id, $idPricelist);
                
                if ($idPricelistContent > 0)
                    $strJsonPricelistContent = $dbPricelistContent->GetStringField("strJson", "id=".$idPricelistContent);*/
                
                //  Найти картинку
                $strJsonImages = $dbImage->GetJsonPhotos($id, EnumEssential::RESOURCES);
                if (!empty($strJsonImages))
                    $strJsonImages = ',"images":['.$strJsonImages.']';

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson;
                    if (!empty($strJsonPricelistContent))
                        $str .= ",".$strJsonPricelistContent;
                    if (!empty($strJsonImages))
                        $str .= $strJsonImages;
                    $str .= '}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>