<?php
//start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


//for header redirection
ob_start();

//funtion to check for login
/**
 * Check if a user is logged in
 * Returns true if a session user exists, false otherwise
 */
function isLoggedIn() {
    return $isLoggedIn = isset($_SESSION['user_name']);
}


function getUserName() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

//function to get user ID
function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

//function to check for role (admin, customer, etc)
/**
 * Check if the logged-in user has admin privileges
 * Returns true if user_role == 1 (admin), false otherwise
 */
function isAdmin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1);
}



?>