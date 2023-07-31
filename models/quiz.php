<?php
class Quiz{
    protected $id;
    protected $type;
    protected $active;
    protected $timeout = -1;
    protected $link;
    protected $terminal_link;
    private $owner_id;
    protected $name;
    protected $desc;
    protected $lite;
    protected $questions;
    protected $sessions_count;
    protected $users_count;
    protected $created;
    protected $last_session_id;
    protected $last_session = null;
    protected $locked = null;

    // Constructors
    public function __construct($link=null, int $id=null, string $seo_link = null){
        if(!is_null($link) && ((int) $id > 0 || (!is_null($seo_link) && strlen($seo_link) > 0))){
            $response = null;
            if(strlen($seo_link) > 0) {
                $sql = "SELECT *, (
                        SELECT COUNT(*) FROM `online_sessions` WHERE `online_sessions`.`quiz_id` = quizzes.`id`
                    ) as `sessions_count`, (
                        SELECT COUNT(*) FROM `offline_answers` WHERE `offline_answers`.`quiz_id` = quizzes.`id`
                    ) as `users_count`, (
                        SELECT `id` FROM `online_sessions` WHERE `quiz_id` = quizzes.`id` ORDER BY `id` DESC LIMIT 1
                    ) as `last_session` FROM quizzes WHERE `seo_link` = ? OR `terminal_link` = ?";
                $response = $link->prepare($sql);
                if (!$response->bind_param("ss", $seo_link, $seo_link)) {
                    $this->id = -1;
                    return;
                }
            } else {
                $sql = "SELECT *, (
                        SELECT COUNT(*) FROM `online_sessions` WHERE `online_sessions`.`quiz_id` = quizzes.`id`
                    ) as `sessions_count`, (
                        SELECT COUNT(*) FROM `offline_answers` WHERE `offline_answers`.`quiz_id` = quizzes.`id`
                    ) as `users_count`, (
                        SELECT `id` FROM `online_sessions` WHERE `quiz_id` = quizzes.`id` ORDER BY `id` DESC LIMIT 1
                    ) as `last_session` FROM quizzes WHERE `id` = ?";
                $response = $link->prepare($sql);
                if (!$response->bind_param("i", $id)) {
                    $this->id = -1;
                    return;
                }
            }
            $response->execute();
            $result = $response->get_result()->fetch_assoc();
            if(is_null($result)){
                $this->id = -1;
                return;
            } else {
                $this->id = $result['id'];
                $this->type = $result['type'];
                $this->active = $result['active'] == 1;
                $this->timeout = $result['deactivation_date'];
                $this->link = $result['seo_link'];
                $this->terminal_link = $result['terminal_link'];
                $this->lite = $result['lite_mode'];
                $this->owner_id = $result['owner_id'];
                $this->name = $result['name'];
                $this->desc = $result['description'];
                $this->questions = json_decode($result['questions']);
                $this->sessions_count = $result['sessions_count'];
                $this->users_count = $result['users_count'];
                $this->created = $result['created'];
                $this->last_session_id = $result['last_session'];
            }
        }
    }
    public static function create_static(
        $id, $type, $active, $terminal_link, $timeout, $owner_id, $name,
        $desc, $questions, $lite_mode, $sessions_count, $users_count,
        $created, $last_session_id, $locked = null, $link = null
    ): Quiz{
        // Alternative constructor with static information
        $obj = new self();
        $obj->id = $id;
        $obj->type = $type;
        $obj->active = $active;
        $obj->timeout = $timeout;
        $obj->owner_id = $owner_id;
        $obj->name = $name;
        $obj->desc = $desc;
        $obj->questions = json_decode($questions);
        $obj->lite = $lite_mode;
        $obj->sessions_count = $sessions_count;
        $obj->users_count = $users_count;
        $obj->created = $created;
        $obj->last_session_id = $last_session_id;
        $obj->locked = $locked;
        $obj->link = $link;
        $obj->terminal_link = $terminal_link;
        return $obj;
    }
    public static function create(mysqli $link, int $type):Quiz {
        while(true){
            $terminal_link = self::gen_code(5);
            $query = $link->prepare("SELECT `id` FROM `quizzes` WHERE `terminal_link` = ?");
            $query->bind_param("s", $terminal_link);
            $query->execute();
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) break;
        }
        while(true){
            $seo_link = self::gen_code(5);
            $query = $link->prepare("SELECT `id` FROM `quizzes` WHERE `seo_link` = ?");
            $query->bind_param("s", $seo_link);
            $query->execute();
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) break;
        }
        $query = $link->prepare("INSERT INTO quizzes(`owner_id`, `name`, `questions`, `type`, `terminal_link`,`seo_link`) VALUES (?,'Новый опрос','[]',?,?,?)");
        $query->bind_param("iiss", $_SESSION['id'], $type, $terminal_link, $seo_link);
        if($query->execute()){
            return new Quiz($link, $query->insert_id);
        } else { return new self(); }
    }

    // Getters
    public function get_id():int { return $this->id; }
    public function get_owner():int { return $this->owner_id; }
    public function is_online():bool { return $this->type == 0; }
    public function is_offline():bool { return $this->type != 0; }
    public function get_name():string { return $this->name; }
    public function get_desc():string { return $this->desc; }
    public function get_seo_link():?string { return $this->link; }
    public function get_terminal_link():string { return $this->terminal_link; }
    public function get_questions():array { return $this->questions; }
    public function get_sessions_count():int { return $this->sessions_count; }
    public function get_users_count():int { return $this->users_count; }
    public function get_created_date():int { return strtotime($this->created); }
    public function get_last_session($link):?Session {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/models/session.php";
        if(!is_null($this->last_session)) return $this->last_session;
        if(!is_null($this->last_session_id)){
            $session = new Session($link, $this->last_session_id);
            if($session->get_id() > 0){
                $this->last_session = $session;
                return $session;
            }
        }
        return null;
    }
    public function get_deactivation_date():string {
        return $this->timeout == -1 ? "" : date("Y-m-d", $this->timeout);
    }
    public function get_deactivation_time():string {
        return $this->timeout == -1 ? "" : date("H:i", $this->timeout);
    }
    public function is_owner(int $user_id = null): bool {
        if(!isset($user_id)) $user_id = $_SESSION['id'];
        return $user_id == $this->owner_id;
    }
    public function is_lite():bool { return $this->lite; }
    public function is_active():bool { return ($this->timeout == -1 || time() < $this->timeout) && $this->active; }
    public function is_locked(mysqli $link, int $limit, int $user_id = -1):bool {
        if(!is_null($this->locked)) return $this->locked;
        if($limit < 0) return false;
        if($user_id < 0) $user_id = $_SESSION['id'];
        $query = $link->prepare("SELECT `id` FROM quizzes WHERE `owner_id` = ? LIMIT ?;");
        $query->bind_param("ii", $user_id, $limit);
        if($query->execute()){
            foreach($query->get_result()->fetch_all() as $allow_id){
                if($this->id == $allow_id[0]) return false;
            }
        } else {
            send_log($link, $link->error);
            return true;
        }
        return true;
    }

    // Setters
    /**
     * @throws Exception
     */
    public function set_questions(array $questions){
        foreach($questions as $question){
            if(empty($question['question']) || strlen($question['question']) > 1000){
                throw new Exception("Вопрос слишком длинный");
            }
            if((int) $question['time'] < 0 || (int) $question['time'] > 600){
                throw new Exception("Время вопроса указано некорректно");
            }
            if(count($question['answers']) < 2 && !($question["is_free"] && $this->is_offline())){
                throw new Exception("Вариантов ответа не может быть меньше двух");
            }
            if(count($question['answers']) > 8){
                throw new Exception("Вариантов ответа не может быть больше восьми");
            }
            foreach($question['answers'] as $answer){
                if(empty($answer) || strlen($answer) > 100){
                    throw new Exception("Время вопроса указано некорректно");
                }
            }
        }
        $this->questions = $questions;
    }
    /**
     * @throws Exception
     */
    public function set_name(string $name){
        if(strlen($name) > 50){
            throw new Exception("Имя опроса слишком длинное");
        } else {
            $this->name = $name;
        }
    }
    /**
     * @throws Exception
     */
    public function set_desc(string $desc){
        if(strlen($desc) > 500){
            throw new Exception("Имя опроса слишком длинное");
        } else {
            $this->desc = $desc;
        }
    }
    /**
     * @throws Exception
     */
    public function set_link(mysqli $link, string $seo_link){
        if($seo_link == $this->link) return;
        if(empty($seo_link)){
            $this->link = null;
            return;
        }
        if(ctype_digit($seo_link)){
            throw new Exception("SEO ссылка не может состоять только из цифр");
        } else if(strlen($seo_link) > 15){
            throw new Exception("Слишком длинная SEO ссылка");
        } else {
            $query = $link->prepare("SELECT `id` FROM `quizzes` WHERE `seo_link` = ?");
            $query->bind_param("s", $seo_link);
            $query->execute();
            if(count($query->get_result()->fetch_all())){
                throw new Exception("SEO ссылка уже занята");
            } else {
                $this->link = $seo_link;
            }
        }
    }
    public function set_lite(bool $is_lite) { $this->lite = $is_lite; }
    public function set_active(bool $is_active) { $this->active = $is_active; }
    public function set_timeout(int $timestamp) { $this->timeout = -1; if($timestamp > 0) $this->timeout = $timestamp; }

    // Actions
    public function save($link){
        $query = $link->prepare("UPDATE quizzes SET `name`=?, `description`=?, `questions`=?, 
                   `lite_mode`=?, `active`= ?, `seo_link` = ?, `deactivation_date` = ? WHERE id=?");
        $query->bind_param('sssiisii',
            htmlspecialchars($this->name), 
            htmlspecialchars($this->desc), 
            json_encode($this->questions),
            $this->lite,
            $this->active,
            $this->link,
            $this->timeout,
            $this->id
        );
        return $query->execute();
    }
    public function remove(mysqli $link, int $quiz_id, int $owner_id): bool{
        $query = $link->prepare("DELETE FROM quizzes WHERE `id` = ?");
        $query->bind_param("i", $quiz_id);
        if($owner_id > 0){
            $query = $link->prepare("DELETE FROM quizzes WHERE `id` = ? AND `owner_id` = ?");
            $query->bind_param("ii", $quiz_id, $owner_id);
        }

        $query->execute();
        return $query->affected_rows > 0;
    }

    // Other
    public static function get_quizzes_list(mysqli $link, int $limit, int $type, int $user_id=null):array {
        if(is_null($user_id)) $user_id = $_SESSION['id'];

        $allowed_list = null;
        if($limit > -1) {
            $allowed_list = $link->prepare("SELECT `id` FROM quizzes WHERE `owner_id` = ? LIMIT ?;");
            if(!$allowed_list){
                die($link->error);
            }
            $allowed_list->bind_param("ii", $user_id, $limit);
            $allowed_list->execute();
            $allowed_list = $allowed_list->get_result()->fetch_all();
        }

        $query = $link->prepare("SELECT *, (
                SELECT COUNT(*) FROM `online_sessions` WHERE `online_sessions`.`quiz_id` = quizzes.`id`
            ) as `sessions_count`, (
                SELECT COUNT(*) FROM `offline_answers` WHERE `offline_answers`.`quiz_id` = quizzes.`id`
            ) as `users_count`, (
                SELECT `id` FROM `online_sessions` WHERE `quiz_id` = quizzes.`id` ORDER BY `id` DESC LIMIT 1
            ) as `last_session` FROM quizzes WHERE `type` = ? AND `owner_id` = ?");
        $query->bind_param('ii', $type, $user_id);

        $query->execute();
        $quiz_list = [];
        $query = $query->get_result();
        while($data = $query->fetch_assoc()){
            $locked = true;
            if($limit < 0){
                $locked = false;
            } else{
                foreach($allowed_list as $allowed_id){
                    if($allowed_id[0] == $data['id']){
                        $locked = false;
                        break;
                    }
                }
            }
            array_push($quiz_list, Quiz::create_static(
                $data['id'],
                $data['type'],
                $data['active'] == 1,
                $data['terminal_link'],
                $data['deactivation_date'],
                $data['owner_id'],
                $data['name'],
                $data['description'],
                $data['questions'],
                $data['lite_mode'],
                $data['sessions_count'],
                $data['users_count'],
                $data['created'],
                $data['last_session'],
                $locked,
                $data['seo_link']
            ));
        }
        return $quiz_list;
    }
    public static function get_user_quizzes_count($link, int $user_id=null):int {
        if(is_null($user_id)) $user_id = $_SESSION['id'];
        $query = $link->prepare("SELECT COUNT(`id`) as `quiz_count` FROM quizzes WHERE `owner_id` = ?;");
        $query->bind_param("i", $user_id);
        if($query->execute()){
            return $query->get_result()->fetch_assoc()["quiz_count"];
        }
        return 0;
    }
    static function gen_code($size): string {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $size; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
