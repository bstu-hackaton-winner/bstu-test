<?php
/**
 * @var mysqli $link
 * @var User $_USER
 * @var array $_SETTINGS
 */
include("init.php");
require_once($_SERVER['DOCUMENT_ROOT']."/models/log.php");

$CURRENT_FILE = 'users';
$TITLE = "Пользователи";
$notify = "";

if(isset($_POST['action'])){
    if($_POST['token'] != $_SESSION['token']){
        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden", true, 403);
        die();
    }
    switch ($_POST['action']) {
        case 'change_passwd':
            if(strlen($_POST['new_pass']) < 6){
                $notify = '
                <div class="alert background-danger notify">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    
                    <strong>Ошибка!</strong> Слишком кототкий пароль
                </div>';
            }
            else{
                list($status, $result) = User::change_password($link, (int) $_POST['user'], null, $_POST['new_pass'], null, "", true);
                if($status){
                    $notify = '
                    <div class="alert background-success notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>   
                        <strong>Успех!</strong> Пароль успешно изменен</b>
                    </div>';
                }
                else{
                    $notify = '
                    <div class="alert background-danger notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>
                        
                        <strong>Ошибка!</strong> '.$result.'
                    </div>';
                }
            }
            break;
        case "change_profile":
            try {
                $user = new User($link, (int) $_POST['user']);
                $user->set_name($_POST['profile_name']);
                $user->set_phone($_POST['profile_phone']);
                $user->set_account_type((int) $_POST['account_type']);
                $user->set_status($link, $_POST['account_status']);
                if((int) $_POST['user'] == $_USER->get_id() && !isset($_POST['is_admin'])){
                    throw new Exception("Нельзя забрать права администратора у самого себя");
                }
                $subscription = new Subscription($link, (int) $_POST['account_sub_id']);
                if($subscription->get_id() < 0) $subscription = $user->get_sub();
                if(!empty($_POST["sub_end_date"])){
                    if(empty($_POST['sub_end_time']))
                        $_POST['sub_end_time'] = "00:00";
                    $date = DateTime::createFromFormat('Y-m-d H:i',
                        $_POST["sub_end_date"].''.$_POST['sub_end_time']);
                    if($date){
                        $user->set_subscription($subscription   , $date->getTimestamp());
                    }
                }
                $user->set_admin(isset($_POST['is_admin']));
                $user->save($link);
                $notify = '
                    <div class="alert background-success notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>   
                        <strong>Успех!</strong> Профиль успешно изменен</b>
                    </div>';
            } catch (Exception $exception){
                $notify = '
                    <div class="alert background-danger notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>
                        
                        <strong>Ошибка!</strong> '.$exception->getMessage().'
                    </div>';
            }
            break;
        default:
            break;
    }
}

if(isset($_GET['edit'])){
    if(!ctype_digit($_GET['edit']) || (int) $_GET['edit'] < 1){
        header("Location: /apanel/users.php"); die();
    }
    $current_user = new User($link, (int) $_GET['edit']);
    if($current_user->get_id() < 0){
        header("Location: /apanel/users.php"); die();
    }
    $subscriptions = Subscription::get_subscriptions_list($link);
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/header.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/users/user_edit.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/footer.php");
} else {
    $users = User::get_users_list($link);
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/header.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/users/users_list.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/footer.php");
}