<?php
/**
 * @var mysqli $link
 * @var string $_TOKEN_SECRET_
 * @var User $_USER
 */
include("init.php");
if(!isset($_SESSION['id']) || $_POST['token'] != $_SESSION['token']){
    die(http_response_code(403));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/core/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/db.php";

if(!isset($_POST['action']) || !isset($_POST['value'])){
    die(http_response_code(400));
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/quiz.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/session.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/payment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/media.php";
switch($_POST['action']){
    case "remove_session":
        $session = new Session($link, (int) $_POST['value']);
        if((new Quiz($link, $session->get_quiz_id()))->is_owner()){
            if($session->is_expire()){
                if($session->remove($link, (int) $_POST['value'], $_SESSION['id']))
                    die(http_response_code(200));
                die(http_response_code(500));
            }
            die(http_response_code(400));
        }
        die(http_response_code(403));
    case "remove_quiz":
        $quiz = new Quiz($link, $_POST['value']);
        if($quiz->get_id() < 0) die(http_response_code(400));
        if($quiz->is_owner($_SESSION['id']) && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())){
            if($quiz->remove($link, $_POST['value'], $_SESSION['id']))
                die(http_response_code(200));
            die(http_response_code(500));
        }
        die(http_response_code(403));
    case "start_session":
        $quiz = new Quiz($link, $_POST['value']);
        if($quiz->get_id() < 0) die(http_response_code(400));
        if($quiz->is_owner($_SESSION['id']) && !$quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())){
            header("Content-Type: application/json");
            $url = "http://localhost:3000/createSession?token=$_TOKEN_SECRET_&id={$quiz->get_id()}";
            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_request, CURLOPT_HEADER, 0);
            $result = curl_exec($curl_request);
            curl_close($curl_request);
            if($result){
                try{
                    $response = json_decode($result, true);
                    if(isset($response['error'])){
                        http_response_code(400);
                        die($result);
                    }
                    die($result);
                } catch (Exception $e){
                    die(http_response_code(500));
                }
            } else {
                die(http_response_code(500));
            }
        }
        die(http_response_code(403));
    case "stop_session":
        $session = new Session($link, (int) $_POST['value']);
        if($session->get_id() < 0) die(http_response_code(400));
        $quiz = new Quiz($link, $session->get_quiz_id());
        if($quiz->is_owner($_SESSION['id'])){
            header("Content-Type: application/json");
            $url = "http://localhost:3000/stopSession?token=$_TOKEN_SECRET_&id={$session->get_id()}";
            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_request, CURLOPT_HEADER, 0);
            $result = curl_exec($curl_request);
            curl_close($curl_request);
            if($result){
                try{
                    $response = json_decode($result, true);
                    if(isset($response['error'])){
                        http_response_code(400);
                        die($result);
                    }
                    die($result);
                } catch (Exception $e){
                    die(http_response_code(500));
                }
            } else {
                die(http_response_code(500));
            }
        }
        die(http_response_code(403));
    case "accept_payment":
        $payment = new Payment($link, $_POST['value']);
        if($payment->get_id() < 0 || $payment->is_done() || (int) $_POST['payment_id'] == 0){
            die(http_response_code(400));
        }
        if($payment->accept($link, (int) $_POST['payment_id'])){
            $subscription = new Subscription($link, $payment->get_sub_id());
            $sub_end = [-1, strtotime("+1 month"), strtotime("+1 year")][$subscription->get_period()];
            $user = new User($link, $payment->get_owner_id());
            $user->set_subscription($subscription, $sub_end);
            try{
                $user->save($link);
                die(http_response_code(200));
            } catch (Exception $ex){
                die(http_response_code(500));
            }
        }
        die(http_response_code(500));
    case "remove_media":
        $media = new Media($link, $_POST['value']);
        if($media->get_id() < 0){
            die(http_response_code(400));
        }
        if($media->remove($link))
            die(http_response_code(200));
        die(http_response_code(400));
    case "remove_user":
        $user = new User($link, $_POST['value']);
        if($user->get_id() < 0){
            die(http_response_code(400));
        }
        if($user->remove($link))
            die(http_response_code(200));
        die(http_response_code(400));
    default:
        die(http_response_code(405));
}