<?php
session_start();
require 'db.php';
require 'menu.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['student_id']) && isset($_SESSION['student_id'])) {
    header("Location: student.php?student_id=" . $_SESSION['student_id']);
    exit;
}

$student_id = $_GET['student_id'];

$query = "SELECT m.subject_name, m.ct1, m.ct2, m.total_marks, m.average_marks, m.percentage
          FROM marks m
          WHERE m.student_id = $student_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Marks</title>
    <link rel="stylesheet" href="styles/student.css">
</head>
<body>
    <h1>Your Marks</h1>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>CT1 Marks</th>
                <th>CT2 Marks</th>
                <th>Total Marks</th>
                <th>Average</th>
                <th>Percentage</th>
                <th>Print</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= htmlspecialchars($row['ct1']) ?></td>
                    <td><?= htmlspecialchars($row['ct2']) ?></td>
                    <td><?= htmlspecialchars($row['total_marks']) ?></td>
                    <td><?= htmlspecialchars($row['average_marks']) ?></td>
                    <td><?= htmlspecialchars($row['percentage']) ?>%</td>
                    <td><a href="print_marks.php?student_id=<?= $student_id ?>&subject=<?= urlencode($row['subject_name']) ?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
