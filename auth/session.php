<?php
// auth/session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) || !empty($_SESSION['admin_logged_in']);
}

function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || !empty($_SESSION['admin_logged_in']);
}

function checkAdminSession() {
    if (!isLoggedIn() || !isAdmin()) {
        header("Location: ../admin/login.php");
        exit();
    }
}
?>