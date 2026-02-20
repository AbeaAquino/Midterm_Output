<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angeles Electric Corporation</title>
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
        <!-- Only show Login and Sign Up buttons when logged out -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn-nav">Log In</a>
            <a href="signup.php" class="btn-nav btn-signup">Sign Up</a>
        <?php endif; ?>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="yellow-line"></div>
        <h2>Powering Your Future</h2>
        <p>
            Powering your home with reliable electric service,
            easy online billing, and timely service updates—all in one place.
        </p>
        <a href="access_selection.php" class="btn-getstarted">Get Started</a>  <!-- Link to Access Selection Page -->
    </div>

    <!-- HOME FEATURE CARDS -->
    <div class="feature-cards">
        <div class="feature-card">
            <img src="assets/icon-bill.png">
            <h3>View Bills</h3>
            <p>View your billing history and download official receipts instantly.</p>
        </div>

        <div class="feature-card">
            <img src="assets/icon-payment.png">
            <h3>Online Payment</h3>
            <p>Pay your electric bills securely using GCash, card, or bank transfer.</p>
        </div>

        <div class="feature-card">
            <img src="assets/icon-advisory.png">
            <h3>Advisories</h3>
            <p>Check official notices on billing due dates and interruptions.</p>
        </div>
    </div>

    <!-- WAVES -->
    <div class="wave wave1"></div>
    <div class="wave wave2"></div>
</section>

</body>
</html>

