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
        $user_id = intval($created_by);

        // Insert product
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_keywords,created_by)
                VALUES ($cat_id, $brand_id, '$title', $price, '$description', '$keywords','$created_by')";
        
        $result = $this->db_query($sql);

        if (!$result) {
            error_log("Insert failed: $sql | DB Error: " . $this->db_conn()->error);
            return false;
        }
    }

    public function add_product_image($product_id, $image_path) {
        $product_id = intval($product_id);
        $image_path = mysqli_real_escape_string($this->db_conn(), $image_path);

        $sql = "INSERT INTO product_images (product_id, image_path)
                VALUES ($product_id, '$image_path')";
        
        return $this->db_query($sql);
    }

  
    // public function update($product_id, $cat_id, $brand_id, $title, $price, $description, $image_path, $keywords) {
    //     $sql = "UPDATE products SET 
    //             cat_id=$cat_id, 
    //             brand_id=$brand_id, 
    //             title='$title', 
    //             price=$price, 
    //             description='$description', 
    //             image_path='$image_path', 
    //             keywords='$keywords'
    //             WHERE product_id=$product_id";
    //     return $this->db_query($sql);
    // }

    // public function delete($product_id) {
    //     $sql = "DELETE FROM products WHERE product_id=$product_id";
    //     return $this->db_query($sql);
    // }

    // // Get products created by a specific user
    // public function get_user_products($user_id) {
    //     $user_id = intval($user_id); // security against injection
    //     $sql = "SELECT 
    //                 p.*, 
    //                 c.cat_name, 
    //                 b.brand_name 
    //             FROM products p
    //             JOIN categories c ON p.cat_id = c.cat_id
    //             JOIN brands b ON p.brand_id = b.brand_id
    //             WHERE p.created_by = $user_id";
    //     return $this->db_fetch_all($sql);
    // }

    // // Get all products (admin view)
    // public function get_all_products() {
    //     $sql = "SELECT 
    //                 p.*, 
    //                 c.cat_name, 
    //                 b.brand_name 
    //             FROM products p
    //             JOIN categories c ON p.cat_id = c.cat_id
    //             JOIN brands b ON p.brand_id = b.brand_id";
    //     return $this->db_fetch_all($sql);
    // }

  
}
?>
