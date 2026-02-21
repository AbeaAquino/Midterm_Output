<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* GET USER INFO */
$user_query = $conn->prepare("SELECT * FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

/* GET UNPAID BILL */
$due_query = $conn->prepare("SELECT * FROM bills WHERE user_id=? AND status='Unpaid' LIMIT 1");
$due_query->bind_param("i", $user_id);
$due_query->execute();
$due = $due_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-image">

<?php include 'includes/header.php'; ?>

<!-- ACCOUNT DROPDOWN (ONLY IN ACCOUNT PAGE) -->
<div class="account-wrapper-nav" id="accountNav">

    <div class="account-dropdown">

        <!-- USER INFO -->
        <div class="dropdown-header">
            <div class="dropdown-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#999">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>
            <div class="dropdown-header-info">
                <h4><?= htmlspecialchars($user['name']); ?></h4>
                <span><?= htmlspecialchars($user['email']); ?></span>
            </div>
        </div>

        <!-- Dashboard -->
        <a href="home.php" class="dropdown-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#555">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            Dashboard
        </a>

        <!-- Payment History -->
        <a href="view_bill.php" class="dropdown-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#555">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a3 3 0 110 6 3 3 0 010-6zm6 13H6v-.6c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/>
            </svg>
            Payment History
        </a>

        <!-- Log Out -->
        <a href="logout.php" class="dropdown-item logout">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e53935">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4a2 2 0 00-2 2v14a2 2 0 002 2h8v-2H4V5z"/>
            </svg>
            Log Out
        </a>

    </div>
</div>

<!-- PAGE CONTENT -->
<div class="page-content">

    <div class="account-wrapper">
        <div class="account-card">

            <div class="account-user">
                <div class="account-avatar"></div>
                <div>
                    <h3><?= htmlspecialchars($user['name']); ?></h3>
                    <span><?= htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="account-top">
                Statement Date: <?= date("F d, Y"); ?><br>
                Account Number: <?= htmlspecialchars($user['account_number']); ?>
            </div>

            <div class="account-details">
                <strong>Address:</strong> <?= htmlspecialchars($user['address']); ?><br>
                <strong>Contact No:</strong> <?= htmlspecialchars($user['contact_no']); ?>
            </div>

            <div class="amount-section">
                <div>
                    <div>Amount Due</div>
                    <div class="amount-box">
                        ₱ <?= $due ? number_format($due['amount'],2) : "0.00"; ?>
                    </div>
                    <a href="view_bill.php">
                        <button class="viewbill-btn">View Bill</button>
                    </a>
                </div>

                <div class="due-box">
                    Due Date
                    <strong>
                        <?= $due ? htmlspecialchars($due['due_date']) : "No Due"; ?>
                    </strong>
                </div>
            </div>

        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const icon = document.querySelector(".account-icon");
    const dropdown = document.querySelector(".account-dropdown");

    if(icon && dropdown){
        icon.addEventListener("click", function(e){
            e.preventDefault();
            dropdown.style.display =
                dropdown.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function(e){
            if(!dropdown.contains(e.target) && !icon.contains(e.target)){
                dropdown.style.display = "none";
            }
        });
    }

});
</script>

</body>
</html>
