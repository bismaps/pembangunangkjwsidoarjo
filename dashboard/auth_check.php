<?php
// Secure Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timeout Duration (30 Minutes)
$timeout_duration = 1800;

// Check for Timeout
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: login.php?msg=timeout");
        exit();
    }
}

// Update Last Activity
$_SESSION['last_activity'] = time();

// Check Login Status
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
