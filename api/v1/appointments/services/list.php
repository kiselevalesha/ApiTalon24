<?php
    function GetJsonList($arrayIds, $offset=0, $limit=0) {
        global $idDB;
        $dbClient = new DBClient($idDB);
        $dbAdress = new DBAdress($idDB);
        $dbImage = new DBImage($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);
        $sqlWhere .= " AND isDeleted=0";

        $str = "";
        $comma = "";
        

            $arrayField = array();
            $query = "SELECT  id,strJson  FROM  ".$dbClient->strTableName;
            if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
            $query .= ' ORDER BY strSurname, strName, strPatronymic';
            if ($limit > 0)
                if ($offset >= 0)
                    $query .= ' LIMIT '.$offset.','.$limit;
            $result = $dbClient->ExecuteQuery($query);
            
            if ($result)
                while ($row = $result->fetch_array(MYSQL_NUM)) {
                    
                    $id = $row[0];
                    $strJson = $row[1];

                    $idAdress = $dbAdress->GetIdAdress($id, EnumEssential::CLIENTS);
                    $strJsonAdress = "";
                    if ($idAdress > 0)
                        $strJsonAdress = $dbAdress->GetStringField("strJson", "id=".$idAdress);
                        
                    
                    //  Найти картинку
                    $image = $dbImage->Get("idOwner=".$id." AND idEssential=".EnumEssential::CLIENTS." AND isMainInSequence=1");
                    $strUrlImage = "";
                    if ($image instanceof Image)
                        $strUrlImage = $image->GetFullPath();
                    
                    if (!empty($strJson)) {
                        $str .= $comma . "{".$strJson;
                        if (!empty($strJsonAdress))
                            $str .= ',"adress":{'.$strJsonAdress.'}';
                        if (!empty($strUrlImage))
                            $str .= '"url":"'.$strUrlImage.'"';
                        $str .= '}';
                        $comma = ",";
                    }
                }
        return $str;

    }
?>