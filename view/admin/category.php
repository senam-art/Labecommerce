<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';

requireLogin();
requireAdmin();

$user_id = getUserId();
$categories = get_user_categories_ctr($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        #content { flex-grow: 1; padding: 20px; }
    </style>
</head>
<body>
    <?php include_once 'sidebar.php'; ?>

    <div id="content">
        <h2>Category Management</h2>

        <form id="addCategoryForm" class="mb-3">
            <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Enter category name" required>
            <button type="submit" class="btn btn-primary mt-2">Add Category</button>
        </form>

        <h4>Categories</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Created By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="categoryTable"></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/category.js"></script>
</body>
</html>
