<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Log the user out
logoutUser();

// Set a logout message
setMessage('You have been logged out successfully.', 'info');

// Redirect to home page
redirect('index.php');