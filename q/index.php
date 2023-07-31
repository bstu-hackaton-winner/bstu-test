<?php
/**
 * @var mysqli $link
 * @var string $_TOKEN_OFFLINE_
 */
require_once "init.php";
function _exit(){
    header("Location: /"); die();
}

if(!isset($_SESSION['client_token'])){
    $_SESSION['client_token'] = md5(time().md5($_TOKEN_OFFLINE_));
}

if(!isset($_GET['quiz'])) _exit();

$quiz = null;
$session = new Session($link, (int) $_GET['quiz']);

if($session->get_id() < 0){
    $quiz = new Quiz($link, -1, $_GET['quiz']);
} else {
    $quiz = new Quiz($link, $session->get_quiz_id());
}
if($quiz->get_id() < 0) _exit();

if($quiz->is_online()){
    $_POST['quiz_id'] = $session->get_id();
    include $_SERVER['DOCUMENT_ROOT'] . "/offline.php";
} else {
    $protect = true;
    if($quiz->get_terminal_link() == $_GET['quiz']){
        $protect = false;
    }
    include $_SERVER['DOCUMENT_ROOT'] . "/q/offline.php";
}