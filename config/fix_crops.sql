USE smart_agrosupport;
DELETE FROM crops;
INSERT INTO crops (id, name_en, name_bn, category) VALUES
(1, 'Rice', 'ধন', 'Grain'),
(2, 'Wheat', 'গম', 'Grain'),
(3, 'Potato', 'আল', 'Vegetable'),
(4, 'Onion', 'পযজ', 'Vegetable'),
(5, 'Tomato', 'টমট', 'Vegetable'),
(6, 'Jute', 'পট', 'Fiber'),
(7, 'Corn', 'ভটট', 'Grain'),
(8, 'Lentil', 'মসর ডল', 'Pulse'),
(9, 'Mustard', 'সরষ', 'Oil Seed'),
(10, 'Sugarcane', 'আখ', 'Cash Crop');
