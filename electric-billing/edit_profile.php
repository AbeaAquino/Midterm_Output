```php
<?php
include 'config/database.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

$user = $conn->query("
    SELECT * FROM users
    WHERE id = $user_id
")->fetch_assoc();


if(isset($_POST['update_profile'])){

    $email   = $_POST['email'];
    $address = $_POST['address'];
    $contact = $_POST['contact_no'];

    $stmt = $conn->prepare("
        UPDATE users
        SET email=?, address=?, contact_no=?
        WHERE id=?
    ");

    $stmt->bind_param("sssi",$email,$address,$contact,$user_id);
    $stmt->execute();

    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Profile</title>
<link rel="stylesheet" href="assets/style.css">

</head>

<body>

<?php include 'includes/header.php'; ?>


<div class="glass-wrapper">

<div class="glass-card">

<div class="glass-top">
Edit Profile
</div>


<div class="glass-content">

<form method="POST">


<label>Email Address</label>

<input type="email"
name="email"
value="<?php echo $user['email']; ?>"
required>


<label>Address</label>

<input type="text"
name="address"
value="<?php echo $user['address']; ?>"
required>


<label>Contact Number</label>

<input type="text"
name="contact_no"
value="<?php echo $user['contact_no']; ?>"
required>


<div class="center-btn">

<button type="submit"
name="update_profile"
class="btn-confirm">

UPDATE PROFILE

</button>

</div>

</form>

</div>

</div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
```
