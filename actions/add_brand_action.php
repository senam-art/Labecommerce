<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';

requireLogin(); 

header('Content-Type: application/json; charset=UTF-8');

$user_id = getUserId();

// Validate POST data
if(isset($_POST['brand_name'], $_POST['cat_id'])) {
    $brand_name = trim($_POST['brand_name']);
    $cat_id     = intval($_POST['cat_id']); // already sent from dropdown

    $result = add_brand_ctr($brand_name, $cat_id, $user_id); // use cat_id directly

    if($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Brand added successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Brand already exists or could not be added'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Invalid data: brand name and category ID required'
    ]);
}