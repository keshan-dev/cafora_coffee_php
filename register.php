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
     $row = $stmt->fetch(PDO::FETCH_ASSOC);

     if ($row && password_verify($password, $row['password'])) {
         $_SESSION['email'] = $row['email'];
         header("Location: index.php");
         exit();
     } else {
         echo "Not Found, Incorrect Email or Password";
     }
 }
?>

