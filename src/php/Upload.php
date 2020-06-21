<?php
session_start();
require_once ("config.php");
require_once ("function.php");
if (isset($_GET['imageID'])) {
    $img = getImageById($_GET['imageID']);
    $imgId = $_GET['imageId'];
} else $imgId = "";
$UID = $_SESSION['id'];
?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/header.css" rel="stylesheet" type="text/css">
    <link href="../css/upload-content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">

    <title>Upload</title>
</head>
<body>
    <!--header部分-->
    <header>
        <nav id="top">
            <div class="Nav-left">
                <ul>
                    <!--logo-->
                    <li><a href="../../Index.php"><img src="../../images/Nav-logo.png" alt="logo" class="Nav-logo"
                                                       width="70"></a></li>
                    <!--导航条目-->
                    <li><a href="../../Index.php">Home</a></li>
                    <li><a href="Browser.php">Browser</a></li>
                    <li><a href="Search.php">Search</a></li>
                </ul>
            </div>
            <div class="Nav-right">
                <?php
                if (isset($_SESSION['id'])) {
                    $UID = $_SESSION['id'];
                    echo '
                <!--个人中心-->
                <ul>
                    <li><a href="" id="account">My account</a></li>
                </ul>
                <!--下拉菜单-->
                <div class ="Nav-right-menu">
                    <ul>
                        <!--利用font-awesome-->
                        <li><a href="Upload.php" class="Nav-active"><i class="fa fa-upload"></i>Upload</a></li>
                        <li><a href="My_photo.php"><i class="fa fa-photo"></i>My Photo</a></li>
                        <li><a href="My_favourite.php"><i class="fa fa-heart"></i>My Favorite</a></li>
                        <li><a href="Logout.php"><i class="fa fa-sign-in"></i>Log Out</a></li>
                    </ul>
                </div>
            ';
                } else {
                    echo '<ul>
                    <li><a href="Login.php"  id="account">Login</a></li>
                </ul>';
                }
                ?>
            </div>
        </nav>
        <hr>
    </header
    <!--content部分-->
    <div class="main-container">
        <div class="section-head">
            <p>Upload</p>
        </div>
        <form enctype="multipart/form-data" action="uploadChange.php"
             method="post">
        <hr id="long1">
        <div class="upload-picture-container">
                <?php
                //如有id，输出图片,如无id，输出待上传
                if (isset($_GET['imageID'])) echo '<img src="../../images/large/' . $img['PATH'] . '" alt="The Photo" id="uploadedImg">';
                else echo '<img  src="" alt="The Photo" id="uploadedImg" style="display: none">';
                ?>
        </div>
        <hr id="long2">
        <div class="text-container" id="text-container1">
            <div class="request">
                Upload Photo
            </div>
            <div>
                <label>
                    <input type="file"  id="file" name="file">
                </label>
            </div>
        </div>
        <hr>
        <div class="text-container">
            <div class="request">
                Photo Title
            </div>
            <div>
                <label>
                    <input type="text"  placeholder="Title here" name="title" id="title" required
                        <?php
                        if (isset($_GET['imageID'])) echo 'value="' . $img['Title'] . '"';
                        ?>>
                </label>
            </div>
        </div>
        <hr>
        <div class="text-container">
            <div class="request">
                Photo Description
            </div>
            <div class="textarea">
                <label>
                    <textarea id="description"><?php if (isset($_GET['imageID'])) echo $img['Description']; ?></textarea>
                </label>
            </div>
        </div>
        <hr>
        <div class="text-container">
            <div class="request">
                Photo Content
            </div>
            <div class="textarea">
                <label>
                    <select  name="Content" id="content">
                        <?php
                        for ($i = 0; $i < 8; $i++) {
                            $contentText = getSelectedContent($i);
                            echo '<option ';
                            if (isset($img)) if ($img['Content'] == $contentText) echo 'selected ';
                            echo 'value="' . $contentText . '">';
                            if ($i == 0) $contentText = 'Choose content';
                            echo $contentText . '</option>';
                        }
                        ?>
                    </select>
                </label>
            </div>
        </div>
        <hr>
        <div class="text-container">
            <div class="request">
                Photo Country
            </div>
            <div class="textarea">
                <label>
                    <input type="text"  placeholder="Country here" name="Country" id="country"
                    <?php
                    if (isset($_GET['imageID'])) echo ' value="' . getCountryByCountryCode($img['Country_RegionCodeISO']) . '"';
                    ?>">
                </label>
            </div>
        </div>
        <hr>
        <div class="text-container">
            <div class="request">
               Photo City
            </div>
            <div class="textarea">
                <label>
                <input type="text" placeholder="City here" name="City" id="city"
                <?php
                if (isset($_GET['imageID'])) echo ' value="' . getCityByCityCode($img['CityCode']) . '"';
                ?>"
                    <label>
            </div>
        </div>
        <div class="submit">
            <label>
                <?php
                if(isset($_GET['imageID'])){
                    echo'
                    <input type="image" id="submit" value="submit" alt="submit"
                       src="../../images/icon/modify.png" width="50px">';
                }else{
                    echo '
                    <input type="image" id="submit" value="submit" alt="submit"
                       src="../../images/icon/submit.png" width="50px">';
                }
                ?>
                </label>
        </div>
        </form>
    </div>
    <!--辅助图标部分-->
    <div class="support">
        <div class="go-to-top">
        <a href="#top">
            <img src="../../images/icon/top.png" alt="" width="30px">
        </a>
    </div>

        <div class="refresh" onclick="alert('图片已刷新')" >
        <img src="../../images/icon/refresh.png" alt="" width="30px">
    </div>
    </div>
    <!--foot部分-->
    <footer>
        <div class="container">
            <table >
                <tr>
                    <td><p><a href="">Terms of use</a></p></td>
                    <td><p><a href="">About me</a></p></td>
                    <td>
                        <a href="https://weixin.qq.com/" target="_blank"><img src="../../images/icon/weChat.png" alt="weChat" width="30px"/></a>
                        <a href="https://github.com/" target="_blank"><img src="../../images/icon/github.png"  alt="github" width="30px" /></a>
                    </td>
                    <td rowspan="3"><img src="../../images/icon/weChat2DCode.png" alt="weChat2DCode" width="150px"/></td>
                </tr>
                <tr>
                    <td><p><a href="">Privacy</a></p></td>
                    <td><p><a href="">Contact me</a></p></td>
                    <td>
                        <a href="https://im.qq.com/" target="_blank"><img src="../../images/icon/qq.png" alt="qq" width="30px" /></a>
                        <a href="https://www.twitter.com/" target="_blank"><img src="../../images/icon/twitter.png" alt="twitter" width="30px" /></a>
                    </td>
                </tr>
                <tr>
                    <td >
                        <p><a href="">Cookie</a></p>
                    </td>

                </tr>
            </table>
        </div>
        <div id="copyrightRow">
            <div class="container">
                <p class="copyright">Copyright © 2019-2021 Web fundamental. All Rights Reserved. 备案号：沪IronMan备000727号-1</p>
            </div>
        </div>
    </footer>
</body>
<script type="text/javascript" src="../js/upload.js"></script>
</html>