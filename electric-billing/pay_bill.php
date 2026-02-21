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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Bill</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- PAGE CONTENT WRAPPER (Fixes Footer Issue) -->
<div class="page-content">

    <div class="glass-wrapper">

        <div class="glass-card">

            <!-- USER INFO -->
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

                <form method="POST" action="">

                    <!-- MONTH -->
                    <div class="form-group">
                        <label>Month</label>

                        <select name="bill_id" required <?php if($bills->num_rows == 0) echo 'disabled'; ?>>

                            <?php if($bills->num_rows > 0){ ?>

                                <?php while($bill = $bills->fetch_assoc()){ ?>
                                    <option value="<?php echo $bill['id']; ?>">
                                        <?php echo $bill['month']; ?> 
                                        
                                    </option>
                                <?php } ?>

                            <?php } else { ?>

                                <option selected>No unpaid bills available</option>

                            <?php } ?>

                        </select>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div class="form-group">
                        <label>Payment Method</label>

                        <div class="method-row">

                            <!-- E-Wallet Payment -->
                            <label class="payment-box">
                                <input type="radio" name="method" value="GCash" 
                                <?php if($bills->num_rows == 0) echo 'disabled'; ?> required>
                                <img src="assets/gcash.png">
                                <span>E-Wallet</span>
                            </label>

                            <!-- Credit/Debit Card Payment -->
                            <label class="payment-box">
                                <input type="radio" name="method" value="Card" 
                                <?php if($bills->num_rows == 0) echo 'disabled'; ?> required>
                                <img src="assets/mastercard.png">
                                <span>Credit/Debit Card</span>
                            </label>

                        </div>
                    </div>

                    <!-- CONFIRM BUTTON -->
                    <div class="center-btn">
                        <button type="submit" name="pay_method" class="btn-confirm" 
                        <?php if($bills->num_rows == 0) echo 'disabled'; ?>>
                            CONFIRM
                        </button>
                    </div>

                </form>

                <?php 
                // Handle payment method and redirect accordingly
                if(isset($_POST['pay_method'])){
                    $method = $_POST['method'];
                    if($method == 'GCash'){
                        // Redirect to E-Wallet Payment Page (pay_wallet.php)
                        header("Location: pay_wallet.php?bill_id=".$_POST['bill_id']."&method=GCash");
                        exit();
                    } else if($method == 'Card'){
                        // Redirect to Card Payment Page (pay_card.php)
                        header("Location: pay_card.php?bill_id=".$_POST['bill_id']."&method=Card");
                        exit();
                    }
                }
                ?>

            </div>

        </div>

    </div>

</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

</body>
</html>

