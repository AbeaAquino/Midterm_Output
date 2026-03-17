<?php
include 'config/database.php';
include 'includes/auth.php';

if(!isset($_GET['id'])){
header("Location: manage_advisories.php");
exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("
DELETE FROM advisories WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: manage_advisories.php");
exit();
?>
```
