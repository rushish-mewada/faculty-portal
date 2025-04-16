<?php
?>
<link rel="stylesheet" href="styles/menu.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="navbar">
    <div class="nav-left">
        <a href="/problem" class="logo">Marks Manager</a>
    </div>
    <div class="nav-right">
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'faculty'): ?>
                <a href="admin.php"><i class="fa fa-dashboard"></i> Dashboard</a>
            <?php elseif ($_SESSION['role'] === 'student'): ?>
                <a href="student.php"><i class="fa fa-user"></i> Profile</a>
            <?php endif; ?>
            <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
        <?php else: ?>
            <a href="index.php"><i class="fa fa-sign-in"></i> Login</a>
        <?php endif; ?>
    </div>
</div>
