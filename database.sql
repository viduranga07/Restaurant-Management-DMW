CREATE DATABASE IF NOT EXISTS restaurant_management;
USE restaurant_management;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Manager','Staff') NOT NULL DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    status ENUM('Available','Unavailable') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount_rate DECIMAL(5,2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending','Preparing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

INSERT INTO users (name,email,password,role) VALUES
('System Manager','manager@gastronova.com','$2y$12$VxEceS3.RdPW/dKnU9FyYe9mQaMEaAK4gADgHBLZx3pSM3TshewtS','Manager');

INSERT INTO menu_items (name,category,price,stock,status) VALUES
('Chicken Fried Rice','Fried Rice',950,30,'Available'),
('Chicken Kottu','Kottu',1100,25,'Available'),
('Chicken Biriyani','Biriyani',1250,20,'Available'),
('Nasi Goreng','Rice',1050,20,'Available'),
('Chicken Burger','Burger',900,18,'Available'),
('Mongolian Chicken','Mongolian',1350,15,'Available'),
('Thai Chicken','Thai',1200,15,'Available'),
('Vegetable Kottu','Kottu',800,20,'Available');
