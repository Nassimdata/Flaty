<?php

require_once 'db.php';


$code = $_GET['code'];
if (empty($code)) {
    echo 'code is required';
    die;
}


$codeRecord = DB::select('password_reset_code', '*', "code = '$code'", '', 1);

if (count($codeRecord) < 1) {
    back("code incorrect");
}

$codeRecord = array_shift($codeRecord);

if ($codeRecord['expired_at'] < date("Y-m-d H:i:s")) {
    echo 'Link expired';
    die;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>repasswordEmail</title>
</head>

<body>
    <div style="width: 100%;">
        <div class="form">
            <form action="reset_handler.php" method="post">
                <input type="hidden" value="<?php echo $code;?>" name="code">
                <div style="display: flex;align-items: center;justify-content: flex-start;">
                    <div style="flex: 1;">
                        <img src="img/logo.png" style="width: 80px;height: 80px;" />
                    </div>
                </div>
                <div style="font-size: 23px;font-weight: 700;margin: 15px 0;">Vérifie ton adresse e.mail et saisis un nouveau
                    mot de passe.</div>
                <!-- 容器宽度设置为 auto 或 100% 来适配长内容 -->
                <div style="font-size: 14px;margin: 15px 0;width: auto;">
                    Nous avons envoyé un code à <span id="maskedEmail">votre e-mail</span> Modifier
                </div>

                <div style="margin-top: 20px;">
                    <input class="input" type="text" placeholder="Code à 6 chiffres*" name="new_password"/>
                </div>

                <div style="margin-top: 20px;">
                    <input class="input" type="text" placeholder="Nouveau mot de passe*" name="confirm_password"/>
                </div>

                <div style="display: flex;justify-content: flex-end;margin-top: 100px;">
                    <div><a href="login.html"><button class="annuler">Annuler</button></a></div>
                    <div><button class="enregistrer" type="submit">Enregistrer</button></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 页面加载后从 localStorage 获取邮箱
        document.addEventListener("DOMContentLoaded", function() {
            const maskedEmail = localStorage.getItem("maskedEmail") || "votre e-mail";
            const emailSpan = document.getElementById('maskedEmail');
            if (emailSpan) {
                emailSpan.textContent = maskedEmail;
            } else {
                console.error("Masked email element not found!");
            }
        });
    </script>
</body>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    .form {
        width: 420px;
        margin: 150px auto;
        border: 2px solid #C4C4C4;
        padding: 15px;
    }

    .input {
        width: 400px;
        height: 23px;
        line-height: 23px;
        border: 3px solid #C4C4C4;
    }

    .input::placeholder {
        color: #F0C173;
    }

    .annuler {
        width: 100px;
        height: 35px;
        line-height: 35px;
        background-color: #fff;
        border-radius: 30px;
        border: 1px solid grey;
        margin-right: 10px;
    }

    .enregistrer {
        width: 100px;
        height: 35px;
        line-height: 35px;
        background-color: black;
        border-radius: 30px;
        border: 1px solid grey;
        color: #fff;
    }
</style>

</html>