<?php

abstract class Site{

    public static function get_all_options(mysqli $link):array {
        $sql = "SELECT `name`, `value` FROM `site_settings`;";
        $result = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);
        $options = [];
        foreach($result as $option){
            $options[$option['name']] = $option['value'];
            if(ctype_digit($option['value'])){
                $options[$option['name']] = (int) $option['value'];
            }
        }
        return $options;
    }

    public static function get_editable_options(mysqli $link):array {
        $sql = "SELECT * FROM `site_settings`;";
        $result = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);
        $options = [];
        foreach($result as $option){
            $options[$option['name']] = ['value' => $option['value'], 'display_name'=> $option['display_name']];
        }
        return $options;
    }

    public static function set_options(mysqli $link, array $options) {
        foreach(array_keys($options) as $option_key){
            $query = $link->prepare("UPDATE `site_settings` SET `value` = ? WHERE `name` = ?;");
            $query->bind_param("ss", $options[$option_key], $option_key);
            $query->execute();
        }
    }
}
