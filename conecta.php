<?php

class conecta {

    var $bd;

    function __construct() {
        $this->bd = ADONewConnection("postgres");
        $this->bd->debug = false;
        $this->bd->Connect("host=localhost port=5432 dbname=trabalhopw2 user=postgres password=963852741");
    }

}
?>
