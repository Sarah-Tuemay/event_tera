<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Security.php';
 $user = new User();
 $error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = Security::sanitizeInput($_POST['fullname']);
    $email = Security::sanitizeInput($_POST['email']);
    $pass = $_POST['password'];
    if($user->register($name, $email, $pass)) { header("Location: login.php"); exit(); }
    else { $error = "Email already exists!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Register | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" href="../images/Temp-favicon.jpg">
</head>
<body>
    <header class="header">
        <a href="../index.php" class="logo">EVENT ተራ<span>Admin Portal</span></a>
        <nav id="main-nav"><a href="login.php">Login</a></nav>
    </header>
    <main id="main-content">
        <div class="container">
            <div class="dashboard-container">
                <h2>Create Organizer Account</h2>
                <?php if($error) echo "<p class='error'>$error</p>"; ?>
                <form method="POST" action="">
                    <label>Full Name / Club Name</label>
                    <input type="text" name="fullname" required>
                    <label>Official Email</label>
                    <input type="email" name="email" required>
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6">
                    <button type="submit" class="btn-submit">Register</button>
                </form>
                <p style="text-align:center; margin-top:15px; color:#9aa9b1;">Already registered? <a href="login.php" style="color:#00bcd4;">Login Here</a></p>
            </div>
        </div>
    </main>
</body>
</html>