<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: index.php");
    exit;
}

require 'db.php';
include 'menu.php';

$query = "SELECT * FROM classes";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <h1 class="heading">Available Classes</h1>
    <div class="card-container">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="class-card" onclick="location.href='class.php?class=<?= urlencode($row['name']) ?>'">
                <?= htmlspecialchars($row['name']) ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
