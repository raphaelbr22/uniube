-- Create database if it does not exist
CREATE DATABASE IF NOT EXISTS `product_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `product_db`;

-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
