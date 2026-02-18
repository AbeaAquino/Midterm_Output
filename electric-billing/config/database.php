<?php
$conn = new mysqli("localhost","root","","angeles_electric_db");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}
session_start();
?>
