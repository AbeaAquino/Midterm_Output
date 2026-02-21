<?php
include 'config/database.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $bill_id = $_POST['bill_id'];
    $method  = $_POST['method'];

    $bill = $conn->query("SELECT * FROM bills WHERE id=$bill_id")->fetch_assoc();
    $amount = $bill['amount'];

    $reference = "REF".rand(10000,99999);

    $conn->query("INSERT INTO payments(user_id,bill_id,amount,payment_method,reference_number)
                  VALUES($user_id,$bill_id,$amount,'$method','$reference')");

    $conn->query("UPDATE bills SET status='Paid' WHERE id=$bill_id");

    header("Location: view_bill.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['method']) || !isset($_GET['bill_id'])) {
    echo "Error: Payment method or bill ID missing.";
    exit();
}

$method = $_GET['method'];
$bill_id = $_GET['bill_id'];

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
    <title><?php echo $method; ?> Payment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-image">

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
            <div class="profile-header">
                <div class="account-avatar">
                    <img src="assets/user.png" alt="User">
                </div>
                <div>
                    <h3><?php echo $user['name']; ?></h3>
                    <span><?php echo $user['email']; ?></span>
                </div>
            </div>

            <div class="glass-top">
                <?php echo $method; ?> Payment
            </div>

            <div class="glass-content">
                <div class="card-payment-layout">

                    <!-- LEFT SIDE -->
                    <div class="bill-overview">
                        <div class="overview-box">
                            <label>Amount</label>
                            <h2>₱ <?php echo number_format($bill['amount'],2); ?></h2>
                        </div>
                        <div class="overview-box">
                            <label>Date</label>
                            <h3><?php echo $bill['month']; ?></h3>
                        </div>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="card-form">
                        <form method="POST">
                            <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                            <input type="hidden" name="method" value="E-Wallet">

                            <label>Mobile Number</label>
                            <input type="text" name="mobile_number"
                                   placeholder="Enter mobile number linked to your e-wallet account"
                                   required>

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
