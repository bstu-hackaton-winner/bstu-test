<?php
/**
 * @var string $error
 * @var array $_SETTINGS
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/online_quiz.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="/js/registration.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6 form-container">
            <form class="form-horizontal" method="POST" action="">
                <span class="heading">Регистрация</span>
                <div class="form-group help" style="display: flex;justify-content: center;flex-direction: column">
                    <p>Для завершения регистрации нужно подтвердить почту</p>
                    <p>На вашу почту отправлено письмо с инструкциями</p>
                </div>
                <div id="error_place"></div>
                <div class="form-group send">
                    <button id="send_email" type="button" class="btn button btn-default" onclick="send_activation(`<?=$_SESSION['retoken']?>`);">Письмо не пришло</button>
                </div>
                <a href="/logout.php" class="action-link">Сменить аккаунт</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>