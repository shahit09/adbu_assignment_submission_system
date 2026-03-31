<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Assignment System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/theme.js"></script>
</head>
<body>

<div class="container center">
    <h1>Online Assignment Submission System</h1>

    <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="dashboard.php" class="btn">Go to Dashboard</a>
        <a href="logout.php" class="btn danger">Logout</a>
    <?php } else { ?>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    <?php } ?>

    <br><br>
    <button onclick="toggleTheme()">Toggle Dark/Light Mode</button>
</div>

</body>
</html>