<?php
require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/settings/db_class.php';


class Brand extends db_connection {

    public function add($brand_name, $cat_id, $created_by) {
        $brand_name = mysqli_real_escape_string($this->db_conn(), trim($brand_name));
        $cat_id     = intval($cat_id);
        $created_by = intval($created_by);
        
        // Check if brand already exists
        $check = $this->db_fetch_one("SELECT * FROM brands WHERE brand_name='$brand_name' AND cat_id=$cat_id AND created_by=$created_by");
        if($check) return false;
        
        // FIXED: Removed extra quote before closing parenthesis
        $sql = "INSERT INTO brands (brand_name, cat_id, created_by) VALUES ('$brand_name', $cat_id, $created_by)";
        
        $result = $this->db_query($sql);
        if(!$result){
            error_log("Insert failed: $sql | DB Error: " . $this->db_conn()->error);
        }
        return $result;
    }
    


    // Update brand name
    public function update($brand_id, $brand_name) {
        $brand_name = mysqli_real_escape_string($this->db_conn(), trim($brand_name));
        $brand_id = intval($brand_id);
        
        $sql = "UPDATE brands SET brand_name='$brand_name' WHERE brand_id=$brand_id";
        return $this->db_query($sql);
    }

    // Delete a brand
    public function delete($brand_id) {
        $sql = "DELETE FROM brands WHERE brand_id=$brand_id";
        return $this->db_query($sql);
    }

   public function get_user_brands($created_by) {
    // Cast to integer to avoid SQL injection
    $created_by = (int)$created_by;

    $sql = "SELECT 
                b.brand_id, 
                b.brand_name, 
                c.cat_name, 
                cu.customer_name
            FROM brands b
            JOIN categories c ON b.cat_id = c.cat_id
            JOIN customer cu ON b.created_by = cu.customer_id
            WHERE b.created_by = $created_by";

    return $this->db_fetch_all($sql);
}


    // Get all brands 
    public function get_all_brands() {
        $sql = "SELECT b.brand_id, b.brand_name
        FROM brands b
        LEFT JOIN customers u ON b.created_by = u.customer_id
        ORDER BY b.brand_id DESC";
        return $this->db_fetch_all($sql);
    }
}
