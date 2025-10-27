<?php

require_once __DIR__ . '/../settings/core.php';
require_once PROJECT_ROOT . '/settings/db_class.php';




class Category extends db_connection {

    public function add_category($name, $created_by) {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $sql = "INSERT INTO categories (cat_name, created_by) VALUES ('$name', '$created_by')";
        return $this->db_query($sql);
    }

    public function get_user_categories($user_id) {
        $sql = "SELECT * FROM categories WHERE created_by = '$user_id'";
        return $this->db_fetch_all($sql);
    }

    public function update_category($cat_id, $new_name) {
        $new_name = mysqli_real_escape_string($this->db_conn(), $new_name);
        $sql = "UPDATE categories SET cat_name = '$new_name' WHERE cat_id = '$cat_id'";
        return $this->db_query($sql);
    }

    public function delete_category($cat_id) {
        $sql = "DELETE FROM categories WHERE cat_id = '$cat_id'";
        return $this->db_query($sql);
  
    }

    
    public function get_all_categories(){
        $sql = "SELECT c.cat_id, c.cat_name, u.customer_name as created_by 
                FROM categories c 
                LEFT JOIN customer u ON c.created_by = u.customer_id 
                ORDER BY c.cat_id DESC";
        return $this->db_fetch_all($sql);
    }

}
