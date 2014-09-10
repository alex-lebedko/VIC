<?php

include_once dirname(__FILE__) . '/general/general.php';

class generator_hub {

    var $generator_name = "general"; // default generator value
    var $field_data_length = false;
    var $field_type_name = false;
    var $field_addnation_data = false;
    private $generstors_obj;

    function __construct() {
        $this->generstors_obj['general'] = new general_config_object();
        //Другие генераторы
    }

    function give_me_data() {
        $res = $this->generstors_obj[$this->generator_name]->generate($this->field_type_name, $this->field_data_length, $this->field_addnation_data);
        return $res;
    }

}

?>