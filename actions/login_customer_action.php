<?php
header('Content-Type: application/json');
session_start();

$response = array();

try {

    if (isset($_SESSION['user_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'You are already logged in';
        echo json_encode($response);
        exit();
    }

    require_once '../controllers/user_controller.php';

    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;


    if (empty($email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Email and password are required!';
        echo json_encode($response);
        exit();
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Please enter a valid email address!';
        echo json_encode($response);
        exit();
    }


    $user = login_user_ctr($email, $password);

    if ($user) {

        $_SESSION['user_id'] = $user['customer_id'];
        $_SESSION['user_name'] = $user['customer_name'];
        
        $response['status'] = 'success';
        $response['message'] = 'Login successful! Redirecting...';
        $response['user_id'] = $user['customer_id'];
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email or password';
    }

} catch (Exception $e) {

    error_log("Login error: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = 'An error occurred during login. Please try again.';
}

echo json_encode($response);
?>