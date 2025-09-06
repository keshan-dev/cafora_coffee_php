<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // not logged in
    header("Location: ../login.php");
    exit;
}

// If the page requires a specific role
if (isset($requiredRole) && $_SESSION['role'] !== $requiredRole) {
    // if role mismatch
    header("Location: ../login.php"); // create this page for better UX
    exit;
}
?>