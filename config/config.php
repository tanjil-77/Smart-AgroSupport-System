<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_agrosupport');

// Site Configuration
define('SITE_NAME', 'Smart AgroSupport System');
define('SITE_URL', 'http://localhost/Smart AgroSupport System');
define('TIMEZONE', 'Asia/Dhaka');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8mb4 for Bengali support
    $conn->set_charset("utf8mb4");
    $conn->query("SET NAMES 'utf8mb4'");
    $conn->query("SET CHARACTER SET utf8mb4");
    $conn->query("SET character_set_connection=utf8mb4");
    $conn->query("SET character_set_client=utf8mb4");
    $conn->query("SET character_set_results=utf8mb4");
    $conn->query("SET collation_connection=utf8mb4_unicode_ci");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

function showAlert($message, $type = 'info') {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}
?>
