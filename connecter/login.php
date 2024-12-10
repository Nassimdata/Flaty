<?php

require_once 'db.php';


$password = $_POST['password'];
$email = $_POST['email'];
function back($msg)
{
    echo '<script>alert("' . $msg . '");history.back();</script>';
    die;
}


// 校验字段是否为空
if (empty($email)) {
    back("Email is required");
}
// 校验字段是否为空
if (empty($password)) {
    back("password is required");
}

// 检查用户是否存在
$user = DB::select('user', '*', "email = '$email'", '', 1);
if (count($user) < 1) {
    back("Email ou mot de passe incorrect");
}
$user = array_shift($user);

//验证密码
$passworVerify = password_verify($password, $user['password']);

if (!$passworVerify) {
    back("Email ou mot de passe incorrect");
}
//登录成功
session_start();
$_SESSION['user'] = $user;
header("Location:member.php");
