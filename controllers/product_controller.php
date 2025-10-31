<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/classes/product_class.php';
 


function add_product_ctr($cat_id, $brand_id, $title, $price, $description, $keywords, $user_id) {
    $product = new Product();
    return $product->add($cat_id, $brand_id, $title, $price, $description, $keywords, $user_id);
}

// function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $description, $image_path, $keywords) {
//     $product = new Product();
//     return $product->update($product_id, $cat_id, $brand_id, $title, $price, $description, $image_path, $keywords);
// }

// function delete_product_ctr($product_id) {
//     $product = new Product();
//     return $product->delete($product_id);
// }

function get_user_products_ctr($user_id) {
    $product = new Product();
    return $product->get_user_products($user_id);
}

function add_product_image_ctr($product_id, $image_path) {
    $product = new Product();
    return $product->add_image($product_id, $image_path);
}

?>
