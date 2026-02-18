<?php
include 'config/database.php';

if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $account = rand(100000000,999999999);

    $stmt = $conn->prepare("INSERT INTO users(account_number,name,email,password,dob) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$account,$name,$email,$password,$dob);
    $stmt->execute();

    $_SESSION['user_id'] = $stmt->insert_id;
    header("Location: home.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-card">
        <div class="auth-avatar"></div>
        <h2>Create New Account</h2>

        <form method="POST">
            <input type="text" name="name" placeholder="NAME" required>
            <input type="email" name="email" placeholder="EMAIL" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <input type="date" name="dob" required>

            <button type="submit" name="signup" class="btn-auth">SIGN UP</button>
        </form>

        <p class="auth-link">
            Already Registered?
            <a href="login.php">Login</a>
        </p>
    </div>

</div>

</body>
</html>
