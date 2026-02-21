<?php
include 'config/database.php';

if(isset($_POST['signup'])){

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob      = $_POST['dob'];
    $address  = $_POST['address'];
    $contact  = $_POST['contact_no'];
    $account  = rand(100000000, 999999999);

    $stmt = $conn->prepare("INSERT INTO users(account_number,name,email,password,dob,address,contact_no) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $account, $name, $email, $password, $dob, $address, $contact);
    $stmt->execute();

    $_SESSION['user_id'] = $stmt->insert_id;
    header("Location: home.php");
    exit();
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
        <h2>Create Account</h2>

        <form method="POST">
            <input type="text"     name="name"       placeholder="NAME"           required>
            <input type="email"    name="email"       placeholder="EMAIL"          required>
            <input type="password" name="password"    placeholder="PASSWORD"       required>

            <div class="auth-field">
                <label class="auth-label">DATE OF BIRTH</label>
                <input type="date" name="dob" required>
            </div>

            <input type="text" name="address"    placeholder="ADDRESS"         required>
            <input type="text" name="contact_no" placeholder="CONTACT NUMBER"  required>

            <button type="submit" name="signup" class="btn-auth">SIGN UP</button>
        </form>

        <p class="auth-link">
            Already registered?
            <a href="login.php">Log in</a>
        </p>
    </div>

</div>

</body>
</html>
