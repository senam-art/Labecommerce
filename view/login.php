
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login & Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
    background-image: url('/images/background3.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.login-section {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>

</head>

<body class="bg-dark">
<main class="d-flex justify-content-center align-items-center vh-100">
<div class="login-section p-5 rounded text-white" style="max-width: 400px; width: 100%;">
<h2 class="text-center mb-3">Welcome Back</h2>
<h3 class="text-center mb-4">Login to your account</h3>

<form id="loginForm" method="POST" class="text-center">

<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="email" class="form-control" id="email" name="email" required>
</div>

<div class="mb-3">
<label for="password" class="form-label">Password</label>
<input type="password" class="form-control" id="password" name="password" required>
</div>

<button type="submit" class="btn btn-primary w-100">Login</button>
</form>

<div class="mt-3 text-center">

<a href="../index.php" class="text-decoration-none">Homepage</a>
</div>
<div class="mt-4 text-center">
<a href="register.php" class="btn w-100">Create an Account</a>
</div>
</div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/login.js"></script>
</body>
</html>