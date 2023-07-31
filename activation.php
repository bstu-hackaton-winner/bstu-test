<?php
/**
 * @var mysqli $link
 */

session_start(); 
require_once($_SERVER['DOCUMENT_ROOT'] . "/models/user.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/db.php");

if(isset($_SESSION['retoken'])){
    if(isset($_GET['retoken'])){
        $_GET['retoken'] = mysqli_escape_string($link, htmlspecialchars($_GET['retoken']));
        $sql = "SELECT `email`, `activation` FROM `users` WHERE `activation` LIKE '{$_GET['retoken']}%';";
        $result = mysqli_query($link, $sql);
        if($result){
            $result = mysqli_fetch_row($result);
            User::send_mail($result[0], explode("_", $result[1])[1]);
            die("OK");
        } else {
            die("ERROR");
        }
    } else if(!empty($_GET['token'])){
        $_GET['retoken'] = strtoupper(mysqli_escape_string($link, htmlspecialchars($_GET['token'])));
        $sql = "SELECT * FROM `users` WHERE `activation` LIKE '%{$_GET['token']}';";
        $result = mysqli_query($link, $sql);
        if($result){
            $result = mysqli_fetch_array($result);
            // die(var_dump($result));
            if(strtolower(explode("_", $result['activation'])[1]) == strtolower($_GET['token'])){
                if(User::activate_mail($link, (int) $result['id'])){
                    unset($_SESSION['retoken']);
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['admin'] = $result['admin'];
                    $_SESSION['name'] = $result['name'];
                    $_SESSION['mail'] = $result['mail'];
                    $_SESSION['account_type'] = $result['type'];
                    $_SESSION['token'] = md5(bin2hex(random_bytes(32)));
                    header("Location: /panel/");
                } else {
                    header("Location: /index.php?fail=5");
                }
            } else {
                header("Location: /index.php?fail=4");
            }
            die();
        } else {
            header("Location: /index.php?fail=3");
        }
    } else{
        header("Location: /index.php?fail=2");
    }
} else {
    header("Location: /index.php?fail=1");
}
?>
