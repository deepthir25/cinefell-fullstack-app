<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Store logout message before destroying session
$_SESSION['flash_message'] = 'You have been logged out successfully.';
$_SESSION['flash_type'] = 'success';

// Perform logout
logoutUser();

// Redirect to home page
redirect('index.php');  // This will use BASE_URL automatically