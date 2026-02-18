<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* GET USER */
$user_query = $conn->prepare("SELECT name,email FROM users WHERE id=?");
$user_query->bind_param("i",$user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

/* GET UNPAID BILLS */
$bills_query = $conn->prepare("SELECT * FROM bills WHERE user_id=? AND status='Unpaid'");
$bills_query->bind_param("i",$user_id);
$bills_query->execute();
$bills = $bills_query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Payment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-image">

<header class="header">
    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC CORPORATION</h1>
            <span>Powering Your Future</span>
        </div>
    </div>

    <div class="nav-links">
        <a href="home.php">Home</a>
        <span class="divider">|</span>
        <a href="view_bill.php">View Bill</a>
        <span class="divider">|</span>
        <a href="pay_bill.php" class="active">Pay Bill</a>
        <div class="account-icon">👤</div>
    </div>
</header>

<div class="glass-wrapper">

    <div class="glass-card">

        <!-- USER -->
        <div class="profile-header">
            <div class="profile-avatar"></div>
            <div>
                <h3><?php echo $user['name']; ?></h3>
                <span><?php echo $user['email']; ?></span>
            </div>
        </div>

        <!-- BLUE STRIP -->
        <div class="glass-top">
            Payment Details
        </div>

        <div class="glass-content">

            <form method="POST" action="pay_card.php">

                <div class="form-group">
                    <label>Month</label>
                    <select name="bill_id" required>
                        <?php while($bill = $bills->fetch_assoc()){ ?>
                        <option value="<?php echo $bill['id']; ?>">
                            <?php echo $bill['month']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>

                    <div class="method-row">

                        <label class="payment-box">
                            <input type="radio" name="method" value="GCash" required>
                            <img src="assets/gcash.png">
                            <span>E-Wallet</span>
                        </label>

                        <label class="payment-box">
                            <input type="radio" name="method" value="Card" required>
                            <img src="assets/mastercard.png">
                            <span>Credit/Debit Card</span>
                        </label>

                    </div>
                </div>

                <div class="center-btn">
                    <button class="btn-confirm">CONFIRM</button>
                </div>

            </form>

        </div>

    </div>

</div>

<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>

</html>
