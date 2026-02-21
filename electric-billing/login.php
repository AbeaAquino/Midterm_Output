<?php
include 'config/database.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        if($password === $user['password']){
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
    <div class="logo-area">
        <a href="index.php" class="logo-area" style="text-decoration:none; color:white;">
            <img src="assets/logo.png" class="logo" alt="Logo">
            <div>
                <h1>ANGELES ELECTRIC POWER PORTAL</h1>
                <span>Powering Your Future</span>
            </div>
        </a>
    </div>
</header>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-avatar"></div>
        <h2>Log In</h2>

        <form method="POST">
            <input type="email" name="email" placeholder="EMAIL ADDRESS" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <button type="submit" name="login" class="btn-auth">LOG IN</button>
        </form>

        <p class="auth-link">
            Don't have an account?
            <a href="signup.php">Sign up</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
