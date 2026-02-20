<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In (Admin)</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-image">

<header class="header">
    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC CORPORATION</h1>
            <span>Powering Your Future</span>
        </div>
    </div>
</header>

<section class="login-section">
    <div class="login-card">
        <h2>Log In (Admin)</h2>

        <form action="admin_dashboard.php" method="POST">
            <div class="form-group">
                <label for="admin-id">Admin ID</label>
                <input type="text" id="admin-id" name="admin_id" required placeholder="Enter Admin ID">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn-login">Log In</button>
        </form>
    </div>
</section>

<footer class="footer">
    © 2026 Angeles Electric Corporation
</footer>

</body>
</html>
