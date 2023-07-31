<?php

class User{
    protected $id = -1;
    protected $status;
    protected $mail_activated;
    protected $mail;
    protected $name;
    protected $phone;
    protected $account_type;
    protected $admin = 0;

    protected $account_types = [
        "Студент", "Преподаватель"
    ];

    public function __construct(mysqli $link = null, int $id = null){
        if(is_null($link)) return;
        require_once $_SERVER['DOCUMENT_ROOT'] . "/models/subscription.php";
        $query = $link->prepare("SELECT * FROM `users` WHERE `id` = ?;");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        $this->id = (int) $result['id'];
        $this->status = (int) $result['status'];
        $this->mail_activated = empty($result['activation']);
        $this->name = $result['name'];
        $this->phone = $result['phone'];
        $this->account_type = $result['account_type'];
        $this->admin = $result['admin'];
        $this->subscription = new Subscription($link, $result['subscription_type']);
        if(time() > $result['subscription_end'] && $this->subscription->get_price() < 1)
            $this->subscription = new Subscription($link, 0);
        $this->subscription_end = $result['subscription_end'];
        $this->mail = $result['email'];
    }

    // Getters
    public function get_id():int { return $this->id; }
    public function get_mail():string { return $this->mail; }
    public function get_name():string { return $this->name; }
    public function get_phone():string { return is_null($this->phone) ? "" : $this->phone; }
    public function get_account_type():int { return $this->account_type; }
    public function get_account_type_str():string { return $this->account_types[$this->account_type]; }
    public function get_sub():Subscription { return $this->subscription; }
    public function get_sub_end():int { return $this->subscription_end; }
    public function is_admin():bool { return ($this->admin == 1); }
    /**
     * Return description
     * 0 - Blocked
     * 1 - Active
     * 2 - Mail Activation
     */
    public function get_account_status():int {
        if($this->status == 0){
            if(!$this->mail_activated){
                return 2;
            }
            return 1;
        }
        return 0;
    }

    public static function get_users_list(mysqli $link, int $limit = 0, int $offset = 0):array {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/models/subscription.php";
        if($limit > 0){
            $query = $link->prepare("SELECT * FROM `users` LIMIT ? OFFSET ?;");
            $query->bind_param("ii", $limit, $offset);
        } else {
            $query = $link->prepare("SELECT * FROM `users`;");
        }
        if(!$query->execute() || !$result = $query->get_result()){
            send_log($link, $link->error);
            return [];
        }

        $users = [];
        while($user = $result->fetch_assoc()){
            $obj = new self();
            $obj->id = (int) $user['id'];
            $obj->status = $user['status'];
            $obj->mail_activated = empty($user['activation']);
            $obj->name = $user['name'];
            $obj->phone = $user['phone'];
            $obj->account_type = $user['account_type'];
            $obj->admin = $user['admin'];
            $obj->subscription = new Subscription($link, $user['subscription_type']);
            if(time() > $user['subscription_end'] && $obj->subscription->get_price() < 1)
                $obj->subscription = new Subscription($link, 0);
            $obj->subscription_end = $user['subscription_end'];
            $obj->mail = $user['email'];
            array_push($users, $obj);
        }
        return $users;
    }

