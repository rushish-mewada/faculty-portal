<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: index.php");
    exit;
}

require 'db.php';
include 'menu.php';

$student_id = $_GET['student_id'] ?? '';
$error = '';

$query = "SELECT m.*, u.class, u.username FROM marks m JOIN users u ON m.student_id = u.id WHERE m.student_id = $student_id";
$result = mysqli_query($conn, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $mark_id = $_POST['update'];
    $ct1 = $_POST['ct1'][$mark_id];
    $ct2 = $_POST['ct2'][$mark_id];

    if ($ct1 <= 20 && $ct2 <= 20) {
        $total = $ct1 + $ct2;
        $avg = $total / 2;
        $per = ($total / 40) * 100;

        mysqli_query($conn, "UPDATE marks SET ct1 = $ct1, ct2 = $ct2, total_marks = $total, average_marks = $avg, percentage = $per WHERE id = $mark_id");

        $agg_query = "SELECT AVG(average_marks) as avg_marks, AVG(percentage) as percent FROM marks WHERE student_id = $student_id";
        $agg_result = mysqli_fetch_assoc(mysqli_query($conn, $agg_query));

        $updateUserQuery = "UPDATE users SET average_marks = {$agg_result['avg_marks']}, percentage = {$agg_result['percent']} WHERE id = $student_id";
        mysqli_query($conn, $updateUserQuery);

        header("Location: edit_marks.php?student_id=$student_id");
        exit;
    } else {
        $error = "Marks should be between 0 and 20.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Marks</title>
    <link rel="stylesheet" href="styles/class.css">
</head>
<body>
    <h1>Edit Marks for Roll No. <?= htmlspecialchars($student_id) ?></h1>
    <form method="POST">
        <table>
            <tr>
                <th>Subject</th>
                <th>CT1</th>
                <th>CT2</th>
                <th>Total Marks</th>
                <th>Average Marks</th>
                <th>Percentage</th>
                <th>Update</th>
            </tr>
            <?php mysqli_data_seek($result, 0); ?>
            <?php while ($marks = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($marks['subject_name']) ?></td>
                    <td><input type="number" name="ct1[<?= $marks['id'] ?>]" value="<?= $marks['ct1'] ?>" max="20" maxlength="2" min="0" oninput="limitDigits(this)" required></td>
                    <td><input type="number" name="ct2[<?= $marks['id'] ?>]" value="<?= $marks['ct2'] ?>" max="20" maxlength="2" min="0" oninput="limitDigits(this)" required></td>
                    <td><?= $marks['total_marks'] ?></td>
                    <td><?= $marks['average_marks'] ?></td>
                    <td><?= $marks['percentage'] ?>%</td>
                    <td><button type="submit" name="update" value="<?= $marks['id'] ?>">Update</button></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <script>
        function limitDigits(input) {
            if (input.value.length > 2) {
                input.value = input.value.slice(0, 2);
            }
        }
</script>
</body>
</html>
