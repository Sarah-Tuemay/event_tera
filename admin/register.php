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

    if($user->register($name, $email, $pass)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Email already exists or registration failed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Register | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="auth-container">
        <h2>Create Organizer Account</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name / Club Name</label>
                <input type="text" name="fullname" required>
            </div>
            <div class="form-group">
                <label>Official Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="link-text">Already registered? <a href="login.php">Login Here</a></p>
    </div>
</body>
</html>