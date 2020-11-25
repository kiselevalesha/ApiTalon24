<?
    require_once('../php-scripts/db/dbClients.php');

    $idEssential = EnumEssential::CLIENTS;

    //  в зависимости от action восстановить по разному.
    switch ($history->idAction) {
        
        case EnumTypeActions::ActionCreate:  //  удалить созданный
        
            //  загрузим в массив все удаляемые айдишники
            $arrayIdDeletes = array();
            foreach($json->tables as $table) {
                $strTable = $table->name;
                if (! empty($strTable))
                    foreach($table->rows as $row)
                        array_push($arrayIdDeletes, $row->id);
            }

            require_once 'api/v1/clients/mark.php';
            MarkClientsDeleted($idDB, $arrayIdDeletes);
            EndResponsePureData('"objects":['.$strGlobalJsonReturnRows.'],"action":"delete"');
            break;
            

        case EnumTypeActions::ActionEdit:    //  вернуть изменения
            foreach($json->tables as $table) {
                $strTable = $table->name;
                if (! empty($strTable))
                    foreach($table->rows as $row) {

                        $jsonParameters = $row->old;
                        $jsonParameters->id = $row->id;
                        $arrayJsonObjs = array();
                        array_push($arrayJsonObjs, $jsonParameters);

                        require_once 'api/v1/clients/save.php';
                        $strJsonRows = SaveAll($idDB, $arrayJsonObjs);
                        EndResponsePureData('"objects":['.$strJsonRows.'],"action":"edit"');
                        break;
                    }
            }
            break;

        case EnumTypeActions::ActionDelete:  //  вернуть удалённый
            $arrayIds = SetFieldDeleted($json, 0);

            $dbTable = new DBClient($idDB);
            $strJsonRows = GetJsonListRows($arrayIds, $dbTable);
            EndResponsePureData('"objects":['.$strJsonRows.'],"action":"edit"');
            break;
    }

?>