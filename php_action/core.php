<?php 

session_start();

require_once 'db_connect.php';

// echo $_SESSION['userId'];

if (empty($_SESSION['userId'])) {
    header('location:'.$store_url);
    exit;
}
?>