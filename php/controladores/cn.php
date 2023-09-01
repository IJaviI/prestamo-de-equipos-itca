<?php
class cn {
    private $server;
    private $user;
    private $password;
    private $db;

    public function __construct() {
        $this -> server = "localhost";
        $this -> user = "root";
        $this -> password = "";
        $this -> db = "prestamo_de_equipos_itca";
    }

    public function cn() {
        return new mysqli($this -> server, $this -> user, $this -> password, $this -> db);
    }
}
?>