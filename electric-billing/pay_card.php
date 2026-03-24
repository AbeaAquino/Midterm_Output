<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* HANDLE PAYMENT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bill_id     = $_POST['bill_id'];
    $card_name   = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $cvv         = $_POST['cvv'];
    $expiry      = $_POST['expiry'];

    /* VALIDATION */
    if (!preg_match("/^[a-zA-Z ]+$/", $card_name)) {
        echo "<script>alert('Invalid card name');history.back();</script>";
        exit();
    }

    if (!preg_match("/^[0-9]{16}$/", $card_number)) {
        echo "<script>alert('Card number must be 16 digits');history.back();</script>";
        exit();
    }

    if (!preg_match("/^[0-9]{3}$/", $cvv)) {
        echo "<script>alert('Invalid CVV');history.back();</script>";
        exit();
    }

    if (!preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $expiry)) {
        echo "<script>alert('Expiry must be MM/YY');history.back();</script>";
        exit();
    }

    /* GET BILL (SECURE) */
    $stmt = $conn->prepare("SELECT * FROM bills WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $bill_id, $user_id);
    $stmt->execute();
    $bill = $stmt->get_result()->fetch_assoc();

    if (!$bill) {
        die("Invalid bill.");
    }

    $amount = $bill['amount'];
    $reference = "REF" . rand(10000, 99999);
    $payment_method_id = 3;

    /* INSERT PAYMENT */
    $insert = $conn->prepare("
        INSERT INTO payments (user_id, bill_id, payment_method_id, amount, reference_number)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->bind_param("iiiis", $user_id, $bill_id, $payment_method_id, $amount, $reference);
    $insert->execute();

    /* UPDATE BILL */
    $update = $conn->prepare("UPDATE bills SET status='Paid' WHERE id=?");
    $update->bind_param("i", $bill_id);
    $update->execute();

    /* SUCCESS MESSAGE */
    echo "<script>
        alert('Payment Successful!');
        window.location.href='view_bill.php';
    </script>";
    exit();
}

/* CHECK PARAMS */
if (!isset($_GET['bill_id']) || !isset($_GET['method'])) {
    die("Missing payment info.");
}

$bill_id = $_GET['bill_id'];

/* GET BILL */
$stmt = $conn->prepare("SELECT * FROM bills WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $bill_id, $user_id);
$stmt->execute();
$bill = $stmt->get_result()->fetch_assoc();

/* GET USER */
$user_stmt = $conn->prepare("SELECT name,email FROM users WHERE id=?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Card Payment</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<?php include 'includes/header.php'; ?>

<div class="page-content">
    <div class="glass-wrapper">
        <div class="glass-card">

            <div class="profile-header">
                <div class="account-avatar">
                    <img src="assets/user.png">
                </div>
                <div>
                    <h3><?= $user['name']; ?></h3>
                    <span><?= $user['email']; ?></span>
                </div>
            </div>

            <div class="glass-top">Card Payment</div>

            <div class="glass-content">
                <div class="card-payment-layout">

                    <!-- LEFT -->
                    <div class="bill-overview">

                        <div class="overview-box">
                            <label>Amount</label>
                            <h2>₱ <?= number_format($bill['amount'], 2); ?></h2>
                        </div>

                        <div class="overview-box">
                            <label>Date</label>
                            <h3><?= $bill['billing_month']; ?></h3>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="card-form">
                        <form method="POST">

                            <input type="hidden" name="bill_id" value="<?= $bill_id ?>">

                            <label>Name on Card</label>
                            <input type="text" name="card_name" pattern="[A-Za-z ]+" required>

                            <label>Card Number</label>
                            <input type="text"
                                   name="card_number"
                                   maxlength="16"
                                   pattern="[0-9]{16}"
                                   required>

                            <div class="card-row">

                                <div>
                                    <label>CVV</label>
                                    <input type="text"
                                           name="cvv"
                                           maxlength="3"
                                           pattern="[0-9]{3}"
                                           required>
                                </div>

                                <div>
                                    <label>Expiry</label>
                                    <input type="text"
                                           name="expiry"
                                           placeholder="MM/YY"
                                           pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
                                           required>
                                </div>

                            </div>

                            <div class="center-btn">
                                <button type="submit" class="btn-confirm">PAY</button>
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
