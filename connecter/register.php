<?php

require_once 'db.php';


function back($msg)
{
    echo '<script>alert("' . $msg . '");history.back();</script>';
    die;
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$telphone = $_POST['telphone'];
$email = $_POST['email'];
$password = $_POST['password'];


$insertData = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'telphone' => $telphone,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT)
];

if(empty($email)){
    back('email is required');
}


if(empty($password)){
    back('password is required');
}


// 检查邮箱是否已存在
$existingUserByEmail = DB::select('user', 'id', "email = '$email'", '', 1);
if (count($existingUserByEmail) > 0) {
    back('Email déjà enregistré');
}

$success = DB::insert('user', $insertData);

if ($success) {
    session_start();
    $insertData['id'] = DB::lastInsertId();
    $_SESSION['user'] = $insertData;
    header("Location:member.php");
} else {
    back('L’inscription a échoué');
}
