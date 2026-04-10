<?php
include '../includes/db.php';
session_start();

// Clear token from database if user is logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Clear session
$_SESSION = array();
session_destroy();

// Clear cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/");
}

header("Location: ../index.php");
exit;
?>
