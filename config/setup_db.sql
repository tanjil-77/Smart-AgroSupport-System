-- Smart AgroSupport System Database Setup
-- Complete Fresh Database Schema
-- Note: Run this script as a MySQL user with appropriate privileges
-- File Type: MySQL/MariaDB
-- syntax: mysql

-- MySQL/MariaDB Database Setup
-- DROP DATABASE IF EXISTS smart_agrosupport;
-- CREATE DATABASE smart_agrosupport;
-- USE smart_agrosupport;

-- Note: Save this file as .mysql.sql or change language mode to MySQL in your editor
-- The syntax used here is MySQL/MariaDB specific, not Oracle SQL
-- USE smart_agrosupport; -- Oracle uses ALTER SESSION SET CURRENT_SCHEMA instead

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'farmer', 'buyer') NOT NULL DEFAULT 'farmer',
    location VARCHAR(100),
    nid VARCHAR(20),
    is_verified TINYINT(1) DEFAULT 0,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crops Master Table
CREATE TABLE crops (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_en VARCHAR(100) NOT NULL,
    name_bn VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crop Posts (Marketplace)
CREATE TABLE crop_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    farmer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) DEFAULT 'kg',
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    contact_number VARCHAR(15),
    status ENUM('active', 'sold', 'expired') DEFAULT 'active',
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_farmer (farmer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crop Prices Table
CREATE TABLE crop_prices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    crop_id INT NOT NULL,
    location VARCHAR(100) NOT NULL,
    price_min DECIMAL(10,2) NOT NULL,
    price_max DECIMAL(10,2) NOT NULL,
    price_avg DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) DEFAULT 'kg',
    date_recorded DATE NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_date (date_recorded),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Weather Advisory Rules
CREATE TABLE weather_advisory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    weather_condition VARCHAR(50) NOT NULL,
    temperature_range VARCHAR(20),
    advisory_bn TEXT NOT NULL,
    advisory_en TEXT NOT NULL,
    crop_action TEXT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crop Calendar Table
CREATE TABLE crop_calendar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    crop_id INT NOT NULL,
    month INT NOT NULL,
    task_bn VARCHAR(255),
    task_en VARCHAR(255),
    description TEXT,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Knowledge Base Table
CREATE TABLE knowledge_base (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    title_bn VARCHAR(255),
    content TEXT NOT NULL,
    content_bn TEXT,
    crop_id INT,
    category VARCHAR(50),
    author_id INT,
    is_published TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Test Users (for login testing)
INSERT INTO users (name, email, phone, password, role, location, is_verified) VALUES
('Admin User', 'admin@gmail.com', '01700000001', '$2y$10$dXJ3U1ZLVDAxMDE0MzUxMQEAAAEE.MLDXaJbXSEGUaJBvpEt3V4mS', 'admin', 'Dhaka', 1),
('রহিম কৃষক', 'farmer@gmail.com', '01700000002', '$2y$10$dXJ3U1ZLVDAxMDE0MzUxMQEAAAEE.MLDXaJbXSEGUaJBvpEt3V4mS', 'farmer', 'Cumilla', 1),
('করিম ক্রেতা', 'buyer@gmail.com', '01700000003', '$2y$10$dXJ3U1ZLVDAxMDE0MzUxMQEAAAEE.MLDXaJbXSEGUaJBvpEt3V4mS', 'buyer', 'Dhaka', 1),
('Test Admin', 'ug2102036@cse.pstu.gc.bd', '01234567890', '$2y$10$dXJ3U1ZLVDAxMDE0MzUxMQEAAAEE.MLDXaJbXSEGUaJBvpEt3V4mS', 'admin', 'Patuakhali', 1);

-- Insert Crops Data
INSERT INTO crops (id, name_en, name_bn, category) VALUES
(1, 'Rice', 'ধান', 'Grain'),
(2, 'Wheat', 'গম', 'Grain'),
(3, 'Potato', 'আলু', 'Vegetable'),
(4, 'Onion', 'পেঁয়াজ', 'Vegetable'),
(5, 'Tomato', 'টমেটো', 'Vegetable'),
(6, 'Jute', 'পাট', 'Fiber'),
(7, 'Corn', 'ভুট্টা', 'Grain'),
(8, 'Lentil', 'মসুর ডাল', 'Pulse'),
(9, 'Mustard', 'সরিষা', 'Oil Seed'),
(10, 'Sugarcane', 'আখ', 'Cash Crop');

-- Insert Sample Weather Advisory
INSERT INTO weather_advisory (weather_condition, advisory_bn, advisory_en, priority) VALUES
('Heavy Rain', 'ভারী বৃষ্টি হলে ধান মাড়াই কাজ স্থগিত করুন', 'Suspend rice threshing work', 'high'),
('High Temperature', 'উচ্চ তাপমাত্রায় গাছে বেশি পানি দিন', 'Water plants more frequently', 'medium'),
('Low Temperature', 'ঠান্ডা আবহাওয়ায় শীতকালীন ফসলের যত্ন নিন', 'Care for winter crops', 'medium');

-- Set auto-increment values
ALTER TABLE users AUTO_INCREMENT = 5;
ALTER TABLE crops AUTO_INCREMENT = 11;
