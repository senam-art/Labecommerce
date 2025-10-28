<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';
requireLogin();

header('Content-Type: application/json; charset=UTF-8');

if(isset($_POST['brand_id'], $_POST['brand_name'])) {
    $brand_id = intval($_POST['brand_id']);
    $brand_name = trim($_POST['brand_name']);

    $result = update_brand_ctr($brand_id, $brand_name);
    if($result){
        echo json_encode(['status'=>'success','message'=>'Brand updated successfully']);
    } else {
        echo json_encode(['status'=>'fail','message'=>'Update failed']);
    }
} else {
    echo json_encode(['status'=>'fail','message'=>'Invalid data']);
}

