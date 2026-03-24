<?php
include 'config/database.php';

if (!isset($_GET['category'])) {
    die("Invalid advisory.");
}

$category = intval($_GET['category']);

$stmt = $conn->prepare("
    SELECT * FROM advisories
    WHERE category_id = ?
    ORDER BY created_at DESC
    LIMIT 1
");

$stmt->bind_param("i", $category);
$stmt->execute();

$result = $stmt->get_result();
$advisory = $result->fetch_assoc();

if (!$advisory) {
    die("No advisory available.");
}

/* ============================
   PARSE DESCRIPTION FIELD
============================ */

$parsed_date  = '';
$parsed_time  = '';
$parsed_area  = '';
$parsed_desc  = '';
$parsed_notes = '';

if (!empty($advisory['description'])) {

    $lines = explode("\n", $advisory['description']);

    foreach ($lines as $line) {

        $line = trim($line);

        if (str_starts_with($line, 'Date:')) {
            $parsed_date = trim(substr($line, 5));
        }

        elseif (str_starts_with($line, 'Time:')) {
            $parsed_time = trim(substr($line, 5));
        }

        elseif (str_starts_with($line, 'Affected Area/s:')) {
            $parsed_area = trim(substr($line, 16));
        }

        elseif (str_starts_with($line, 'Additional Notes:')) {
            $parsed_notes = trim(substr($line, 17));
        }

        else {
            if ($line !== '') {
                $parsed_desc .= $line . "\n";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Advisory</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<?php include 'includes/header.php'; ?>

<div class="glass-wrapper">

    <div class="glass-card">

        <div class="glass-top">
            Advisory
        </div>

        <div class="glass-content">

            <!-- LETTERHEAD -->
            <div class="advisory-letterhead">

                <img src="assets/logo.png" class="advisory-logo">

                <div class="advisory-org">
                    <span class="org-name">ANGELES ELECTRIC POWER PORTAL</span>
                    <span class="important">IMPORTANT NOTICE</span>
                </div>

            </div>

            <div class="divider-line"></div>

            <p class="advisory-intro">
                Please be advised of the following announcement:
            </p>

            <div class="divider-line"></div>

            <h2 class="advisory-title">
                <?php echo strtoupper(htmlspecialchars($advisory['title'])); ?>
            </h2>

            <div class="advisory-info">

                <?php if ($parsed_date): ?>
                    <div class="info-row">
                        <span class="label">Date:</span>
                        <span><?php echo htmlspecialchars($parsed_date); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($parsed_time): ?>
                    <div class="info-row">
                        <span class="label">Time:</span>
                        <span><?php echo htmlspecialchars($parsed_time); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($parsed_area): ?>
                    <div class="info-row">
                        <span class="label">Affected Area/s:</span>
                        <span><?php echo htmlspecialchars($parsed_area); ?></span>
                    </div>
                <?php endif; ?>

            </div>

            <?php if ($parsed_desc): ?>
                <p class="advisory-desc">
                    <?php echo nl2br(htmlspecialchars($parsed_desc)); ?>
                </p>
            <?php endif; ?>

            <div class="divider-line"></div>

            <div class="warning-box">

                <span class="warning-icon">⚠</span>

                <p>
                    <?php
                    if ($parsed_notes) {
                        echo nl2br(htmlspecialchars($parsed_notes));
                    } else {
                        echo "We apologize for the inconvenience and thank you for understanding.";
                    }
                    ?>
                </p>

            </div>

            <div class="divider-line"></div>

            <p class="advisory-contact">
                For more information, please contact
                <strong>0987 056 4356</strong>
            </p>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
