
<?php
include 'config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_bills.php");
    exit();
}

$id = $_GET['id'];

/* GET BILL */
$stmt = $conn->prepare("SELECT * FROM bills WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bill = $stmt->get_result()->fetch_assoc();

/* UPDATE BILL */
if (isset($_POST['update_bill'])) {

    $month = $_POST['billing_month'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];

    $update = $conn->prepare("
        UPDATE bills
        SET billing_month=?, amount=?, due_date=?
        WHERE id=?
    ");

    $update->bind_param("sdsi", $month, $amount, $due_date, $id);
    $update->execute();

    header("Location: manage_bills.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Bill</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<header class="header">

    <div class="logo-area">
        <img src="assets/logo.png" class="logo">

        <div>
            <h1>ANGELES ELECTRIC CORPORATION</h1>
            <span>Powering Your Future</span>
        </div>
    </div>

    <div class="nav-links">
        <a href="manage_bills.php" class="active">Manage Bills</a>
        <span class="divider">|</span>
        <a href="manage_advisories.php">Advisories</a>
        <span class="divider">|</span>
        <a href="logout.php">Logout</a>
    </div>

</header>

<div class="dashboard-wrapper">

    <div class="manage-container">

        <div class="manage-left">

            <h2>Edit Bill</h2>

            <form method="POST">

                <label>Billing Month & Year</label>

                <input type="text"
                       name="billing_month"
                       value="<?php echo $bill['billing_month']; ?>"
                       required>

                <label>Amount</label>

                <input type="number"
                       step="0.01"
                       name="amount"
                       value="<?php echo $bill['amount']; ?>"
                       required>

                <label>Due Date</label>

                <input type="date"
                       name="due_date"
                       value="<?php echo $bill['due_date']; ?>"
                       required>

                <button type="submit"
                        name="update_bill"
                        class="btn-confirm">

                    UPDATE BILL

                </button>

            </form>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
