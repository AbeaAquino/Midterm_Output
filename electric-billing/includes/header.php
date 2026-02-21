<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="header">

    <!-- CLICKABLE LOGO -->
    <a href="home.php" class="logo-area" style="text-decoration:none; color:white;">
        <img src="assets/logo.png" class="logo" alt="Logo">
        <div>
            <h1>ANGELES ELECTRIC POWER PORTAL</h1>
            <span>Powering Your Future</span>
        </div>
    </a>

    <div class="nav-links">

        <a href="home.php"
           class="<?= ($current_page == 'home.php') ? 'active' : '' ?>">
           Home
        </a>

        <span class="divider">|</span>

        <a href="view_bill.php"
           class="<?= ($current_page == 'view_bill.php') ? 'active' : '' ?>">
           View Bill
        </a>

        <span class="divider">|</span>

        <a href="pay_bill.php"
           class="<?= ($current_page == 'pay_bill.php') ? 'active' : '' ?>">
           Pay Bill
        </a>

        <!-- ACCOUNT ICON -->
        <a href="account.php">
            <div class="account-icon">👤</div>
        </a>

    </div>
</header>
