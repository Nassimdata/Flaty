<?php
require_once 'db.php';

$code = $_POST['code'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';



function back($msg)
{
    echo '<script>alert("' . $msg . '");history.back();</script>';
    die;
}


if (empty($code)) {
    back('code incorrect');
}
if (empty($new_password)) {
    back('Please fill in the new password');
}

if ($confirm_password != $new_password) {
    back('The password entries are inconsistent');
}


$codeRecord = DB::select('password_reset_code', '*', $where, '', 1);

if (count($codeRecord) < 1) {
    back('code incorrect');
}
$codeRecord = array_shift($codeRecord);
$userId = $codeRecord['user_id'];
//code expired check
if($codeRecord['expired_at'] < date('Y-m-d H:i:s')){
    back('Link expired');
}


$user = DB::select('user', 'password', "id = '$userId'", '', 1);
if (count($user) < 1) {
    back('error:00000');
}
$user = array_shift($user);

$password = password_hash($new_password, PASSWORD_BCRYPT);

$updateData = [
    'password' => $password
];


$update = DB::update('user', $updateData, "id=$userId limit 1");
if ($update) {
    echo '<script>alert("Your password has been reset, please login again");location.href="login.html";</script>';
} else {
    back("error:000001");
}

?>