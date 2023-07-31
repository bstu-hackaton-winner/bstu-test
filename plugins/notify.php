<?php

function render_notify(string $type, string $message, string $title = null): string
{
    switch ($type){
        case "error":
            $type = "alert-danger";
            if(is_null($title)) $title = "Ошибка:";
            break;
        case "success":
            $type = "alert-success";
            if(is_null($title)) $title = "Успех:";
            break;
        default:
            $type = "alert-info";
            if(is_null($title)) $title = "";
            break;
    }
    return '<div class="alert '.$type.' icons-alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="icofont icofont-close-line-circled"></i>
                </button>
                <p><strong>'.$title.'</strong> '.$message.'</p>
            </div>';
}