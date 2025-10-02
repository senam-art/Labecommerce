<?php
require_once '../settings/core.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to homepage
header("Location: ../index.php");
exit();