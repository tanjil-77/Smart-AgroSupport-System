-- Fix database and table charset
ALTER DATABASE smart_agrosupport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE crops CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE crops MODIFY name_bn VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Clear existing data and insert proper Bengali names
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

SELECT 'Crops updated successfully!' as status;
