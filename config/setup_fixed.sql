-- Smart AgroSupport System Database Schema
CREATE DATABASE IF NOT EXISTS smart_agrosupport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_agrosupport;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
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
CREATE TABLE IF NOT EXISTS crops (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_en VARCHAR(100) NOT NULL,
    name_bn VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crop Posts (Marketplace)
CREATE TABLE IF NOT EXISTS crop_posts (
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
CREATE TABLE IF NOT EXISTS crop_prices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    crop_id INT NOT NULL,
    location VARCHAR(100) NOT NULL,
    price_min DECIMAL(10,2) NOT NULL,
    price_max DECIMAL(10,2) NOT NULL,
    price_avg DECIMAL(10,2) NOT NULL,
    unit VARCHAR(20) DEFAULT 'kg',
    date DATE NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_date (date),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Weather Advisory Rules
CREATE TABLE IF NOT EXISTS weather_advisory (
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

-- Notifications Table
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('price_alert', 'weather_alert', 'general', 'system') DEFAULT 'general',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crop Calendar
CREATE TABLE IF NOT EXISTS crop_calendar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    crop_id INT NOT NULL,
    season VARCHAR(50),
    sowing_month VARCHAR(50),
    harvesting_month VARCHAR(50),
    duration_days INT,
    tips_bn TEXT,
    tips_en TEXT,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Knowledge Base
CREATE TABLE IF NOT EXISTS knowledge_base (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title_bn VARCHAR(200) NOT NULL,
    title_en VARCHAR(200) NOT NULL,
    category VARCHAR(50),
    content_bn TEXT NOT NULL,
    content_en TEXT NOT NULL,
    image VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Delete existing data (disable foreign key checks)
SET FOREIGN_KEY_CHECKS=0;
DELETE FROM users;
DELETE FROM crops;
DELETE FROM weather_advisory;
DELETE FROM knowledge_base;
DELETE FROM crop_posts;
DELETE FROM crop_prices;
DELETE FROM notifications;
DELETE FROM crop_calendar;
SET FOREIGN_KEY_CHECKS=1;

-- Insert Default Admin User (password: admin123)
INSERT INTO users (name, email, phone, password, role, is_verified) VALUES
('Admin User', 'admin@agrosupport.com', '01700000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Insert Sample Crops
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
(10, 'Sugarcane', 'আখ', 'Cash Crop'),
(11, 'Garlic', 'রসুন', 'Vegetable'),
(12, 'Eggplant', 'বেগুন', 'Vegetable'),
(13, 'Chili', 'মরিচ', 'Vegetable'),
(14, 'Bean', 'শিম', 'Vegetable'),
(15, 'Papaya', 'পেপে', 'Fruit'),
(16, 'Bottle Gourd', 'লাউ', 'Vegetable'),
(17, 'Sweet Pumpkin', 'মিষ্টি কুমড়া', 'Vegetable');

-- Insert Sample Weather Advisory Rules
INSERT INTO weather_advisory (weather_condition, advisory_bn, advisory_en, priority) VALUES
('Heavy Rain', 'Heavy rain - stop harvesting. Fields may get waterlogged. No irrigation needed.', 'Stop harvesting due to heavy rain. Water may accumulate in fields, no need for irrigation.', 'high'),
('Drought', 'Drought - irrigate regularly. Meet crop water requirements.', 'Irrigate regularly due to drought. Meet crop water requirements.', 'high'),
('Moderate Rain', 'Moderate rain is good for crops. You can apply fertilizer.', 'Moderate rain is good for crops. You can apply fertilizer.', 'medium'),
('Hot Weather', 'Hot weather - irrigate in evening. Provide shade to crops.', 'Irrigate in the evening during hot weather. Provide shade to crops.', 'medium'),
('Cold Weather', 'Cold weather - suitable for winter crops. Protect from frost.', 'Suitable time for winter crops. Protect from frost.', 'low');

-- Insert Sample Knowledge Base
INSERT INTO knowledge_base (title, title_bn, content, content_bn, category, is_published) VALUES
('Fertilizer Usage Guidelines', 'Soyabeans ke liye guideline', 
'Use fertilizer in right amount. Apply after soil testing. Use organic fertilizer.', 
'Use fertilizer in right amount. Apply after soil testing. Use organic fertilizer.', 'Fertilizer', 1),

('Pest Control', 'Insect Control', 
'Inspect fields regularly. Use organic pesticides. Use chemical pesticides carefully.', 
'Inspect fields regularly. Use organic pesticides. Use chemical pesticides carefully.', 'Disease Management', 1);
