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

<?php include 'includes/header.php'; ?>

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
<?php include 'includes/footer.php'; ?>

</body>
</html>
