<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application configuration
define('APP_NAME', 'CineFeel');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/cinefeel');
define('ADMIN_PATH', '/admin');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinefeel');
define('DB_CHARSET', 'utf8mb4');

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('UTC');

// Connect to database
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper functions
if (!function_exists('redirect')) {
    function redirect($url) {
        // Handle absolute URLs
        if (strpos($url, 'http') === 0) {
            header("Location: $url");
            exit();
        }
        
        // Handle admin paths
        $isAdminRequest = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
        $isAdminRedirect = strpos($url, '/admin/') !== false;
        
        // Build base path
        $base = BASE_URL;
        if ($isAdminRequest && !$isAdminRedirect) {
            $base .= '/admin';
        }
        
        // Clean and build final URL
        $redirectUrl = rtrim($base, '/') . '/' . ltrim($url, '/');
        $redirectUrl = preg_replace('/([^:])\/\//', '$1/', $redirectUrl);
        
        header("Location: $redirectUrl");
        exit();
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        if (is_array($data)) {
            return array_map('sanitize', $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('jsonResponse')) {
    function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        exit();
    }
}

if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verifyCsrfToken')) {
    function verifyCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Generate CSRF token for forms
$csrfToken = generateCsrfToken();