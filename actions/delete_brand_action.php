<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';

requireLogin();

if(isset($_POST['brand_id'])){
    $brand_id = intval($_POST['brand_id']);
    $result = delete_brand_ctr($brand_id);
    if($result){
        echo json_encode(['status'=>'success','message'=>'Brand deleted successfully']);
    } else {
        echo json_encode(['status'=>'fail','message'=>'Delete failed']);
    }
} else {
    echo json_encode(['status'=>'fail','message'=>'Invalid data']);
}
?>
