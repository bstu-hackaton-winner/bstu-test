<?php
/**
 * @var mysqli $link
 * @var User $_USER
 */
require_once("init.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/models/quiz.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/models/answer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/plugins/notify.php');

$CURRENT_FILE = 'offline_quizzes';
$TITLE = "Офлайн опросы";
$ACTION = "view";

$seo_link_base = "http".(isset($_SERVER['HTTPS']) ? "s" : "").'://'.$_SERVER["HTTP_HOST"]."/q/";

if(isset($_POST['action'])){
    if(!isset($_SESSION['id']) || $_POST['token'] != $_SESSION['token']){
        die(http_response_code(403));
    }
    switch ($_POST['action']){
        case "edit":
            $quiz = new Quiz($link, (int) $_POST['quiz_id']);
            if($quiz->get_id() < 0 || !$quiz->is_owner() && $quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes()) ||
                $quiz->is_online()){
                $notify = render_notify("error", "Ошибка доступа");
                break;
            }
            try {
                $quiz->set_name($_POST['quiz_name']);
                $quiz->set_desc($_POST['quiz_desc']);
                $quiz->set_lite(isset($_POST['lite_mode']));
                $quiz->set_active(isset($_POST['quiz_active']));
                $quiz->set_link($link, $_POST['quiz_link']);

                if(!empty($_POST["quiz_disable_date"])){
                    if(empty($_POST['quiz_disable_time']))
                        $_POST['quiz_disable_time'] = "00:00";
                    $date = DateTime::createFromFormat('Y-m-d H:i',
                        $_POST["quiz_disable_date"].''.$_POST['quiz_disable_time']);
                    if($date){
                        $quiz->set_timeout($date->getTimestamp());
                    } else {
                        $quiz->set_timeout(-1);
                    }
                }

                $allow_edit = count(Answer::get_sessions_list($link, $quiz->get_id())) == 0;
                $questions = [];
                for($i = 0; $i < count($_POST['questions']); $i++){
                    if($i >= $_USER->get_sub()->get_max_questions() &&
                        $_USER->get_sub()->get_max_questions() > 0){
                        break;
                    }
                    $question = [
                        "question" => htmlspecialchars($_POST['questions'][$i]),
                        "time"     => (int) $_POST['questions_time'][$i] > 0 ? (int) $_POST['questions_time'][$i] : 30,
                        "answers"  => [],
                        "is_free"  => false
                    ];
                    foreach($_POST["free_input"] as $free_input_field){
                        if((int) $free_input_field == $i){
                            $question["is_free"] = true;
                        }
                    }
                    if(!$question["is_free"]) {
                        for ($k = 0; $k < count($_POST["answers_$i"]); $k++) {
                            if ($k >= $_USER->get_sub()->get_max_questions() &&
                                $_USER->get_sub()->get_max_questions() > 0) {
                                break;
                            }
                            if (strlen($_POST["answers_$i"][$k]) > 0)
                                array_push($question["answers"], htmlspecialchars($_POST["answers_$i"][$k]));
                        }
                    }
                    if(!is_null($question['question']) && count($question['answers']) > 0 || $question["is_free"]){
                        array_push($questions, $question);
                    }
                }
                if(!$allow_edit){
                    if(count($quiz->get_questions()) != count($questions)){
                        throw new Exception("Изменение опроса запрещено");
                    }
                    for($i = 0; $i < count($quiz->get_questions()); $i++){
                        if(count($quiz->get_questions()[$i]->answers) != count($questions[$i]['answers'])){
                            throw new Exception("Изменение опроса запрещено");
                        }
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
        case "create_quiz":
            $new_quiz = Quiz::create($link, 1);
            header("Location: /panel/offline.php?edit={$new_quiz->get_id()}");
            break;
        default:
            die(http_response_code(400));
    }
}

if(isset($_GET['edit'])) {
    $quiz_id = (int)$_GET['edit'];
    $quiz = new Quiz($link, $quiz_id);
    if ($quiz->get_id() > 0 && $quiz->is_owner() && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
        if($quiz->is_online()){
            header("Location: /panel/offline.php?edit={$quiz->get_id()}");
            die();
        }
        $allow_edit = count(Answer::get_sessions_list($link, $quiz->get_id())) == 0;
        $ACTION = "edit";
        $TITLE = "Редактирование опроса";
    } else {
        header("Location: /panel/offline.php");
        die();
    }
} else if(isset($_GET['stats'])){
    $quiz_id = (int)$_GET['stats'];
    $quiz = new Quiz($link, $quiz_id);
    if ($quiz->get_id() > 0 && $quiz->is_owner() && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
        if($quiz->is_online()){
            header("Location: /panel/offline.php?stats={$quiz->get_id()}");
            die();
        }
        $answers = Answer::get_sessions_list($link, $quiz->get_id());
        $answers_list = [];
        for($i = 0; $i < count($quiz->get_questions()); $i++) {
            array_push($answers_list, []);
            foreach ($answers as $answer) {
                array_push($answers_list[count($answers_list)-1], $answer->get_answers()[$i]);
            }
        }
        $ACTION = "stats";
        $TITLE = "Статистика опроса";
    } else {
        header("Location: /panel/offline.php");
        die();
    }
//} else if(isset($_GET['session'])){
//    $session_id = (int)$_GET['session'];
//    $session = new Session($link, $session_id);
//    $quiz = new Quiz($link, $session->get_quiz_id());
//    if ($session->get_id() > 0 && $quiz->get_id() > 0 && $quiz->is_owner() && $session->is_expire() &&
//        !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())) {
//        if($quiz->is_online()){
//            header("Location: /panel/offline.php?session={$session->get_id()}");
//            die();
//        }
//        $ACTION = "session";
//        $TITLE = "Статистика опроса";
//    } else {
//        header("Location: /panel/offline.php");
//        die();
//    }
} else {
    $quizzes = Quiz::get_quizzes_list($link, $_USER->get_sub()->get_max_quizzes(), 1);
}

include($_SERVER['DOCUMENT_ROOT']."/views/panel/header.php");
switch($ACTION){
    case "edit":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/quiz/edit/offline_quiz_edit.php");
        break;
    case "stats":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/quiz/view/offline_quiz_stats.php");
        break;
    case "session":
        include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/quiz/view/offline_quiz_session.php");
        break;
    default:
        include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/quiz/view/offline_quiz_list.php");
        break;
}
include($_SERVER['DOCUMENT_ROOT']."/views/panel/footer.php");