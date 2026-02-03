-- Add new crops to the database
USE smart_agrosupport;

-- Insert 7 new crops
INSERT INTO crops (id, name_en, name_bn, category) VALUES
(11, 'Garlic', 'রসুন', 'Vegetable'),
(12, 'Eggplant', 'বেগুন', 'Vegetable'),
(13, 'Chili', 'মরিচ', 'Vegetable'),
(14, 'Bean', 'শিম', 'Vegetable'),
(15, 'Papaya', 'পেপে', 'Fruit'),
(16, 'Bottle Gourd', 'লাউ', 'Vegetable'),
(17, 'Sweet Pumpkin', 'মিষ্টি কুমড়া', 'Vegetable')
ON DUPLICATE KEY UPDATE 
    name_en = VALUES(name_en),
    name_bn = VALUES(name_bn),
    category = VALUES(category);
