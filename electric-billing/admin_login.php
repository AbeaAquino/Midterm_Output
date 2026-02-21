<?php
include 'config/database.php';

if(isset($_POST['admin_id'])){

    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    if(!ctype_digit($admin_id)){
    echo "<script>alert('Admin ID must be numbers only');</script>";
    exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE admin_id=?");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $admin = $result->fetch_assoc();

        if($password === $admin['password']){
            $_SESSION['admin_logged_in'] = $admin['id'];
            header("Location: manage_bills.php");
            exit();
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    } else {
        echo "<script>alert('Admin not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In (Admin)</title>
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

<div class="auth-container">

    <div class="auth-card">
        <h2>Log In</h2>

        <form method="POST">
            <input type="text" name="admin_id" placeholder="ADMIN ID" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <button type="submit" class="btn-auth">LOG IN</button>
        </form>
    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
