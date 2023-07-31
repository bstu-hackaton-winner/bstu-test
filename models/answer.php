<?php


class Answer
{
    protected $id = -1;
    protected $quiz_id;
    protected $sign;
    protected $user_agent;
    protected $questions;
    protected $answers;
    protected $date;

    public function __construct(mysqli $link = null, int $id = null){
        if(is_null($link) || is_null($id) || $id <= 0) return;
        $query = $link->prepare("SELECT * FROM `offline_answers` WHERE `id` = ?");
        $response = null;
        try{
            $query->bind_param('i', $id);
            if(!$query->execute()){
                return;
            }
            $response = $query->get_result()->fetch_assoc();
        } catch (Exception $err){
            send_log($link, $link->error);
            return;
        }

        $this->id = $response['id'];
        $this->quiz_id = $response['quiz_id'];
        $this->user_agent = $response['user_agent'];
        $this->questions = $response['questions'];
        $this->answers = $response['answers'];
        $this->sign = $response['quiz_sign'];
        $this->date = $response['date'];
    }
    public static function create_static(
        int $id, int $quiz_id, string $user_agent, array $questions,
        array $answers, string $sign, string $date
    ): Answer
    {
        $obj = new Answer();
        $obj->id = $id;
        $obj->quiz_id = $quiz_id;
        $obj->user_agent = $user_agent;
        $obj->questions = $questions;
        $obj->answers = $answers;
        $obj->sign = $sign;
        $obj->date = $date;
        return $obj;
    }

    public static function create(
        mysqli $link, int $quiz_id, string $user_agent,
        string $questions, string $answers, string $sign
    ): bool
    {
        $query = $link->prepare("INSERT INTO `offline_answers` (
            `quiz_id`, `answers`, `questions`, `quiz_sign`, `user_agent`
        ) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("issss",
            $quiz_id,
            $answers,
            $questions,
            $sign,
            $user_agent
        );
        return $query->execute();
    }

    // Getters
    public function get_id():int { return $this->id; }
    public function get_quiz_id():int { return $this->quiz_id; }
    public function get_sign():string { return $this->sign; }
    public function get_questions():array { return $this->questions; }
    public function get_answers():array { return $this->answers; }
    public function get_date():string { return $this->date; }
    public function get_agent():string { return is_null($this->user_agent) ? "" : $this->user_agent; }

    public static function get_sessions_list($link, int $quiz_id): array{
        $query = $link->prepare("SELECT * FROM `offline_answers` WHERE `quiz_id` = ? ORDER BY `id` DESC");
        $query->bind_param('i',$quiz_id);
        $query->execute();
        $sessions_list = [];
        $query = $query->get_result();
        while($data = $query->fetch_assoc()){
            $new_session = new self();
            $new_session->id = $data['id'];
            $new_session->quiz_id = $data['quiz_id'];
            $new_session->user_agent = $data['user_agent'];
            $new_session->answers = json_decode($data['answers'], true);
            $new_session->questions = json_decode($data['questions'], true);
            $new_session->sign = $data['quiz_sign'];
            $new_session->date = $data['date'];
            array_push($sessions_list, $new_session);
        }
        return $sessions_list;
    }
}