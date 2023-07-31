<?php
/**
 * @var object $link
 * @var array $_SETTINGS
 */
session_start();
$error = "";
$redirect = "/panel/";
if(isset($_GET["redirect"])){
    $redirect = $_GET['redirect'];
}

if(isset($_SESSION["admin"])){
    header("Location: ".$redirect);
}

include($_SERVER['DOCUMENT_ROOT'] . "/core/db.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/models/user.php");
// $auth = User::ResetPassword($link, "admin@adm1in.ru");

switch($_POST['action'] ?? ""){
    case "auth":
        if(!empty($_POST['mail']) && !empty($_POST['passwd'])){
            require_once("./models/user.php");
            $auth = User::authentication($link, mysqli_real_escape_string($link, $_POST['mail']), mysqli_real_escape_string($link, $_POST['passwd']), isset($_POST['remember']));
            if($auth[0]){
                $data = $auth[1];
                //['id'], $result['admin'], $result['name'], $result['mail']
                unset($_SESSION['retoken']);
                $_SESSION['id'] = $data['id'];
                $_SESSION['admin'] = $data['admin'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['mail'] = $data['mail'];
                $_SESSION['account_type'] = $data['type'];
                $_SESSION['token'] = md5(time().md5($_POST['passwd']));
                if(isset($_POST['remember']) && !empty($data['token'])){
                    setcookie("bb33f285255ebb9089d20aaa82b56eb4", $data['token']);
                }
                if($redirect == "index.php")
                    if($_SESSION['admin'] == 1)
                        $redirect = "/apanel/";
                header("Location: ".$redirect);
            }
            else{
                switch($auth[1]){
                    case 'wrong':
                        $error = "Логин или пароль неверный, <a href='login.php?recovery'>забыли пароль</a>?";
                        break;
                    case "activation":
                        $_SESSION['retoken'] = $auth[3];
                        header("Location: /signup.php");
                        break;
                    case "blocked":
                        $error = "Ваш аккаунт был деактивирован администратором, обратитесь в поддержку";
                        break;
                    default:
                        $error = "Ошибка авторизации (500)";
                        break;
                }
            }
        }
        break;
    case "recovery":
        if(!empty($_POST['mail']) && !empty($_POST['g-recaptcha-response'])){

            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$_SETTINGS['captcha_private_option']."&response=".$_POST['g-recaptcha-response']);
            $g_response = json_decode($response);
            if ($g_response->success !== true){
                $error = "Проверка на робота не пройдена";
                break;
            }

            require_once($_SERVER["DOCUMENT_ROOT"]. "/models/user.php");
            $auth = User::reset_password($link, mysqli_real_escape_string($link, $_POST['mail']));

            if($auth) {
                header("Location: /login.php?recovery&done");
                die();
            }
            else {
                $error = "Такой аккаунт не найден";
                break;
            }
        } else {
            $error = "Произошла ошибка, попробуйте еще раз";
            break;
        }
    case "change_password":
        if(!empty($_POST['passwd']) && !empty($_POST['token']) && !empty($_POST['passwd_re'])){
            if($_POST['passwd'] != $_POST['passwd_re']){
                $error = "Пароли не совпадают";
                $_GET['recovery'] = true;
                $_GET['token'] = $_POST['token'];
                break;
            }
            $result = User::change_password($link, -1, null, $_POST['passwd'], $_POST['passwd_re'], $token = $_POST['token']);
            if($result){
                header("Location: /login.php?recovery&success");
                die();
            }
        } else {
            $error = "Не все поля заполнены";
            $_GET['recovery'] = true;
            $_GET['token'] = $_POST['token'];
            break;
        }
}

if(isset($_GET['recovery'])){
    include("./views/auth/password_recovery.php");
} else {
    include("./views/auth/login.php");
}