<?php
// Show all errors and warnings
error_reporting(E_ALL);
// Display errors on the screen
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';



header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$categories = get_all_categories_ctr();

echo json_encode(['status' => 'success', 'data' => $categories]);

