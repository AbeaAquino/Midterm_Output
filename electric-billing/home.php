<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Angeles Electric</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <div class="yellow-line"></div>
        <h2>Powering Your Future</h2>
        <p>
            Powering your home with reliable electric service,
            easy online billing, and timely service updates—all in one place.
        </p>
    </div>

    <!-- FEATURE CARDS -->
    <div class="feature-cards">

        <div class="feature-card">
            <h3>View Bills</h3>
            <p>View your billing history and download official receipts instantly.</p>
            <a href="view_bill.php" class="btn-getstarted">View</a>
        </div>

        <div class="feature-card">
            <h3>Online Payment</h3>
            <p>Pay your electric bills securely using GCash, card, or bank transfer.</p>
            <a href="pay_bill.php" class="btn-getstarted">Pay</a>
        </div>

        <div class="feature-card">
            <h3>Advisories</h3>
            <p>Check official notices on billing due dates and interruptions.</p>
            <button onclick="scrollToAdvisories()" class="btn-getstarted">Check</button>
        </div>

    </div>

    <div class="wave wave1"></div>
    <div class="wave wave2"></div>

</section>

<!-- ADVISORIES SECTION -->
<section class="advisories-section" id="advisories">

    <h2 class="adv-title">Advisories</h2>
    <p class="adv-sub">
        Updates and notices related to your electricity bill
    </p>

    <div class="advisory-cards">

        <?php
        $ads = $conn->query("SELECT * FROM advisories ORDER BY created_at DESC LIMIT 3");
        while($row = $ads->fetch_assoc()){
        ?>

        <div class="advisory-card">
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <button class="btn-view">View Details</button>
        </div>

        <?php } ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script>
function scrollToAdvisories(){
    document.getElementById('advisories').scrollIntoView({
        behavior: 'smooth'
    });
}
</script>
</body>
</html>
