<?php
include 'config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

/* ============================
   CREATE ADVISORY
============================ */
if (isset($_POST['create_advisory'])) {

    $category    = $_POST['category'];
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $date        = $_POST['date'];
    $start_time  = $_POST['start_time'];
    $end_time    = $_POST['end_time'];
    $area        = $_POST['area'];
    $notes       = $_POST['notes'];

    /* Format time range */
    $formatted_start = date('g:i A', strtotime($start_time));
    $formatted_end   = date('g:i A', strtotime($end_time));
    $formatted_time  = $formatted_start . " - " . $formatted_end;

    /* Format date */
    $formatted_date = date('F j, Y', strtotime($date));

    $full_desc =
        "Date: $formatted_date\n" .
        "Time: $formatted_time\n" .
        "Affected Area/s: $area\n\n" .
        "$description\n\n" .
        "Additional Notes: $notes";

    $stmt = $conn->prepare("
        INSERT INTO advisories (title, category_id, description)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("sis", $title, $category, $full_desc);
    $stmt->execute();

    echo "<script>alert('Advisory created successfully.');</script>";
}

/* ============================
   FETCH CATEGORIES
============================ */
$categories = $conn->query("SELECT * FROM advisory_categories");

/* ============================
   FETCH ADVISORIES
============================ */
$ads = $conn->query("
    SELECT advisories.*, advisory_categories.category_name
    FROM advisories
    LEFT JOIN advisory_categories
    ON advisories.category_id = advisory_categories.id
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Advisories</title>
    <link rel="stylesheet" href="assets/style.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<header class="header">

    <div class="logo-area">
        <img src="assets/logo.png" class="logo">
        <div>
            <h1>ANGELES ELECTRIC POWER PORTAL</h1>
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

        <!-- LEFT SIDE FORM -->
        <div class="manage-left">

            <h2>Create New Advisories</h2>

            <form method="POST">

                <label>Advisory Category</label>
                <select name="category" required>

                    <?php while ($cat = $categories->fetch_assoc()) { ?>

                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo $cat['category_name']; ?>
                        </option>

                    <?php } ?>

                </select>

                <label>Advisory Title</label>
                <input type="text" name="title" required>

                <label>Description</label>
                <input type="text" name="description" required>

                <label>Date</label>
                <input type="date" name="date" required>

                <label>Start Time</label>
                <input type="time" name="start_time" required>

                <label>End Time</label>
                <input type="time" name="end_time">

                <label>Affected Areas</label>
                <input type="text" name="area">

                <label>Additional Notes</label>
                <input type="text" name="notes">

                <button type="submit"
                        name="create_advisory"
                        class="btn-confirm">
                    Create Advisory
                </button>

            </form>

        </div>

        <!-- RIGHT SIDE TABLE -->
        <div class="manage-right">

            <h2>All Advisories</h2>

            <table class="bill-table">

                <tr>
                    <th>Advisory Category</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $ads->fetch_assoc()) { ?>

                    <tr>

                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>

                        <td><?php echo htmlspecialchars($row['title']); ?></td>

                        <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>

                        <td>

                         <a href="edit_advisory.php?id=<?php echo $row['id']; ?>"
                               title="Edit Advisory"
                               style="color:#90a4ae; margin-right:8px; font-size:15px; text-decoration:none; transition:color 0.2s;"
                               onmouseover="this.style.color='#1e5fa8'"
                               onmouseout="this.style.color='#90a4ae'">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <a href="delete_advisory.php?id=<?php echo $row['id']; ?>"
                               onclick="return confirm('Delete this advisory?')"
                               title="Delete Advisory"
                               style="color:#90a4ae; font-size:15px; text-decoration:none; transition:color 0.2s;"
                               onmouseover="this.style.color='#e53935'"
                               onmouseout="this.style.color='#90a4ae'">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
