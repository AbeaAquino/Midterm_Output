<?php
include 'config/database.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id,password FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php");
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

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
            Don’t have an account?
            <a href="signup.php">Sign up</a>
        </p>
    </div>

</div>

</body>
</html>
