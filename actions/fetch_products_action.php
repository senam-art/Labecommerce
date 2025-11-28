<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';

requireLogin(); 

header('Content-Type: application/json');

$user_id = getUserId();
$products = fetch_all_products_ctr();

if (!empty($products) && is_array($products)) {
    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);
} else {
    echo json_encode([
        'status' => 'success', // Changed to success with empty data
        'data' => [],
        'message' => 'No products found'
    ]);
}
?>