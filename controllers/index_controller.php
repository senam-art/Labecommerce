<?php
require_once __DIR__ . '/../models/user_model.php';

function getLoggedInUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $userId = $_SESSION['user_id'];
    return getUserById($userId); // Fetch from database
}
