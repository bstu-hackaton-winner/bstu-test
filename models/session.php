<?php


class Session
{
    protected $id = -1;
    protected $status;
    protected $quiz_id;
    protected $socket_port;
    protected $token;
    protected $expire_on;
    protected $start_time;
    protected $answers;
    protected $questions;
    protected $answers_count;
    protected $participant_count;

    public function __construct(mysqli $link = null, int $id = null){
        if(is_null($link)){ return; }
        $query = $link->prepare("SELECT * FROM `online_sessions` WHERE `id` = ?;");
        $query->bind_param('i', $id);
        if(!$query->execute()){
            send_log($link, $link->error);
            return;
        }
        $session = $query->get_result()->fetch_assoc();
        if(is_null($session)) return;

        $this->id = $session['id'];
        $this->status = $session['status'];
        $this->quiz_id = $session['quiz_id'];
        $this->socket_port = $session['socket'];
        $this->token = $session['token'];
        $this->expire_on = $session['expire_on'];
        $this->start_time = $session['start_time'];
        $this->answers = json_decode($session['answers'], true);
        $this->questions = json_decode($session['questions'], true);
        $this->answers_count = count($this->answers);

        $participant_count = 0;
        foreach($this->answers as $answer)
            if(count($answer) > $participant_count)
                $participant_count = count($answer);

        $this->participant_count = $participant_count;
    }

    public function remove(mysqli $link, int $session_id, int $owner_id):bool {
        $query = $link->prepare("DELETE FROM `online_sessions` WHERE `id` = ?");
        $query->bind_param("i", $session_id);
        if($owner_id > 0){
            $query = $link->prepare("DELETE FROM `online_sessions` WHERE `id` = ? AND `quiz_id` IN (
                SELECT `id` FROM quizzes WHERE `owner_id` = ?
            )");
            $query->bind_param("ii", $session_id, $owner_id);
        }

        $query->execute();
        return $query->affected_rows > 0;
    }

    // Getters
    public function get_id():int { return $this->id; }
    public function get_quiz_id():int { return $this->quiz_id; }
    public function get_socket_port():int { return $this->socket_port; }
    public function get_token():string { return $this->token; }
    public function get_answers():array { return $this->answers; }
    public function get_questions():array { return $this->questions; }
    public function get_answers_count():int { return $this->answers_count; }
    public function get_participant_count():int { return $this->participant_count; }

    public function get_expire_on():string { return $this->expire_on; }
    public function get_start_time():int { return strtotime($this->start_time); }
    public function is_expire():bool { return $this->status === 0 || time() >= $this->expire_on; }

    public static function get_sessions_list($link, int $quiz_id): array{
        $query = $link->prepare("SELECT * FROM `online_sessions` WHERE `quiz_id` = ? ORDER BY `id` DESC LIMIT 100");
        $query->bind_param('i',$quiz_id);
        $query->execute();
        $sessions_list = [];
        $query = $query->get_result();
        while($data = $query->fetch_assoc()){
            $new_session = new self();
            $new_session->quiz_id = $data['quiz_id'];
            $new_session->id = $data['id'];
            $new_session->token = $data['token'];
            $new_session->status = $data['status'];
            $new_session->socket_port = $data['socket'];
            $new_session->start_time = $data['start_time'];
            $new_session->expire_on = $data['expire_on'];
            $new_session->answers = json_decode($data['answers'], true);
            $new_session->questions = json_decode($data['questions'], true);
            $new_session->answers_count = count($new_session->answers);
            $participant_count = 0;
            foreach($new_session->answers as $answer)
                if(count($answer) > $participant_count)
                    $participant_count = count($answer);

            $new_session->participant_count = $participant_count;
            array_push($sessions_list, $new_session);
        }
        return $sessions_list;
    }

}