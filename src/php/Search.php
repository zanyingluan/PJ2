<?php
session_start();
require_once('config.php');
require_once('function.php');


$pageNumber = 0;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if (isset($_GET['city'])) {
    $city = $_GET['city'];
} else {
    $city = '';
}
if(isset($_GET['way'])){
    $way = $_GET['way'];
}

if(isset($_GET['title'])){
    $title = $_GET['title'];
}

if(isset($_GET['description'])){
    $description = $_GET['description'];
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
    <link href="../css/search-content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">
    <title>Search</title>
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
                <li><a href="Search.php" class="Nav-active">Search</a></li>
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
    <!--Search部分-->
    <div class="search-container">
        <div class="section-head">
            <p>Search</p>
        </div>
        <hr>
        <form  id="search-form">
            <div class="radio-container">
                <label>
                    <input id="search-by-title" name="way" type="radio" value="title" onclick="click1()" >Filter by Title
                </label>
            </div>
            <div class="text-container">
                <label>
                    <textarea id="title-content" name="title" placeholder="Search by title.."  readonly></textarea>
                </label>
            </div>
            <div class="radio-container">
                <label>
                    <input id="search-by-description" name="way" type="radio"  value="description" onclick="click2()">Filter by Description
                </label>
            </div>
            <div class="text-container">
                <label>
                    <textarea id="description-content" name="description" placeholder="Search by description.." readonly></textarea>
                </label>
            </div>
            <div class="filter-icon">
                <label>
                    <input type="image" id="Submit" value="submit" alt="submit"
                           src="../../images/icon/filter.png" width="40px">
                </label>
            </div>
        </form>
        <script>
            let radio1 = document.getElementById('search-by-title');
            let radio2 = document.getElementById('search-by-description');
            let textarea1 = document.getElementById('title-content');
            let textarea2 = document.getElementById('description-content');
            function click1() {
                if(textarea1.readOnly) {
                    textarea1.removeAttribute("readonly");
                }
                if(!textarea2.readOnly){
                    textarea2.setAttribute("readonly","")
                }

            }
            function click2() {
                if(textarea2.readOnly) {
                    textarea2.removeAttribute("readonly");
                }
                if(!textarea1.readOnly){
                    textarea1.setAttribute("readonly","")
                }
            }
        </script>
    </div>
    <!--result部分-->

    <div class="main-result-container">
        <div class="section-head">
            <p>Result</p>

        </div>

        <div class="search-result">
            <?php
            if($way){
                echo '
            <p>Search by ' . $way . '
            </p>';
            }
            if($title) {
                echo '
            <p>Title : ' . $title . '
            </p>
            ';
            }
            if($description) {
                echo '
            <p>Description = ' .$description. '
            
            </p>
            ';
            }
            ?>
        </div>

        <?php
        $result =getAllImage();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($way) {//有搜索条件
                if ($way == "title") {//按title搜索
                    if ($title) {
                        $result = getImageByTitle($_GET['title']);
                    }
                } elseif ($description) {
                    $result = getImageByDescription($_GET['description']);
                }
            }
        }
        $rowNumber=$result->rowCount();
        if ($rowNumber == 0) {
            echo '<div class="row">
    <p>搜索无结果!</p>
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

        $pdo =null;
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

            echo '<a href="' . changePage(1) . '">第一页</a>';
            echo '<a href="' . changePage(previousPage($page)) . '"><</a>';
            for ($i = $startPage; $i < $endPage + 1; $i++) {
                if ($i == $page) echo '<strong>' . $page . '</strong>';
                else echo '<a href="' . changePage($i) . '">' . $i . '</a>';
            }
            echo '<a href="' . changePage(nextPage($page, $pageNumber)) . '">></a>';
            echo '<a href="' . changePage($pageNumber) . '">最后一页 (总共' . $pageNumber . ' 页)</a>';
            function changePage($num)

            {
                if ($_GET['way'] == 'title') {
                    return $_SERVER["PHP_SELF"] . "?page=" . $num . "&way=title&title=" . $_GET['title'];
                } elseif ($_GET['way'] == 'description') {
                    return $_SERVER["PHP_SELF"] . "?page=" . $num . "&way=description&description=" . $_GET['description'];
                }else{
                    return $_SERVER["PHP_SELF"] . "?page=" . $num  ;
                }
            }


            ?>

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