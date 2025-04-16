<?php
session_start();
require 'db.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'faculty') {
        header("Location: admin.php");
        exit;
    } elseif ($_SESSION['role'] === 'student') {
        header("Location: student.php");
        exit;
    }
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] === 'student') {
            $_SESSION['student_id'] = $user['id'];
        }
    
        if ($user['role'] == 'faculty') {
            header("Location: admin.php");
        } else if ($user['role'] == 'student') {
        
            header("Location: student.php?student_id=" . $user['id']);
        }
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h1>Welcome Back!</h1>
        <p>Please login to continue.</p>
    </div>
    <div class="right-panel">
        <h2>Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>
