<?php

include_once dirname(__FILE__) . '/../generators/generator_config.php';
/*
 * Alexander Lebedko (c) 2014
 */

class db_vicman_generator {

    var $db_host = false;
    var $db_name = false;
    var $db_charset = false;
    var $db_user_name = false;
    var $db_user_password = false;
    private $link = false;
    private $generator_hub = false;
    private $log_gen_data = false;

    /**
     * Подключение к БД
     * @throws Exception
     */
    function __construct() {
        $this->generator_hub = new generator_hub();
    }

    function connect() {
//        var_dump($this->db_name);
        $this->link = mysql_connect($this->db_host, $this->db_user_name, $this->db_user_password, true);
        if (!$this->link) {
            throw new Exception('Не удалось установить соединение с MySQL!');
        } else {
            echo "Соединение установлено c mySQL сервером " . $this->db_host . " .\n";
        }
            var_dump(mysql_select_db($this->db_name, $this->link));
        if (!$this->link) {
            if (mysql_select_db($this->db_name, $this->link) == false) {
                throw new Exception('Такой базы не существует');
            } else {
                echo "Выбрана База данных " . $this->db_name . " .\n";
            }
        }
    }

    /**
     * Просмотр таблиц
     * @return ArrayAccess
     */
    private function list_db_tables() {
        if ($this->link === false) {
            $this->connect();
        }
        $query_resource = mysql_list_tables($this->db_name, $this->link);
        while ($row = mysql_fetch_array($query_resource)) {
            $table_list[] = $row[0];
        }
        return $table_list;
    }

    /**
     * Печать структуры таблицы
     * @param string $table
     */
    private function print_db_table_struct($table) {
        echo "Печать структуры таблицы: " . $table . "\n";
        echo $this->get_db_table_struct($table);
        echo "\n";
    }

    /**
     * Получить структуру таблицы
     * @param string $table
     */
    function get_db_table_struct($table) {
        if ($this->link === false) {
            $this->connect();
        }
        $query_resource = mysql_query("SHOW Create Table " . mysql_real_escape_string($table, $this->link), $this->link);
        $row = mysql_fetch_array($query_resource);
        return $row[1];
    }

    /**
     * Печать структуры базы 
     */
    function print_db_struct() {
        if ($this->link === false) {
            $this->connect();
        }
        $tables_list = $this->list_db_tables();
        for ($i = 0; $i < count($tables_list); $i++) {
            $this->print_db_table_struct($tables_list[$i]);
        }
    }

    /**
     * Генерация данных для базы данных 
     * @param int $row_count
     */
    function gen_new_data($row_count = 1) {
        $this->log_gen_data = array();
        if ($this->link === false) {
            $this->connect();
        }
        $tables_list = $this->list_db_tables();
        for ($i = 0; $i < count($tables_list); $i++) {
            $table_struct_text = $this->get_db_table_struct($tables_list[$i]);
            preg_match_all("/\`(\w+)\` ([\w\d\(\)]+) .+\n/i", $table_struct_text, $matches);
            $fields = array();
            for ($j = 0; $j < count($matches[1]); $j++) {
                $fields[$j]['field_name'] = trim($matches[1][$j]);
                $fields[$j]['field_type'] = trim($matches[2][$j]);
                if (strstr($matches[0][$j], "AUTO_INCREMENT") === false) {
                    $fields[$j]['field_is_inc'] = false;
                } else {
                    $fields[$j]['field_is_inc'] = true;
                }

                echo "Опредлено поле: " . $fields[$j]['field_name'] . " || ";
                echo "Тип поля: " . $fields[$j]['field_type'] . "";
                if (!$fields[$j]['field_is_inc']) {
                    echo "\n";
                } else {
                    echo " || Это AUTO_INCREMENT\n";
                }
                $fields[$j]['field_value'] = $this->gen_new_data_for_field($fields[$j], $row_count);
            }
            $this->write_to_DB_generated_data($fields, $tables_list[$i]);
            $this->log_gen_data[$tables_list[$i]] = $fields;
        }
    }

    /**
     * Отобразить сгенерированные данные
     * @throws Exception
     */
    function print_gen_data() {
        if (count($this->log_gen_data) == 0 || $this->log_gen_data == false) {
            throw new Exception("Сгенерируйте данные командой --gen_new_data=количество, перед тем как их печатать на экран");
        }

        foreach ($this->log_gen_data as $key => $value) {
            echo "Таблица: " . $key . "\n";
            for ($i = 0; $i < count($value); $i++) {
                if (count($value[$i]['field_value']) == 0)
                    continue;
                echo "После: " . $value[$i]['field_name'] . " типа " . $value[$i]['field_type'] . " сгенерировано " . count($value[$i]['field_value']) . " строчек:\n";
                echo "==> [" . implode(",", $value[$i]['field_value']) . "]\n";
                echo "\n";
            }
            echo "\n";
        }
    }

    /**
     * Сгенерировать данные для поля
     * @param ArrayAccess $field_resource
     * @param int $row_count
     */
    private function gen_new_data_for_field($field_resource, $row_count) {
        preg_match("/(\w+)/i", $field_resource['field_type'], $match);
        if (isset($match[1])) {
            $field_type_name = $match[1];
        } else {
            $field_type_name = false;
        }
        preg_match("/(\d+)/i", $field_resource['field_type'], $match);
        if (isset($match[1])) {
            $field_type_length = $match[1];
        } else {
            $field_type_length = false;
        }
        $this->generator_hub->generator_name = "general"; // todo: В последующих версиях завадать через аргументы в консоли
        $this->generator_hub->field_type_name = $field_type_name;
        $this->generator_hub->field_data_length = $field_type_length;
        $res = array();
        for ($i = 0; $i < $row_count; $i++) {
            $res[] = $this->generator_hub->give_me_data();
        }
        return $res;
    }

    /**
     * Записать в базу данных новые данные
     * @param fields_resource $fields
     * @param string $table
     */
    private function write_to_DB_generated_data($fields, $table) {
        echo "Запись сгенерированных данных для таблицы " . $table . "\n";
        $query = "insert into `" . mysql_real_escape_string($table, $this->link) . "`";
        $query_fields = array();
        for ($i = 0; $i < count($fields); $i++) {
            if (count($fields[$i]['field_value']) == 0 || $fields[$i]['field_is_inc'] == true)
                continue;
            $query_fields[] = "`" . $fields[$i]['field_name'] . "`";
        }
        $query .= "(" . implode(",", $query_fields) . ") VALUES";
        $field_value_iteration = 0;
        $field_value_iteration_count = -1;
        do {
            $query_fields = array();
            for ($i = 0; $i < count($fields); $i++) {
                if (count($fields[$i]['field_value']) == 0 || $fields[$i]['field_is_inc'] == true)
                    continue;
                if ($field_value_iteration_count == -1) {
                    $field_value_iteration_count = count($fields[$i]['field_value']);
                }
                $query_fields[] = "'" . mysql_real_escape_string($fields[$i]['field_value'][$field_value_iteration], $this->link) . "'";
            }
            $field_value_iteration++;
            $query .= "(" . implode(",", $query_fields) . "),";
        } while ($field_value_iteration < $field_value_iteration_count && $field_value_iteration_count != -1);
        $query = substr($query, 0, -1);
        if (mysql_query($query, $this->link) != false) {
            echo "Запись сгенерированных данных прошла успешно\n";
        } else {
            throw new Exception("Запись сгенерированных данных вызвала ошибку. Запрос:" . $query);
        }
    }

}

?>