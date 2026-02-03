-- Update Bengali names for new crops
USE smart_agrosupport;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

UPDATE crops SET name_bn = 'রসুন' WHERE id = 11;
UPDATE crops SET name_bn = 'বেগুন' WHERE id = 12;
UPDATE crops SET name_bn = 'মরিচ' WHERE id = 13;
UPDATE crops SET name_bn = 'শিম' WHERE id = 14;
UPDATE crops SET name_bn = 'পেপে' WHERE id = 15;
UPDATE crops SET name_bn = 'লাউ' WHERE id = 16;
UPDATE crops SET name_bn = 'মিষ্টি কুমড়া' WHERE id = 17;
