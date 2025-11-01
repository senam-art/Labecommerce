<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/settings/db_class.php';

class Product extends db_connection {

    public function add($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by, $images) {
        // Sanitize inputs
        $conn = $this->db_conn(); // use a single connection object
        $product_cat = intval($product_cat);
        $product_brand = intval($product_brand);
        $product_title = mysqli_real_escape_string($conn, trim($product_title));
        $product_price = floatval($product_price);
        $product_desc = mysqli_real_escape_string($conn, trim($product_desc));
        $product_keywords = mysqli_real_escape_string($conn, trim($product_keywords));
        $created_by = intval($created_by);

        // Insert product
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_keywords, created_by)
                VALUES ($product_cat, $product_brand, '$product_title', $product_price, '$product_desc', '$product_keywords', $created_by)";
        
        $result = $this->db_query($sql);

        if (!$result) {
            error_log("Insert failed: $sql | DB Error: " . $conn->error);
            return [
                'status' => false,
                'message' => 'Product insert failed.'
            ];
        }

        // Get the product_id of the new product
        $product_id = $conn->insert_id;

        // Handle product images upload
        $user_folder = PROJECT_ROOT . "/uploads/u$created_by/";
        $product_folder = $user_folder . "p$product_id/";

        if (!is_dir($product_folder) && !mkdir($product_folder, 0777, true)) {
            return [
                'status' => false,
                'message' => 'Failed to create product upload directory.'
            ];
        }

        // Loop through uploaded images
        $upload_count = 0;
        foreach ($images['tmp_name'] as $index => $tmp_name) {
            if ($images['error'][$index] !== UPLOAD_ERR_OK) continue; // skip failed uploads
            
            $file_name = time() . '_' . basename($images['name'][$index]);
            $target_file = $product_folder . $file_name;
            $relative_path = "uploads/u$created_by/p$product_id/$file_name"; // store relative path in DB

            if (move_uploaded_file($tmp_name, $target_file)) {
                // Insert image path into product_images
                $sql_img = "INSERT INTO product_images (product_id, image_path) VALUES ($product_id, '$relative_path')";
                $this->db_query($sql_img);
                $upload_count++;
            } else {
                error_log("Failed to move uploaded file: $file_name");
            }
        }

        return [
            'status' => true,
            'message' => "Product added successfully with $upload_count images.",
            'product_id' => $product_id
        ];
    }
}
