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
    return isset($_SESSION['user']);
}



//function to get user ID


//function to check for role (admin, customer, etc)
/**
 * Check if the logged-in user has admin privileges
 * Returns true if user_role == 1 (admin), false otherwise
 */
function isAdmin() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1);
}



?>