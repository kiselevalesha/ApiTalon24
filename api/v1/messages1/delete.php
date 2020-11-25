<?
    chdir('../../..');
    require_once '../php-scripts/utils/api.php';
    

    $id = GetInt($json->id);
    if ($id > 0) {
        require_once('../php-scripts/db/dbMessages.php');
        $dbMessage = new DBMessage($idDB);
        $dbMessage->Delete($id);
    }

    $strJson = '{"id":"'.$id.'"}';
    echo GetOutJson('"message":'.$strJson);
?>