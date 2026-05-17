<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Security.php';
 $user = new User();
 $error = '';
if(isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit(); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = Security::sanitizeInput($_POST['email']);
    $pass = $_POST['password'];
    if($user->login($email, $pass)) { header("Location: dashboard.php"); exit(); }
    else { $error = "Invalid email or password!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" href="../images/Temp-favicon.jpg">
</head>
<body>
    <header class="header">
        <a href="../index.php" class="logo">EVENT ተራ<span>Admin Portal</span></a>
        <nav id="main-nav"><a href="../index.php">Back to Site</a></nav>
    </header>
    <main id="main-content">
        <div class="container">
            <div class="dashboard-container">
                <h2>Organizer Login</h2>
                <?php if($error) echo "<p class='error'>$error</p>"; ?>
                <form method="POST" action="">
                    <label>AASTU Email</label>
                    <input type="email" name="email" required>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <button type="submit" class="btn-submit">Login</button>
                </form>
                <p style="text-align:center; margin-top:15px; color:#9aa9b1;">Don't have an account? <a href="register.php" style="color:#00bcd4;">Register Here</a></p>
            </div>
        </div>
    </main>
</body>
</html>