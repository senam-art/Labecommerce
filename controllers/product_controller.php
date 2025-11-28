<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/classes/product_class.php';
 


function add_product_ctr($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by,$images){
    $product = new Product();
    return $product->add($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by, $images);
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

function fetch_all_products_ctr() {
    $product = new Product();
    return $product->fetch_all_products();
}

function associate_product_keywords_ctr(int $product_id, array $keywords): bool {
    $product = new Product();
    return $product->associateKeywords($product_id, $keywords);
}

function get_product_keywords_ctr(int $product_id): array {
    $product = new Product();
    return $product->getProductKeywords($product_id);
}
?>
