<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$bill_id = $_POST['bill_id'];
$method = $_POST['method'];

$bill_query = $conn->prepare("SELECT * FROM bills WHERE id=?");
$bill_query->bind_param("i",$bill_id);
$bill_query->execute();
$bill = $bill_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Card Payment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC CORPORATION</h1>
            <span>Powering Your Future</span>
        </div>
    </div>
</header>

<div class="payment-wrapper">

    <div class="payment-card">

        <h2>Credit / Debit Card</h2>

        <div class="bill-overview">
            <p><strong>Amount:</strong> ₱<?php echo number_format($bill['amount'],2); ?></p>
            <p><strong>Date:</strong> <?php echo $bill['month']; ?></p>
        </div>

        <form method="POST" action="process_payment.php">

            <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
            <input type="hidden" name="method" value="<?php echo $method; ?>">

            <input type="text" placeholder="Card Number" required>
            <input type="text" placeholder="Expiry (MM/YY)" required>
            <input type="text" placeholder="CVV" required>

            <button class="btn-primary">Pay</button>
        </form>

    </div>

</div>

<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
