<?php
/**
 * Database connection using PDO.
 */

$host = '127.0.0.1';
$db   = 'product_db';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
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
    // For local development, display a user-friendly error but also a detailed message.
    die("<div style='font-family: sans-serif; padding: 20px; border: 1px solid #ff5e5e; background-color: #fff0f0; border-radius: 8px; max-width: 600px; margin: 40px auto;'>
            <h3 style='color: #d93838; margin-top: 0;'>Database Connection Failed</h3>
            <p>Could not connect to the database server. Please ensure that XAMPP's MySQL service is running.</p>
            <p style='font-size: 0.85em; color: #666;'><strong>Error details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
