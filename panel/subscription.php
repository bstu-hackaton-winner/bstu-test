<?php
/**
 * @var mysqli $link
 * @var string $_SETTINGS
 */
$CURRENT_FILE = 'subscriptions';
$TITLE = "Подписка";
include("init.php");

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/subscription.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/payment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/media.php";
$subscriptions = Subscription::get_subscriptions_list($link);

function create_payment($link, $sub_id, $_SETTINGS){
    $subscription = new Subscription($link, $sub_id);
    if(empty($_SESSION['id']) || $subscription->get_id() < 0){
        return false;
    }

    $payment = Payment::create($link, $_SESSION['id'], $subscription->get_id(), $subscription->get_price());
    if($payment){
        return $payment->get_link($_SETTINGS, $subscription);
    }
    return false;
}

if(isset($_POST["action"])){
    if($_POST['token'] != $_SESSION['token']){
        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden", true, 403);
        die();
    }
    if($_POST['action'] == "payment"){
        header("Content-Type: application/json");
        if((int) $_POST['sub_id'] > 0){
            $link = create_payment($link, (int) $_POST['sub_id'], $_SETTINGS);
            if($link) die(json_encode(["result" => "ok", "link" => $link]));
            die(json_encode(["result" => "fail"]));
        }
    }
}

include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/subscription.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/footer.php");