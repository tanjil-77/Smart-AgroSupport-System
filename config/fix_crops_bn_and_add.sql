USE smart_agrosupport;

-- Fix existing crops: store Bangla in name_bn
UPDATE crops SET name_bn='ধান' WHERE id=1;
UPDATE crops SET name_bn='গম' WHERE id=2;
UPDATE crops SET name_bn='আলু' WHERE id=3;
UPDATE crops SET name_bn='পেঁয়াজ' WHERE id=4;
UPDATE crops SET name_bn='টমেটো' WHERE id=5;
UPDATE crops SET name_bn='পাট' WHERE id=6;
UPDATE crops SET name_bn='ভুট্টা' WHERE id=7;
UPDATE crops SET name_bn='মসুর ডাল' WHERE id=8;
UPDATE crops SET name_bn='সরিষা' WHERE id=9;
UPDATE crops SET name_bn='আখ' WHERE id=10;

-- Add missing crops (id aligned with existing UI mappings)
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
