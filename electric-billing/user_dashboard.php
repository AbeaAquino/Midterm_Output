<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: user_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Angeles Electric</title>
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
</header>

<h1>Welcome, User</h1>
<!-- User Dashboard content goes here -->

<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
