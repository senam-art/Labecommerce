<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';

requireLogin(); 

header('Content-Type: application/json');

$user_id = getUserId();
$brands = get_user_brands_ctr($user_id);

if (!empty($brands) && is_array($brands)) {
    echo json_encode([
        'status' => 'success',
        'data' => $brands
    ]);
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'No brands found'
    ]);
}


