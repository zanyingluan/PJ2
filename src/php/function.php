<?php

require_once('config.php');

function pdo($sql)
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $result = $pdo->query($sql);
    $pdo = null;
    return $result;
}

function getMaxId()
{
    $sql = 'SELECT `ImageID` FROM `travelimage` ORDER BY `ImageID` DESC LIMIT 1';
    return pdo($sql)->fetch()['ImageID'];
}


function deletePhoto($UID, $imageId)
{
    $sql = 'DELETE FROM travelimage WHERE `UID` = ' . $UID . ' and `ImageID` = ' . $imageId;
    return pdo($sql);
}

function deleteFavor($UID, $imageId)
{
    $sql = 'DELETE FROM travelimagefavor WHERE `UID` = ' . $UID . ' and `ImageID` = ' . $imageId;
    return pdo($sql);
}

function enFavor($UID, $imageId)
{
    $sql = "
INSERT INTO `travelimagefavor` (`UID`, `ImageID`)
VALUES ('" . $UID . "', '" . $imageId . "')";
    return (pdo($sql));
}

function getAllImage()
{
    $sql = 'SELECT travelimage.* FROM travelimage';
    return pdo($sql);
}

function getImageById($imageId)
{
    //按图片ID搜索图片
    $sql = 'SELECT * FROM `travelimage` WHERE ImageID=' . $imageId;
    $result = pdo($sql);
    return $result->fetch();
}

function getFavorNumberById($imageId)
{
    $sql = 'SELECT * FROM `travelimagefavor` WHERE ImageID=' . $imageId;
    return pdo($sql)->rowCount();
}

function getUserNameByUID($UID)
{
    $sql = 'SELECT * FROM `traveluser` WHERE UID=' . $UID;
    $result = pdo($sql);
    return $result->fetch()['UserName'];
}

function getMyPhotoByUID($UID)
{
    $sql = 'SELECT * FROM `travelimage` WHERE UID=' . $UID;
    return pdo($sql);
}

function getFavorPhotoByUID($UID)
{
    $sql = 'SELECT *
FROM `travelimagefavor`
JOIN travelimage 
ON travelimage.ImageID=travelimagefavor.ImageID 
WHERE travelimagefavor.UID=' . $UID;
    return pdo($sql);
}


function getCityCodeByCity($city)
{
    if ($city != null) {
        $sql = 'SELECT * FROM `geocities` WHERE AsciiName=' . $city;
        $result = pdo($sql);
        $geo = $result->fetch();
        $cityCode = $geo['AsciiName'];
    } else {
        $cityCode = 'unknown-CityCode';
    }
    return $cityCode;
}

function getCountryCodeByCountry($country)
{
    if ($country != null) {
        $sql = 'SELECT * FROM `geocountries_regions` WHERE Country_RegionName="' . $country . '"';
        $result = pdo($sql);
        $countryCode = $result->fetch()['ISO'];
    } else {
        $countryCode = 'unknown-CountryCode';
    }
    return $countryCode;
}

function isFavor($UID, $imageId)
{
    $sql = 'SELECT * FROM `travelimagefavor` WHERE UID=' . $UID . ' AND ImageID=' . $imageId;
    $result = pdo($sql);
    if ($result->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

//Browser
function getHotContent($limit)
{
    $sql = 'SELECT `Content` FROM `travelimage` GROUP BY travelimage.Content ORDER BY COUNT(travelimage.Content) DESC LIMIT ' . $limit;
    $result = pdo($sql);
    $content = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($content, $row['Content']);
    }
    return $content;
}

function getHotCountryCode($limit)
{
    $sql = 'SELECT `Country_RegionCodeISO` FROM `travelimage` GROUP BY travelimage.Country_RegionCodeISO ORDER BY COUNT(travelimage.Country_RegionCodeISO) DESC LIMIT ' . $limit;
    $result = pdo($sql);
    $ISO = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($ISO, $row['Country_RegionCodeISO']);
    }
    return $ISO;
}


function getAllContent()
{
    $sql = 'SELECT `Content` FROM `travelimage` GROUP BY travelimage.Content ORDER BY COUNT(travelimage.Content)';
    $result = pdo($sql);
    $content = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($content, $row['Content']);
    }
    return $content;
}

function getAllCountryCode()
{
    $sql = 'SELECT `Country_RegionCodeISO` FROM `travelimage` GROUP BY travelimage.Country_RegionCodeISO ORDER BY COUNT(travelimage.Country_RegionCodeISO)';
    $result = pdo($sql);
    $ISO = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($ISO, $row['Country_RegionCodeISO']);
    }
    return $ISO;
}

