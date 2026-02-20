<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

// Check if 'method' and 'bill_id' are set in the URL
if (!isset($_GET['method']) || !isset($_GET['bill_id'])) {
    // Handle error if the method or bill_id is not passed in the URL
    echo "Error: Payment method or bill ID missing.";
    exit();
}

$method = $_GET['method'];  // E-Wallet method (e.g., GCash)
$bill_id = $_GET['bill_id'];  // The bill ID to be paid

// Get the bill details
$bill_query = $conn->prepare("SELECT * FROM bills WHERE id=?");
$bill_query->bind_param("i", $bill_id);
$bill_query->execute();
$bill = $bill_query->get_result()->fetch_assoc();

// Get the user details
$user_query = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay Bill - E-Wallet</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-image">

<!-- HEADER -->
<header class="header">
    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC CORPORATION</h1>
            <span>Powering Your Future</span>
        </div>
    </div>
</header>

<!-- PAGE CONTENT -->
<div class="page-content">
    <div class="payment-wrapper">
        <div class="payment-card">
            <h2><?php echo $method; ?> Payment</h2>  <!-- Dynamically show the payment method -->

            <!-- BILL OVERVIEW -->
            <div class="bill-overview">
                <p><strong>Amount:</strong> ₱<?php echo number_format($bill['amount'],2); ?></p>
                <p><strong>Date:</strong> <?php echo $bill['month']; ?></p>
            </div>

            <!-- MOBILE NUMBER FORM -->
            <form method="POST" action="process_payment.php">
                <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                <input type="hidden" name="method" value="E-Wallet">

                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile_number" placeholder="Enter mobile number linked to your e-wallet account" required>
                </div>

                <div class="center-btn">
                    <button type="submit" class="btn-confirm">PAY</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