    // Setters
    /**
     * @throws Exception
     */
    public function set_name(string $name) {
        if(count(explode(" ", $name)) < 2 || count(explode(" ", $name)) > 5){
            throw new Exception("Необходимо указать имя и фамилию");
        }
        $this->name = $name;
    }
    /**
     * @throws Exception
     */
    public function set_phone(string $phone) {
        if(empty($phone)){
            $this->phone = $phone; return;
        }
        preg_match("/^\+?\d+$/", $phone, $m);
        if(count($m) == 1 && $m[0] == $phone){
            $this->phone = $phone;
        } else {
            throw new Exception("Необходимо указать корректный номер телефона");
        }
    }
    /**
     * @throws Exception
     */
    public function set_account_type(int $type) {
        if($type < 1 || $type > 4){
            throw new Exception("Необходимо указать корректный тип аккаунта");
        }
        $this->account_type = $type;
    }
    /**
     * @throws Exception
     */
    public function set_status(mysqli $link, int $status):bool {
        if($status == 2){
            $this->status = 0;
            $activation_code = User::gen_code(10);
            $retoken = md5(htmlspecialchars(mysqli_real_escape_string($link, $this->mail)));
            $activation = $retoken."_".$activation_code;
            $query = $link->prepare("UPDATE `users` SET `activation` = ? WHERE `id` = ?");
            $query->bind_param("si", $activation, $this->id);
            return $query->execute();
        } else {
            if($status > 2 || $status < 0){
                throw new Exception("Некорректный статус аккаунта");
            }
            if($status == 1){
                $this->status = 0;
            } else {
                $this->status = 1;
            }
            if(!$this->mail_activated){
                $query = $link->prepare("UPDATE `users` SET `activation` = '' WHERE `id` = ?");
                $query->bind_param("i", $this->id);
                return $query->execute();
            }
            return true;
        }
    }
    public function set_admin(bool $admin){
        $this->admin = $admin ? 1 : 0;
    }
    public function set_subscription(Subscription $sub, int $sub_end){
        $this->subscription = $sub;
        $this->subscription_end = $sub_end;
    }

    // Actions
    public static function authentication($link, $mail, $pass, $remember, $by_token = null): array
    {
        if($by_token != null){
            $query = $link->prepare("SELECT * FROM `users` WHERE `auth_token` = ?;");
            $query->bind_param("s", htmlspecialchars(mysqli_real_escape_string($link, $by_token)));
            if(!$query->execute()) return [False];
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) return [False];

            if(!empty($result['activation'])){
                // Аккаунт не активирован
                return Array(False, "activation", $mail, explode("_", $result['activation'])[0]);
            }
            else if($result['status'] == 1 && $result['admin'] == 0){
                return Array(False, "blocked");
            }
            return Array(True, $result); 
        } else {
            $query = $link->prepare("SELECT * FROM `users` WHERE `email` = ?;");
            $query->bind_param("s", htmlspecialchars(mysqli_real_escape_string($link, $mail)));
            if(!$query->execute()) return [False];
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) return [False];
            
