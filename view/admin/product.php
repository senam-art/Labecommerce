<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../settings/core.php';
require_once PROJECT_ROOT . '/controllers/category_controller.php';
require_once PROJECT_ROOT . '/controllers/brand_controller.php';
require_once PROJECT_ROOT . '/controllers/product_controller.php';

requireLogin();
requireAdmin();

$user_id = getUserId();
$categories = get_user_categories_ctr($user_id);
$brands = get_user_brands_ctr($user_id);
// $products = get_user_products_ctr($user_id);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FilePond core styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.9/filepond.min.css" rel="stylesheet">

    <!-- FilePond image preview styles -->
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        #sidebar {
            width: 220px;
            background: #343a40;
            color: #fff;
            flex-shrink: 0;
        }
        #sidebar a {
            color: #fff;
            display: block;
            padding: 10px 15px;
            text-decoration: none;
        }
        #sidebar a.active {
            background: #495057;
        }
        #content {
            flex-grow: 1;
            padding: 20px;
        }
       
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div id="content">
    <h2>Product Management</h2>

    <!-- Add Product Button -->
    <button id="showProductFormBtn" class="btn btn-success mb-3">Add Product</button>

    <!-- Add/Edit Product Form (Initially Hidden) -->
    <form id="productForm" class="hidden" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="text" class="form-control" name="title" id="title" placeholder="Product Title" required>
        </div>

        <div class="mb-3">
            <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Product Price" required>
        </div>

        <div class="mb-3">
            <select name="cat_id" id="cat_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['cat_id'] ?>"><?= $cat['cat_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <select name="brand_id" id="brand_id" class="form-control" required>
                <option value="">Select Brand</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['brand_id'] ?>"><?= $brand['brand_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
                <textarea class="form-control" name="description" id="description" placeholder="Product Description"></textarea>
            </div>

        <div class="mb-3">
            <label for="images" class="form-label">Product Images (Max 5)</label>
            <input 
                type="file" 
                class="filepond" 
                name="images[]" 
                id="images" 
                multiple 
                accept="image/*" 
                required
            >
            <small class="text-muted">You can upload up to 5 images</small>
        </div>


            <div class="mb-3">
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Product Keywords">
            </div>

            <button type="submit" class="btn btn-primary">Save Product</button>
            <button type="button" class="btn btn-secondary" id="cancelProductFormBtn">Cancel</button>
    </form>

    <hr>

        <!-- Products Table -->
        <h4>Products</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productTable"></tbody>
        </table>
</div>




<!-- FilePond and plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.9/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>

<script>
  // Register plugins
  FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
  );

  // Get file input element
  const inputElement = document.querySelector('#images');

  // Create FilePond instance
  FilePond.create(inputElement, {
    allowMultiple: true,
    maxFiles: 5,
    acceptedFileTypes: ['image/*'],
    labelIdle: '📁 Drag & Drop your images or <span class="filepond--label-action">Browse</span>',
    maxFileSize: '3MB'
   
  });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../js/product.js"></script>
</body>
</html>
