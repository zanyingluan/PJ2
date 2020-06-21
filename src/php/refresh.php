<?php
session_start();    //启动会话
$_SESSION['refresh'] = true;
header("Location: " . $_SERVER['HTTP_REFERER']);