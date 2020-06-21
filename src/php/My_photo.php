<?PHP
session_start();
require_once('config.php');
require_once("function.php");

$pageNumber = 0;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}


function generate($row)
{
    echo '  <hr>
    <div class="row" >
        <a href="Details.php?imageID=' . $row['ImageID'] . '">
            <div class="picture">
                <img src="../../images/pictures/large/' . $row['PATH'] . ' " alt="图片">
            </div>
        </a>
        <div class="picture_introduce">
            <div class="details">
                <article>
                    <h1>' . $row['Title'] . '</h1>
                    <p>
                        ' . $row['Description'] . '
                    </p>
                </article>
            </div>
            <div class="operation">
                <a href="Upload.php?img=' . $row['ImageID'] . '"   onclick=" return  myClick1();">
                   <img src="../../images/icon/modify.png" alt="Delete" class="icon">
                   </a>
                <p class="word" id="modify-word-1">Modify</p>
            </div>
            <div class="operation">
                  <a href="My_photo.php?delete=' . $row['ImageID'] . '"   onclick="return myClick();">
                   <img src="../../images/icon/delete.png" alt="Delete" class="icon">
                   </a>
                     <script type="text/javascript">
                     function myClick(){
                         return confirm("确定要删除这张照片吗？");
                     }
                      function myClick1(){
                         return confirm("确定要修改这张照片吗？");
                     }
</script>
                <p class="word">Delete</p>
            </div>
        </div>
    </div>
   ';

}

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/header.css" rel="stylesheet" type="text/css">
    <link href="../css/my_content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">
    <title>My Photo</title>
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

                if (isset($_GET['delete'])) {//删除照片
                    deletePhoto($UID, $_GET['delete']);
                    echo '<script>location.href="'.$_SERVER["HTTP_REFERER"].'"</script>' ;//返回原始界面
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
                        <li><a href="My_photo.php" class="Nav-active"><i class="fa fa-photo"></i>My Photo</a></li>
                        <li><a href="My_favourite.php" ><i class="fa fa-heart"></i>My Favorite</a></li>
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
        <p>My Photo</p>
    </div>

    <?php
    if (isset($_SESSION['id'])) {
        $result = getMyPhotoByUID($UID);
        $rowNumber = $result->rowCount();
        if ($rowNumber == 0) {
            echo '<div class="row">
    <p>你还没有照片，快去上传照片吧!</p>
    </div>';

        }
        global $pageNumber;
        $pageNumber = ceil($rowNumber / 5.0);
        //翻页
        global $page;
        for ($i = 1; $i <= ($page - 1) * 5; $i++) $result->fetch();

        //生成
        for ($i = 0; $i < 5; $i++) {
            if ($row = $result->fetch()) {
                generate($row);
            }
        }

        $pdo = null;
    }
    ?>

    <!--页码-->
    <div class="page-number">
        <?php
        $startPage = 1;
        $endPage = 1;
        $pageRoom = 5;
        if ($pageNumber <= $pageRoom) {
            $startPage = 1;
            $endPage = $pageNumber;
        } elseif ($page <= $pageNumber - $pageRoom + 1) {
            $startPage = $page;
            $endPage = $pageRoom + $page - 1;
        } else {
            $startPage = $pageNumber - $pageRoom + 1;
            $endPage = $pageNumber;
        }

        echo '<a href="' . $_SERVER["PHP_SELF"] . '?page=1">第一页</a>';
        echo '<a href="' . $_SERVER["PHP_SELF"] . '?page=' . previousPage($page) . '"><</a>';
        for ($i = $startPage; $i < $endPage + 1; $i++) {
            if ($i == $page) echo '<strong>' . $page . '</strong>';
            else echo '<a href="' . $_SERVER["PHP_SELF"] . '?page=' . $i . '">' . $i . '</a>';
        }
        echo '<a href="' . $_SERVER["PHP_SELF"] . '?page=' . nextPage($page, $pageNumber) . '">></a>';
        echo '<a href="' . $_SERVER["PHP_SELF"] . '?page=' . $pageNumber . '">最后一页 (总共' . $pageNumber . ' 页)</a>';
        ?>

    </div>

</div>
<!--辅助图标部分-->
<div class="support">
    <div class="go-to-top">
        <a href="#top">
            <img src="../../images/icon/top.png" alt="go-to-top" width="30px">
        </a>
    </div>

    <div class="refresh" onclick="alert('图片已刷新')">
        <img src="../../images/icon/refresh.png" alt="refresh" width="30px">
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