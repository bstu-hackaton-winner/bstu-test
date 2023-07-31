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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/js/registration.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-6 form-container">
                <form class="form-horizontal" method="POST" action="">
                    <span class="heading">Регистрация</span>
                    <input type="hidden" name="action" value="registration"/>
                    <div class="form-group">
                        <input type="email" name="mail" class="form-control" id="inputEmail" placeholder="Почта">
                        <i class="fa fa-at"></i>
                    </div>
                    <div class="form-group">
                        <input type="password" name="passwd" class="form-control" id="inputPassword" placeholder="Пароль">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="form-group">
                        <input type="password" name="passwd_re" class="form-control" id="inputPassword" placeholder="Пароль еще раз">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="inputName" placeholder="Фамилия и имя">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="form-group">
                        <select class="form-select form-control" aria-label="" name="account_type">
                            <option value="1">Я студент</option>
                            <option value="2">Я преподаватель</option>
                        </select>
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div class="form-group" style="display: flex;justify-content: center;">
                        <div class="g-recaptcha" data-sitekey="<?=$_SETTINGS['captcha_public_option']?>"></div>
                    </div>
                    <div class="form-group send">
                        <button type="button" onclick="verify_form();" class="btn button btn-default">Создать аккаунт</button>
                        <?php if(!empty($error)) { ?>
                            <div id="error_place">
                                <div class="alert alert-danger" role="alert" style="margin-top: 25px;">
                                    <b>Ошибка:</b> <?=$error?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div id="error_place"></div>
                        <?php } ?>
                        <p><small>Создавая аккаунт вы принимаете <a href="#">пользовательское соглашение</small></p>
                        <a href="/" class="action-link">У меня уже есть аккаунт</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>