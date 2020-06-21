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

if (isset($_GET['Country'])) {
    $country = $_GET['Country'];
}else{
    $country = "NULL";
}

if (isset($_GET['Content'])) {
    $content = $_GET['Content'];
}else{
    $content = "NULL";
}

if (isset($_GET['City'])) {
    $city = $_GET['City'];
}else{
    $city = "NULL";
}
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
}
if (isset($_GET['Title'])) {
    $title = $_GET['Title'];
}


function generate($row)
{
    echo '
                <div class="picture_container">
                <a href="Details.php?imageID=' . $row['ImageID'] . '">
                    <div class="picture">
                        <img src="../../images/pictures/large/' . $row['PATH'] . ' " alt="图片">
                    </div>
                </a>
            </div>';
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
    <link href="../css/browser-content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/linkage.js"></script>
    <title>Browser</title>
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
                <li><a href="Browser.php" class="Nav-active">Browser</a></li>
                <li><a href="Search.php">Search</a></li>
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

    <!--侧边栏-->
    <div class="aside">
        <div class="search-by-title"><!--搜索框-->
            <div class="section-head">
                <p>Search By Title</p>
                <?
                echo $search;
                ?>
            </div>
            <hr>
            <div>
                <form action="Browser.php?page=1" >
                    <label><input type="text" id="condition" placeholder="Search by tittle.." name="Title" required></label>
                    <label><input name = Search type="image" id="Submit" value="search" alt="submit"
                                  src="../../images/icon/search.png"></label>
                </form>
            </div>
        </div>
        <div class="hot-content" id="character">
            <div class="section-head">
                <p>Hot content</p>
            </div>
            <hr/>
            <div>
                <ul>
                    <?php
                    $hotContent = getHotContent(5);
                    for ($i = 0; $i <= count($hotContent) - 1; $i++) {

                        echo '<li>                       
                                <a href="' . $_SERVER["PHP_SELF"] . '?page=1&Content=' . $hotContent[$i] . '&Country=NULL&City=NULL&filter=filter">' . $hotContent[$i] . '</a>
                            </li>';
                    }
                    ?>
                </ul>
                <div class="hot-content-picture">
                    <img src="../../images/icon/hot-content.png" alt="hot-content" width="40px"
                         id="hot-content-picture">
                </div>
            </div>

        </div>
        <div class="hot-content" id="country">
            <div class="section-head">
                <p>Hot Country</p>
            </div>
            <hr/>
            <div>
                <ul>
                    <?php
                    $hotCountryCode = getHotCountryCode(5);
                    for ($i = 0; $i <= count($hotCountryCode) - 1; $i++) {
                        echo '<li><a  href="' . $_SERVER["PHP_SELF"] . '?page=1&Content=NULL&Country=' . $hotCountryCode[$i] . '&City=NULL&filter=filter">' . getCountryByCountryCode($hotCountryCode[$i]) . '</a></li>';;
                    }
                    ?>
                </ul>
                <div class="hot-content-picture">
                    <img src="../../images/icon/hot-country.png" alt="hot-country" width="40">
                </div>
            </div>

        </div>
    </div>
    <!--主要页面-->
    <div class="main">
        <!--筛选框-->
        <div class="section-head">
            <p>Filter</p>
        </div>
        <div class="search-result">
            <?php

            if($title) {
                echo '
            <p>Title = ' . $title . '
            </p>
            ';
            }



            if($filter) {
                echo '
            <p>Content = ' . $content . '
               Country = ' . getCountryByCountryCode($country) . '
               City = ' . getCityByCityCode($city) . '
            </p>
            ';
            }



            ?>
        </div>
        <hr>
        <div class="filter">
            <label>
                <select class="content-select" id="contentMenu" onchange="changeValue()">
                    <option value="NULL" selected>Choose Content</option>
                    <?php
                    $allContent = getAllContent();
                    for ($i = count($allContent); $i >= 1; $i--) {
                        echo '<option value="' . $allContent[$i - 1] . '">' . $allContent[$i - 1] . '</option>';
                    }
                    ?>
                </select>
            </label>
            <!--二级联动-->
            <label>
                <select class="country-select" id="countryMenu"  onchange=" linkage();changeValue();">
                    <option value="NULL" selected>Choose Country</option>
                    <?php
                    $allCountryCode = getAllCountryCode();
                    for ($i = count($allCountryCode); $i >= 1; $i--) {
                        echo '<option value="' . $allCountryCode[$i - 1] . '">' . getCountryByCountryCode($allCountryCode[$i - 1]) . '</option>';
                    }
                    ?>
                </select>

            </label>
            <label>
                <select class="city-select" id="cityMenu" onchange="changeValue()">
                    <option value="NULL" selected>Choose City</option>
                </select>
            </label>

            <label>
                <a id=filter href="">
                    <img src="../../images/icon/filter.png" alt="filter" width="40px ">
                </a>
            </label>
        </div>
        <div class="grid-container">
            <?php

            //筛选逻辑

            if ($_SERVER['REQUEST_METHOD'] == 'GET'){
                if($title){
                    $title = $_GET['Title'];
                    $result = getImageByTitle($title);
                }
                elseif($filter == "filter") {
                    if ($content == "NULL") {
                        if ($city == "NULL" && $country != "NULL") {
                            $result = getImageByCountry($country);
                        }
                        if ($city == "NULL" && $country == "NULL") {
                            $result = getAllImage();
                        }
                        if ($city != "NULL" && $country == "NULL") {
                            $result = getImageByCity($city);
                        }
                        if ($city != "NULL" && $country != "NULL") {
                            $result = getImageByCity($city);
                        }
                    } else {
                        if ($country == "NULL" && $city != "NULL") {
                            $result = getImageByContentAndCity($content, $city);
                        }
                        if ($country == "NULL" && $city == "NULL") {
                            $result = getImageByContent($content);
                        }

                        if ($country != "NULL" && $city != "NULL") {
                            $result = getImageByContentAndCity($content, $city);
                        }

                        if ($country != "NULL" && $city == "NULL") $result = getImageByContentAndCountry($content, $country);
                    }

                }
                elseif(!$filter){
                    $result = getAllImage();
                }
            }



            //默认检索


            $rowNumber = $result->rowCount();


            global $pageNumber;
            $pageNumber = ceil($rowNumber / 12.0);
            //翻页
            global $page;
            for ($i = 1; $i <= ($page - 1) * 12; $i++) $result->fetch();
            //生成
            for ($i = 0; $i < 12; $i++) {
                if ($row = $result->fetch()) {
                    generate($row);
                }
            }
            $pdo = null;

            ?>
        </div>

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
                if (isset($_GET['filter'])) {
                    return $_SERVER["PHP_SELF"] . "?page=" . $num . "&Country=" . $_GET['Country'] . "&City=" . $_GET['City'] . "&Content=" . $_GET['Content'] . "&filter=filter";
                } elseif (isset($_GET['Title'])) {
                    return $_SERVER["PHP_SELF"] . "?page=" . $num . "&Title=" . $_GET['Title'];
                }else{
                    return $_SERVER["PHP_SELF"] . "?page=" . $num  ;
                }
            }


            ?>


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
    <!-- foot部分-->
    <footer>
        <div class="container">
            <table>
                <tr>
                    <td><p><a href="">Terms of use</a></p></td>
                    <td><p><a href="">About me</a></p></td>
                    <td>
                        <a href="https://weixin.qq.com/" target="_blank"><img src="../../images/icon/weChat.png"
                                                                              alt="weChat" width="30px"/></a>
                        <a href="https://github.com/" target="_blank"><img src="../../images/icon/github.png"
                                                                           alt="github"
                                                                           width="30px"/></a>
                    </td>
                    <td rowspan="3"><img src="../../images/icon/weChat2DCode.png" alt="weChat2DCode" width="150px"/>
                    </td>
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
                <p class="copyright">Copyright © 2019-2021 Web fundamental. All Rights Reserved.
                    备案号：沪IronMan备000727号-1</p>
            </div>
        </div>
    </footer>
</body>