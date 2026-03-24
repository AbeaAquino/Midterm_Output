<?php
include 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid advisory ID.");
}

$id = intval($_GET['id']); // sanitize

$stmt = $conn->prepare("DELETE FROM advisories WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        echo "<script>
            alert('Advisory deleted successfully');
            window.location.href='manage_advisories.php';
        </script>";
    } else {
        echo "<script>
            alert('Advisory not found or already deleted');
            window.location.href='manage_advisories.php';
        </script>";
    }

} else {
    echo "Error deleting advisory.";
}
?>
