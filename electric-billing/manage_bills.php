<?php
include 'config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

/* INSERT BILL */
if(isset($_POST['create_bill'])){

    $user_id = $_POST['user_id'];
    $month = $_POST['month'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $status = "Unpaid";

    $stmt = $conn->prepare("INSERT INTO bills (user_id, month, amount, due_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $month, $amount, $due_date, $status);
    $stmt->execute();
}

/* FETCH USERS */
$users = $conn->query("SELECT id, name, account_number FROM users");

/* FETCH BILLS */
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
        <a href="logout.php">Log Out</a>
    </div>
</header>

<div class="dashboard-wrapper">

    <div class="manage-container">

    <!-- LEFT SIDE -->
    <div class="manage-left">
        <h2>Create New Bill</h2>

        <form method="POST">

            <label>Select User</label>
            <select name="user_id" required>
                <?php while($user = $users->fetch_assoc()) { ?>
                    <option value="<?php echo $user['id']; ?>">
                        <?php echo $user['name']; ?> - <?php echo $user['account_number']; ?>
                    </option>
                <?php } ?>
            </select>

            <label>Billing Month & Year</label>
            <input type="text" name="month" placeholder="July 2026" required>

            <label>Amount</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Due Date</label>
            <input type="date" name="due_date" required>

            <button type="submit" name="create_bill" class="btn-confirm">
                CREATE BILL
            </button>
        </form>
    </div>

    <!-- RIGHT SIDE -->
    <div class="manage-right">
        <h2>All Bills</h2>

        <table class="bill-table">
            <tr>
                <th>Name</th>
                <th>Account Number</th>
                <th>Month</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>

            <?php while($row = $bills->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['account_number']; ?></td>
                <td><?php echo $row['month']; ?></td>
                <td>₱<?php echo number_format($row['amount'],2); ?></td>
                <td>
                <?php if($row['status'] == 'Paid'){ ?>
                    <span class="status-paid">Paid</span>
                <?php } else { ?>
                    <span class="status-unpaid">Unpaid</span>
                <?php } ?>
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
