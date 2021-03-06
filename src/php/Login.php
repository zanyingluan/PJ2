<?php
session_start();
require_once('config.php')
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../../src/css/reset.css" rel="stylesheet" type="text/css">
    <link href="../../src/css/login-content.css" rel="stylesheet" type="text/css">
    <link href="../../src/css/footer.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="contents">
    <div class="main-container">
        <div class="logo-container">
            <img src="../../images/Nav-logo.png" alt="logo" width="80px">
            <p>Sign in</p>
        </div>
        <div class="form-container">
            <form action="Login.php" method="post">
                <div class="form-group">
                    <div class="word-div">
                        <h1>Username:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="username" value="" name="username" type="text"
                                   placeholder="请输入用户名" pattern="^[a-zA-Z0-9_-]{4,16}$"
                                   required="required">
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="word-div">
                        <h1>Password:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="password" name="password" type="password"
                                   placeholder="请输入密码" pattern="^[0-9A-Za-z]{8,16}$"
                                   required="required">
                        </label>
                    </div>
                </div>

                <?php
                function validLogin()
                {
                    try {
                        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                        $sql = "SELECT * FROM Traveluser WHERE UserName=:user";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':user', $_POST['username']);
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            $pdo = null;
                            $row = $statement->fetch();
                            $salt = $row['Salt'];
                            $password = sha1($_POST['password'] . $salt);
                            if ($password === $row['Password']) {
                                return $row;
                            } else {
                                return false;
                            }

                        } else {
                            $pdo = null;
                            return false;
                        }
                    } catch (PDOException $e) {
                        $pdo = null;
                        return "exception";
                    }
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    switch ($row = validLogin()) {
                        case false:
                            echo "<script>alert('用户名或者密码错误!');window.location='Login.php';</script>";
                            break;
                        case "exception":
                            echo "<script>alert('无法连接到服务器，请重试');window.location='Login.php';</script>";
                            break;
                        default:
                            //存储session
                            $_SESSION['id'] = $row['UID'];
                            $_SESSION['username'] = $row['UserName'];
                            $_SESSION['password'] = $row['Password'];
                            $_SESSION['state'] = $row['State'];
                            $_SESSION['email'] = $row['Email'];
                            echo "<script>alert('登录成功!');window.location='../../index.php';</script>";
                    }
                }
                ?>

                <div class="login-logo">
                    <label><input type="image" id="Submit" value="submit" alt="submit" src="../../images/icon/login.png"
                                  width="50px"></label>
                </div>

            </form>
        </div>
        <div class="go-to-register">
            <p>New to us?
                <a href="Register.php">Let's create a new account!</a>
            </p>
        </div>
    </div>
</div>
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