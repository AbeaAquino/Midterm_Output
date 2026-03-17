<?php
include 'config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

/* ============================
   CREATE BILL (WITH DUPLICATE CHECK)
============================ */
if (isset($_POST['create_bill'])) {

    $user_id  = $_POST['user_id'];
    $month    = $_POST['billing_month'];
    $amount   = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $status   = "Unpaid";

    /* CHECK IF BILL EXISTS */
    $check = $conn->prepare("
        SELECT id FROM bills
        WHERE user_id=? AND billing_month=?
    ");

    $check->bind_param("is", $user_id, $month);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        echo "<script>alert('This user already has a bill for this month.');</script>";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO bills (user_id, billing_month, amount, due_date, status)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("isdss", $user_id, $month, $amount, $due_date, $status);
        $stmt->execute();

        echo "<script>alert('Bill created successfully');</script>";
    }
}

/* ============================
   FETCH USERS
============================ */
$users = $conn->query("
    SELECT id, name, account_number
    FROM users
");

/* ============================
   FETCH BILLS
============================ */
$bills = $conn->query("
    SELECT bills.*, users.name, users.account_number
    FROM bills
    JOIN users ON bills.user_id = users.id
    ORDER BY bills.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Bills</title>
<link rel="stylesheet" href="assets/style.css">

<!-- ICON LIBRARY -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<header class="header">
<div class="logo-area">
<img src="assets/logo.png" class="logo">

<div>
<h1>ANGELES ELECTRIC POWER PORTAL</h1>
<span>Powering Your Future</span>
</div>
</div>

<div class="nav-links">
<a href="manage_bills.php" class="active">Manage Bills</a>
<span class="divider">|</span>

<a href="manage_advisories.php">Advisories</a>
<span class="divider">|</span>

<a href="logout.php">Log Out</a>
</div>
</header>


<div class="dashboard-wrapper">

<div class="manage-container">

<!-- =========================
     CREATE BILL SECTION
========================= -->
<div class="manage-left">

<h2>Create New Bill</h2>

<form method="POST">

<label>Select User</label>

<select name="user_id" required>

<?php while ($user = $users->fetch_assoc()) { ?>

<option value="<?php echo $user['id']; ?>">
<?php echo $user['name']; ?> - <?php echo $user['account_number']; ?>
</option>

<?php } ?>

</select>


<label>Billing Month & Year</label> 
<input type="text" name="billing_month" placeholder="July 2026" required>

<label>Amount</label>
<input type="number" step="0.01" name="amount" required>

<label>Due Date</label>
<input type="date" name="due_date" required>

<button type="submit" name="create_bill" class="btn-confirm">
CREATE BILL
</button>

</form>

</div>


<!-- =========================
     BILLS TABLE
========================= -->
<div class="manage-right">

<h2>All Bills</h2>

<div style="display:flex; justify-content:center; margin-top:18px; margin-bottom:18px;">
<input
    type="text"
    id="searchBills"
    placeholder="Search Users"
    onkeyup="filterBills()"
    style="
        width: 55%;
        padding: 11px 22px;
        border: 1px solid #e0e0e0;
        border-radius: 999px;
        font-size: 14px;
        color: #555;
        background: #fafafa;
        outline: none;
        box-sizing: border-box;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    "
>
</div>

<table class="bill-table">

<tr>
<th>Name</th>
<th>Account Number</th>
<th>Month</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while ($row = $bills->fetch_assoc()) { ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['account_number']; ?></td>

<td>
<?php echo $row['billing_month']; ?>
</td>

<td>
₱<?php echo number_format($row['amount'], 2); ?>
</td>

<td>

<?php if ($row['status'] == 'Paid') { ?>

<span class="status-paid">Paid</span>

<?php } else { ?>

<span class="status-unpaid">Unpaid</span>

<?php } ?>

</td>

<td>

<a href="edit_bill.php?id=<?php echo $row['id']; ?>"
   title="Edit Bill"
   style="color:#90a4ae; margin-right:8px; font-size:15px; text-decoration:none; transition:color 0.2s;"
   onmouseover="this.style.color='#1e5fa8'"
   onmouseout="this.style.color='#90a4ae'">
    <i class="fa-regular fa-pen-to-square"></i>
</a>

<a href="delete_bill.php?id=<?php echo $row['id']; ?>"
   onclick="return confirm('Delete this bill?')"
   title="Delete Bill"
   style="color:#90a4ae; font-size:15px; text-decoration:none; transition:color 0.2s;"
   onmouseover="this.style.color='#e53935'"
   onmouseout="this.style.color='#90a4ae'">
    <i class="fa-regular fa-trash-can"></i>
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>


<script>
function filterBills() {
    const input = document.getElementById('searchBills').value.toLowerCase();
    const rows = document.querySelectorAll('.bill-table tr:not(:first-child)');
    rows.forEach(row => {
        const name = row.cells[0]?.textContent.toLowerCase() || '';
        row.style.display = name.includes(input) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>
