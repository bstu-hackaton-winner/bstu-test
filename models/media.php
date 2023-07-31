<?php


class Media
{
    protected $id = -1;
    protected $name = "No photo available";
    protected $file = "no_photo.jpg";
    protected $type = "jpg";

    public function __construct(mysqli $link = null, int $id = null){
        if(is_null($link)) return;
        $query = $link->prepare("SELECT * FROM `media` WHERE `id` = ?");
        if(!$query->bind_param("i", $id) || !$query->execute() || !($result = $query->get_result()->fetch_assoc())){
            send_log($link, $link->error);
            return;
        }

        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->file = $result['file'];
        $this->type = $result['type'];
    }
    /**
     * @throws Exception
     */
    public static function upload(mysqli $link, array $file, string $name):Media {
        if(is_uploaded_file($file['tmp_name'])){
            $file_type = strtolower(explode(".", $file['name'])[
                count(explode(".", $file['name']))-1
            ]);
            $file_name = $file['name'];
            if(file_exists($_SERVER['DOCUMENT_ROOT']. "/media/" . $file['name'])){
                $file_name = substr($file['name'], 0, strlen($file['name']) - strlen($file_type) -1) . "_" . time() . "." . $file_type;
            }
            if(move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT']. "/media/" . $file_name)){
                $query = $link->prepare("INSERT INTO `media` VALUES(null, ?, ?, ?)");
                $query->bind_param("sss", $name, $file_name, $file_type);
                if(!$query->execute() || !($media_id = $link->insert_id)){
                    send_log($link, $link->error);
                    throw new Exception("Ошибка записи файла в БД");
                }
                return new self($link, $media_id);
            } else {
                throw new Exception("Не удалось переместить загруженный файл");
            }
        }
        throw new Exception("Не удалось загрузить файл на сервер");
    }

    // Getters
    public function get_id():int { return $this->id; }
    public function get_name():string { return $this->name; }
    public function get_file_url():string { return $this->file; }
    public function get_type():string { return $this->type; }
    public static function get_media_list(mysqli $link, ...$types):array {
        if($types[0] == "ALL")
            $query = $link->prepare("SELECT * FROM `media`");
        else{
            $where = "";
            foreach($types as $type){
                $where .= (strlen($where) == 0 ? " WHERE"  : " OR") . " `type` = '$type'";
            }
            $query = $link->prepare("SELECT * FROM `media` $where");
        }
        if(!$query->execute() || !($result = $query->get_result())){
            send_log($link, $link->error);
            return [];
        }

        $media_files = [];
        while($media = $result->fetch_assoc()){
            $obj = new self();
            $obj->id = $media['id'];
            $obj->name = $media['name'];
            $obj->file = $media['file'];
            $obj->type = $media['type'];
            array_push($media_files, $obj);
        }
        return $media_files;
    }

    // Setters
    public function set_name(string $value){
        $this->name = $value;
    }
    /**
     * @throws Exception
     */
    public function set_file(string $value){
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/media/" . $value))
            $this->file = $value;
        else throw new Exception("Файл не существует");
    }
    public function set_type(string $value){
        $this->type = $value;
    }
    public function save(mysqli $link):bool {
        $query = $link->prepare("UPDATE `media` SET `name`=?, `file`=?, `type`=? WHERE `id`=?");
        $query->bind_param("sssi", $this->name, $this->file, $this->type, $this->id);
        return $query->execute();
    }
    public function remove(mysqli $link):bool {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/media/" . $this->file);
        $query = $link->prepare("DELETE FROM `media` WHERE `id` = ?;");
        return $query->bind_param("i", $this->id) && $query->execute();
    }
}