<?php
/**
 * @var mysqli $link
 * @var User $_USER
 */
include("init.php");
require_once($_SERVER['DOCUMENT_ROOT']."/models/user.php");

$CURRENT_FILE = 'settings';
$TITLE = "Настройки";
$notify = "";

// API обработчики
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
            else if($_POST['new_pass'] != $_POST['new_pass_repeat']){
                $notify = '
                <div class="alert background-danger notify">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    
                    <strong>Ошибка!</strong> Пароли не совпадают
                </div>';
            }
            else{
                list($status, $result) = User::change_password($link, $_SESSION['id'], $_POST['old_pass'], $_POST['new_pass'], $_POST['new_pass_repeat']);
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
                $_USER->set_name($_POST['profile_name']);
                $_USER->set_phone($_POST['profile_phone']);
                $_USER->set_account_type((int) $_POST['account_type']);
                $_USER->save($link);
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

include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/account_settings.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/footer.php");