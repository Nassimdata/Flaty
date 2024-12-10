<?php
session_start();
$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
</head>

<body>
    <div style="width: 100%;">
        
            <div class="form">
                <div style="display: flex;align-items: center;justify-content: flex-start;">
                    <div style="flex: 1;">
                        <img src="img/logo.png" style="width: 80px;height: 80px;" />
                    </div>
                </div>
                <div style="font-size: 23px;font-weight: 700;margin: 15px 0;">Bienvenue à!</div>
                <div style="font-size: 14px;margin: 15px 0;"><?php echo $user['first_name'].' '.$user['last_name'];?>
                </div>

                <div style="margin-top: 20px;">
                    <label>E-mail：</label><?php echo  $user['email'];?>
                </div>

                <div style="margin-top: 20px;">
                <label>téléphone：</label><?php echo $user['telphone'];?>
                </div>

                <div style="margin-top: 20px;">
                <label>Enregistré chez：</label><?php echo $user['created_at'];?>
                </div>
    </div>

    </div>
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
        border: 3px solid #C4C4C4
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