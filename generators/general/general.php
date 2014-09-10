<?php

class general_config_object {

    var $default_text_length = 16;
    var $default_int_length = 4;

    /**
     * Сгенерировать данные стандартным генератором
     * @param type $type
     * @param type $length
     * @param type $addnation_data
     */
    function generate($type, $length, $addnation_data) {
        $res = false;
        switch ($type) {
            case "int":
                if ($length === false) {
                    $length = $this->default_int_length;
                }
                $res = $this->gen_int($length);
                break;
            case "tinyint":
                if ($length === false) {
                    $length = 1;
                }
                $res = $this->gen_int($length);
                break;
            case "text":
                if ($length === false) {
                    $length = $this->default_text_length;
                }
                $res = $this->gen_txt($length);
                break;
            default:
                $res = false;
                break;
        }
        return $res;
    }

    private function gen_int($length) {
        $al = "1234567890";
        $count_al = strlen($al)-1;
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $result.= $al[rand(0, $count_al)];
        }
        return intval($result);
    }
    
    private function gen_txt($length) {
        $al = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
        $count_al = strlen($al)-1;
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $result.= $al[rand(0, $count_al)];
        }
        return $result;
    }

}
