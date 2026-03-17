
<?php
include 'config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: manage_advisories.php");
    exit();
}

$id = $_GET['id'];

/* GET ADVISORY */
$stmt = $conn->prepare("SELECT * FROM advisories WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$advisory = $stmt->get_result()->fetch_assoc();

/* UPDATE ADVISORY */
if(isset($_POST['update_advisory'])){

    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $area = $_POST['area'];
    $notes = $_POST['notes'];

    $full_desc =
        "Date: $date\n".
        "Time: $time\n".
        "Affected Area/s: $area\n\n".
        "$description\n\n".
        "Additional Notes: $notes";

    $update = $conn->prepare("
        UPDATE advisories
        SET title=?, category_id=?, description=?
        WHERE id=?
    ");

    $update->bind_param("sisi",$title,$category,$full_desc,$id);
    $update->execute();

    header("Location: manage_advisories.php");
    exit();
}

/* FETCH CATEGORIES */
$categories = $conn->query("SELECT * FROM advisory_categories");
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Advisory</title>
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

<div class="nav-links">
<a href="manage_bills.php">Manage Bills</a>
<span class="divider">|</span>
<a href="manage_advisories.php" class="active">Advisories</a>
<span class="divider">|</span>
<a href="logout.php">Logout</a>
</div>

</header>


<div class="dashboard-wrapper">

<div class="manage-container">

<div class="manage-left">

<h2>Update Advisory</h2>

<form method="POST">

<label>Advisory Category</label>

<select name="category" required>

<?php while($cat=$categories->fetch_assoc()){ ?>

<option value="<?php echo $cat['id']; ?>"
<?php if($cat['id']==$advisory['category_id']) echo "selected"; ?>>

<?php echo $cat['category_name']; ?>

</option>

<?php } ?>

</select>


<label>Advisory Title</label>
<input type="text"
name="title"
value="<?php echo $advisory['title']; ?>"
required>


<label>Description</label>
<input type="text"
name="description"
required>


<label>Date</label>
<input type="date" name="date" required>


<label>Start Time</label>
<input type="time" name="start_time" required>

<label>End Time</label>
<input type="time" name="end_time" required>


<label>Affected Areas</label>
<input type="text" name="area">


<label>Additional Notes</label>
<input type="text" name="notes">


<button type="submit"
name="update_advisory"
class="btn-confirm">

Update Advisory

</button>

</form>

</div>

</div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>

