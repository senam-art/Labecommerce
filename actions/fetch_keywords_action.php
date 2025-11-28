<?php
// Show all errors and warnings
error_reporting(E_ALL);
// Display errors on the screen
ini_set('display_errors', 1);


require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/keyword_controller.php';


requireLogin();
requireAdmin();

header('Content-Type: application/json');


$term = isset($_GET['term']) ? trim($_GET['term']) : '';

// If nothing typed yet, return empty list
if ($term === '') {
    echo json_encode(['status' => 'success', 'keywords' => []]);
    exit;
}


$keywords = search_keywords_ctr($term);

// ALWAYS return JSON
echo json_encode([
    'status' => 'success',
    'keywords' => $keywords
]);

