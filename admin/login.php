<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in as admin, go to dashboard
if (isset($_SESSION["user_id"]) && $_SESSION["user_role"] == "admin") {
    header("Location: index.php");
    exit;
}

// Redirect to unified login page
header("Location: ../login.php");
exit;
?>
