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
    <title>Авторизация</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/online_quiz.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6 form-container">
            <?php if(isset($_GET['token'])){ ?>
            <form class="form-horizontal" method="POST" action="">
                <span class="heading">Восстановление пароля</span>
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" name="token" value="<?=$_GET['token']?>">
                <div class="form-group help">
                    <input type="password" name="passwd" class="form-control" id="inputPassword" placeholder="Пароль">
                    <i class="fa fa-lock"></i>
                </div>
                <div class="form-group help">
                    <input type="password" name="passwd_re" class="form-control" id="inputPasswordRe" placeholder="Повтор пароля">
                    <i class="fa fa-lock"></i>
                </div>
                <div class="form-group send">
                    <button type="submit" class="btn button btn-default">Сохранить</button>
                    <?php if(!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert" style="margin-top: 25px;">
                            <b>Ошибка:</b> <?=$error?>
                        </div>
                    <?php } ?>
                </div>
            </form>
            <?php } else if(isset($_GET['done'])) { ?>
                <form class="form-horizontal" method="POST" action="">
                    <span class="heading">Восстановление пароля</span>
                    <div class="form-group help" style="display: flex;justify-content: center;">
                        <p>На вашу почту отправлено письмо с инструкциями</p>
                    </div>
                    <div class="form-group send">
                        <a href="/login.php" style="text-decoration: none">
                            <button type="button" class="btn button btn-default">Вернуться назад</button>
                        </a>
                    </div>
                </form>
            <?php } else if(isset($_GET['success'])) { ?>
                <form class="form-horizontal" method="POST" action="">
                    <span class="heading">Восстановление пароля</span>
                    <div class="form-group help" style="display: flex;justify-content: center;">
                        <p>Ваш пароль успешно сброшен, теперь вы можете зайти в личный кабинет</p>
                    </div>
                    <div class="form-group send">
                        <a href="/login.php" style="text-decoration: none">
                            <button type="button" class="btn button btn-default">Вернуться назад</button>
                        </a>
                    </div>
                </form>
            <?php } else { ?>
            <form class="form-horizontal" method="POST" action="">
                <span class="heading">Восстановление пароля</span>
                <input type="hidden" name="action" value="recovery"/>
                <div class="form-group">
                    <input type="email" name="mail" class="form-control" id="inputEmail" placeholder="Почта">
                    <i class="fa fa-at"></i>
                </div>
                <div class="form-group help" style="display: flex;justify-content: center;">
                    <div class="g-recaptcha" data-sitekey="<?=$_SETTINGS['captcha_public_option']?>"></div>
                </div>
                <div class="form-group send">
                    <button type="submit" class="btn button btn-default">Сбросить пароль</button>
                    <a class="action-link" href="/login.php">Я помню свой пароль</a>
                    <?php if(!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert" style="margin-top: 25px;">
                        <b>Ошибка:</b> <?=$error?>
                    </div>
                    <?php } ?>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>