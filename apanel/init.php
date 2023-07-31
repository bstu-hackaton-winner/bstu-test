<?php
/**
 * @var mysqli $link
 */
session_start();
if(!isset($_SESSION["id"])){
    header("Location: /index.php?redirect=/panel/");
    die();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/user.php";

$_USER = new User($link, $_SESSION['id']);
if($_USER->get_id() < 0){ header("Location: /logout.php"); die(); }
if(!$_USER->is_admin()){ header("Location: /panel/"); die(); }