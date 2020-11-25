<?php
    header('Content-Type: application/json; charset="UTF-8"');
    chdir('../../..');
    require_once '../php-scripts/utils/json.php';
    require_once '../php-scripts/utils/exchange.php';


    $strFolderUpload = "import/";
    $importFileName = "";
    $importFileNamePath = "";
    

    
    $strToken = GetCleanString($_GET["token"]);
    if (empty($strToken))   ExitEmptyError("Token is empty!");
    
    require_once('../php-scripts/db/dbTokenEmployee.php');
    $dbTokenEmployee = new DBTokenEmployee();
    $idDB = $dbTokenEmployee->GetIdDbByToken($strToken);

    
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $importFileName = $_FILES['file']['name'];
        $imgFilePath = $strFolderUpload . $idDB;
        
        if (!file_exists($imgFilePath))     mkdir($imgFilePath);
        
        $importFileNamePath = $imgFilePath .'/'. $importFileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $importFileNamePath);
    }



    if (strlen($importFileNamePath) > 0) {

        $body = file_get_contents($importFileNamePath);
        $json = json_decode($body, false, 32);
        
        //  Сначала все таблицы, относящиеся к этой DB нужно удалить! Иначе может случится смешение данных.
        DeletionDB($idDB);

        foreach ($json->tables as $table) {
            //echo " table=".$table->name;
            $dbBase = null;
            switch($table->name) {
                case 'Adress':
                    require_once('../php-scripts/db/dbAdresses.php');
                    $dbBase = new DBAdress($idDB);
                    break;
                case 'Appointments':
                    require_once('../php-scripts/db/dbAppointments.php');
                    $dbBase = new DBAppointment($idDB);
                    break;
                case 'Actions':
                    require_once('../php-scripts/db/dbActions.php');
                    $dbBase = new DBAction($idDB);
                    break;
                case 'Bills':
                    require_once('../php-scripts/db/dbBills.php');
                    $dbBase = new DBBill($idDB);
                    break;
                case 'Calls':
                    require_once('../php-scripts/db/dbCalls.php');
                    $dbBase = new DBCall($idDB);
                    break;
                case 'Categories':
                    require_once('../php-scripts/db/dbCategories.php');
                    $dbBase = new DBCategory($idDB);
                    break;
                case 'Clients':
                    require_once('../php-scripts/db/dbClients.php');
                    $dbBase = new DBClient($idDB);
                    break;
                case 'Contacts':
                    require_once('../php-scripts/db/dbContacts.php');
                    $dbBase = new DBContact($idDB);
                    break;
                case 'Criterions':
                    require_once('../php-scripts/db/dbCriterions.php');
                    $dbBase = new DBCriterion($idDB);
                    break;
                case 'Employees':
                    require_once('../php-scripts/db/dbEmployee.php');
                    $dbBase = new DBEmployee($idDB);
                    break;
                case 'EmployeeServices':
                    require_once('../php-scripts/db/dbEmployeeServices.php');
                    $dbBase = new DBEmployeeService($idDB);
                    break;
                case 'Groups':
                    require_once('../php-scripts/db/dbGroups.php');
                    $dbBase = new DBGroup($idDB);
                    break;
                case 'Images':
                    require_once('../php-scripts/db/dbImages.php');
                    $dbBase = new DBImage($idDB);
                    break;
                /*case 'Logs':
                    require_once('../php-scripts/db/dbLogs.php');
                    $dbBase = new DBLog($idDB);
                    break;*/
                case 'Messages':
                    require_once('../php-scripts/db/dbMessages.php');
                    $dbBase = new DBMessage($idDB);
                    break;
                case 'Orders':
                    require_once('../php-scripts/db/dbOrders.php');
                    $dbBase = new DBOrder($idDB);
                    break;
                case 'OwnerGroups':
                    require_once('../php-scripts/db/dbOwnerGroup.php');
                    $dbBase = new DBOwnerGroup($idDB);
                    break;
                case 'Payments':
                    require_once('../php-scripts/db/dbPayments.php');
                    $dbBase = new DBPayment($idDB);
                    break;
                case 'PhotoServices':
                    require_once('../php-scripts/db/dbPhotoServices.php');
                    $dbBase = new DBPhotoService($idDB);
                    break;
                case 'PricelistContents':
                    require_once('../php-scripts/db/dbPricelistContents.php');
                    $dbBase = new DBPricelistContent($idDB);
                    break;
                case 'Pricelists':
                    require_once('../php-scripts/db/dbPricelists.php');
                    $dbBase = new DBPricelist($idDB);
                    break;
                case 'Products':
                    require_once('../php-scripts/db/dbProducts.php');
                    $dbBase = new DBProduct($idDB);
                    break;
                case 'ProductsAppointments':
                    require_once('../php-scripts/db/dbProductsAppointments.php');
                    $dbBase = new DBProductsAppointment($idDB);
                    break;
                case 'ReservationTime':
                    require_once('../php-scripts/db/dbReservationTimes.php');
                    $dbBase = new DBReservationTime($idDB);
                    break;
                case 'Resources':
                    require_once('../php-scripts/db/dbResources.php');
                    $dbBase = new DBResource($idDB);
                    break;
                case 'SalaryCalculations':
                    require_once('../php-scripts/db/dbSalaryCalculations.php');
                    $dbBase = new DBSalaryCalculation($idDB);
                    break;
                case 'SalonEmployee':
                    require_once('../php-scripts/db/dbSalonEmployees.php');
                    $dbBase = new DBSalonEmployee($idDB);
                    break;
                case 'Salons':
                    require_once('../php-scripts/db/dbSalons.php');
                    $dbBase = new DBSalon($idDB);
                    break;
                case 'Settings':
                    require_once('../php-scripts/db/dbSettings.php');
                    $dbBase = new DBSettings($idDB);
                    break;
                case 'Shedule':
                    require_once('../php-scripts/db/dbShedule.php');
                    $dbBase = new DBShedule($idDB);
                    break;
                case 'Talks':
                    require_once('../php-scripts/db/dbTalks.php');
                    $dbBase = new DBTalk($idDB);
                    break;
                case 'Taxes':
                    require_once('../php-scripts/db/dbTaxes.php');
                    $dbBase = new DBTax($idDB);
                    break;
            }
            
            if ($dbBase != null) {
                $strValueRows = "";
                $commaRows = "";
                foreach ($table->rows as $row) {
                    $strFields = "";
                    $strValues = "";
                    $commaRow = "";
                    foreach($row as $key => $val) {
                        $strFields .= $commaRow.$key;
                        $strValues .= $commaRow.'"'.$val.'"';
                        $commaRow = ",";
                    }
                    $strValueRows .= $commaRows.'('.$strValues.')';
                    $commaRows = ",";
                }
                $dbBase->SetLocalTableName($table->name, $idDB);
                $sql = 'INSERT INTO '.$dbBase->strTableName.' ('.$strFields.') VALUES '.$strValueRows;
                //echo " sql=".$sql;
                $dbBase->ExecuteQuery($sql);
            }
        }
        ExitEmptyError("Succesfull import file!");
    }
    
    ExitEmptyError("Error on upload import file!");
?>
