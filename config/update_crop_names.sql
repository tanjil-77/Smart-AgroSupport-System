-- Update Crop Names with Proper Bengali Characters
USE smart_agrosupport;

-- Update all crops with correct Bengali names
UPDATE crops SET name_bn = 'ধান', name_en = 'Rice' WHERE id = 1;
UPDATE crops SET name_bn = 'গম', name_en = 'Wheat' WHERE id = 2;
UPDATE crops SET name_bn = 'আলু', name_en = 'Potato' WHERE id = 3;
UPDATE crops SET name_bn = 'পেঁয়াজ', name_en = 'Onion' WHERE id = 4;
UPDATE crops SET name_bn = 'টমেটো', name_en = 'Tomato' WHERE id = 5;
UPDATE crops SET name_bn = 'পাট', name_en = 'Jute' WHERE id = 6;
UPDATE crops SET name_bn = 'ভুট্টা', name_en = 'Corn' WHERE id = 7;
UPDATE crops SET name_bn = 'মসুর ডাল', name_en = 'Lentil' WHERE id = 8;
UPDATE crops SET name_bn = 'সরিষা', name_en = 'Mustard' WHERE id = 9;
UPDATE crops SET name_bn = 'আখ', name_en = 'Sugarcane' WHERE id = 10;
