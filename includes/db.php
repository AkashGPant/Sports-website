<?php
$host = 'localhost';
$db   = 'ssnapp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For a professional look, don't show the error to the user in production
     // but for development, we can log it or show it.
     die("Connection failed: " . $e->getMessage());
}

// Define base URL for the project
define('BASE_URL', '/SSNAPP/');
?>
