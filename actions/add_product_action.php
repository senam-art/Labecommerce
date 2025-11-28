<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';

requireAdmin();
requireLogin(); 

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    if (!isset($_FILES['images']) || count($_FILES['images']['name']) === 0) {
        throw new Exception('Please upload at least one product image');
    }

    $product_cat = $_POST['cat_id'];
    $product_brand = $_POST['brand_id'];
    $product_title = $_POST['title'];
    $product_price = $_POST['price'];
    $product_desc = $_POST['description'];
    $product_keywords = $_POST['keywords'];
    $created_by = getUserId();
    $images = $_FILES['images'];

    $result = add_product_ctr(
        $product_cat,
        $product_brand,
        $product_title,
        $product_price,
        $product_desc,
        $product_keywords,
        $created_by,
        $images
    );

    if (!$result['status']) {
        throw new Exception($result['message']);
    }

    echo json_encode([
        'status' => 'success',
        'message' => $result['message']
    ]);

} catch (Exception $e) {
    http_response_code(400); // always return 400 for errors
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
