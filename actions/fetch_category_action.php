<?php
// Show all errors and warnings
error_reporting(E_ALL);
// Display errors on the screen
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';

requireLogin();
requireAdmin();


header('Content-Type: application/json');



$categories = get_all_categories_ctr();

if ($categories){
   echo json_encode(['status' => 'success', 'data' => $categories]);
} else {
   echo json_encode(['status' => 'fail', 'message' => 'No categories found']);
}
?>

