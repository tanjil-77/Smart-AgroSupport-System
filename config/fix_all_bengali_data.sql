-- Complete Bengali Data Fix for Dashboard
-- Run this entire file in phpMyAdmin

-- Step 1: Fix Database Charset
ALTER DATABASE smart_agrosupport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 2: Create weather_advisory table if not exists
CREATE TABLE IF NOT EXISTS `weather_advisory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weather_condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `advisory_bn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('high','medium','low') DEFAULT 'medium',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 3: Clear and insert weather advisory data
TRUNCATE TABLE weather_advisory;

INSERT INTO `weather_advisory` (`weather_condition`, `advisory_bn`, `priority`, `is_active`) VALUES
('ভারী বৃষ্টি', 'আগামী ৩ দিন ভারী বৃষ্টির সম্ভাবনা রয়েছে। ফসল সংরক্ষণের ব্যবস্থা নিন এবং নিচু এলাকার ফসল তুলে রাখুন।', 'high', 1),
('খরা', 'আগামী সপ্তাহে বৃষ্টির সম্ভাবনা কম। সেচের ব্যবস্থা করুন এবং পানি সাশ্রয়ী ফসল চাষ করুন।', 'medium', 1),
('মাঝারি বৃষ্টি', 'আগামী ২ দিন মাঝারি বৃষ্টির সম্ভাবনা। রোপণের জন্য উপযুক্ত সময়। সার প্রয়োগে বিলম্ব করুন।', 'low', 1);

-- Step 4: Fix crops table
ALTER TABLE crops CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE crops MODIFY name_bn VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 5: Update crop names with Bengali
UPDATE crops SET name_bn = 'ধান' WHERE name_en = 'Rice';
UPDATE crops SET name_bn = 'গম' WHERE name_en = 'Wheat';
UPDATE crops SET name_bn = 'আলু' WHERE name_en = 'Potato';
UPDATE crops SET name_bn = 'পাট' WHERE name_en = 'Jute';
UPDATE crops SET name_bn = 'আখ' WHERE name_en = 'Sugarcane';
UPDATE crops SET name_bn = 'শিম' WHERE name_en = 'Bean';
UPDATE crops SET name_bn = 'পেঁপে' WHERE name_en = 'Papaya';
UPDATE crops SET name_bn = 'লাউ' WHERE name_en = 'Bottle Gourd';
UPDATE crops SET name_bn = 'পেঁয়াজ' WHERE name_en = 'Onion';
UPDATE crops SET name_bn = 'টমেটো' WHERE name_en = 'Tomato';
UPDATE crops SET name_bn = 'ভুট্টা' WHERE name_en = 'Corn';
UPDATE crops SET name_bn = 'সরিষা' WHERE name_en = 'Mustard';
UPDATE crops SET name_bn = 'রসুন' WHERE name_en = 'Garlic';
UPDATE crops SET name_bn = 'বেগুন' WHERE name_en = 'Eggplant';
UPDATE crops SET name_bn = 'মরিচ' WHERE name_en = 'Chili';
UPDATE crops SET name_bn = 'ডাল' WHERE name_en = 'Lentil';

-- Step 6: Create crop_prices table if not exists
CREATE TABLE IF NOT EXISTS `crop_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crop_id` int(11) NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_min` decimal(10,2) DEFAULT NULL,
  `price_max` decimal(10,2) DEFAULT NULL,
  `price_avg` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `crop_id` (`crop_id`),
  CONSTRAINT `crop_prices_ibfk_1` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 7: Insert sample crop prices for today
DELETE FROM crop_prices WHERE date = CURDATE();

INSERT INTO `crop_prices` (`crop_id`, `location`, `price_min`, `price_max`, `price_avg`, `unit`, `date`) VALUES
(1, 'ঢাকা', 45.00, 55.00, 50.00, 'kg', CURDATE()),
(2, 'ময়মনসিংহ', 38.00, 42.00, 40.00, 'kg', CURDATE()),
(3, 'রাজশাহী', 25.00, 30.00, 28.00, 'kg', CURDATE()),
(4, 'চট্টগ্রাম', 60.00, 70.00, 65.00, 'kg', CURDATE()),
(5, 'খুলনা', 55.00, 65.00, 60.00, 'kg', CURDATE());

-- Step 8: Verify the data
SELECT '=== Weather Advisory ===' as 'Section';
SELECT id, weather_condition, advisory_bn, priority FROM weather_advisory;

SELECT '=== Crops ===' as 'Section';
SELECT id, name_bn, name_en FROM crops LIMIT 10;

SELECT '=== Today Prices ===' as 'Section';
SELECT cp.id, c.name_bn, cp.location, cp.price_avg, cp.unit 
FROM crop_prices cp 
JOIN crops c ON cp.crop_id = c.id 
WHERE cp.date = CURDATE();

SELECT 'All data updated successfully!' as 'Status';
