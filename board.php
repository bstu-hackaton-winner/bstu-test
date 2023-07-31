<?php
/**
 * @var mysqli $link
 */

require_once $_SERVER["DOCUMENT_ROOT"] . "/panel/init.php";
if(!isset($_POST['csrf_token']) || !isset($_POST['leader_token']) || !isset($_POST['session_id'])){
    header("Location: /panel/offline.php"); die();
}
if($_POST['csrf_token'] != $_SESSION['token']){
    header("Location: /"); die();
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/models/quiz.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/models/session.php";
$session = new Session($link, (int) $_POST['session_id']);
if($session->is_expire()){
    header("Location: /panel/offline.php"); die();
}
$quiz = new Quiz($link, $session->get_quiz_id());
if(!$quiz->is_owner($_SESSION['id'])){
    header("Location: /panel/offline.php"); die();
}

include $_SERVER['DOCUMENT_ROOT'] . "/views/board/board.php";