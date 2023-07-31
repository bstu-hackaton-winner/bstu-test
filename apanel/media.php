<?php
/**
 * @var mysqli $link
 * @var string $_SETTINGS
 */
$CURRENT_FILE = 'media';
$TITLE = "Медиа файлы";
include("init.php");

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/media.php";

if(isset($_POST["action"])){
    if($_POST['token'] != $_SESSION['token']){
        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden", true, 403);
        die();
    }
    switch($_POST['action']){
        case "edit":
            $media = new Media($link, (int) $_POST['media']);
            if($media->get_id() < 0){
                $notify = '
                <div class="alert background-danger notify">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    
                    <strong>Ошибка!</strong> Неверный ID файла
                </div>';
            } else {
                $media->set_name($_POST['media_name']);
                if($media->save($link)){
                    $notify = '
                    <div class="alert background-success notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>   
                        <strong>Успех!</strong> Файл успешно сохранен</b>
                    </div>';
                } else {
                    send_log($link, $link->error);
                    $notify = '
                    <div class="alert background-danger notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>
                        
                        <strong>Ошибка!</strong> '.$link->error.'
                    </div>';
                }
            }
            break;
        case "create_media":
            if(strlen($_POST['media_name']) == 0){
                $notify = '
                    <div class="alert background-danger notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>
                        
                        <strong>Ошибка!</strong> Вы не указали имя файла
                    </div>';
                break;
            }
            try {
                $media = Media::upload($link, $_FILES['media_file'], $_POST['media_name']);
                $notify = '
                    <div class="alert background-success notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>   
                        <strong>Успех!</strong> Файл успешно загружен</b>
                    </div>';
            } catch (Exception $e) {
                $notify = '
                    <div class="alert background-danger notify">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="icofont icofont-close-line-circled text-white"></i>
                        </button>
                        
                        <strong>Ошибка!</strong> '.$e->getMessage().'
                    </div>';
            }
            break;
    }
}

include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/header.php");
if(isset($_GET['edit'])) {
    $media = new Media($link, (int)$_GET['edit']);
    if ($media->get_id() < 0) {
        header("Location: /apanel/media.php");
        die();
    }
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/media/media_edit.php");
} else if(isset($_GET['create'])) {
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/media/media_create.php");
} else {
    $medias = Media::get_media_list($link, "ALL");
    include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/media/media_list.php");
}
include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/footer.php");

