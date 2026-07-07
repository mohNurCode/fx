<?php
session_start();
require_once 'db.php';
require_once 'auth.php';

if (isset($_SESSION['user_id'])) {
    logActivity($pdo, $_SESSION['user_id'], 'logout', 'User logged out');
}

session_destroy();
header('Location: login.php');
exit;
?>