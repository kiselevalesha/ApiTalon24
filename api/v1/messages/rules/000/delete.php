<?
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';
    

    $id = GetInt($json->id);
    if ($id > 0) {
        require_once('../php-scripts/db/dbMessageRules.php');
        $dbMessageRule = new DBMessageRule($idDB);
        $dbMessageRule->Delete($id);
    }

    $strJson = '{"id":"'.$id.'"}';
    echo GetOutJson('"rule":'.$strJson);
?>