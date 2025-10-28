<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/classes/brand_class.php';

function add_brand_ctr($brand_name, $cat_id, $created_by) {
    $brand = new Brand();
    return $brand->add($brand_name, $cat_id, $created_by);
}
function update_brand_ctr($brand_id, $brand_name) {
    $brand = new Brand();
    return $brand->update($brand_id, $brand_name);
}

function delete_brand_ctr($brand_id) {
    $brand = new Brand();
    return $brand->delete($brand_id);
}

function get_user_brands_ctr($created_by) {
    $brand = new Brand();
    return $brand->get_user_brands($created_by);
}
?>
