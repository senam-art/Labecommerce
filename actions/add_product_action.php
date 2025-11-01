<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';



requireAdmin();
requireLogin(); 

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_cat = $_POST['cat_id'];
    $product_brand = $_POST['brand_id'];
    $product_title = $_POST['title'];
    $product_price = $_POST['price'];
    $product_desc = $_POST['description'];
    $product_keywords = $_POST['keywords'];
    $created_by = getUserId();
    $images = $_FILES['images'];

    // Step 1: Add product
    $result = add_product_ctr($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by,$images);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
    }
}
