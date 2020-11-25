<?php
    chdir('../../../..');
    require_once '../php-scripts/utils/api.php';


    require_once('../php-scripts/db/dbMessageRules.php');
    $dbMessageRule = new DBMessageRule($idDB);
    if ($dbMessageRule->GetCountRows("") == 0)  $dbMessageRule->AddPrepareRules();
    
    require_once('../php-scripts/db/dbCategories.php');
    $dbCategory = new DBCategory($idDB);
    
    require_once('../php-scripts/db/dbOwnerGroup.php');
    $dbOwnerGroup = new DBOwnerGroup($idDB);
    
    
    $strJson = "";
    
    $arrayObjs = array();
    if ($idDB > 0) {

        $id = GetNumeric($json->id);
        if ($id < 0) {
            $messageRule = $dbMessageRule->New();
            $strJson = '{'.$messageRule->MakeJson().'}';
        }
        else {
            $sqlWhere = "isDeleted=0 AND isNew=0";
            if ($id > 0)    $sqlWhere .= " AND id=".$id;
            $arrayObjs = $dbMessageRule->GetArrayOrderRows($sqlWhere, "strName");

            foreach ($arrayObjs as $obj) {
                if (strlen($strJson) > 0)   $strJson .= ",";
                
                $strJsonGroups = "";
                $strJsonCategory = "";
        

                if ($id != 0) {
                    /*
                    // Найдём все группы
                    $strJsonGroups = '';
                    $arrayGroups = $dbOwnerGroup->GetArrayGroups($obj->id, EnumEssential::CLIENTS);
                    foreach($arrayGroups as $group) {
                        if (!empty($strJsonGroups)) $strJsonGroups .= ',';
                        $strJsonGroups .= '{"id":"'.$group->id.'","name":"'.$group->strName.'"}';
                    }
                    $strJsonGroups = ',"groups":['.$strJsonGroups.']';
        
                    // Найдём категорию
                    $category = $dbCategory->Get($obj->idCategory);
                    $strJsonCategory = ',"category":{"id":"'.$category->id.'","name":"'.$category->strName.'"}';
                    */
                }
        
                $strJson .= '{'.$obj->MakeJson().$strJsonPhoto.$strJsonAdress.$strJsonContacts.$strJsonGroups.$strJsonCategory.'}';
            }
        }
    }

    echo GetOutJson('"rules":['.$strJson.']');
?>