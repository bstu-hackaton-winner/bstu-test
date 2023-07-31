<?php
/**
 * @var mysqli $link
 * @var User $_USER
 * @var array $_SETTINGS
 */
include("init.php");
require_once($_SERVER['DOCUMENT_ROOT']."/models/log.php");

$CURRENT_FILE = 'logs';
$TITLE = "Логи";
$notify = "";

$logs = Log::get_logs($link);

include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/logs.php");
include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/footer.php");