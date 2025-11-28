<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';
require_once PROJECT_ROOT . '/classes/product_class.php';

$p = new Product();
$conn = $p->db_conn();  // make sure connection works

echo "<pre>";
var_dump($conn);


// $test = $p->sanitizeProductData([
//     'product_cat' => '2',
//     'product_brand' => '5',
//     'product_title' => "Test Title   ",
//     'product_price' => "99.99",
//     'product_desc' => "desc here  ",
//     'created_by' => 1
// ], $conn);

// print_r($test);


$path = $p->prepareUserFolder(2);
echo $path;

if (!empty($_FILES)) {
    $folder = $p->prepareUserFolder(2);
    $uploaded = $p->uploadImagesToTemp($_FILES['images'], $folder);
    print_r($uploaded);
}

$id = $p->insertProduct([
    'product_cat' => 2,
    'product_brand' => 3,
    'product_title' => 'Test',
    'product_price' => 10,
    'product_desc' => 'desc',
    'created_by' => 19
], $conn);

echo "Inserted product: $id";

$keywords = $p->handleProductKeywords('tag1, tag2, tag3', 5, $conn);
echo "Keywords inserted";




// --- Create a fake product to attach keywords ---
// $conn->query("INSERT INTO products (product_title) VALUES ('Test Product')");
$product_id = 5;

echo "Testing with product_id = $product_id<br><br>";

// --- Fake inputs to test ---
// 1. Normal Tagify JSON
$input1 = '[{"value":"shoe"},{"value":"black"},{"value":"leather"}]';

// 2. Escaped Tagify JSON
$input2 = addslashes('[{"value":"bag"},{"value":"brown"}]');

// 3. Comma separated fallback
$input3 = "red, green, blue";

// Instantiate your class
$obj = $p; // change this to your actual class name


echo "<b>TEST 1: Normal Tagify JSON</b><br>";
try {
    $obj->handleProductKeywords($input1, $product_id, $conn);
    echo "Inserted keywords successfully.<br><br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br><br>";
}


echo "<b>TEST 2: Escaped Tagify JSON</b><br>";
try {
    $obj->handleProductKeywords($input2, $product_id, $conn);
    echo "Inserted keywords successfully.<br><br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br><br>";
}


echo "<b>TEST 3: Comma-separated fallback</b><br>";
try {
    $obj->handleProductKeywords($input3, $product_id, $conn);
    echo "Inserted keywords successfully.<br><br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br><br>";
}


// --- Show DB results so you can verify ---
echo "<h3>Keywords in keywords table:</h3>";
$res = $conn->query("SELECT * FROM keywords");
while ($row = $res->fetch_assoc()) {
    echo $row['keyword_id'] . " - " . $row['keyword'] . "<br>";
}

echo "<br><h3>Product associations:</h3>";
$res2 = $conn->query("SELECT * FROM product_keywords WHERE product_id = $product_id");
while ($row2 = $res2->fetch_assoc()) {
    echo "product_id: " . $row2['product_id'] . " | keyword_id: " . $row2['keyword_id'] . "<br>";
}

$conn->close();
?>
