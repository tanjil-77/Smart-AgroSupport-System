-- Smart AgroSupport System Database Schema
-- Create Database
















(10, 'Sugarcane', 'আখ', 'Cash Crop');(9, 'Mustard', 'সরিষা', 'Oil Seed'),(8, 'Lentil', 'মসুর ডাল', 'Pulse'),(7, 'Corn', 'ভুট্টা', 'Grain'),(6, 'Jute', 'পাট', 'Fiber'),(5, 'Tomato', 'টমেটো', 'Vegetable'),(4, 'Onion', 'পেঁয়াজ', 'Vegetable'),(3, 'Potato', 'আলু', 'Vegetable'),(2, 'Wheat', 'গম', 'Grain'),(1, 'Rice', 'ধান', 'Grain'),INSERT INTO crops (id, name_en, name_bn, category) VALUES-- Insert crops with proper Bengali namesDELETE FROM crops;-- Delete old crops dataCREATE DATABASE IF NOT EXISTS smart_agrosupport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_agrosupport;

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
    date DATE NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crop_id) REFERENCES crops(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_date (date),
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

-- Notifications Table
CREATE TABLE notifications (
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
CREATE TABLE crop_calendar (
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
CREATE TABLE knowledge_base (
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

-- Insert Default Admin User (password: admin123)
INSERT INTO users (name, email, phone, password, role, is_verified) VALUES
('Admin User', 'admin@agrosupport.com', '01700000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Delete old crops data first
DELETE FROM crops;

-- Insert Sample Crops with proper Bengali names
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

-- Insert Sample Weather Advisory Rules
INSERT INTO weather_advisory (weather_condition, advisory_bn, advisory_en, priority) VALUES
('Heavy Rain', 'ভারী বৃষ্টির কারণে ফসল কাটা বন্ধ রাখুন। জমিতে জল জমতে পারে, সেচ দেওয়ার প্রয়োজন নেই।', 'Stop harvesting due to heavy rain. Water may accumulate in fields, no need for irrigation.', 'high'),
('Drought', 'খরার কারণে নিয়মিত সেচ দিন। ফসলে পানির চাহিদা পূরণ করুন।', 'Irrigate regularly due to drought. Meet crop water requirements.', 'high'),
('Moderate Rain', 'মাঝারি বৃষ্টি ফসলের জন্য ভালো। সার প্রয়োগ করতে পারেন।', 'Moderate rain is good for crops. You can apply fertilizer.', 'medium'),
('Hot Weather', 'গরম আবহাওয়ায় বিকালে সেচ দিন। ফসলে ছায়া ব্যবস্থা করুন।', 'Irrigate in the evening during hot weather. Provide shade to crops.', 'medium'),
('Cold Weather', 'শীতকালীন ফসলের জন্য উপযুক্ত সময়। তুষার থেকে রক্ষা করুন।', 'Suitable time for winter crops. Protect from frost.', 'low');

-- Insert Sample Knowledge Base
INSERT INTO knowledge_base (title_bn, title_en, category, content_bn, content_en) VALUES
('সার ব্যবহারের নিয়ম', 'Fertilizer Usage Guidelines', 'Fertilizer', 
'সঠিক পরিমাণে সার ব্যবহার করুন। মাটি পরীক্ষার পর সার দিন। জৈব সার ব্যবহার করুন।', 
'Use fertilizer in right amount. Apply after soil testing. Use organic fertilizer.'),

('পোকামাকড় দমন', 'Pest Control', 'Disease Management', 
'নিয়মিত ক্ষেত পরিদর্শন করুন। জৈব কীটনাশক ব্যবহার করুন। রাসায়নিক কীটনাশক সাবধানে ব্যবহার করুন।', 
'Inspect fields regularly. Use organic pesticides. Use chemical pesticides carefully.');
