<?
    require_once('../php-scripts/db/dbTokenClients.php');
    $dbTokenClient = new DBTokenClient();
    require_once '../php-scripts/utils/getUrlFile.php';

    $idToken = 0;

    //  Сначала проверим не передан ли логин и пароль.
    if ((!empty($strLogin)) && (!empty($strPassword))) {
        
        //  Попытаемся найти токен по логину и паролю
        $idToken = $dbTokenClient->GetIdByLoginPassword($strLogin, $strPassword);
        
        //  Если не найдено, значит закончить с ошибкой, потому что передали некорректные логин или пароль.
        if ($idToken < 1) {
            ExitEmptyError("Login and password is incorrect!");
        }
    }


    if (!empty($uidAndroid) && ($idToken == 0)) {
        require_once('../php-scripts/db/dbUidAndroids.php');
        $dbUidAndroid = new DBUidAndroid();
        
        //  Попытаемся найти токен по идентификатору андроид-устройства.
        $idToken = $dbUidAndroid->GetIdTokenByUid($uidAndroid);
    }


    require_once('../php-scripts/db/dbUidClients.php');
    $dbUidClient = new DBUidClient();

    if (!empty($uid) && ($idToken == 0)) {
        
        //  Попытаемся найти токен по куки, содержащей сгенерированный идентификатор юзера-заказчика.
        $idToken = $dbUidClient->GetIdTokenByUid($uid);
    }


    //  Если мы до этого момента не нашли токен, то его нужно создать
    if ($idToken == 0) {
                
        //  Получить в Firebase uid токена
        $uidFirebase = GetUidClientTokenFromFirebase();
        
        //  Создать DB для этого client-токена
        require_once('../php-scripts/db/dbSqlDbClients.php');
        $dbSqlDbClient = new DBSqlDbClient();
        

        $token = new TokenUser();
        $token->strToken = uniqid();
        $token->isTokenActive = 1;
        $token->longCodeToken = $dbTokenClient->GetOnlyDigits($token->strToken);
        $token->strFirebase = $uidFirebase;
        
        $sqlDB = $dbSqlDbClient->New();
        $token->idMainDB = $sqlDB->id;
        
        $idToken = $dbTokenClient->Save($token);
        
        //  Сохранить idToken в SqlDB, чтобы знать кто создал данную DB
        $dbSqlDbClient->UpdateField("idTokenUser", $idToken, "id=".$token->idMainDB);
        
        //  Сохранить в таблице Client-SqlDb.
        require_once('../php-scripts/db/dbClientSqlDbs.php');
        $dbClientSqldb = new DBClientSqldb();
        $dbClientSqldb->Update($idToken, $token->idMainDB);

        //  Нужно сохранить токен в dbUidClient
        $dbUidClient->Add($uid, $idToken);
    }
    else {
        $token = $dbTokenClient->Get($idToken);
    }

    function GetUidClientTokenFromFirebase() {
        
        $host = 1;

        $response = getUrlFile("https://us-central1-onlinezapis-b03a2.cloudfunctions.net/AddTokenClient?host=".$host, null);
        $jsonFirebase = json_decode($response, false, 32);
        
        if (isSet($jsonFirebase))
            if (isSet($jsonFirebase->status->id))
                if ($jsonFirebase->status->id == 200)
                    if (isSet($jsonFirebase->data->uid))  return $jsonFirebase->data->uid;
                
        return "";
    }
?>