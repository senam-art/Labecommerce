<?php
require_once __DIR__ . '/../settings/core.php';
// Destroy session
session_unset();
session_destroy();

// Redirect to homepage
header("Location: ../index.php");
exit();