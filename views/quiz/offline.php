<?php
/**
 * @var Quiz $quiz
 * @var string $_TOKEN_OFFLINE_
 * @var bool $protect
 * @var User $creator
 */
if(is_null($quiz)){
    header("Location: /"); die();
}
$completed = false;
if($protect) {
    $security = [];
    if (isset($_COOKIE['84a564353f03f8c72dc4fff97b0d2eea'])) {
        $security = json_decode(base64_decode($_COOKIE['84a564353f03f8c72dc4fff97b0d2eea']));
    }
    $completed = in_array($quiz->get_id(), $security);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$quiz->get_name()?></title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-reboot.rtl.min.css" />
    <link rel="stylesheet" href="/css/online_quiz.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="csrf_token" content="<?=$_SESSION['client_token']?>"/>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>
<div class="container">
    <div class="row">
        <div class="form-horizontal col-md-offset-3 col-md-7 form-container">
            <span class="heading" id="header_text"><?=$quiz->get_name()?></span>
            <div id="quiz_content">
                <div class="quiz-description">
                    <div class="description">
                        <i class="fas fa-file-alt"></i> Описание: <?=$quiz->get_desc()?>
                    </div>
                    <div class="description">
                        <i class="fa fa-user-tie"></i> Преподаватель: <?=$creator->get_name()?>
                    </div>
                    <div class="description">
                        <i class="fas fa-clock"></i> Создан: <?=date("d.m.Y в H:m", $quiz->get_created_date())?>
                    </div>
                </div>
                <div id="error_place"></div>
                <div class="form-group send">
                    <?php if(!$quiz->is_active()) { ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            Этот опрос недоступен для прохождения
                        </div>
                    </div>
                    <?php } else if($completed) { ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div>
                                Вы уже проходили этот опрос
                            </div>
                        </div>
                    <?php } else { ?>
                    <button id="join_button" type="button" class="btn button btn-default" onclick="startQuiz();">Принять участие</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const quizID = <?=$quiz->get_id()?>;
    const quizToken = `<?=md5($quiz->get_id().$_TOKEN_OFFLINE_.json_encode($quiz->get_questions(), JSON_UNESCAPED_UNICODE))?>`;
    const questions = <?=json_encode($quiz->get_questions())?>;
</script>
<script src="/js/offline_quiz.js"></script>
</body>
</html>