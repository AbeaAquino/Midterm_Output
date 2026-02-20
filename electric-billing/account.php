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
    <style>
        /* ===== ACCOUNT DROPDOWN ===== */
        .account-wrapper-nav {
            position: relative;
            margin-left: 15px;
        }

        .account-icon {
            cursor: pointer;
            position: relative;
            z-index: 200;
        }

        .account-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background: white;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            min-width: 200px;
            z-index: 999;
            overflow: hidden;
            animation: dropIn 0.2s ease;
        }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Show when active */
        .account-wrapper-nav.open .account-dropdown {
            display: block;
        }

        /* Dropdown header */
        .dropdown-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
        }

        .dropdown-avatar {
            width: 36px;
            height: 36px;
            background: #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dropdown-avatar svg {
            width: 20px;
            height: 20px;
        }

        .dropdown-header-info h4 {
            font-size: 13px;
            font-weight: 700;
            color: #222;
            margin: 0;
        }

        .dropdown-header-info span {
            font-size: 11px;
            color: #888;
        }

        /* Dropdown links — must override .nav-links a white color */
        .account-dropdown .dropdown-item,
        .account-dropdown .dropdown-item:link,
        .account-dropdown .dropdown-item:visited,
        .account-dropdown .dropdown-item:hover,
        .account-dropdown .dropdown-item:active {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #333 !important;
            text-decoration: none !important;
            border-bottom: none !important;
            transition: background 0.2s;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
        }

        .account-dropdown .dropdown-item:hover {
            background: #f4f6fb !important;
            color: #333 !important;
        }

        .account-dropdown .dropdown-item svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .account-dropdown .dropdown-item.logout,
        .account-dropdown .dropdown-item.logout:link,
        .account-dropdown .dropdown-item.logout:visited {
            color: #e53935 !important;
            border-top: 1px solid #eee;
        }

        .account-dropdown .dropdown-item.logout:hover {
            background: #fff5f5 !important;
            color: #e53935 !important;
        }
    </style>
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
        <a href="view_bill.php">View Bill</a>
        <span class="divider">|</span>
        <a href="pay_bill.php">Pay Bill</a>
        <span class="divider">|</span>

        <!-- ACCOUNT ICON WITH DROPDOWN -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="account-wrapper-nav" id="accountNav">
            <div class="account-icon" onclick="toggleDropdown()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#173a7a">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>

            <div class="account-dropdown">

                <!-- User info at top -->
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#999">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                    <div class="dropdown-header-info">
                        <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                        <span><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                </div>

                <!-- Dashboard -->
                <a href="account.php" class="dropdown-item">
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

                <!-- Settings -->
                <a href="settings.php" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#555">
                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.49.49 0 00-.6-.22l-2.39.96a6.96 6.96 0 00-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96a.49.49 0 00-.6.22L2.74 8.87a.48.48 0 00.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.6.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.23.09.48 0 .6-.22l1.92-3.32a.49.49 0 00-.12-.61l-2.01-1.58zM12 15.6a3.6 3.6 0 110-7.2 3.6 3.6 0 010 7.2z"/>
                    </svg>
                    Settings
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
        <?php endif; ?>
    </div>
</header>

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content">

    <div class="account-wrapper">

        <div class="account-card">

            <!-- USER INFO -->
            <div class="account-user">
                <div class="account-avatar"></div>
                <div>
                    <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <!-- BLUE STRIP -->
            <div class="account-top">
                Statement Date: <?php echo date("F d, Y"); ?><br>
                Account Number: <?php echo htmlspecialchars($user['account_number']); ?>
            </div>

            <!-- DETAILS -->
            <div class="account-details">
                <strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?><br>
                <strong>Contact No:</strong> <?php echo htmlspecialchars($user['contact_no']); ?>
            </div>

            <!-- AMOUNT SECTION -->
            <div class="amount-section">
                <div>
                    <div>Amount Due</div>

                    <div class="amount-box">
                        ₱ 
                        <?php 
                            if($due){
                                echo number_format($due['amount'], 2);
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
                            echo htmlspecialchars($due['due_date']);
                        } else {
                            echo "No Due";
                        }
                    ?>
                    </strong>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

<script>
    function toggleDropdown() {
        document.getElementById('accountNav').classList.toggle('open');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const nav = document.getElementById('accountNav');
        if (nav && !nav.contains(e.target)) {
            nav.classList.remove('open');
        }
    });
</script>

</body>
</html>
