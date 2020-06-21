<?php
session_start();
require_once("function.php");
$UID = $_SESSION['id'];

//如果是POST，说明已提交，直接更改数据库
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $imgId = $_POST['imageID'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $city = $_POST['City'];
    $country = $_POST['Country'];
    $content = $_POST['Content'];
    $newId = getMaxId() + 1;

    if ($_FILES['file']['name'] != '') {//传入了图片，下载到图片库
        $file = $_FILES['file'];
        $PATH = $file['name'];
        $upload_path = '../../images/pictures/large/';
        if (move_uploaded_file($file['tmp_name'], $upload_path . $PATH) || move_uploaded_file()) {
            if ($imgId == '') {//新上传图片，添加到数据库

                $sql = '
                INSERT INTO `travelimage` (`ImageID`,`Title`, `Description`, `CityCode`, `Country_RegionCodeISO`, `UID`, `PATH`, `Content`)
                VALUES ("' . $newId . '","' . $title . '","' . $description . '","' . getCityCodeByCity($city) . '","' . getCountryCodeByCountry($country) . '","' . $UID . '","' . $PATH . '","' . $content . '");';

                if (pdo($sql)) header("Location:Details.php?imageID=" . $newId);
                else echo "<script>alert('Upload failure!');history.back();</script>";
            } else {//老图修改，更改数据库
                $sql = 'UPDATE `travelimage` SET `Title`="' . $title . '",`Description`="' . $description . '",`CityCode`="' . getCityCodeByCity($city) . '",`Country_RegionCodeISO`="' . getCountryCodeByCountry($country) . '",`PATH`="' . $PATH . '",`Content`="' . $content . '" WHERE `ImageID`=' . $imgId;
                //echo 1233;
                if (pdo($sql)) header("Location:Details.php?imageID=" . $imgId);
                else echo "<script>alert('Modify failure!');history.back();</script>";
            }
        } else {//文件重名，上传失败
            echo "<script>alert('Upload failure!');history.back();</script>";
        }
    } else {//未上传图片，更改数据库
        $sql = 'UPDATE `travelimage` SET `Title`="' . $title . '",`Description`="' . $description . '",`CityCode`="' . getCityCodeByCity($city) . '",`Country_RegionCodeISO`="' . getCountryCodeByCountry($country) . '",`Content`="' . $content . '" WHERE `ImageID`=' . $imgId;
        if (pdo($sql)) header("Location:Details.php?imageID=" . $imgId);
        else echo "<script>alert('Modify failure!');history.back();</script>";
    }
}



