<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';


header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$cat_id = $_POST['cat_id'] ?? '';

if (empty($cat_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing category ID']);
    exit;
}

if (delete_category_ctr($cat_id)) {
    echo json_encode(['status' => 'success', 'message' => 'Category deleted']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete category']);
}
