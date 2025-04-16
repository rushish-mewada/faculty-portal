<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: index.php");
    exit;
}

require 'db.php';
include 'menu.php';

$className = $_GET['class'] ?? '';

$query = "SELECT u.id, u.username, m.average_marks, m.percentage 
          FROM users u
          JOIN classes c ON u.class = c.id
          LEFT JOIN marks m ON u.id = m.student_id
          WHERE c.name = '$className' AND u.role = 'student'
          GROUP BY u.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class: <?= htmlspecialchars($className) ?></title>
    <link rel="stylesheet" href="styles/class.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <h1>Class: <?= htmlspecialchars($className) ?></h1>
    <table>
        <thead>
            <tr>
                <th>Roll No.</th>
                <th>Name</th>
                <th>Average Marks</th>
                <th>Percentage</th>
                <th>Edit</th>
                <th>Print</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['average_marks']) ?></td>
                    <td><?= htmlspecialchars($row['percentage']) ?>%</td>
                    <td><a href="edit_marks.php?student_id=<?= $row['id'] ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td>
                    <td><a href="print_marks.php?student_id=<?= $row['id'] ?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
