<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" style="width: 220px; background: #343a40; color: #fff; flex-shrink: 0;">
    <h4 class="p-3">Admin Dashboard</h4>
    <a href="category.php" class="<?= $currentPage == 'category.php' ? 'active' : '' ?>" 
       style="color: #fff; display: block; padding: 10px 15px; text-decoration: none; <?= $currentPage == 'category.php' ? 'background:#495057;' : '' ?>">
       Categories
    </a>
    <a href="brand.php" class="<?= $currentPage == 'brand.php' ? 'active' : '' ?>" 
       style="color: #fff; display: block; padding: 10px 15px; text-decoration: none; <?= $currentPage == 'brand.php' ? 'background:#495057;' : '' ?>">
       Brands
    </a>

    <a href="product.php" class="<?= $currentPage == 'products.php' ? 'active' : '' ?>" 
       style="color: #fff; display: block; padding: 10px 15px; text-decoration: none; <?= $currentPage == 'products.php' ? 'background:#495057;' : '' ?>">
       Products
    </a>
</div>
