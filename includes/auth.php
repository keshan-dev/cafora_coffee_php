<?php

session_start();
require 'database_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND status = 'active' LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // verify password
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

         // redirect by role
        if ($user['role'] === 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../user.php");
        }
        exit;
    } else {
        echo "Invalid email or password!";
    }
}

if (isset($requiredRole)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: ../login.php");
        exit;
    }
}

?>
