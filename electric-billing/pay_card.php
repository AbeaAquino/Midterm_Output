<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['bill_id']) || !isset($_GET['method'])) {
    echo "Error: Missing payment information.";
    exit();
}

$bill_id = $_GET['bill_id'];
$method = $_GET['method'];

/* GET BILL */
$bill_query = $conn->prepare("SELECT * FROM bills WHERE id=? AND user_id=?");
$bill_query->bind_param("ii", $bill_id, $user_id);
$bill_query->execute();
$bill = $bill_query->get_result()->fetch_assoc();

/* GET USER */
$user_query = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Card Payment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-image">

<!-- HEADER -->
<header class="header">
    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC POWER PORTAL</h1>
            <span>Powering Your Future</span>
        </div>
    </div>
</header>

<div class="page-content">

    <div class="glass-wrapper">

        <div class="glass-card">

            <!-- PROFILE HEADER -->
            <div class="profile-header">
                <div class="profile-avatar"></div>
                <div>
                    <h3><?php echo $user['name']; ?></h3>
                    <span><?php echo $user['email']; ?></span>
                </div>
            </div>

            <!-- BLUE STRIP -->
            <div class="glass-top">
                Credit / Debit Card
            </div>

            <div class="glass-content">

                <div class="card-payment-layout">

                    <!-- LEFT SIDE -->
                    <div class="bill-overview">

                        <div class="overview-box">
                            <label>Amount</label>
                            <h2>₱ <?php echo number_format($bill['amount'], 2); ?></h2>
                        </div>

                        <div class="overview-box">
                            <label>Date</label>
                            <h3><?php echo $bill['month']; ?></h3>
                        </div>

                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="card-form">

                        <form method="POST" action="process_payment.php">

                            <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                            <input type="hidden" name="method" value="Card">

                            <label>Name on Card</label>
                            <input type="text" name="card_name" placeholder="Cardholder Name" required>

                            <label>Card Number</label>
                            <input type="text" name="card_number" placeholder="0000 0000 0000 0000" required>

                            <div class="card-row">

                                <div>
                                    <label>CVV</label>
                                    <input type="text" name="cvv" placeholder="***" required>
                                </div>

                                <div>
                                    <label>Expiry</label>
                                    <input type="text" name="expiry" placeholder="MM/YY" required>
                                </div>

                            </div>

                            <div class="center-btn">
                                <button type="submit" class="btn-confirm">
                                    PAY
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
