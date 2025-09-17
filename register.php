<?php
session_start();

include 'includes/database_connection.php';




// // ================= SIGN UP =================
if (isset($_POST['signUp'])) {
    $name = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

     $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Email Address Already Exists!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashedPassword])) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }
}

// // ================= SIGN IN =================
if (isset($_POST['signIn'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

     $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

         // redirect by role
        if ($user['role'] === 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: user.php");
        }
        exit;
    } else {
        header("Location: login.php");
    }

    
}
?>

