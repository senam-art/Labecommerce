<?php

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/classes/category_class.php';





function add_category_ctr($cat_name, $created_by) {
    $category = new Category();
    return $category->add_category($cat_name, $created_by);
}

function get_user_categories_ctr($user_id) {
    $category = new Category();
    return $category->get_user_categories($user_id);
}

function update_category_ctr($cat_id, $cat_name) {
    $category = new Category();
    return $category->update_category($cat_id, $cat_name);
}

function delete_category_ctr($cat_id) {
    $category = new Category();
    return $category->delete_category($cat_id);
}

function get_all_categories_ctr(){
    $category = new Category();
    return $category->get_all_categories();
} 
    

