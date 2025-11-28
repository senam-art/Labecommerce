<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';

requireLogin();
requireAdmin();

$user_id = getUserId();
$categories = get_user_categories_ctr($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">q`
    <title>Brand Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        #content { flex-grow: 1; padding: 20px; }
        #brandTable td, #brandTable th { vertical-align: middle; }
    </style>
</head>
<body>
    <?php include_once 'sidebar.php'; ?>

    <div id="content">
        <h2>Brand Management</h2>

        <!-- Add Brand Form -->
        <form id="addBrandForm" class="mb-3">
            <div class="mb-2">
                <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Enter brand name" required>
            </div>
            <div class="mb-2">
                <select id="brand_category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Brand</button>
        </form>

        <!-- Brands Table -->
        <h4>Brands</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:40%;">Brand Name</th>
                    <th style="width:35%;">Category</th>
                    <th style="width:20%;">Action</th>
                </tr>
            </thead>
            <tbody id="brandTable">
                <!-- JS will populate this -->
            </tbody>
        </table>
    </div>

    
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/brand.js"></script>
</body>
</html>
