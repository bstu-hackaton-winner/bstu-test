<?php


class Subscription
{
    protected $id = -1;
    protected $name;
    protected $description;
    protected $special_flag;
    protected $image;
    protected $max_quizzes;
    protected $max_questions;
    protected $max_answers;
    protected $max_clients;
    protected $special_features;
    protected $price;
    protected $period;
    protected $purchases = 0;

    public function __construct(mysqli $link = null, int $id = null, bool $extend = false) {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/models/media.php";
        if(is_null($link)){ return; }
        if($extend){
            $query = $link->prepare("SELECT *, (SELECT COUNT(*) FROM `payments` WHERE `payment_id` > 0 && `sub_id` = `subscriptions`.`id`) as `purchases` FROM `subscriptions` WHERE `id` = ?;");
        } else {
            $query = $link->prepare("SELECT * FROM `subscriptions` WHERE `id` = ?;");
        }
        $query->bind_param('i', $id);
        if(!$query->execute()){
            send_log($link, $link->error);
            return;
        }
        $subscription = $query->get_result()->fetch_assoc();
        if(is_null($subscription)) return;

        $this->id = $subscription['id'];
        $this->name = $subscription['name'];
        $this->description = $subscription['description'];
        $this->image = new Media($link, $subscription['image']);
        $this->special_flag = $subscription['special_flag'];
        $this->max_quizzes = $subscription['max_quizzes'];
        $this->max_questions = $subscription['max_questions'];
        $this->max_answers = $subscription['max_answers'];
        $this->max_clients = $subscription['max_clients'];
        $this->special_features = $subscription['special_features'];
        $this->price = $subscription['price'];
        $this->period = $subscription['period'];
        if($extend) $this->purchases = $subscription['purchases'];
    }

    // Getters
    public function get_id():int {
        return $this->id;
    }
    public function get_name():string {
        return $this->name;
    }
    public function get_desc():string {
        return is_null($this->description) ? "" : $this->description;
    }
    public function get_flag():string {
        return is_null($this->special_flag) ? "" : $this->special_flag;
    }
    public function get_image():Media {
        return $this->image;
    }
    public function get_max_quizzes():int {
        return $this->max_quizzes;
    }
    public function get_max_questions():int {
        return $this->max_questions;
    }
    public function get_max_answers():int {
        return $this->max_answers;
    }
    public function get_max_clients():int {
        return $this->max_clients;
    }
    public function get_special_features():bool {
        return $this->special_features;
    }
    public function get_price():int {
        return $this->price;
    }
    public function get_period():int {
        return $this->period;
    }
    public function get_purchases():int {
        return $this->purchases;
    }
    public function get_str_period():string {
        return ["Безлимит", "1 месяц", "1 год"][$this->period];
    }

    // Setters
    public function set_name(string $value) {
        $this->name = $value;
    }
    public function set_desc(string $value) {
        $this->description = $value;
    }
    public function set_flag(string $value) {
        $this->special_flag = $value;
    }
    public function set_image(Media $value) {
        $this->image = $value;
    }
    public function set_max_quizzes(int $value) {
        $this->max_quizzes = $value;
    }
    public function set_max_questions(int $value) {
        $this->max_questions = $value;
    }
    public function set_max_answers(int $value) {
        $this->max_answers = $value;
    }
    public function set_max_clients(int $value) {
        $this->max_clients = $value;
    }
    public function set_special_features(bool $value) {
        $this->special_features = $value;
    }
    public function set_price(int $value) {
        $this->price = $value;
    }
    public function set_period(int $value):bool {
        if($value < 0 || $value > 2)
            return false;
        $this->period = $value;
        return true;
    }

    public function save(mysqli $link):bool {
        $query = $link->prepare("UPDATE `subscriptions` SET 
            `name`=?, `description`=?, `special_flag`=?, `image` = ?,
            `max_quizzes`=?, `max_questions`=?, `max_answers`=?,
            `max_clients`=?, `price`=?, period=?, `special_features` = ?
        WHERE `id` = ?");
        $special = $this->special_features ? 1:0;
        $image_id = $this->image->get_id();
        $query->bind_param("sssiiiiiiiii",
            $this->name,
            $this->description,
            $this->special_flag,
            $image_id,
            $this->max_quizzes,
            $this->max_questions,
            $this->max_answers,
            $this->max_clients,
            $this->price,
            $this->period,
            $special,
            $this->id
        );
        return $query->execute();
    }
    public static function get_subscriptions_list(mysqli $link, bool $extend = false):array {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/models/media.php";
        if($extend){
            $query = $link->prepare("SELECT *, (SELECT COUNT(*) FROM `payments` WHERE `payment_id` > 0 && `sub_id` = `subscriptions`.`id`) as `purchases` FROM `subscriptions`;");
        } else {
            $query = $link->prepare("SELECT * FROM `subscriptions`;");
        }
        if (!$query->execute()) {
            send_log($link, $link->error);
            return [];
        }
        $result = $query->get_result();

        $subscriptions_list = [];
        while($subscription = $result->fetch_assoc()){
            $object = new self();

            $object->name = $subscription['name'];
            $object->description = $subscription['description'];
            $object->special_flag = $subscription['special_flag'];
            $object->id = $subscription['id'];
            $object->image = new Media($link, $subscription['image']);
            $object->max_quizzes = $subscription['max_quizzes'];
            $object->max_questions = $subscription['max_questions'];
            $object->max_answers = $subscription['max_answers'];
            $object->max_clients = $subscription['max_clients'];
            $object->special_features = $subscription['special_features'];
            $object->price = $subscription['price'];
            $object->period = $subscription['period'];

            if($extend){
                $object->purchases = $subscription['purchases'];
            }

            array_push($subscriptions_list, $object);
        }
        return $subscriptions_list;
    }
}