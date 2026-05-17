<?php 	

$localhost = "127.0.0.1";
$username = "erpuser";
$password = "password";
$dbname = "simple_erp";
$store_url = "http://localhost:8000/";
// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>