<?php
session_start();

// include the database connection file from includes folder
include 'includes/database_connection.php';

// ================= SIGN UP =================
if (isset($_POST['signUp'])) {
    $UserName = $_POST['fName'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Hash password (use md5 only if required, but password_hash is safer)
    $password = md5($password);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users(UserName, email, password) 
                        VALUES ('$UserName','$email','$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// ================= SIGN IN =================
if (isset($_POST['signIn'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>
