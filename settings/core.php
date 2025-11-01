<?php

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}


// Start session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable output buffering for safe redirects
ob_start();

/**
 * Checks if a user is logged in
 * Returns true if a session user exists, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']); // use user_id since it’s the main unique identifier
}

/**
 * Get the logged-in user's name
 * Returns the name or null if not set
 */
function getUserName() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

/**
 * Get the logged-in user's ID
 * Returns the user ID or null if not set
 */
function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Checks if the logged-in user has admin privileges
 * Returns true if user_role == 2 (admin), false otherwise
 */
function isAdmin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2);
}

/**
 * Forces redirect if user is not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF'], 3) . '/index.php');
        exit;
    }
}

function hasLoggedIn() {
    if (isLoggedIn()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF'], 3) . '/index.php');
        exit;
    }
}

/**
 * Forces redirect if user is not admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF'], 3) . '/index.php');
        exit;
    }
}
?>
