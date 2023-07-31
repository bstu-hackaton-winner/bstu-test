<?php
/**
 * @var mysqli $link
 * @var array $_SETTINGS
 */

session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/core/db.php");

if(isset($_SESSION['retoken'])){
    $reactivationToken = $_GET['token'];
    include($_SERVER['DOCUMENT_ROOT'] . "/views/auth/signup_success.php");
}
else{
    $error = false;

    if(isset($_SESSION["id"])){
        header("Location: /");
    }

    if(!empty($_POST['mail']) && !empty($_POST['passwd']) && !empty($_POST['name'])){
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$_SETTINGS['captcha_private_option']."&response=".$_POST['g-recaptcha-response']);
        $g_response = json_decode($response);
        if ($g_response->success !== true){
            $error = "Проверка на робота не пройдена";
        }
        else if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $error = "Некорректная почта";
        }
        else if(count(explode(" ", $_POST['name'])) != 2) {
            $error = "Введите ваше имя и фамилию";
        }
        else if((int) $_POST['account_type'] > 4 || (int) $_POST['account_type'] < 1 || strlen($_POST['passwd']) < 6 || $_POST['passwd'] != $_POST['passwd_re']){
            $error = "Форма заполнена некорректно";
        }
        else{
            require_once($_SERVER['DOCUMENT_ROOT'] . "/models/user.php");
            $reg = User::create($link, $_POST['mail'], $_POST['passwd'], $_POST['name'], (int) $_POST['account_type']);
            if($reg[0]){
                $_SESSION['retoken'] = $reg[1];
                header("Location: /signup.php");
            } else{
                switch($reg[1]){
                    case "mail":
                        $error = "Эта почта уже занята";
                        break;
                    default:
                        $error = $reg[1];
                        break;
                }
            }
        }
    }

    include($_SERVER['DOCUMENT_ROOT'] . "/views/auth/signup.php");
}