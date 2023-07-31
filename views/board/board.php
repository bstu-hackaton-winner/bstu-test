<?php
/**
 * @var Quiz $quiz
 * @var Session $session
 * @var string $_TOKEN_SECRET_
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подключение к опросу</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/board.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<div class="main-container" id="main_container">
    <div>
        <h1><?=$quiz->get_name()?></h1>
    </div>
    <div class="link">
        <h2><?=$_SERVER['HTTP_HOST']?>/q/<?=$session->get_id()?></h2>
    </div>
    <div class="code">
        <?php
        $session_id = str_split(strval(sprintf("%06d", $session->get_id())));
        foreach($session_id as $i){
            echo("<span>$i</span>");
        } ?>
    </div>
    <div class="table-container">
        <div class="qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=1024x1024&data=<?=$_SERVER['HTTP_HOST']?>/q/<?=$session->get_id()?>&format=svg" alt="QR">
        </div>
        <div class="actions">
            <div class="users">
                <div class="users-block">
                    <span class="users-count" id="users-count">0</span>
                    <span class="users-label">Участников подключилось</span>
                </div>
            </div>
            <div class="btn-block"><button class="btn btn-success" disabled id="start_btn" onclick="action('start');">Старт</button></div>
        </div>
    </div>
</div>
<script>
    const quizID = <?=$session->get_id()?>;
    const serverURL = '<?=$_SERVER['HTTP_HOST']?>';
    const socketPort = <?=$session->get_socket_port()?>;
    const quizToken = "<?=md5($session->get_id().":".$session->get_socket_port().":".$_TOKEN_SECRET_);?>";
    const leaderToken = "<?=$session->get_token()?>";
    const question_template = `<?php include($_SERVER['DOCUMENT_ROOT'] . "/views/board/board_question.php"); ?>`;
    const final_template = `<?php htmlspecialchars(include($_SERVER['DOCUMENT_ROOT'] . "/views/board/board_final.php")); ?>`;
</script>
<script src="/js/board.js"></script>
</body>
</html>