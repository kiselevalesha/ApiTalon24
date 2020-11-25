<?
    require_once('../php-scripts/db/dbTokenEmployee.php');
    $dbTokenEmployee = new DBTokenEmployee();
    require_once '../php-scripts/utils/getUrlFile.php';

    $idToken = 0;

    //  Сначала проверим не передан ли логин и пароль.
    if ((!empty($strLogin)) && (!empty($strPassword))) {
        
        //  Попытаемся найти токен по логину и паролю
        $idToken = $dbTokenEmployee->GetIdByLoginPassword($strLogin, $strPassword);
        
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
    
    

    require_once('../php-scripts/db/dbUidEmployee.php');
    $dbUidEmployee = new DBUidEmployee();

    if (!empty($uid) && ($idToken == 0)) {
        
        //  Попытаемся найти токен по куки, содержащей сгенерированный идентификатор юзера-исполнителя.
        $idToken = $dbUidEmployee->GetIdTokenByUid($uid);
    }


    //  Если мы до этого момента не нашли токен, то его нужно создать
    if ($idToken == 0) {
                
        //  Получить в Firebase uid токена
        $uidFirebase = GetUidEmployeeTokenFromFirebase();
        
        //  Создать DB для этого employee-токена
        require_once('../php-scripts/db/dbSqlDbEmployee.php');
        $dbSqlDbEmployee = new DBSqlDbEmployee();
        

        $token = new TokenUser();
        $token->strToken = uniqid();
        $token->isTokenActive = 1;
        $token->longCodeToken = $dbTokenEmployee->GetOnlyDigits($token->strToken);
        $token->strFirebase = $uidFirebase;
        
        $sqlDB = $dbSqlDbEmployee->New();
        $token->idMainDB = $sqlDB->id;
        
        $idToken = $dbTokenEmployee->Save($token);
        
        //  Сохранить idToken в SqlDB, чтобы знать кто создал данную DB
        $dbSqlDbEmployee->UpdateField("idTokenUser", $idToken, "id=".$token->idMainDB);
        
        //  Сохранить в таблице Employee-SqlDb
        require_once('../php-scripts/db/dbEmployeeSqlDbs.php');
        $dbEmployeeSqldb = new DBEmployeeSqldb();
        $dbEmployeeSqldb->Update($idToken, $token->idMainDB);

        //  Нужно сохранить токен в dbUidEmployee
        $dbUidEmployee->Add($uid, $idToken);
    }
    else {
        $token = $dbTokenEmployee->Get($idToken);
        
        //  И нужно отметить время запроса токена, чтобы знать, кто не пользуется сервисом.
        $dbTokenEmployee->UpdateField("ageChanged", $dbTokenEmployee->NowLong(), "id=".$idToken);
    }
    
    function GetUidEmployeeTokenFromFirebase() {
        
        $host = 1;

        $response = getUrlFile("https://us-central1-onlinezapis-b03a2.cloudfunctions.net/AddTokenEmployee?host=".$host, null);
        $jsonFirebase = json_decode($response, false, 32);
        
        if (isSet($jsonFirebase->status->id))
            if ($jsonFirebase->status->id == 200)
                if (isSet($jsonFirebase->data->uid))  return $jsonFirebase->data->uid;
                
        return "";
    }

?>