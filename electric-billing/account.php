<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* GET USER INFO */
$user_query = $conn->prepare("SELECT * FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

/* GET UNPAID BILL (Amount Due) */
$due_query = $conn->prepare("SELECT * FROM bills WHERE user_id=? AND status='Unpaid' LIMIT 1");
$due_query->bind_param("i", $user_id);
$due_query->execute();
$due = $due_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- HEADER -->
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
        <a href="pay_bill.php">Pay Bill</a>
        <div class="account-icon">👤</div>
    </div>
</header>

<div class="account-wrapper">

    <div class="account-card">

        <!-- USER INFO -->
        <div class="account-user">
            <div class="account-avatar"></div>
            <div>
                <h3><?php echo $user['name']; ?></h3>
                <span><?php echo $user['email']; ?></span>
            </div>
        </div>

        <!-- BLUE STRIP -->
        <div class="account-top">
            Statement Date: <?php echo date("F d, Y"); ?><br>
            Account Number: <?php echo $user['account_number']; ?>
        </div>

        <!-- DETAILS -->
        <div class="account-details">
            <strong>Address:</strong> <?php echo $user['address']; ?><br>
            <strong>Contact No:</strong> <?php echo $user['contact_no']; ?>
        </div>

        <!-- AMOUNT SECTION -->
        <div class="amount-section">
            <div>
                <div>Amount Due</div>

                <div class="amount-box">
                    ₱ 
                    <?php 
                        if($due){
                            echo number_format($due['amount'],2);
                        } else {
                            echo "0.00";
                        }
                    ?>
                </div>

                <a href="view_bill.php">
                    <button class="viewbill-btn">View Bill</button>
                </a>
            </div>

            <div class="due-box">
                Due Date
                <strong>
                <?php 
                    if($due){
                        echo $due['due_date'];
                    } else {
                        echo "No Due";
                    }
                ?>
                </strong>
            </div>
        </div>

    </div>

</div>

<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
