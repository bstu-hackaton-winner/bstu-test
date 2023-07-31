<?php
/**
 * @var Quiz $quiz
 * @var string $_TOKEN_OFFLINE_
 * @var mysqli $link
 * @var bool $protect
 */
$json = file_get_contents('php://input');
$data = json_decode($json, true);
if(is_null($data)){
    require_once $_SERVER['DOCUMENT_ROOT'] . "/models/user.php";
    $creator = new User($link, $quiz->get_owner());
    include $_SERVER['DOCUMENT_ROOT'] . "/views/quiz/offline.php";
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/q/init.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/models/answer.php";
    try {
        if ($data['csrf'] != $_SESSION['client_token']) {
            die(http_response_code(403));
        }
        $sign = md5($data['quiz'] . $_TOKEN_OFFLINE_ . json_encode($data['questions'], JSON_UNESCAPED_UNICODE));
        if ($sign != $data['token'] || count($data['answers']) != count($data['questions'])) {
            die(http_response_code(400));
        }

        $security = [];
        if (!isset($_COOKIE['84a564353f03f8c72dc4fff97b0d2eea'])) {
            setcookie('84a564353f03f8c72dc4fff97b0d2eea', base64_encode(
                json_encode([$data['quiz']])
            ));
        }
//        else {
//            $security = json_decode(base64_decode($_COOKIE['84a564353f03f8c72dc4fff97b0d2eea']));
//            if (in_array($data['quiz'], $security)) {
//                die(http_response_code(403));
//            }
//        }

        for ($i = 0; $i < count($data['questions']); $i++) {
            if ($data['questions'][$i]['is_free']) {
                if (strlen(trim($data['answers'][$i])) > 0) {
                    $data['answers'][$i] = substr($data['answers'][$i], 0, 500);
                } else {
                    die(http_response_code(400));
                }
            } else {
                if ($data['answers'][$i] >= count($data['questions'][$i]['answers'])) {
                    die(http_response_code(400));
                }
            }
        }

        if(is_null($data['agent'])) $data['agent'] = "";
        if (Answer::create(
            $link,
            $data['quiz'],
            $data['agent'],
            json_encode($data['questions']),
            json_encode($data['answers']),
            $sign
        )) {
            array_push($security, $data['quiz']);
            setcookie('84a564353f03f8c72dc4fff97b0d2eea', base64_encode(
                json_encode($security)
            ));
            die(http_response_code(200));
        }
        send_log($link, $link->error);
        die(http_response_code(500));
    } catch (Exception $exception){
        send_log($link, $exception->getMessage());
        die(http_response_code(500));
    }
}
