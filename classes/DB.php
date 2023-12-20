<?php 
class DB {

    public $conn;

    public  function __construct() {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'lazy_loading';
        $conn = new mysqli($host, $user, $password, $database);
        $this->set_db($conn);
    }

    function set_db($conn) {
        $this->conn = $conn;
    }
    
}