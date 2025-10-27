<?php
require_once 'settings/core.php';





// // Debugging: print all session variables
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <!-- Minimal menu -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a href="index.php" class="navbar-brand">MyStore</a>
    <div class="d-flex">
      <?php if (!isLoggedIn()): ?>
          <a href="view/register.php" class="btn btn-outline-primary me-2">Register</a>
          <a href="view/login.php" class="btn btn-primary">Login</a>
      <?php else: ?>
          <a href="actions/logout.php" class="btn btn-danger me-2">Logout</a>
          <?php if (isAdmin()): ?>
              <a href="view/admin/category.php" class="btn btn-secondary">Category</a>
          <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</nav>

    <div class="container mt-5">
    <?php if (isLoggedIn()): ?> 
        <h1>Welcome <?php echo htmlspecialchars(getUserName()); ?>, to the Great Homepage</h1>
    <?php else: ?>
        <h1>Welcome to the Great HomePage</h1>
    <?php endif; ?>
    <p>This is the home page.</p>
</div>
</body>
</html>
