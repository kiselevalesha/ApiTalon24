<?php
    function GetJsonList($arrayIds, $idEssential, $offset=0, $limit=0) {
        global $idDB;
        $dbCategory = new DBCategory($idDB);

        $sqlWhere = GetSQLSetOfIds($arrayIds);
        $sqlWhere .= " AND isDeleted=0 ";
        if ($idEssential > 0)   $sqlWhere .= " AND idEssential=".$idEssential;

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbCategory->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY strName';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbCategory->ExecuteQuery($query);

        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson.'}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>