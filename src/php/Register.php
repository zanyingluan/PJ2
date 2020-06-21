<?php
require_once('config.php')
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/register-content.css" rel="stylesheet" type="text/css">
    <link href="../css/footer.css" rel="stylesheet" type="text/css">
    <script src="../js/register.js" rel="script" type="text/javascript"></script>
</head>

<body>
<div class="contents">
    <div class="main-container">
        <div class="logo-container">
            <img src="../../images/Nav-logo.png" alt="logo" width="80px">
            <h2>Sign up</h2>
        </div>
        <div class="form-container">
            <form action="Register.php" method="post" onsubmit="return submit_check()">
                <div class="form-group">
                    <div class="word-div">
                        <h1>E-mail:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="email" name="email" type="text" placeholder="请输入正确的邮箱格式 example:zmgg@qq.com"
                                   pattern="^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$"
                                   required="required"
                                   onBlur=email_check()>
                        </label>
                    </div>
                    <div class="alert-div" id="email-check">
                    </div>

                </div>
                <hr>
                <div class="form-group">
                    <div class="word-div">
                        <h1>Username:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="username" name="username" type="text" placeholder="不少于4位不高于16位的数字、字母、下划线"
                                   pattern="^[a-zA-Z0-9_-]{4,16}$"
                                   required="required"
                                   onBlur=username_check()>
                        </label>
                    </div>
                    <div class="alert-div" id="username-check">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="word-div">
                        <h1>Password:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="password1" name="password1" type="password"
                                   placeholder="不少于8位不高于16位的数字、字母"
                                   pattern="^[0-9A-Za-z]{8,16}$"
                                   required="required"
                                   onBlur=password1_check()>
                        </label>
                    </div>
                    <div class="alert-div" id="password1-check">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="word-div">
                        <h1>Confirm Your Password:</h1>
                    </div>
                    <div class="input-div">
                        <label>
                            <input id="password2" name="password2" type="password"
                                   placeholder="请保持你输入的密码一致"
                                   required="required"
                                   onblur="password2_check()">
                        </label>
                    </div>
                    <div class="alert-div" id="password2-check">
                    </div>
                </div>
                <hr>

                <?php
                //随机生成盐，用来加密密码
                function salt_generate($length)
                {
                    $output = '';
                    for ($a = 0; $a < $length; $a++) {
                        $output .= chr(mt_rand(33, 126)); //生成php随机数
                    }
                    return $output;
                }

                $salt = salt_generate(10);
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                        $sql = "SELECT * FROM Traveluser WHERE UserName=:user";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':user', $_POST['username']);
                        $statement->execute();

                        if ($statement->rowCount() == 0) {

                            $statement = null;
                            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                            $sql = "INSERT INTO traveluser (Email,UserName,Password,Salt,State,DateJoined,DateLastModified) VALUES (:email,:username,:password,:salt,:state,:date_joined,:date_last_modified)";
                            $statement = $pdo->prepare($sql);
                            $statement->bindValue(':email', $_POST['email']);
                            $statement->bindValue(':username', $_POST['username']);
                            $statement->bindValue(':password', sha1($_POST['password1'] . $salt));
                            $statement->bindValue(':salt', $salt);
                            $statement->bindValue(':email', $_POST['email']);
                            $statement->bindValue(':state', '1');
                            $statement->bindValue(':date_joined', date("Y-m-d H:i:s"));
                            $statement->bindValue(':date_last_modified', date("Y-m-d H:i:s"));
                            $statement->execute();

                            if ($statement) {
                                $pdo = null;
                                echo "<script>alert('注册成功！');window.location='login.php';</script>";
                            } else {
                                echo "<script>alert('注册失败！');window.location='Register.php';</script>";
                            }
                            $pdo = null;
                        } else {
                            echo "<script>alert('注册失败!该用户名已经被使用！');window.location='Register.php';</script>";
                        } //验证用户名是否重复
                        $pdo = null;
                    } catch (PDOException $e) {
                        echo "<script>alert('网络错误！');window.location='Register.php';</script>";
                    }
                }
                ?>

                <div class="register-logo">
                    <label><input type="image" id="submit" value="submit" alt="submit"
                                  src="../../images/icon/register.png" width="50px"></label>
                </div>

            </form>
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