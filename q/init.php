<?php
/**
 * @var mysqli $link
 */
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/core/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/quiz.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/session.php";