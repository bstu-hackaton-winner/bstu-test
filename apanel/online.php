<?php
/**
 * @var mysqli $link
 * @var User $_USER
 */
require_once("init.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/models/quiz.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/models/session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/plugins/notify.php');

$CURRENT_FILE = 'online_quizzes';
$TITLE = "Онлайн опросы";
$ACTION = "users";

if(isset($_POST['action'])){
    if(!isset($_SESSION['id']) || $_POST['token'] != $_SESSION['token']){
        die(http_response_code(403));
    }
    switch ($_POST['action']){
        case "edit":
            $quiz = new Quiz($link, (int) $_POST['quiz_id']);
            if($quiz->get_id() < 0 || $quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())){
                $notify = render_notify("error", "Ошибка доступа");
                break;
            }
            try {
                $quiz->set_name($_POST['quiz_name']);
                $quiz->set_desc($_POST['quiz_desc']);
                $quiz->set_lite(isset($_POST['lite_mode']));

                $questions = [];
                for($i = 0; $i < count($_POST['questions']); $i++){
                    if($i >= $_USER->get_sub()->get_max_questions() &&
                        $_USER->get_sub()->get_max_questions() > 0){
                        break;
                    }
                    $question = [
                        "question" => htmlspecialchars($_POST['questions'][$i]),
                        "time"     => (int) $_POST['questions_time'][$i] > 0 ? (int) $_POST['questions_time'][$i] : 30,
                        "answers"  => []
                    ];
                    for($k = 0; $k < count($_POST["answers_$i"]); $k++){
                        if($k >= $_USER->get_sub()->get_max_questions() &&
                            $_USER->get_sub()->get_max_questions() > 0){
                            break;
                        }
                        if(strlen($_POST["answers_$i"][$k]) > 0)
                            array_push($question["answers"], htmlspecialchars($_POST["answers_$i"][$k]));
                    }
                    if(!is_null($question['question']) && count($question['answers']) > 0){
                        array_push($questions, $question);
                    }
                }
                $quiz->set_questions($questions);

                $quiz->save($link);
                $notify = render_notify("success", "Опрос успешно сохранен");
            } catch (Exception $e) {
                $notify = render_notify("error", $e->getMessage());
                break;
            }
            break;
        default:
            die(http_response_code(400));
    }
}

if(isset($_GET['edit'])) {
    $quiz_id = (int)$_GET['edit'];
    $quiz = new Quiz($link, $quiz_id);
    if ($quiz->get_id() > 0 && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
        if($quiz->is_offline()){
            header("Location: /apanel/offline.php?edit={$quiz->get_id()}");
            die();
        }
        $ACTION = "edit";
        $TITLE = "Редактирование опроса";
        $current_user = new User($link, $quiz->get_owner());
    } else {
        header("Location: /apanel/offline.php");
        die();
    }
} else if(isset($_GET['stats'])){
    $quiz_id = (int)$_GET['stats'];
    $quiz = new Quiz($link, $quiz_id);
    if ($quiz->get_id() > 0 && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
        if($quiz->is_offline()){
            header("Location: /apanel/offline.php?stats={$quiz->get_id()}");
            die();
        }
        $sessions = Session::get_sessions_list($link, $quiz->get_id());
        $ACTION = "stats";
        $TITLE = "Статистика опроса";
        $current_user = new User($link, $quiz->get_owner());
    } else {
        header("Location: /apanel/offline.php");
        die();
    }
} else if(isset($_GET['session'])){
    $session_id = (int)$_GET['session'];
    $session = new Session($link, $session_id);
    $quiz = new Quiz($link, $session->get_quiz_id());
    if ($session->get_id() > 0 && $quiz->get_id() > 0 && $session->is_expire() &&
        !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
        if($quiz->is_offline()){
            header("Location: /apanel/offline.php?session={$session->get_id()}");
            die();
        }
        $ACTION = "session";
        $TITLE = "Статистика опроса";
        $current_user = new User($link, $quiz->get_owner());
    } else {
        header("Location: /apanel/offline.php");
        die();
    }
} else {
    if(!isset($_GET['user']) || !ctype_digit($_GET['user'])){
        header("Location: /apanel/users.php");
    }
    $current_user = new User($link, $_GET['user']);
    $quizzes = Quiz::get_quizzes_list($link, $_USER->get_sub()->get_max_quizzes(), 0, $_GET['user']);
}

include($_SERVER['DOCUMENT_ROOT']."/views/admin/header.php");
switch($ACTION){
    case "edit":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/quiz/edit/online_quiz_edit.php");
        break;
    case "stats":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/quiz/view/online_quiz_stats.php");
        break;
    case "session":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/quiz/view/online_quiz_session.php");
        break;
    default:
        include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/quiz/view/online_quiz_list.php");
        break;
}
include($_SERVER['DOCUMENT_ROOT']."/views/admin/footer.php");