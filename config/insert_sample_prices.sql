-- Insert sample crop prices
INSERT INTO crop_prices (crop_id, location, price_min, price_max, price_avg, unit, date_recorded, created_by) VALUES
(1, 'Dhaka', 55.00, 65.00, 60.00, 'kg', CURDATE(), 6),
(1, 'Chittagong', 58.00, 68.00, 63.00, 'kg', CURDATE(), 6),
(1, 'Rajshahi', 50.00, 60.00, 55.00, 'kg', CURDATE(), 6),
(2, 'Dhaka', 45.00, 55.00, 50.00, 'kg', CURDATE(), 6),
(2, 'Rangpur', 40.00, 50.00, 45.00, 'kg', CURDATE(), 6),
(3, 'Dhaka', 25.00, 35.00, 30.00, 'kg', CURDATE(), 6),
(3, 'Bogura', 20.00, 30.00, 25.00, 'kg', CURDATE(), 6),
(3, 'Munshiganj', 18.00, 28.00, 23.00, 'kg', CURDATE(), 6),
(4, 'Dhaka', 80.00, 90.00, 85.00, 'kg', CURDATE(), 6),
(4, 'Pabna', 75.00, 85.00, 80.00, 'kg', CURDATE(), 6),
(4, 'Faridpur', 70.00, 80.00, 75.00, 'kg', CURDATE(), 6),
(5, 'Dhaka', 40.00, 60.00, 50.00, 'kg', CURDATE(), 6),
(5, 'Cumilla', 35.00, 55.00, 45.00, 'kg', CURDATE(), 6),
(6, 'Faridpur', 2500.00, 3000.00, 2750.00, 'mon', CURDATE(), 6),
(7, 'Dinajpur', 30.00, 40.00, 35.00, 'kg', CURDATE(), 6),
(8, 'Jessore', 110.00, 130.00, 120.00, 'kg', CURDATE(), 6),
(9, 'Tangail', 90.00, 110.00, 100.00, 'kg', CURDATE(), 6),
(10, 'Natore', 15.00, 25.00, 20.00, 'piece', CURDATE(), 6),
-- Yesterday's data
(1, 'Dhaka', 54.00, 64.00, 59.00, 'kg', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 6),
(3, 'Dhaka', 26.00, 36.00, 31.00, 'kg', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 6),
(4, 'Dhaka', 85.00, 95.00, 90.00, 'kg', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 6);
