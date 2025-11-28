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

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <link rel="stylesheet" href="../../css/basic_css.css">


    <!-- JS -->


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

    
        .hidden {
            display: none;
        }
        
        /* Optional: Better styling for Tagify */
        .tagify {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem;
        }

       
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div id="content">
    <h2>Product Management</h2>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">Add Product</button>

    <!-- Bootstrap modal for Add/Edit Product -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <form id="productForm" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="productModalLabel">Add Product</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" name="title" id="title" placeholder="Product Title" required>
                </div>

                <div class="mb-3">
                    <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Product Price" required>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <select name="cat_id" id="cat_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['cat_id'] ?>"><?= $cat['cat_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <select name="brand_id" id="brand_id" class="form-control" required>
                        <option value="">Select Brand</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['brand_id'] ?>"><?= $brand['brand_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="mb-3">
                    <textarea class="form-control" name="description" id="description" placeholder="Product Description"></textarea>
                </div>

                <div class="mb-3">
                  <input name="keywords" id="keywords" placeholder="Add product keywords..." value="" autofocus>
                  <small class="text-muted d-block">Press enter or comma to add keywords</small>
                  <button class='btn btn-sm btn-danger removeTagsBtn mt-2' type='button'>Remove all tags</button>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Product Images (Max 5)</label>
                    <input type="file" class="filepond" name="images[]" id="images" multiple accept="image/*">
                    <small class="text-muted d-block">You can upload up to 5 images (max 3MB each)</small>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

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
            <tbody id="productTable">
                <!-- js will populate -->
            </tbody>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>



<!--  custom scripts -->
<script src="../../js/keywords.js"></script>
<script src="../../js/product.js"></script>

</body>
</html>
