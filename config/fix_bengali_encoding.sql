-- Fix database charset
ALTER DATABASE smart_agrosupport CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Fix crops table charset
ALTER TABLE crops CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Check if crops have Bengali names
SELECT id, name_bn, name_en FROM crops LIMIT 5;
