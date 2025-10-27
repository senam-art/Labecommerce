<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';


header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$cat_name = $_POST['cat_name'] ?? '';

if (empty($cat_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Category name required']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (add_category_ctr($cat_name, $user_id)) {
    echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add category']);
}
