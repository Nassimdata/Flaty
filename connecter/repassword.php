<?php

require_once 'db.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function back($msg)
{
    echo '<script>alert("' . $msg . '");history.back();</script>';
    die;
}


//生成重置码
function generateRandomPassword($length = 60) {
    // 定义可用字符集
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}


function getCurrentDirectory() {
    // 获取协议 (http 或 https)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    
    // 获取主机名
    $host     = $_SERVER['HTTP_HOST'];
    
    // 获取请求 URI 并移除查询字符串
    $request  = strtok($_SERVER['REQUEST_URI'], '?');
    
    // 分离目录和文件名
    $pathInfo = pathinfo($request);
    $directory = isset($pathInfo['dirname']) ? $pathInfo['dirname'] : '/';
    
    // 将所有反斜杠替换为正斜杠，并规范化路径
    $directory = '/' . trim(str_replace('\\', '/', $directory), '/');
    
    // 构建完整的目录 URL
    $currentDirectory = "$protocol://$host$directory";
    
    // 如果目录不是根目录且末尾没有斜杠，则添加斜杠
    if ($directory !== '/' && substr($currentDirectory, -1) !== '/') {
        $currentDirectory .= '/';
    }

    return $currentDirectory;
}




$email = $_POST['email'];

$user = DB::select('user', '*', "email = '$email'", '', 1);

if (count($user) < 1) {
    back("Email incorrect");
}

$user = array_shift($user);


//5分钟内防止重复发送
$dateLimit = date('Y-m-d H:i:s',time() - 1200);

$where = "user_id = '{$user['id']}' and created_at > '$dateLimit'";

$codeRecord = DB::select('password_reset_code', '*', $where, '', 1);

if (count($codeRecord) > 0) {
    back("Your account reset email has been sent to your email address");
}

$code = generateRandomPassword();
$directory = getCurrentDirectory();
$link = $directory.'repasswordEmail.php?code='.$code;

$data = [
    'user_id' => $user['id'],
    'code' => $code,
    'expired_at' => date('Y-m-d H:i:s', time() + 86400)
];

$res = DB::insert('password_reset_code', $data);
if (!$res) {
    back('error:000000');
}


$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                            // 使用 SMTP
    $mail->Host       = 'smtp.qq.com';                          // 设置 SMTP 服务器
    $mail->SMTPAuth   = true;                                   // 启用 SMTP 认证
    $mail->Username   = 'your Email';                // SMTP 用户名 (你的 QQ 邮箱地址)
    $mail->Password   = 'your password';              // SMTP 密码 (你在 QQ 邮箱设置里获取的授权码)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // 启用 TLS 加密
    $mail->Port       = 587;                                    // TCP 端口连接到

    // Recipients
    $mail->setFrom('your Email', 'Your Name');       // 发件人信息
    $mail->addAddress($user['email'], $user['first_name'] . ' ', $user['last_name']); // 收件人信息

    // Content
    $mail->isHTML(true);                                        // 设置邮件格式为 HTML
    $mail->Subject = 'Reset your account';
    $mail->Body    = 'Your password reset link<br><br>'.$link;

    $mail->send();
    echo 'A reset link has been sent to your email';
} catch (Exception $e) {
    echo "Failed to send the email. Procedure Error message:{$mail->ErrorInfo}";
}
