<?
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';
    

    $id = GetInt($json->id);
    if ($id > 0) {
        require_once('../php-scripts/db/dbMessageTemplates.php');
        $dbMessageTemplate = new DBMessageTemplate($idDB);
        $dbMessageTemplate->Delete($id);
    }

    $strJson = '{"id":"'.$id.'"}';
    echo GetOutJson('"template":'.$strJson);
?>