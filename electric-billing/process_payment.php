<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$bill_id = $_POST['bill_id'];
$method = $_POST['method'];

$bill = $conn->query("SELECT * FROM bills WHERE id=$bill_id")->fetch_assoc();
$amount = $bill['amount'];

$reference = "REF".rand(10000,99999);

$conn->query("INSERT INTO payments(user_id,bill_id,amount,payment_method,reference_number)
VALUES($user_id,$bill_id,$amount,'$method','$reference')");

$conn->query("UPDATE bills SET status='Paid' WHERE id=$bill_id");

header("Location: view_bill.php");
exit();
