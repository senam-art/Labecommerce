<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/settings/db_class.php';

class Product extends db_connection {
    
    public function add(
        $product_cat, 
        $product_brand, 
        $product_title, 
        $product_price, 
        $product_desc, 
        $product_keywords, 
        $created_by, 
        $images
    ) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // 1. Sanitize input
            $sanitized = $this->sanitizeProductData([
                'product_cat' => $product_cat,
                'product_brand' => $product_brand,
                'product_title' => $product_title,
                'product_price' => $product_price,
                'product_desc' => $product_desc,
                'created_by' => $created_by
            ], $conn);

            // 2. Prepare user folder
            $user_folder = $this->prepareUserFolder($sanitized['created_by']);

            // 3. Upload images to temp folder
            $uploaded_files = $this->uploadImagesToTemp($images, $user_folder);

            // 4. Insert product
            $product_id = $this->insertProduct($sanitized, $conn);

            // 5. Move images to final product folder
            $this->moveImagesToProductFolder($uploaded_files, $user_folder, $product_id, $sanitized['created_by'], $conn);

            // 6. Handle product keywords
            $this->handleProductKeywords($product_keywords, $product_id, $conn);

            // 7. Commit transaction
            $conn->commit();

            return [
                "status" => true,
                "message" => "Product created successfully with " . count($uploaded_files) . " images.",
                "product_id" => $product_id
            ];

        } catch (Exception $e) {
            $conn->rollback();
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public function fetch_all_products() {
        $sql = "SELECT * FROM products ORDER BY product_id DESC";
        $result = $this->db_conn()->query($sql);
        $products = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // --------------------------
    // PRIVATE HELPER METHODS
    // --------------------------

    private function sanitizeProductData($data, $conn) {
        return [
            'product_cat' => intval($data['product_cat']),
            'product_brand' => intval($data['product_brand']),
            'product_title' => mysqli_real_escape_string($conn, trim($data['product_title'])),
            'product_price' => floatval($data['product_price']),
            'product_desc' => mysqli_real_escape_string($conn, trim($data['product_desc'])),
            'created_by' => intval($data['created_by'])
        ];
    }

    private function prepareUserFolder($user_id) {
        $user_folder = PROJECT_ROOT . "/uploads/u$user_id/";
        if (!is_dir($user_folder)) mkdir($user_folder, 0777, true);
        return $user_folder;
    }

    private function uploadImagesToTemp($images, $user_folder) {
        $uploaded_files = [];
        $errors = [];

        if (isset($images['tmp_name']) && is_array($images['tmp_name'])) {
            foreach ($images['tmp_name'] as $i => $tmp_name) {
                if ($images['error'][$i] !== UPLOAD_ERR_OK) {
                    $errors[] = "Failed to upload image: " . $images['name'][$i];
                    continue;
                }

                $file_name = time() . "_" . basename($images['name'][$i]);
                $temp_folder = $user_folder . "temp/";
                if (!is_dir($temp_folder)) mkdir($temp_folder, 0777, true);
                $target_file = $temp_folder . $file_name;

                if (!move_uploaded_file($tmp_name, $target_file)) {
                    $errors[] = "Error saving file: " . $images['name'][$i];
                } else {
                    $uploaded_files[] = $file_name;
                }
            }
        }

        if (!empty($errors)) {
            throw new Exception(implode(", ", $errors));
        }

        return $uploaded_files;
    }

    private function insertProduct($sanitized, $conn) {
        $sql = "INSERT INTO products 
                (product_cat, product_brand, product_title, product_price, product_desc, created_by)
                VALUES ({$sanitized['product_cat']}, {$sanitized['product_brand']}, '{$sanitized['product_title']}', {$sanitized['product_price']}, '{$sanitized['product_desc']}', {$sanitized['created_by']})";

        if (!$conn->query($sql)) {
            throw new Exception("Database product insert failed: " . $conn->error);
        }

        return $conn->insert_id;
    }

    private function moveImagesToProductFolder($uploaded_files, $user_folder, $product_id, $created_by, $conn) {
        $product_folder = $user_folder . "p$product_id/";
        mkdir($product_folder, 0777, true);

        foreach ($uploaded_files as $file_name) {
            $temp_file = $user_folder . "temp/" . $file_name;
            $final_file = $product_folder . $file_name;

            if (!rename($temp_file, $final_file)) {
                throw new Exception("Failed to move file $file_name to final folder");
            }

            $relative_path = "uploads/u$created_by/p$product_id/$file_name";
            $sql_img = "INSERT INTO product_images (product_id, image_path) VALUES ($product_id, '$relative_path')";
            if (!$conn->query($sql_img)) {
                throw new Exception("Failed to save image entry for $file_name");
            }
        }

        @rmdir($user_folder . "temp/");
    }

    private function handleProductKeywords($product_keywords, $product_id, $conn) {
        if (empty($product_keywords)) return;

        // Parse keywords from JSON or comma-separated
        $keywordsArray = [];
        // First attempt: normal decode
        $decoded = json_decode($product_keywords, true);

        // If Tagify escaped JSON, fix it and decode again
        if (json_last_error() !== JSON_ERROR_NONE) {
            $product_keywords = stripslashes($product_keywords);
            $decoded = json_decode($product_keywords, true);
        }

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // Extract the 'value'
            $keywordsArray = array_map(function($item){
                return is_array($item) ? ($item['value'] ?? '') : $item;
            }, $decoded);
        } else {
            // Fallback: treat it as comma-separated text
            $keywordsArray = array_map('trim', explode(',', $product_keywords));
        }
        

        foreach ($keywordsArray as $kw) {
            $kw_value = mysqli_real_escape_string($conn, trim($kw));
            if ($kw_value === '') continue;

            $res = $conn->query("SELECT keyword_id FROM keywords WHERE keyword = '$kw_value' LIMIT 1");

            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $keyword_id = $row['keyword_id'];
            } else {
                if (!$conn->query("INSERT INTO keywords (keyword) VALUES ('$kw_value')")) {
                    throw new Exception("Failed to insert keyword '$kw_value': " . $conn->error);
                }
                $keyword_id = $conn->insert_id;
            }

            if (!$conn->query("INSERT INTO product_keywords (product_id, keyword_id) VALUES ($product_id, $keyword_id)")) {
                throw new Exception("Failed to associate keyword '$kw_value' with product: " . $conn->error);
            }
        }
    }


    // --------------------------
    // KEYWORDS METHODS 
    // --------------------------
    
    // Get all unique keywords
    public function get_all_keywords() {
        $sql = "SELECT DISTINCT keyword_name FROM keywords ORDER BY keyword_name ASC";
        $result = $this->db_conn()->query($sql);
        $keywords = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $keywords[] = ["value" => $row['keyword_name']];
            }
        }
        return $keywords;
    }

    // Search keywords for autocomplete
    public function search_keywords($searchTerm) {
        $db = $this->db_conn();
        $searchTerm = $db->real_escape_string($searchTerm);

        $sql = "SELECT DISTINCT keyword 
                FROM keywords 
                WHERE keyword LIKE '%$searchTerm%' 
                ORDER BY keyword ASC
                LIMIT 20";

        $result = $db->query($sql);
        $keywords = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $keywords[] = ["value" => $row['keyword']];
            }
        }

        return $keywords;
    }

    // Associate keywords with product
    public function associateKeywords(int $product_id, array $keywords): bool {
        $conn = $this->db_conn();
        
        // First, clear existing associations
        $stmt = $conn->prepare("DELETE FROM product_keywords WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
        
        // Then add new associations
        foreach ($keywords as $keyword) {
            // Get keyword_id
            $stmt = $conn->prepare("SELECT keyword_id FROM keywords WHERE keyword_name = ?");
            $stmt->bind_param("s", $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $keyword_id = $row['keyword_id'];
                
                // Insert association
                $stmt2 = $conn->prepare("INSERT INTO product_keywords (product_id, keyword_id) VALUES (?, ?)");
                $stmt2->bind_param("ii", $product_id, $keyword_id);
                $stmt2->execute();
                $stmt2->close();
            }
            $stmt->close();
        }
        
        return true;
    }

    public function getProductKeywords(int $product_id): array {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("
            SELECT k.keyword_name 
            FROM keywords k
            JOIN product_keywords pk ON k.keyword_id = pk.keyword_id
            WHERE pk.product_id = ?
        ");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $keywords = [];
        while ($row = $result->fetch_assoc()) {
            $keywords[] = $row['keyword_name'];
        }
        $stmt->close();
        
        return $keywords;
    }
}