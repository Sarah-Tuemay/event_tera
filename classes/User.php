<?php
// [WEEK 6: Authentication & Security (Password Hashing, Sessions, Role-Based Access)]

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = $GLOBALS['pdo'];
    }

    // REGISTER
    public function register($fullname, $email, $password) {
        // [WEEK 6: Password Hashing]
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (fullname, email, password) VALUES (:name, :email, :pass)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $fullname, ':email' => $email, ':pass' => $hashedPassword]);
    }

    // LOGIN
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // [WEEK 6: Session Tracking]
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_role'] = $user['role']; // Role-Based Access Control
            return true;
        }
        return false;
    }

    // LOGOUT
    public static function logout() {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    // CHECK IF LOGGED IN (RBAC)
    public static function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }
}
?>