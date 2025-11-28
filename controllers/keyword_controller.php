<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/classes/product_class.php';
 
function search_keywords_ctr($term) {
    $search = new Product();
    return $search->search_keywords($term);
}