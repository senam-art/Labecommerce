<?php
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../settings/core.php';


header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$cat_id = $_POST['cat_id'] ?? '';
$cat_name = $_POST['cat_name'] ?? '';

if (empty($cat_id) || empty($cat_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

if (update_category_ctr($cat_id, $cat_name)) {
    echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}

