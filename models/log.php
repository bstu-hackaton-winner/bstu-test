<?php


class Log
{
    protected $id;
    protected $value;
    protected $date;

    public function __construct(int $id, string $value, string $date) {
        $this->id = $id;
        $this->value = $value;
        $this->date = $date;
    }
    public static function create(string $value):Log {
        return new self(-1, $value, "");
    }

    public function get_id():int { return $this->id; }
    public function get_value():string { return $this->value; }
    public function get_date():string { return $this->date; }

    public function write(mysqli $link){
        $query = $link->prepare("INSERT INTO `logs` VALUES(null, ?, CURRENT_TIMESTAMP);");
        $query->bind_param("s", $this->value);
        $query->execute();
        $this->id = $query->insert_id;
    }
    public static function get_logs(mysqli $link, int $limit = 0, int $offset = 0):array {
        if($limit > 0){
            $query = $link->prepare("SELECT * FROM `logs` ORDER BY `id` DESC LIMIT ?, ?;");
            $query->bind_param("ii", $limit, $offset);
        }
        else
            $query = $link->prepare("SELECT * FROM `logs` ORDER BY `id` DESC;");

        if(!$query->execute()){
            send_log($link, var_export($link));
            return [];
        } else {
            $logs = [];
            $results = $query->get_result();
            while($row = $results->fetch_assoc()){
                if(empty($row['value'])) continue;
                array_push($logs, new self($row['id'], $row['value'], $row['date']));
            }
            return $logs;
        }
    }
}