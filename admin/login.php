<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Security.php';

 $user = new User();
 $error = '';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = Security::sanitizeInput($_POST['email']);
    $pass = $_POST['password'];

    if($user->login($email, $pass)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | EVENT ተራ</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="auth-container">
        <h2>Organizer Login</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>AASTU Email</label>
                <input type="email" name="email" required placeholder="name@aastu.edu.et">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="link-text">Don't have an account? <a href="register.php">Register Here</a></p>
        <p class="link-text"><a href="../index.php">← Back to Public Site</a></p>
    </div>
</body>
</html>