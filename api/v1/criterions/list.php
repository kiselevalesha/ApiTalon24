<?php
    function GetJsonList($arrayIds, $offset=0, $limit=0) {
        global $idDB;
        $dbCriterion = new DBCriterion($idDB);

        $sqlWhere = GetSQLSetOfIds($arrayIds);
        $sqlWhere .= " AND isDeleted=0";

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbCriterion->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY strName';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbCriterion->ExecuteQuery($query);
        
        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson;
                    $str .= '}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>