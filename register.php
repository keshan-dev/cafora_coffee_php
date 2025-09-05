<?php
session_start();

// include the database connection file from includes folder
include 'includes/database_connection.php';

// ================= SIGN UP =================
if (isset($_POST['signUp'])) {
    $name = $_POST['fName'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Hash password (use md5 only if required, but password_hash is safer)
    $password = md5($password);

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Email Address Already Exists!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }
}

// ================= SIGN IN =================
if (isset($_POST['signIn'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['email'] = $row['email'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>

