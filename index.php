<?php
session_start(); // start the session

// Example: $_SESSION['user'] could store email or user ID after login
$isLoggedIn = isset($_SESSION['user']);
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
            <a class="navbar-brand" href="index.php"></a>
            <div class="d-flex">
                <?php if (!$isLoggedIn): ?>
                    <!-- Show Register/Login buttons if not logged in -->
                    <a href="view/register.php" class="btn btn-outline-primary me-2">Register</a>
                    <a href="view/login.php" class="btn btn-primary">Login</a>
                <?php else: ?>
                    <!-- Show user info and Logout button if logged in -->
                    <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Welcome to the Great HomePage</h1>
        <p>This is the home page.</p>
    </div>
</body>
</html>