            if(strtolower($result['passwd']) == strtolower(md5($pass))){
                if(!empty($result['activation'])){
                    // Аккаунт не активирован
                    return Array(False, "activation", $mail, explode("_", $result['activation'])[0]);
                }
                else if($result['status'] == 1 && $result['admin'] == 0){
                    return Array(False, "blocked");
                }

                if($remember){
                    $token = md5(implode("_", $result)."_".time());
                    $sql = "UPDATE `users` SET `auth_token` = '' WHERE `users`.`id` = {$result['id']};";
                    if(mysqli_query($link, $sql)){
                        $result['token'] = $token;
                    } else {
                        $result['token'] = null;
                    }
                    $query = $link->prepare("UPDATE `users` SET `auth_token` = ? WHERE `id` = ?;");
                    $query->bind_param("si", $token, $result['id']);
                    if($query->execute() && $query->affected_rows > 0)
                        $result['token'] = $token;
                    else return [False];
                }

                return Array(True, $result); 
            }
            else{
                return Array(False, "wrong");
            }
        }
    }
    public static function activate_mail($link, int $id)
    {
        $query = $link->prepare("UPDATE `users` SET `activation` = '' WHERE `id` = ?;");
        $query->bind_param('i', $id);
        return $query->execute();
    }
    public static function change_password($link, int $id, $old_pass, $new_pass, $new_pass_repeat, $token = "", $force = false){
        if(!empty($token)){
            if(md5($new_pass) != md5($new_pass_repeat)){
                return False;
            }
            $query = $link->prepare("SELECT `id` FROM `users` WHERE `recovery_token` = ?;");
            $query->bind_param("s", htmlspecialchars(mysqli_real_escape_string($link, $token)));
            if(!$query->execute()) return False;
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) return False;

            if((int) $result['id'] > 0){
                $query = $link->prepare("UPDATE `users` SET `passwd` = ?, `recovery_token`='' WHERE `id` = ?;");
                $query->bind_param("si",
                    strtolower(md5($new_pass)),
                    $result['id']
                );
                if($query->execute())
                    return $query->affected_rows > 0;
                return False;
            }
            return False;
        } else {
            if($force){
                $query = $link->prepare("UPDATE `users` SET `passwd` = ? WHERE `id` = ?;");
                $query->bind_param("si", strtolower(md5($new_pass)), $id);
                if($query->execute()){
                    return [$query->affected_rows > 0, "OK"];
                }
                return [False, $link->error];
            }
            if(md5($new_pass) != md5($new_pass_repeat)){
                return False;
            }
            $query = $link->prepare("SELECT `passwd` FROM `users` WHERE `id` = ?;");
            $query->bind_param('i', $id);
            if(!$query->execute()) return False;
            $result = $query->get_result()->fetch_assoc();
            if(is_null($result)) return False;

            if(strtolower($result['passwd']) == strtolower(md5($old_pass))){
                $query = $link->prepare("UPDATE `users` SET `passwd` = ? WHERE `id` = ?;");
                $query->bind_param("si", strtolower(md5($new_pass)), $id);
                if($query->execute()){
                    return Array($query->affected_rows > 0, $result);
                }
                return Array(False);
            }
            else{
                return Array(False, "wrong");
            }
        }
    }
    public static function reset_password($link, $mail){
        $query = $link->prepare("SELECT * FROM `users` WHERE `email` = ?;");
        $query->bind_param("s", htmlspecialchars(mysqli_real_escape_string($link, $mail)));
        if(!$query->execute()) return False;
        $result = $query->get_result()->fetch_assoc();
        if(is_null($result)) return False;

        if((int) $result['id'] > 0){
            $token = md5(implode("_", $result)."_".time());
            $query = $link->prepare("UPDATE `users` SET `recovery_token` = ? WHERE `id` =  ?");
            $query->bind_param("si", $token, $result['id']);
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']."/login.php?recovery&token=$token";
            User::send_mail($mail, null, "Сброс пароля", "Ссылка для восстановления пароля: ", $url);
            return $query->execute();
        } else {
            return False;
        }
    }

    public static function create(mysqli $link, string $mail, string $passwd, string $name, int $account_type):array {
        $query = $link->prepare("SELECT `id` FROM `users` WHERE `email` = ?;");
        if(!$query->bind_param("s", $mail) || !$query->execute()){
            return [False, "Внутренняя ошибка. Код 1"];
        }

        if($query->get_result()->num_rows > 0){
            return [False, "Эта почта уже занята"];
        } else {
            $activation_code = User::gen_code(10);
            $retoken = md5(htmlspecialchars(mysqli_real_escape_string($link, $mail)));
            $activation = $retoken."_".$activation_code;
            $passwd = md5($passwd);

            $query->prepare("INSERT INTO `users` (`email`, `passwd`, `name`, `activation`, `account_type`) VALUES (?,?,?,?,?)");
            if(!$query->bind_param("sssss",
                $mail, $passwd, $name, $activation, $account_type
            ) || !$query->execute()){
                User::send_mail($mail, $activation_code);
                return [False, "Внутренняя ошибка. Код 2"];
            }
            return [True, $retoken];
        }
    }
    /**
     * @throws Exception
     */
    public function save(mysqli $link){
        $query = $link->prepare("UPDATE `users` SET `name` = ?, `phone` = ?, `account_type` = ?, 
                   `subscription_type` = ?, `subscription_end` = ?, `admin` = ?, `status` = ? WHERE `id` = ?");
        if(!$query->bind_param("ssiiiiii",
        $this->name, $this->phone, $this->account_type, $this->subscription->get_id(),
            $this->subscription_end, $this->admin, $this->status, $this->id
        ) || !$query->execute()){
            throw new Exception("Внутренняя ошибка. Код 3");
        }
    }
    public function remove($link):bool{
        $query = $link->prepare("DELETE FROM `users` WHERE `id` = ?;");
        return ($query->bind_param("i", $this->id) && $query->execute());
    }

    // Tools
    static function gen_code($size): string {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $size; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public static function send_mail($mail, $code, $title = '', $text = '', $url = ''){
        if(empty($title)) $title = 'Активация аккаунта';
        if(empty($text)) $text = "Ссылка для активации: ";
        if(empty($url))
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']."/activation.php?token=$code";
        mail($mail, $title, $text . $url);
    }
}