<?php
session_start();
require_once('src/php/config.php');


function generate($result)
{
    while ($result->rowCount() > 0 && $row = $result->fetch()) {
        echo ' <div class="picture_container">
            <!-- 图片部分都是超链接-->
                <a href="src/php/Details.php?imageID=' . $row['ImageID'] . '">
                    <div class="picture">
                        <img src="images/pictures/large/' . $row['PATH'] . ' " alt="图片">
                    </div>
                </a>

                <div class="picture_introduce">
                    <article>
                        <h1>' . $row['Title'] . '</h1>
                        <p>
                          ' . $row['Description'] . '
                        </p>
                    </article>
                </div>
            </div>';
    }

}

?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="src/css/reset.css" rel="stylesheet" type="text/css">
    <link href="src/css/header.css" rel="stylesheet" type="text/css">
    <link href="src/css/home-content.css" rel="stylesheet" type="text/css">
    <link href="src/css/footer.css" rel="stylesheet" type="text/css">
    <title>Home</title>
</head>
<body>
<!--header部分-->
<header>
    <nav id="top">
        <div class="Nav-left">
            <ul>
                <!--logo-->
                <li><a href="Index.php"><img src="images/Nav-logo.png" alt="logo" class="Nav-logo" width="70"></a></li>
                <!--导航条目-->
                <li><a href="Index.php" class="Nav-active">Home</a></li>
                <li><a href="src/php/Browser.php">Browser</a></li>
                <li><a href="src/php/Search.php">Search</a></li>
            </ul>
        </div>
        <div class="Nav-right">
            <?php
            if (isset($_SESSION['id'])) {
                echo '
                <!--个人中心-->
                <ul>
                    <li><a href="" id="account">My account</a></li>
                </ul>
                <!--下拉菜单-->
                <div class ="Nav-right-menu">
                    <ul>
                        <!--利用font-awesome-->
                        <li><a href="src/php/Upload.php"><i class="fa fa-upload"></i>Upload</a></li>
                        <li><a href="src/php/My_photo.php"><i class="fa fa-photo"></i>My Photo</a></li>
                        <li><a href="src/php/My_favourite.php"><i class="fa fa-heart"></i>My Favorite</a></li>
                        <li><a href="src/php/Logout.php"><i class="fa fa-sign-in"></i>Log Out</a></li>
                    </ul>
                </div>
            ';
            } else {
                echo '<ul>
                    <li><a href="src/php/Login.php" id="account">Login</a></li>
                </ul>';
            }
            ?>
        </div>
    </nav>
    <hr>
</header>
<!--content部分-->
<div class="main-container">
    <!--头图-->
    <div class="main_picture-container">
        <a href="src/php/Details.php?imageID=6" >
            <div class="main_picture" id="main_picture">
            </div>
        </a>
    </div>
    <!--图片展示部分-->
    <div class="section-head">Selected pictures</div>
    <!--grid-container用来实现grid布局-->
    <div class="grid-container">
        <?php
        try {
            if ($_GET['refresh']) {
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                $sql = "SELECT ImageID,PATH,Title,Description 
                               FROM travelimage 
                               ORDER BY RAND() LIMIT 15";
                $result = $pdo->query($sql);
                generate($result);
            } else {

                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                $sql = "SELECT travelimagefavor.ImageID ,travelimage.PATH,travelimage.Title,travelimage.Description ,count(*) 
                               FROM travelimagefavor 
                               JOIN travelimage 
                               ON travelimage.ImageID=travelimagefavor.ImageID 
                               GROUP BY travelimage.ImageID 
                               ORDER BY count(*) 
                               DESC LIMIT 15";
                $result = $pdo->query($sql);
                generate($result);
            }
            $pdo = null;
        } catch (PDOException $e) {
            $pdo = null;
            echo '<script>alert("服务器错误！请重新加载！")</script>';
        }
        ?>
    </div>
    <!--辅助功能部分-->
</div>

<!--辅助图标部分-->
<div class="support">
    <div class="go-to-top">
        <a href="#top">
            <img src="images/icon/top.png" alt="" width="30px">
        </a>
    </div>

    <div class="refresh">
        <a href="Index.php?refresh=true">
            <img src="images/icon/refresh.png" alt="" width="30px">
        </a>
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
                    <a href="https://weixin.qq.com/" target="_blank"><img src="images/icon/weChat.png" alt="weChat"
                                                                          width="30px"/></a>
                    <a href="https://github.com/" target="_blank"><img src="images/icon/github.png" alt="github"
                                                                       width="30px"/></a>
                </td>
                <td rowspan="3"><img src="images/icon/weChat2DCode.png" alt="weChat2DCode" width="150px"/></td>
            </tr>
            <tr>
                <td><p><a href="">Privacy</a></p></td>
                <td><p><a href="">Contact me</a></p></td>
                <td>
                    <a href="https://im.qq.com/" target="_blank"><img src="images/icon/qq.png" alt="qq"
                                                                      width="30px"/></a>
                    <a href="https://www.twitter.com/" target="_blank"><img src="images/icon/twitter.png" alt="twitter"
                                                                            width="30px"/></a>
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