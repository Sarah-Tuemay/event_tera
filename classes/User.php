<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = $GLOBALS['pdo'];
    }

    public function register($fullname, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, email, password) VALUES (:name, :email, :pass)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $fullname, ':email' => $email, ':pass' => $hashedPassword]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }

    public static function logout() {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    public static function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }
}
?>