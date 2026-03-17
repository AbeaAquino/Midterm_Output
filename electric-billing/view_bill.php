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

/* GET FIRST BILL FOR STATEMENT DATE */
$first_bill = $bills->fetch_assoc();
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

<div class="page-content">

<div class="statement-wrapper">

<div class="statement-card">

<div class="statement-user">

<div class="account-avatar">
<img src="assets/user.png" alt="User">
</div>

<div>
<h3><?php echo $user['name']; ?></h3>
<span><?php echo $user['email']; ?></span>
</div>

</div>


<div class="statement-top">
Statement Date:
<?php
if($first_bill){
echo $first_bill['billing_month'];
}else{
echo "No Bill Yet";
}
?>
</div>


<table class="statement-table">

<tr>
<th>BILL STATEMENTS</th>
<th>BALANCE</th>
<th>STATUS</th>
</tr>


<?php if($first_bill){ ?>

<tr>

<td><?php echo $first_bill['billing_month']; ?></td>

<td class="<?php echo $first_bill['status']=='Unpaid' ? 'amount-unpaid' : 'amount-paid'; ?>">
₱ <?php echo number_format($first_bill['amount'],2); ?>
</td>

<td>
<?php if($first_bill['status']=='Paid'){ ?>
<span class="badge-paid">Paid</span>
<?php } else { ?>
<span class="badge-unpaid">Unpaid</span>
<?php } ?>
</td>

</tr>


<?php while($bill = $bills->fetch_assoc()){ ?>

<tr>

<td><?php echo $bill['billing_month']; ?></td>

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
<td colspan="3" style="text-align:center;padding:20px;">
No bill records available.
</td>
</tr>

<?php } ?>


</table>

</div>

</div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>

