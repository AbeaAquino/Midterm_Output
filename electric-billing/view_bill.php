<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* GET USER INFO */
$user_query = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

/* GET BILLS */
$bills_query = $conn->prepare("SELECT * FROM bills WHERE user_id=? ORDER BY created_at DESC");
$bills_query->bind_param("i", $user_id);
$bills_query->execute();
$bills = $bills_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Account</title>
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

    <div class="nav-links">
        <a href="home.php">Home</a>
        <span class="divider">|</span>
        <a href="view_bill.php" class="active">View Bill</a>
        <span class="divider">|</span>
        <a href="pay_bill.php">Pay Bill</a>
        <span class="divider">|</span>

        <!-- ACCOUNT ICON -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="account.php" style="line-height:0;">
                <div class="account-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#173a7a">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>
            </a>
        <?php endif; ?>
    </div>
</header>

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content">

    <div class="statement-wrapper">

        <div class="statement-card">

            <!-- USER INFO -->
            <div class="statement-user">
                <div class="statement-avatar"></div>
                <div>
                    <h3><?php echo $user['name']; ?></h3>
                    <span><?php echo $user['email']; ?></span>
                </div>
            </div>

            <!-- BLUE STRIP -->
            <div class="statement-top">
                Statement Date: <?php echo date("F d, Y"); ?>
            </div>

            <!-- TABLE -->
            <table class="statement-table">

                <tr>
                    <th>BILL STATEMENTS</th>
                    <th>BALANCE</th>
                    <th>STATUS</th>
                </tr>

                <?php if($bills->num_rows > 0){ ?>

                    <?php while($bill = $bills->fetch_assoc()){ ?>

                        <tr>
                            <td><?php echo $bill['month']; ?></td>

                            <td class="<?php echo $bill['status']=='Unpaid' ? 'amount-unpaid' : 'amount-paid'; ?>">
                                ₱ <?php echo number_format($bill['amount'],2); ?>
                            </td>

                            <td>
                                <?php if($bill['status']=='Paid'){ ?>
                                    <span class="badge-paid">Paid</span>
                                <?php } else { ?>
                                    <span class="badge-unpaid">Unpaid</span>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="3" style="text-align:center; padding:20px;">
                            No bill records available.
                        </td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
