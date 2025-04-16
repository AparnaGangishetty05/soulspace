<?php
// Database Configuration

define('DB_HOST', 'localhost');
define('DB_NAME', 'soulspace_db');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize user inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to hash passwords
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify passwords
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}