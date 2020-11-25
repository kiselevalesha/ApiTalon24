<?
    chdir('../../..');
    require_once '../php-scripts/utils/api.php';

    require_once('../php-scripts/db/dbMessages.php');
    $dbMessage = new DBMessage($idDB);

    $sql = "(ageWillSend>0 OR ageWasSended>0) AND isDeleted=0 AND isNew=0 AND isDraft=0";
    $srJsonMessages = '"messages":['.$dbMessage->OutJsonMessages($sql).']';

    echo GetOutJson($srJsonMessages);
?>