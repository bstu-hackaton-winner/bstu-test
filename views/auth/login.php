<?php
/**
 * @var string $error
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация | БГТУ им. Шухова</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/online_quiz.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-6 form-container">
                <form class="form-horizontal" method="POST" action="">
                    <img class="mt-5 mb-3 m-auto" style="max-width: 90%" alt="БГТУ им. Шухова" src="/img/logo.png">
                    <h3 class="mb-5">Система тестирования</h3>
                    <input type="hidden" name="action" value="auth"/>
                    <div class="form-group">
                        <input type="email" name="mail" class="form-control" id="inputEmail" placeholder="Почта">
                        <i class="fa fa-at"></i>
                    </div>
                    <div class="form-group help">
                        <input type="password" name="passwd" class="form-control" id="inputPassword" placeholder="Пароль">
                        <i class="fa fa-lock"></i>
                    </div>
<!--                    <div class="form-group remember-me">-->
<!--                        <div class="form-check form-switch">-->
<!--                            <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>-->
<!--                            <label class="form-check-label" for="remember">Запомнить меня на этом компьютере</label>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="form-group send">
                        <button type="submit" class="btn button btn-default">Войти</button>
                        <?php if(!empty($error)) { ?>
                            <div class="alert alert-danger" role="alert" style="margin-top: 25px;">
                                <b>Ошибка:</b> <?=$error?>
                            </div>
                        <?php } ?>
                        <a href="/signup.php" class="action-link">Регистрация на сайте</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>