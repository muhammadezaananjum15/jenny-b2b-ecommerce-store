<?php
// admin/includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $_SESSION['admin_logged_in'] = true;
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . (strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '' : 'admin/') . 'login.php');
    exit();
}
?>
