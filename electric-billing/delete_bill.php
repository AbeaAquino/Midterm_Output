<?php
include 'config/database.php';
include 'includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location: manage_bills.php");
    exit();
}

$bill_id = $_GET['id'];

/* DELETE PAYMENTS FIRST */
$delete_payments = $conn->prepare("
    DELETE FROM payments WHERE bill_id=?
");

$delete_payments->bind_param("i", $bill_id);
$delete_payments->execute();

/* DELETE BILL */
$delete_bill = $conn->prepare("
    DELETE FROM bills WHERE id=?
");

$delete_bill->bind_param("i", $bill_id);
$delete_bill->execute();

header("Location: manage_bills.php");
exit();
?>
