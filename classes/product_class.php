<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/settings/db_class.php';

class Product extends db_connection {
    
    public function add($cat_id, $brand_id, $title, $price, $description, $keywords, $user_id) {
        // Sanitize inputs
        $cat_id = intval($cat_id);
        $brand_id = intval($brand_id);
        $title = mysqli_real_escape_string($this->db_conn(), trim($title));
        $price = floatval($price);
        $description = mysqli_real_escape_string($this->db_conn(), trim($description));
        $keywords = mysqli_real_escape_string($this->db_conn(), trim($keywords));
        $user_id = intval($user_id); // Fixed: was $created_by
        
        // Insert product
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_keywords, created_by)
                VALUES ($cat_id, $brand_id, '$title', $price, '$description', '$keywords', $user_id)"; // Fixed: was '$created_by'
        
        $result = $this->db_query($sql);
        
        if (!$result) {
            error_log("Insert failed: $sql | DB Error: " . $this->db_conn()->error);
            return false;
        }
        
        // Return the newly inserted product ID
        return $this->db_conn()->insert_id;
    }
    
    public function add_product_image($product_id, $image_path) {
        $product_id = intval($product_id);
        $image_path = mysqli_real_escape_string($this->db_conn(), $image_path);
        
        $sql = "INSERT INTO product_images (product_id, image_path)
                VALUES ($product_id, '$image_path')";
        
        return $this->db_query($sql);
    }
    
    // Get products created by a specific user with images
    public function get_user_products($user_id) {
        $user_id = intval($user_id);
        
        $sql = "SELECT 
                    p.product_id,
                    p.product_title,
                    p.product_price,
                    p.product_desc,
                    p.product_keywords,
                    p.product_cat,
                    p.product_brand,
                    c.cat_name,
                    b.brand_name,
                    GROUP_CONCAT(pi.image_path) as product_images
                FROM products p
                JOIN categories c ON p.product_cat = c.cat_id
                JOIN brands b ON p.product_brand = b.brand_id
                LEFT JOIN product_images pi ON p.product_id = pi.product_id
                WHERE p.created_by = $user_id
                GROUP BY p.product_id
                ORDER BY p.product_id DESC";
        
        return $this->db_fetch_all($sql);
    }
    
    // Get all products (admin view)
    public function get_all_products($user_id) {
        $user_id = intval($user_id);
        
        $sql = "SELECT 
                    p.product_id,
                    p.product_title,
                    p.product_price,
                    p.product_desc,
                    p.product_keywords,
                    p.product_cat,
                    p.product_brand,
                    c.cat_name,
                    b.brand_name,
                    GROUP_CONCAT(pi.image_path) as product_images
                FROM products p
                JOIN categories c ON p.product_cat = c.cat_id
                JOIN brands b ON p.product_brand = b.brand_id
                LEFT JOIN product_images pi ON p.product_id = pi.product_id
                WHERE p.created_by = $user_id
                GROUP BY p.product_id
                ORDER BY p.product_id DESC";
        
        return $this->db_fetch_all($sql);
    }
    
    public function delete($product_id) {
        $product_id = intval($product_id);
        
        // First delete related images
        $sql = "DELETE FROM product_images WHERE product_id = $product_id";
        $this->db_query($sql);
        
        // Then delete the product
        $sql = "DELETE FROM products WHERE product_id = $product_id";
        return $this->db_query($sql);
    }
}
?>