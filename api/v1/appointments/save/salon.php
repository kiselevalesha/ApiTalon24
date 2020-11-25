<?php
    require_once '../php-scripts/db/dbSalons.php';
    require_once '../php-scripts/db/dbImages.php';
    require_once '../php-scripts/db/dbContacts.php';
    require_once 'api/v1/adresess/update.php';

    function SaveSalon($idDB, $salon) {

            $dbSalon = new DBSalon($idDB);

            if ($salon->id > 0) {
                $salon = $dbSalon->GetSalon($salon->id);
            }
            /*else {
                //  Создадим нового клиента, если передана о нём хоть какая-то информация
                $str = $strPhone.$strEmail.$strVK.GetCleanString($salon->name);
                if (strlen(trim($str)) > 0)
                    $salon = $dbSalon->New();
            }*/
        
            if ($salon->id > 0) {
                if (empty($salon->strName))   $salon->strName = GetCleanString($salon->name);

                $salon->isNew = 0;
                $dbSalon->Save($salon);


                $dbImage = new DBImage($idDB);
                if ($salon->photo)
                    if (! ($salon->photo->id > 0))
                        if (!empty($salon->photo->url)) {
                            $image = $dbImage->SaveNewPhoto($salon->photo->url, EnumEssential::SALONS);
                            if ($image->id > 0) {
                                $dbSalon->UpdateField("idMainPhoto", $image->id, "id=".$salon->id);
                                $dbSalon->idMainPhoto = $image->id;
                            }
                        }


                //  Сохранить данные о Контактах
                if (isSet($salon->contacts)) {
                    $dbContact = new DBContact($idDB);
                    $salon->strJsonContacts = $dbContact->SaveContacts($salon->contacts, EnumEssential::SALONS);
                }
                // $strJsonContacts->new  $strJsonContacts->old


                //  Сохранить данные об Адресе
                if (isSet($salon->adress)) {
                    $salon->strJsonAdress = UpdateAdress($salon->adress, $salon->id, EnumEssential::SALONS);
                }
                // $strJsonAdress->new  $strJsonAdress->old

                $salon->strJson = $dbSalon->MakeJson().',"contacts":['.$salon->strJsonContacts.'],"adress":{'.$salon->strJsonAdress.'}';
            }

        //  возвращаем данные в той же структуре, в которой получили
        return $salon;
    }
?>