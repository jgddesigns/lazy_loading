<?php 

class Connect {

    public $conn;
    public $rows = 5;

    public function __construct() {
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

    function get_all(){
        $conn = $this->conn;
        return $conn->query('SELECT * FROM food');
    }

    function get_initial(){
        $conn = $this->conn;
        $this->rows = 5;  
        $data = $conn->query('SELECT * FROM food ORDER BY id ASC LIMIT 5 ')->fetch_all();
        $array = ($data); 
        // for ($i = 0; $i < count($data); $i++){
        //     array_push($array, $data[$i]);
        // }
        return $array;
        // return $array;
    }

    function get_more(){
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
        $rows = $this->rows;
        return $rows;
    }

}

//FROM CHAT GPT
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $connect = new Connect(); 
    
    if (method_exists($connect, $action)) {

        if(isset($_POST['variableName'])){
            echo json_encode($connect->$action($_POST['variableName']));
        }else{
            echo json_encode($connect->$action());
        }
        // echo json_encode(['result' => $result]);
    } else {
        echo json_encode(['error' => 'Method not found']);
    }
}


