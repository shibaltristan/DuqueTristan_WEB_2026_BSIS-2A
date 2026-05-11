<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'school_transport');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Start session
session_start();

// Helper functions

// Sanitize input for safe SQL usage and remove surrounding whitespace.
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Hash a plain-text password for secure storage.
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify a plain-text password against a stored hash.
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Redirect the browser to another page and stop further execution.
function redirect($url) {
    header("Location: $url");
    exit();
}

// Determine whether a user is currently logged in.
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get the current user's role from the session.
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

// Require the given role for page access.
// If the user is not logged in or does not have the correct role,
// redirect them to the appropriate login page.
function requireRole($role) {
    if (!isLoggedIn() || getUserRole() !== $role) {
        if ($role === 'admin') {
            redirect('admin/login.php');
        }
        redirect('index.php');
    }
}

// Require any logged-in user to access a page.
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php');
    }
}
?>