function getAllCityCodeByCountryCode($countryCode)
{
    $sql = "SELECT `CityCode` FROM `travelimage` WHERE `Country_RegionCodeISO`= '.$countryCode.' GROUP BY travelimage.CityCode ORDER BY COUNT(travelimage.CityCode) DESC ";

    $result = pdo($sql);
    $cityCode = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($cityCode, $row['GeoNameID']);
    }
    return $cityCode;
}

function getCountryByCountryCode($countryCode)
{
    if ($countryCode == null) {
        $country = 'UNKNOWN';
    } elseif ($countryCode != 'NULL') {
        $sql = 'SELECT * FROM `geocountries_regions` WHERE ISO="' . $countryCode . '"';
        $result = pdo($sql);
        $country = $result->fetch()['Country_RegionName'];
    } else {
        $country = 'NULL';
    }
    return $country;
}

function getCityByCityCode($cityCode)
{
    if ($cityCode == null) {
        $city = 'UNKNOWN';
    } elseif ($cityCode != "NULL") {
        $sql = 'SELECT * FROM `geocities` WHERE GeoNameID=' . $cityCode;
        $result = pdo($sql);
        $geo = $result->fetch();
        $city = $geo['AsciiName'];
    } else {
        $city = 'NULL';
    }
    return $city;
}

function getHotCityCode($limit)
{
    $sql = 'SELECT `CityCode` FROM `travelimage` GROUP BY travelimage.CityCode ORDER BY COUNT(travelimage.CityCode) DESC LIMIT ' . $limit;
    $result = pdo($sql);
    $CityCode = array();
    for ($i = 1; $i <= $result->rowCount(); $i++) {
        $row = $result->fetch();
        array_push($CityCode, $row['CityCode']);
    }
    return $CityCode;
}

//search
function getImageByTitle($title)
{
    //标题模糊查询
    $keys = explode(" ", $title);
    $keyNum = count($keys);

    $sql = 'SELECT * FROM travelimage WHERE `Title` LIKE  "%' . $keys[0] . '%"';
    for ($i = 2; $i <= $keyNum; $i++) {
        $sql = $sql . ' AND `Title` LIKE "%' . $keys[$i - 1] . '%"';
    }

    return pdo($sql);
}

function getImageByDescription($description)
{
    $keys = explode(" ", $description);
    $keyNum = count($keys);

    $sql = 'SELECT * FROM travelimage WHERE `Description` LIKE  "%' . $keys[0] . '%"';
    for ($i = 2; $i <= $keyNum; $i++) {
        $sql = $sql . ' OR `Description` LIKE "%' . $keys[$i - 1] . '%"';
    }

    return pdo($sql);
}



function getImageByCity($cityCode)
{
    $sql = 'SELECT * FROM travelimage WHERE `CityCode` = "' . $cityCode . '"';
    return pdo($sql);
}

function getImageByContent($content)
{
    $sql = 'SELECT * FROM travelimage WHERE `Content` = "' . $content . '"';
    return pdo($sql);
}

function getImageByCountry($countryCode)
{
    $sql = 'SELECT * FROM travelimage WHERE `Country_RegionCodeISO` = "' . $countryCode . '"';
    return pdo($sql);
}

function getImageByContentAndCountry($content, $countryCode)
{
    $sql = 'SELECT * FROM travelimage WHERE `Country_RegionCodeISO` = "' . $countryCode . '" AND `Content` ="' . $content . '"';
    return pdo($sql);
}

function getImageByContentAndCity($content, $cityCode)
{
    $sql = 'SELECT * FROM travelimage WHERE `CityCode` = "' . $cityCode . '" AND `Content` ="' . $content . '"';

    return pdo($sql);
}


function previousPage($page)
{
    if ($page == 1) return 1;
    else return $page - 1;
}

function nextPage($page, $pageNumber)
{
    if ($page == $pageNumber) return $pageNumber;
    else return $page + 1;
}

function getSelectedContent($index)
{
    switch ($index) {
        case 1:
            $result = 'Scenery';
            break;
        case 2:
            $result = 'City';
            break;
        case 3:
            $result = 'People';
            break;
        case 4:
            $result = 'Animal';
            break;
        case 5:
            $result = 'Building';
            break;
        case 6:
            $result = 'Wonder';
            break;
        case 7:
            $result = 'Others';
            break;
        default:
            $result = '';
    }
    return $result;
}