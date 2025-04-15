<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration for XAMPP
$host = '127.0.0.1';  // Using IP instead of localhost
$dbname = 'helphub';
$username = 'root';
$password = '';  // Default XAMPP MySQL password is empty

try {
    // First try to connect to MySQL without database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Check if database exists
    $result = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->rowCount() == 0) {
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET NAMES utf8mb4");
    
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database Connection Error: " . $e->getMessage() . "\nPlease check:\n1. MySQL service is running in XAMPP\n2. Database credentials are correct\n3. Try accessing phpMyAdmin to verify MySQL is working");
}

// Function to handle database errors
function handleDatabaseError($e) {
    // Log the error
    error_log("Database Error: " . $e->getMessage());
    
    // Return user-friendly error message
    return [
        'success' => false,
        'message' => 'Une erreur est survenue lors de l\'opération. Veuillez réessayer plus tard.'
    ];
}

// Function to execute prepared statements safely
function executeQuery($query, $params = []) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        return handleDatabaseError($e);
    }
}

// Function to get last inserted ID
function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}

// Function to begin transaction
function beginTransaction() {
    global $pdo;
    return $pdo->beginTransaction();
}

// Function to commit transaction
function commitTransaction() {
    global $pdo;
    return $pdo->commit();
}

// Function to rollback transaction
function rollbackTransaction() {
    global $pdo;
    return $pdo->rollBack();
}
?>