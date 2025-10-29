<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';



requireAdmin();
requireLogin(); 

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];
    $brand_id = $_POST['brand_id'];
    $title = $_POST['product_title'];
    $price = $_POST['product_price'];
    $desc = $_POST['product_desc'];
    $keywords = $_POST['product_keywords'];
    $user_id = getUserId();

    // Step 1: Add product
    $product_id = add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $keywords, $user_id);

    if ($product_id) {
        //loop through uploaded images
        foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$index]);
            $folder_path = "../../uploads/u$user_id/p$product_id/";
            if (!is_dir($folder_path)) mkdir($folder_path, 0777, true);
            
            $target_file = $folder_path . $file_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                add_product_image_ctr($product_id, "uploads/u$user_id/p$product_id/$file_name");
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
    }
}
