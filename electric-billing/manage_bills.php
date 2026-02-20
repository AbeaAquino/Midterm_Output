<?php 
include 'config/database.php';
include 'includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_logged_in'];

// Fetch all users and their billing records
$bills_query = $conn->prepare("SELECT * FROM bills ORDER BY created_at DESC");
$bills_query->execute();
$bills = $bills_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bills - Angeles Electric</title>
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
        <a href="admin_dashboard.php">Dashboard</a>
        <span class="divider">|</span>
        <a href="manage_bills.php" class="active">Manage Bills</a>
        <span class="divider">|</span>
        <a href="logout.php">Log Out</a>
    </div>
</header>

<!-- MANAGE BILLS CONTENT -->
<section class="manage-bills">
    <h2>Manage Bills</h2>

    <!-- Billing Information Form -->
    <form action="update_bill.php" method="POST">
        <label for="bill_id">Select Bill</label>
        <select name="bill_id" required>
            <?php while($bill = $bills->fetch_assoc()){ ?>
                <option value="<?php echo $bill['id']; ?>">
                    <?php echo $bill['month']; ?> - ₱<?php echo number_format($bill['amount'], 2); ?>
                </option>
            <?php } ?>
        </select>

        <label for="status">Payment Status</label>
        <select name="status" required>
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
        </select>

        <button type="submit" class="btn-action">Update Bill</button>
    </form>

    <!-- Bills List -->
    <h3>Bills List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Account Number</th>
            <th>Amount Due</th>
            <th>Status</th>
        </tr>
        <?php 
        $bills_query->execute();
        $result = $bills_query->get_result();
        while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['account_number']; ?></td>
            <td>₱<?php echo number_format($row['amount'], 2); ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</section>

<!-- FOOTER -->
<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
