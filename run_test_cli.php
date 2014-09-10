<?php
/*
 * Alexander Lebedko (c) 2014
 */
include_once dirname(__FILE__) . '/lib/db_vicman_generator.php';

class vic_test_run {

    private $vicDB;
    var $args_array;

    function init() {
        $this->vicDB = new db_vicman_generator();
        $this->vicDB->db_host = "127.0.0.1";
        $this->vicDB->db_name = "vic";
        $this->vicDB->db_charset = "utf-8";
        $this->vicDB->db_user_name = "root";
        $this->vicDB->db_user_password = "root";
        $this->read_arg_from_console();
    }

    private function read_arg_from_console() {
        global $argv;
        for ($i = 1; $i < count($argv); $i++) {
            $res = explode("=", $argv[$i]);
            $key = trim($res[0]);
            if (isset($res[1])) {
                $value = trim($res[1]);
            }
            echo "Выполняется команда: " . $argv[$i] . "\n";
            switch ($key) {
                case "--set_db_host":
                    //todo: добавить фильтр для $value по необходимому шаблону
                    $this->vicDB->db_host = $value;
                    break;
                case "--set_db_name":
                    //todo: добавить фильтр для $value по необходимому шаблону
                    $this->vicDB->db_name = $value;
                    break;
                case "--set_db_charset":
                    //todo: добавить фильтр для $value по необходимому шаблону
                    $this->vicDB->db_charset = $value;
                    break;
                case "--set_db_user_name":
                    //todo: добавить фильтр для $value по необходимому шаблону
                    $this->vicDB->db_user_name = $value;
                    break;
                case "--set_db_user_password":
                    //todo: добавить фильтр для $value по необходимому шаблону
                    $this->vicDB->db_user_password = $value;
                    break;
                case "--print_db_struct":
                    $this->vicDB->print_db_struct();
                    break;
                case "--gen_new_data":
                    $this->vicDB->gen_new_data(intval($value));
                    break;
                case "--print_gen_data":
                    $this->vicDB->print_gen_data();
                    break;
                case "--reconect":
                    $this->vicDB->connect();
                    break;
                case "--help":
                    $this->print_help();
                    break;
                default:
                    echo "WARNING: неизвестная команда " . $key . "\n";
                    break;
            }
        }
    }

    function print_help() {
        echo file_get_contents(dirname(__FILE__) . '/documentation/readme.php');
    }

}

try {
    echo "Добро пожаловать в консольный генератор тестовых данных VIC для mySQL\n";
    echo "Alexander Lebedko (C) 2014\n\n";
    $vic_test_run = new vic_test_run();
    $vic_test_run->init();
} catch (Exception $ex) {
    echo "Обнаружена ошибка: \n";
    echo $ex->getMessage() . "\n";
}
?>