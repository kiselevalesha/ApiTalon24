<?php

    $id = GetInt($request->id);
    
    switch($action) {
        case "create":
                //$post = '{"token":"'.$strToken.'","objects":[{"id":'.$id.',"name":"Антон","patronymic":"Николаевич","surname":"Прутков"}]}';
                $post = '{"token":"'.$strToken.'","objects":[{"id":0}]}';
                $strJson = getUrlFile("https://talon24.ru/api/v1/pricelists/set.php", $post);
            break;
        case "edit":
            if ($id > 0) {
                $post = json_encode(array("id"=> $id));
                $strJson = getUrlFile("https://talon24.ru/api/v1/pricelists/set.php", $post);
            }
            break;
        case "delete":
            if ($id > 0) {
                $post = json_encode(array("id"=> $id));
                $strJson = getUrlFile("https://talon24.ru/api/v1/pricelists/delete.php", $post);
            }
            break;
        default:
            ExitError(152, "Not understand of Action-clause.");
    }


?>