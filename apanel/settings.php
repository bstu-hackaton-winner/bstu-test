<?php
/**
 * @var mysqli $link
 * @var User $_USER
 * @var array $_SETTINGS
 */
include("init.php");
require_once($_SERVER['DOCUMENT_ROOT']."/models/user.php");

$CURRENT_FILE = 'settings';
$TITLE = "Конфигурация сайта";
$notify = "";

// API обработчики
if(isset($_POST['action'])){
    if($_POST['token'] != $_SESSION['token']){
        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden", true, 403);
        die(); 
    }
    switch ($_POST['action']) {
        case 'edit':
            $options = [];
            foreach($_POST['options'] as $option){
                $options[$option] = $_POST[$option];
            }
            Site::set_options($link, $options);
            $notify = '
            <div class="alert background-success notify">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="icofont icofont-close-line-circled text-white"></i>
                </button>   
                <strong>Успех!</strong> Конфигурация успешно изменена</b>
            </div>';
            break;
        default:
            break;
    }
}

$options = Site::get_editable_options($link);

include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/settings.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/footer.php");