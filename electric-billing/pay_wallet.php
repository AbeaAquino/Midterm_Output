<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* HANDLE PAYMENT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bill_id = $_POST['bill_id'];
    $mobile_number = $_POST['mobile_number'];

    /* VALIDATE MOBILE NUMBER (11 DIGITS) */
    if(!preg_match('/^[0-9]{11}$/', $mobile_number)){
        echo "<script>alert('Mobile number must be exactly 11 digits.'); window.history.back();</script>";
        exit();
    }

    /* payment_methods table
       1 = GCash
       2 = Cash
       3 = Bank Transfer
    */
    $payment_method_id = 1; // GCash

    /* GET BILL */
    $bill_stmt = $conn->prepare(
        "SELECT * FROM bills WHERE id=? AND user_id=?"
    );

    $bill_stmt->bind_param("ii",$bill_id,$user_id);
    $bill_stmt->execute();

    $bill = $bill_stmt->get_result()->fetch_assoc();

    if(!$bill){
        echo "Invalid bill.";
        exit();
    }

    $amount = $bill['amount'];
    $reference = "REF".rand(10000,99999);

    /* INSERT PAYMENT */
    $pay_stmt = $conn->prepare("
        INSERT INTO payments
        (user_id,bill_id,payment_method_id,amount,reference_number)
        VALUES(?,?,?,?,?)
    ");

    $pay_stmt->bind_param(
        "iiiis",
        $user_id,
        $bill_id,
        $payment_method_id,
        $amount,
        $reference
    );

    $pay_stmt->execute();

    /* UPDATE BILL STATUS */
    $update_stmt = $conn->prepare(
        "UPDATE bills SET status='Paid' WHERE id=?"
    );

    $update_stmt->bind_param("i",$bill_id);
    $update_stmt->execute();

    header("Location: view_bill.php");
    exit();
}


/* CHECK PARAMETERS */
if (!isset($_GET['method']) || !isset($_GET['bill_id'])) {
    echo "Error: Payment method or bill ID missing.";
    exit();
}

$method = $_GET['method'];
$bill_id = $_GET['bill_id'];


/* GET BILL */
$bill_query = $conn->prepare(
    "SELECT * FROM bills WHERE id=? AND user_id=?"
);

$bill_query->bind_param("ii",$bill_id,$user_id);
$bill_query->execute();

$bill = $bill_query->get_result()->fetch_assoc();


/* GET USER */
$user_query = $conn->prepare(
    "SELECT name,email FROM users WHERE id=?"
);

$user_query->bind_param("i",$user_id);
$user_query->execute();

$user = $user_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $method; ?> Payment</title>
<link rel="stylesheet" href="assets/style.css">
</head>



<header class="header">

    <!-- CLICKABLE LOGO -->
    <a href="home.php" class="logo-area" style="text-decoration:none; color:white;">
        <img src="assets/logo.png" class="logo" alt="Logo">
        <div>
            <h1>ANGELES ELECTRIC POWER PORTAL</h1>
            <span>Powering Your Future</span>
        </div>
    </a>

    <div class="nav-links">

        <a href="home.php"
           class="<?= ($current_page == 'home.php') ? 'active' : '' ?>">
           Home
        </a>

        <span class="divider">|</span>

        <a href="view_bill.php"
           class="<?= ($current_page == 'view_bill.php') ? 'active' : '' ?>">
           View Bill
        </a>

        <span class="divider">|</span>

        <a href="pay_bill.php"
           class="<?= ($current_page == 'pay_bill.php') ? 'active' : '' ?>">
           Pay Bill
        </a>

        <!-- ACCOUNT ICON -->
        <a href="account.php">
            <div class="account-icon">👤</div>
        </a>

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
<h3><?php echo $bill['billing_month']; ?></h3>
</div>

</div>


<!-- RIGHT SIDE -->
<div class="card-form">

<form method="POST">

<input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">

<label>Mobile Number</label>

<input type="text"
name="mobile_number"
placeholder="Enter mobile number linked to your e-wallet account"
pattern="[0-9]{11}"
maxlength="11"
title="Mobile number must be exactly 11 digits"
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

