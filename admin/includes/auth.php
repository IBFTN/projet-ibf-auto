<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if the admin is logged in.
 * @param string $base_path Path to reach the admin root from the current file.
 */
function check_auth($base_path = './') {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: " . $base_path . "login.php");
        exit;
    }
}
