<?
    chdir('../../../..');
    require_once '../php-scripts/utils/response.php';
    require_once '../php-scripts/utils/headers.php';

    require_once('../php-scripts/db/dbMessages.php');
    $dbMessage = new DBMessage($idDB);

    $sql = "ageIncome>0 AND isDeleted=0 AND isNew=0 AND isDraft=0";
    $srJsonMessages = '"messages":['.$dbMessage->OutJsonMessages($sql).']';

    echo GetOutJson($srJsonMessages);
?>