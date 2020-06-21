<?php
session_start();
require_once('config.php');
require_once('function.php');
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $imageID = $_GET['imageID'];
    $imageRow = getImageById($imageID);
    $content = $imageRow['Content'];
    $description = $imageRow['Description'];
    $title = $imageRow['Title'];
    $path = $imageRow['PATH'];
    $UserName = getUserNameByUID($imageRow['UID']);
    $country = getCountryByCountryCode($imageRow['Country_RegionCodeISO']);
    $city = getCityByCityCode($imageRow['CityCode']);
    //收藏数
    $favor = getFavorNumberById($imageID);

} catch (PDOException $e) {
    $pdo = null;
    echo '<script>alert("服务器错误！请重新加载！")</script>';
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/header.css" rel="stylesheet" type="text/css">
    <link href="../css/details-content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">
    <title>Details</title>

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

                if (isset($_GET['favor'])) {
                    $enFavor = $_GET['favor'];
                    if($enFavor == "like"){
                        enFavor($UID,$imageID);
                    }

                    if($enFavor == "dislike"){
                        deleteFavor($UID,$imageID);
                    }
                    echo '<script>location.href="' . $_SERVER["HTTP_REFERER"] . '"</script>';
                   }


                echo '
                <!--个人中心-->
                <ul>
                    <li><a href="" id="account">My account</a></li>
                </ul>
                <!--下拉菜单-->
                <div class ="Nav-right-menu">
                    <ul>
                        <!--利用font-awesome-->
                        <li><a href="Upload.php"><i class="fa fa-upload"></i>Upload</a></li>
                        <li><a href="My_photo.php"><i class="fa fa-photo"></i>My Photo</a></li>
                        <li><a href="My_favourite.php"><i class="fa fa-heart"></i>My Favorite</a></li>
                        <li><a href="Logout.php"><i class="fa fa-sign-in"></i>Log Out</a></li>
                    </ul>
                </div>
            ';
            } else {
                echo '<ul>
                    <li><a href="Login.php" id="account">Login</a></li>
                </ul>';
            }
            ?>
        </div>
    </nav>
    <hr>
</header>
<!--content部分-->
<div class="main-container">
    <div class="section-head">
        <p>Details</p>
    </div>
    <hr>
    <div class="top">
        <div class="title">
            <h1>
                <?php
                echo $title
                ?>
            </h1>
        </div>
    </div>

    <div class="middle">
        <div class="left">
            <div class="picture" id="shower">
                <?php
                echo '<img src="../../images/pictures/large/' . $path . '" alt ="图片">'
                ?>
            </div>

        </div>

        <div class="right">
            <div class="like-number">
                <div class="section-head">
                    <p>Like Number</p>
                </div>
                <hr id="right-1">
                <div class="number">
                    <div id="favor-number">
                        <?php
                        echo $favor;
                        ?>
                    </div>

                    <div id="love">
                        <?php

                        if(isset($UID)) {
                            if (!isFavor($UID, $imageID)) {
                                echo '<a href="'.$_SERVER['REQUEST_URI'] .'&favor=like " id="favor" onclick="return confirm1()">
                            <img src="../../images/icon/love.png" alt="love" >
                        </a>';
                            } else {
                                echo '<a href="'.$_SERVER['REQUEST_URI'] .'&favor=dislike " id="disfavor" onclick="return confirm2()">
                            <img src="../../images/icon/loved.png" alt="loved"">
                        </a>';
                            }
                        }else{
                            echo '<a href="Login.php" id="favor" onclick="return goLogin()">
                            <img src="../../images/icon/love.png" alt="love">
                        </a>
                        ';
                        }
                        ?>

                    </div>
                    <script>
                        function goLogin() {
                            return(confirm("登陆后才能收藏噢！"))
                        }
                        function confirm1(){
                            return(confirm("确认收藏吗？"))
                        }
                        function confirm2(){
                            return(confirm("取消收藏吗？"))
                        }
                    </script>
                </div>
            </div>

            <div class="image-details">
                <div class="section-head">
                    <p>Image Details</p>
                </div>
                <hr id="right-2">
                <div class="details-ul">
                    <ul>
                        <?php
                        echo '<li>Photographer:  ' . $UserName . '</li>
                            <li>Content:  ' . $content . '</li>
                            <li>Country:  ' . $country . '</li>
                            <li>City:  ' . $city . '</li>'
                        ?>
                    </ul>
                </div>
            </div>

        </div>

    </div>

    <div class="bottom">
        <div class="description">
            <p>
                <?php
                echo $description
                ?>
            </p>
        </div>
    </div>

</div>
<!--辅助图标部分-->
<div class="support">
    <div class="go-to-top">
        <a href="#top">
            <img src="../../images/icon/top.png" alt="" width="30px">
        </a>
    </div>

    <div class="refresh" onclick="alert('图片已刷新')">
        <img src="../../images/icon/refresh.png" alt="" width="30px">
    </div>
</div>
<!--foot部分-->
<footer>
    <div class="container">
        <table>
            <tr>
                <td><p><a href="">Terms of use</a></p></td>
                <td><p><a href="">About me</a></p></td>
                <td>
                    <a href="https://weixin.qq.com/" target="_blank"><img src="../../images/icon/weChat.png"
                                                                          alt="weChat" width="30px"/></a>
                    <a href="https://github.com/" target="_blank"><img src="../../images/icon/github.png" alt="github"
                                                                       width="30px"/></a>
                </td>
                <td rowspan="3"><img src="../../images/icon/weChat2DCode.png" alt="weChat2DCode" width="150px"/></td>
            </tr>
            <tr>
                <td><p><a href="">Privacy</a></p></td>
                <td><p><a href="">Contact me</a></p></td>
                <td>
                    <a href="https://im.qq.com/" target="_blank"><img src="../../images/icon/qq.png" alt="qq"
                                                                      width="30px"/></a>
                    <a href="https://www.twitter.com/" target="_blank"><img src="../../images/icon/twitter.png"
                                                                            alt="twitter" width="30px"/></a>
                </td>
            </tr>
            <tr>
                <td>
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

</html>