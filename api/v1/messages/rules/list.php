<?php
    function GetJsonList($idDB, $arrayIds, $offset=0, $limit=0) {
        $dbMessageRule = new DBMessageRule($idDB);
        $sqlWhere = GetSQLSetOfIds($arrayIds);

        $str = "";
        $comma = "";

        $arrayField = array();
        $query = "SELECT  id,strJson  FROM  ".$dbMessageRule->strTableName;
        if (!empty($sqlWhere))  $query .= ' WHERE '.$sqlWhere;
        $query .= ' ORDER BY strName';
        if ($limit > 0)
            if ($offset >= 0)
                $query .= ' LIMIT '.$offset.','.$limit;
        $result = $dbMessageRule->ExecuteQuery($query);


        if ($result)
            while ($row = $result->fetch_array(MYSQL_NUM)) {
                
                $id = $row[0];
                $strJson = $row[1];

                if (!empty($strJson)) {
                    $str .= $comma . "{".$strJson. '}';
                    $comma = ",";
                }
            }
        return $str;
    }
?>