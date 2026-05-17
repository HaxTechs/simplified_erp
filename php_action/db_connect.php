<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$localhost = "127.0.0.1";
$username = "erpuser";
$password = "password";
$dbname = "simple_erp";
$store_url = "http://localhost:8000/";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
$connect->set_charset('utf8mb4');

// check connection
if ($connect->connect_error) {
    die("Connection Failed: " . $connect->connect_error);
}

?>