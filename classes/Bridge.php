<?php 
include_once("Connect.php");
session_start();

//FROM CHAT GPT
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if (method_exists($_SESSION['connect'], $action)) {
        if(isset($_POST['variableName'])){
            echo json_encode($_SESSION['connect']->$action($_POST['variableName']));
        }else{
            echo json_encode($_SESSION['connect']->$action());
        }
    } else {
        echo json_encode(['error' => 'Method not found']);
    }

}