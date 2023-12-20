<?php 
include_once("DB.php");
class Connect {
    public $conn;
    public $rows = 0;

    public function __construct() {

    }

    function set_db() {
        $db = new DB();
        $conn = $db->conn;
        $this->conn = $conn;
    }


    function get_all(){
        $conn = $this->conn;
        return $conn->query('SELECT * FROM food');
    }


    function get_initial(){
        $this->set_db();
        $conn = $this->conn;
        $this->rows = 5;  
        $data = $conn->query('SELECT * FROM food ORDER BY id ASC LIMIT 5 ')->fetch_all();
        $array = ($data); 
        return $array;
    }


    function get_more(){
        $this->set_db();
        $conn = $this->conn;
        $floor_rows = $this->rows; 
        $this->rows = $this->rows + 5;
        $ceil_rows = $this->rows;
        return $conn->query('SELECT * FROM food WHERE id > '. $floor_rows. ' AND id <= '. $ceil_rows .' ORDER BY id ASC LIMIT 5 ')->fetch_all();
    }


    function get_count(){
        $rows = $this->rows;
        return $rows;
    }


    function set_count($count){
        $this->rows = $count;
        return $this->rows;
    }

}




