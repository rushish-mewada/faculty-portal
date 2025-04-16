<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: index.php");
    exit;
}

require 'db.php';
require_once('tcpdf/tcpdf.php');

$student_id = $_GET['student_id'] ?? '';

$query = "SELECT u.username, m.ct1, m.ct2, m.total_marks, m.average_marks, m.percentage 
          FROM marks m
          JOIN users u ON m.student_id = u.id
          WHERE m.student_id = $student_id";
$result = mysqli_query($conn, $query);
$marks = mysqli_fetch_assoc($result);

if (!$marks) {
    die("Student data not found.");
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Marks Manager');
$pdf->SetTitle('Student Marks Card');
$pdf->SetSubject('Student Marks Report');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Student Marks Card', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Student Name: ' . htmlspecialchars($marks['username']), 0, 1);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 12);

$table = '
    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #4CAF50; color: white;">
                <th>Subject</th>
                <th>CT1</th>
                <th>CT2</th>
                <th>Total Marks</th>
                <th>Average Marks</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mathematics</td>
                <td>' . $marks['ct1'] . '</td>
                <td>' . $marks['ct2'] . '</td>
                <td>' . $marks['total_marks'] . '</td>
                <td>' . $marks['average_marks'] . '</td>
                <td>' . $marks['percentage'] . '%</td>
            </tr>
        </tbody>
    </table>
';

$pdf->writeHTML($table, true, false, false, false, '');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 10, 'Rushish Mewada - All rights reserved', 0, 1, 'C');

$pdf->Output('Student_Marks_Card_' . $student_id . '.pdf', 'D');
exit;
?>
