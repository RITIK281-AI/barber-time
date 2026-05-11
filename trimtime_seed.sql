SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:45";

-- ============================================================
-- DISABLE FK CHECKS THEN DELETE ALL TABLES (phpMyAdmin safe)
-- ============================================================
SET FOREIGN_KEY_CHECKS=0;
DELETE FROM `working_hours`;
DELETE FROM `services`;
DELETE FROM `booking_items`;
DELETE FROM `bookings`;
DELETE FROM `reviews`;
DELETE FROM `loyalty_transactions`;
DELETE FROM `favourite_shops`;
DELETE FROM `barbers`;
DELETE FROM `service_categories`;
DELETE FROM `barber_shops`;
DELETE FROM `users`;
SET FOREIGN_KEY_CHECKS=0;

-- Reset AUTO_INCREMENT
ALTER TABLE `working_hours` AUTO_INCREMENT = 1;
ALTER TABLE `services` AUTO_INCREMENT = 1;
ALTER TABLE `barbers` AUTO_INCREMENT = 1;
ALTER TABLE `service_categories` AUTO_INCREMENT = 1;
ALTER TABLE `barber_shops` AUTO_INCREMENT = 1;
ALTER TABLE `users` AUTO_INCREMENT = 1;

-- ============================================================
-- SERVICE CATEGORIES (7 rows)
-- ============================================================
INSERT INTO `service_categories` (`id`, `name`, `slug`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Haircut',    'haircut',    1, NOW(), NOW()),
(2, 'Shave',      'shave',      2, NOW(), NOW()),
(3, 'Beard Trim', 'beard-trim', 3, NOW(), NOW()),
(4, 'Hair Color', 'hair-color', 4, NOW(), NOW()),
(5, 'Facial',     'facial',     5, NOW(), NOW()),
(6, 'Hair Wash',  'hair-wash',  6, NOW(), NOW()),
(7, 'Kids Cut',   'kids-cut',   7, NOW(), NOW());

-- ============================================================
-- USERS (61 rows)
-- 1 admin, 20 barber_shop owners (barber_shop_id = NULL initially),
-- 20 barbers (role=barber, standalone login only),
-- 20 customers
-- Password: password (bcrypt)
-- ============================================================
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `loyalty_points`, `phone`, `profile_photo`, `address`, `notify_email`, `notify_reminders`, `notify_promotions`, `barber_shop_id`, `remember_token`, `created_at`, `updated_at`) VALUES

-- Admin
(1, 'Diman Pun', 'admin@trimtime.com.np', NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'admin', 0, '9800000001', NULL, 'Pokhara, Gandaki', 1, 1, 0, NULL, NULL, NOW(), NOW()),

-- Barber Shop Owners (IDs 2–21)
(2,  'Ramesh Gurung',     'ramesh.gurung@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234501', NULL, 'Lakeside, Pokhara',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(3,  'Sanjay Thapa',      'sanjay.thapa@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234502', NULL, 'Newroad, Kathmandu',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(4,  'Bikash Rai',        'bikash.rai@trimtime.com.np',        NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234503', NULL, 'Biratnagar-3, Morang',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(5,  'Nabin Shrestha',    'nabin.shrestha@trimtime.com.np',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234504', NULL, 'Butwal-10, Rupandehi',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(6,  'Dipak Magar',       'dipak.magar@trimtime.com.np',       NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234505', NULL, 'Bharatpur-6, Chitwan',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(7,  'Suresh Tamang',     'suresh.tamang@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234506', NULL, 'Hetauda-4, Makwanpur',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(8,  'Prabesh Limbu',     'prabesh.limbu@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234507', NULL, 'Dharan-8, Sunsari',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(9,  'Kamal Adhikari',    'kamal.adhikari@trimtime.com.np',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234508', NULL, 'Itahari-5, Sunsari',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(10, 'Arun Karki',        'arun.karki@trimtime.com.np',        NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234509', NULL, 'Birgunj-4, Parsa',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(11, 'Rajan Pandey',      'rajan.pandey@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234510', NULL, 'Nepalgunj-3, Banke',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(12, 'Saroj Basnet',      'saroj.basnet@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234511', NULL, 'Dhangadhi-2, Kailali',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(13, 'Nirajan Pokhrel',   'nirajan.pokhrel@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234512', NULL, 'Damak-4, Jhapa',           1, 1, 0, NULL, NULL, NOW(), NOW()),
(14, 'Binod Khadka',      'binod.khadka@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234513', NULL, 'Ghorahi-5, Dang',          1, 1, 0, NULL, NULL, NOW(), NOW()),
(15, 'Yogesh Bhandari',   'yogesh.bhandari@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234514', NULL, 'Tulsipur-3, Dang',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(16, 'Manoj Ghimire',     'manoj.ghimire@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234515', NULL, 'Janakpur-2, Dhanusha',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(17, 'Prasad Gautam',     'prasad.gautam@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234516', NULL, 'Bhairahawa-1, Rupandehi',  1, 1, 0, NULL, NULL, NOW(), NOW()),
(18, 'Roshan Dhakal',     'roshan.dhakal@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234517', NULL, 'Baglung-1, Baglung',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(19, 'Bishal Tiwari',     'bishal.tiwari@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234518', NULL, 'Waling-2, Syangja',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(20, 'Suman Koirala',     'suman.koirala@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234519', NULL, 'Damauli-3, Tanahun',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(21, 'Lokendra Paudel',   'lokendra.paudel@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber_shop', 0, '9801234520', NULL, 'Gorkha-1, Gorkha',         1, 1, 0, NULL, NULL, NOW(), NOW()),

-- Barbers login users (IDs 22–41, role=barber, standalone)
(22, 'Hari Gurung',       'hari.gurung@trimtime.com.np',       NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234501', NULL, 'Lakeside, Pokhara',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(23, 'Santosh Thapa',     'santosh.thapa@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234502', NULL, 'Newroad, Kathmandu',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(24, 'Bibek Rai',         'bibek.rai@trimtime.com.np',         NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234503', NULL, 'Biratnagar-3, Morang',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(25, 'Anil Shrestha',     'anil.shrestha@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234504', NULL, 'Butwal-10, Rupandehi',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(26, 'Raju Magar',        'raju.magar@trimtime.com.np',        NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234505', NULL, 'Bharatpur-6, Chitwan',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(27, 'Sunil Tamang',      'sunil.tamang@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234506', NULL, 'Hetauda-4, Makwanpur',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(28, 'Sagar Limbu',       'sagar.limbu@trimtime.com.np',       NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234507', NULL, 'Dharan-8, Sunsari',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(29, 'Rohan Adhikari',    'rohan.adhikari@trimtime.com.np',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234508', NULL, 'Itahari-5, Sunsari',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(30, 'Naresh Karki',      'naresh.karki@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234509', NULL, 'Birgunj-4, Parsa',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(31, 'Deepak Pandey',     'deepak.pandey@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234510', NULL, 'Nepalgunj-3, Banke',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(32, 'Milan Basnet',      'milan.basnet@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234511', NULL, 'Dhangadhi-2, Kailali',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(33, 'Prakash Pokhrel',   'prakash.pokhrel@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234512', NULL, 'Damak-4, Jhapa',           1, 1, 0, NULL, NULL, NOW(), NOW()),
(34, 'Ashok Khadka',      'ashok.khadka@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234513', NULL, 'Ghorahi-5, Dang',          1, 1, 0, NULL, NULL, NOW(), NOW()),
(35, 'Ganesh Bhandari',   'ganesh.bhandari@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234514', NULL, 'Tulsipur-3, Dang',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(36, 'Bishnu Ghimire',    'bishnu.ghimire@trimtime.com.np',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234515', NULL, 'Janakpur-2, Dhanusha',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(37, 'Pawan Gautam',      'pawan.gautam@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234516', NULL, 'Bhairahawa-1, Rupandehi',  1, 1, 0, NULL, NULL, NOW(), NOW()),
(38, 'Suraj Dhakal',      'suraj.dhakal@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234517', NULL, 'Baglung-1, Baglung',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(39, 'Kiran Tiwari',      'kiran.tiwari@trimtime.com.np',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234518', NULL, 'Waling-2, Syangja',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(40, 'Bijay Koirala',     'bijay.koirala@trimtime.com.np',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234519', NULL, 'Damauli-3, Tanahun',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(41, 'Rabindra Paudel',   'rabindra.paudel@trimtime.com.np',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'barber', 0, '9811234520', NULL, 'Gorkha-1, Gorkha',         1, 1, 0, NULL, NULL, NOW(), NOW()),

-- Customers (IDs 42–61)
(42, 'Priya Sharma',      'priya.sharma@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 50,  '9841000001', NULL, 'Baneshwor, Kathmandu',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(43, 'Anish Maharjan',    'anish.maharjan@gmail.com',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 30,  '9841000002', NULL, 'Patan, Lalitpur',          1, 1, 0, NULL, NULL, NOW(), NOW()),
(44, 'Sunita Lama',       'sunita.lama@gmail.com',       NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000003', NULL, 'Bhaktapur-2',              1, 1, 0, NULL, NULL, NOW(), NOW()),
(45, 'Rajesh Budhathoki', 'rajesh.budhathoki@gmail.com', NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 100, '9841000004', NULL, 'Pokhara-8, Kaski',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(46, 'Manisha Oli',       'manisha.oli@gmail.com',       NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 20,  '9841000005', NULL, 'Biratnagar-5',             1, 1, 0, NULL, NULL, NOW(), NOW()),
(47, 'Sujan Pun',         'sujan.pun@gmail.com',         NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000006', NULL, 'Butwal-6, Rupandehi',      1, 1, 0, NULL, NULL, NOW(), NOW()),
(48, 'Kabita Thapa',      'kabita.thapa@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 75,  '9841000007', NULL, 'Chitwan-4',                1, 1, 0, NULL, NULL, NOW(), NOW()),
(49, 'Sandesh Tamang',    'sandesh.tamang@gmail.com',    NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 10,  '9841000008', NULL, 'Hetauda-3, Makwanpur',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(50, 'Binita Rai',        'binita.rai@gmail.com',        NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000009', NULL, 'Dharan-6, Sunsari',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(51, 'Kushal Karki',      'kushal.karki@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 60,  '9841000010', NULL, 'Birgunj-2, Parsa',         1, 1, 0, NULL, NULL, NOW(), NOW()),
(52, 'Alisha Poudel',     'alisha.poudel@gmail.com',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000011', NULL, 'Nepalgunj-2, Banke',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(53, 'Dipesh Basnet',     'dipesh.basnet@gmail.com',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 40,  '9841000012', NULL, 'Dhangadhi-1, Kailali',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(54, 'Shreya Adhikari',   'shreya.adhikari@gmail.com',   NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000013', NULL, 'Damak-3, Jhapa',           1, 1, 0, NULL, NULL, NOW(), NOW()),
(55, 'Aakash Limbu',      'aakash.limbu@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 15,  '9841000014', NULL, 'Ghorahi-3, Dang',          1, 1, 0, NULL, NULL, NOW(), NOW()),
(56, 'Nisha Ghimire',     'nisha.ghimire@gmail.com',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000015', NULL, 'Janakpur-5, Dhanusha',     1, 1, 0, NULL, NULL, NOW(), NOW()),
(57, 'Prabin Gautam',     'prabin.gautam@gmail.com',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 90,  '9841000016', NULL, 'Bhairahawa-3, Rupandehi',  1, 1, 0, NULL, NULL, NOW(), NOW()),
(58, 'Sabina Dhakal',     'sabina.dhakal@gmail.com',     NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000017', NULL, 'Baglung-3, Baglung',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(59, 'Raman Tiwari',      'raman.tiwari@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 25,  '9841000018', NULL, 'Waling-5, Syangja',        1, 1, 0, NULL, NULL, NOW(), NOW()),
(60, 'Puja Koirala',      'puja.koirala@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 0,   '9841000019', NULL, 'Damauli-1, Tanahun',       1, 1, 0, NULL, NULL, NOW(), NOW()),
(61, 'Sudip Paudel',      'sudip.paudel@gmail.com',      NOW(), '$2y$12$mjf3BBDEQeZwIq5/PEv7Y.nNDq5jjYi8.7/Ei4ogBk1qj9EOknFCy', 'user', 5,   '9841000020', NULL, 'Gorkha-3, Gorkha',         1, 1, 0, NULL, NULL, NOW(), NOW());

-- ============================================================
-- BARBER SHOPS (20 rows)
-- owner_name matches user names above (IDs 2–21)
-- ============================================================
INSERT INTO `barber_shops` (`id`, `name`, `address`, `shop_image`, `latitude`, `longitude`, `phone`, `opening_time`, `closing_time`, `owner_name`, `status`, `average_rating`, `total_reviews`, `email`, `district`, `city`, `pan_number`, `number_of_barbers`, `services_offered`, `description`, `admin_remarks`, `reviewed_at`, `created_at`, `updated_at`) VALUES

(1,  'Classic Cut Pokhara',        'Lakeside Road, Baidam',          NULL, 28.2096, 83.9540, '061-123401', '09:00:00', '18:00:00', 'Ramesh Gurung',   'approved', 4.50, 12, 'classiccut@gmail.com',     'Kaski',      'Pokhara',     '100234501', 2, 'Haircut, Shave, Beard Trim, Hair Wash',                'Premium barber shop at Lakeside offering classic and modern cuts.',       'Verified and approved.', NOW(), NOW(), NOW()),
(2,  'New Look Salon Kathmandu',   'Newroad, Kathmandu-29',          NULL, 27.7061, 85.3126, '01-4123402', '09:00:00', '18:00:00', 'Sanjay Thapa',    'approved', 4.20, 20, 'newlook@gmail.com',        'Kathmandu',  'Kathmandu',   '100234502', 3, 'Haircut, Beard Trim, Hair Color, Facial',               'Trendy salon in the heart of Kathmandu with expert stylists.',            'Verified and approved.', NOW(), NOW(), NOW()),
(3,  'Royal Barber Biratnagar',    'Traffic Chowk, Biratnagar-3',    NULL, 26.4525, 87.2718, '021-123403', '09:00:00', '18:00:00', 'Bikash Rai',      'approved', 4.00, 8,  'royalbarber@gmail.com',    'Morang',     'Biratnagar',  '100234503', 2, 'Haircut, Shave, Beard Trim, Kids Cut',                  'Reliable barber shop serving Biratnagar for over 10 years.',             'Verified and approved.', NOW(), NOW(), NOW()),
(4,  'Style Zone Butwal',          'Golpark, Butwal-10',             NULL, 27.6944, 83.4488, '071-123404', '09:00:00', '18:00:00', 'Nabin Shrestha',  'approved', 4.30, 15, 'stylezone@gmail.com',      'Rupandehi',  'Butwal',      '100234504', 2, 'Haircut, Hair Color, Facial, Hair Wash',                'Modern style zone in Butwal with trained professionals.',                 'Verified and approved.', NOW(), NOW(), NOW()),
(5,  'The Gents Studio Chitwan',   'Narayangadh, Bharatpur-6',       NULL, 27.6745, 84.4354, '056-123405', '09:00:00', '18:00:00', 'Dipak Magar',     'approved', 4.60, 18, 'gentstudio@gmail.com',     'Chitwan',    'Bharatpur',   '100234505', 3, 'Haircut, Shave, Beard Trim, Facial, Hair Color',        'Premium gents studio with air conditioning and WiFi.',                   'Verified and approved.', NOW(), NOW(), NOW()),
(6,  'Smart Cuts Hetauda',         'Puspalal Chowk, Hetauda-4',      NULL, 27.4278, 85.0311, '057-123406', '09:00:00', '18:00:00', 'Suresh Tamang',   'approved', 3.90, 6,  'smartcuts@gmail.com',      'Makwanpur',  'Hetauda',     '100234506', 1, 'Haircut, Shave, Kids Cut, Hair Wash',                   'Budget-friendly barber shop in Hetauda city.',                           'Verified and approved.', NOW(), NOW(), NOW()),
(7,  'Urban Barber Dharan',        'B.P. Road, Dharan-8',            NULL, 26.8120, 87.2835, '025-123407', '09:00:00', '18:00:00', 'Prabesh Limbu',   'approved', 4.40, 11, 'urbanbarber@gmail.com',    'Sunsari',    'Dharan',      '100234507', 2, 'Haircut, Beard Trim, Hair Color, Kids Cut',             'Urban style barber shop serving Dharan and surrounding areas.',           'Verified and approved.', NOW(), NOW(), NOW()),
(8,  'Trend Setters Itahari',      'Main Road, Itahari-5',           NULL, 26.6638, 87.2786, '025-123408', '09:00:00', '18:00:00', 'Kamal Adhikari',  'approved', 4.10, 9,  'trendsetters@gmail.com',   'Sunsari',    'Itahari',     '100234508', 2, 'Haircut, Shave, Beard Trim, Facial',                    'Trendy barber shop catering to young professionals.',                    'Verified and approved.', NOW(), NOW(), NOW()),
(9,  'Kings Barber Birgunj',       'Adarshanagar, Birgunj-4',        NULL, 27.0104, 84.8776, '051-123409', '09:00:00', '18:00:00', 'Arun Karki',      'approved', 4.20, 14, 'kingsbarber@gmail.com',    'Parsa',      'Birgunj',     '100234509', 2, 'Haircut, Shave, Hair Color, Hair Wash, Kids Cut',       'Kings barber offering royal grooming experience in Birgunj.',            'Verified and approved.', NOW(), NOW(), NOW()),
(10, 'Elite Gents Nepalgunj',      'Tribhuvan Chowk, Nepalgunj-3',   NULL, 28.0500, 81.6167, '081-123410', '09:00:00', '18:00:00', 'Rajan Pandey',    'approved', 3.80, 7,  'elitegents@gmail.com',     'Banke',      'Nepalgunj',   '100234510', 1, 'Haircut, Beard Trim, Shave, Facial',                    'Elite gents salon providing quality grooming in western Nepal.',          'Verified and approved.', NOW(), NOW(), NOW()),
(11, 'Fresh Look Dhangadhi',       'Pragati Chowk, Dhangadhi-2',     NULL, 28.6833, 80.6000, '091-123411', '09:00:00', '18:00:00', 'Saroj Basnet',    'approved', 4.00, 5,  'freshlook@gmail.com',      'Kailali',    'Dhangadhi',   '100234511', 2, 'Haircut, Shave, Kids Cut, Hair Wash',                   'Fresh Look is the go-to barber shop in Far-Western Nepal.',              'Verified and approved.', NOW(), NOW(), NOW()),
(12, 'The Barber House Damak',     'Damak Bazaar, Damak-4',          NULL, 26.6608, 87.6969, '023-123412', '09:00:00', '18:00:00', 'Nirajan Pokhrel', 'approved', 4.30, 10, 'barberhouse@gmail.com',    'Jhapa',      'Damak',       '100234512', 2, 'Haircut, Beard Trim, Hair Color, Facial, Hair Wash',    'Professional barber house serving the Jhapa district.',                  'Verified and approved.', NOW(), NOW(), NOW()),
(13, 'Scissor Kings Ghorahi',      'Rapti Road, Ghorahi-5',          NULL, 28.0500, 82.4833, '082-123413', '09:00:00', '18:00:00', 'Binod Khadka',    'approved', 3.70, 4,  'scissorkings@gmail.com',   'Dang',       'Ghorahi',     '100234513', 1, 'Haircut, Shave, Kids Cut',                              'Affordable haircuts and shaves in Ghorahi.',                             'Verified and approved.', NOW(), NOW(), NOW()),
(14, 'Grooming Hub Tulsipur',      'Tulsipur Bazaar, Tulsipur-3',    NULL, 28.1333, 82.3000, '082-123414', '09:00:00', '18:00:00', 'Yogesh Bhandari', 'approved', 4.00, 6,  'groomhub@gmail.com',       'Dang',       'Tulsipur',    '100234514', 1, 'Haircut, Beard Trim, Shave, Hair Wash',                 'Complete grooming hub for men in Tulsipur.',                             'Verified and approved.', NOW(), NOW(), NOW()),
(15, 'Janaki Barbers Janakpur',    'Ram Mandir Chowk, Janakpur-2',   NULL, 26.7288, 85.9289, '041-123415', '09:00:00', '18:00:00', 'Manoj Ghimire',   'approved', 4.20, 13, 'janakibarbers@gmail.com',  'Dhanusha',   'Janakpur',    '100234515', 2, 'Haircut, Shave, Beard Trim, Kids Cut, Hair Wash',       'Named after the holy city, offering divine grooming experiences.',        'Verified and approved.', NOW(), NOW(), NOW()),
(16, 'Max Style Bhairahawa',       'Buddha Chowk, Bhairahawa-1',     NULL, 27.5050, 83.4480, '071-123416', '09:00:00', '18:00:00', 'Prasad Gautam',   'approved', 4.50, 16, 'maxstyle@gmail.com',       'Rupandehi',  'Bhairahawa',  '100234516', 3, 'Haircut, Hair Color, Beard Trim, Facial, Hair Wash',    'Max Style is the premium grooming destination near Lumbini.',            'Verified and approved.', NOW(), NOW(), NOW()),
(17, 'Mountain Cuts Baglung',      'Baglung Bazaar, Baglung-1',      NULL, 28.2693, 83.5888, '068-123417', '09:00:00', '18:00:00', 'Roshan Dhakal',   'approved', 3.90, 5,  'mountaincuts@gmail.com',   'Baglung',    'Baglung',     '100234517', 1, 'Haircut, Shave, Kids Cut',                              'Simple and clean barber shop in Baglung hill district.',                 'Verified and approved.', NOW(), NOW(), NOW()),
(18, 'Prime Barbers Waling',       'Waling Bazaar, Waling-2',        NULL, 28.0900, 83.7800, '063-123418', '09:00:00', '18:00:00', 'Bishal Tiwari',   'approved', 4.10, 8,  'primebarbers@gmail.com',   'Syangja',    'Waling',      '100234518', 1, 'Haircut, Beard Trim, Shave, Hair Color',                'Prime barbers serving Syangja district with quality service.',            'Verified and approved.', NOW(), NOW(), NOW()),
(19, 'Gold Scissors Damauli',      'Damauli Bazaar, Damauli-3',      NULL, 27.9763, 84.2906, '065-123419', '09:00:00', '18:00:00', 'Suman Koirala',   'approved', 4.00, 7,  'goldscissors@gmail.com',   'Tanahun',    'Damauli',     '100234519', 2, 'Haircut, Shave, Beard Trim, Hair Wash, Kids Cut',       'Gold standard haircuts and shaves in Damauli, Tanahun.',                 'Verified and approved.', NOW(), NOW(), NOW()),
(20, 'Hill Top Barbers Gorkha',    'Gorkha Bazaar, Gorkha-1',        NULL, 28.0000, 84.6333, '064-123420', '09:00:00', '18:00:00', 'Lokendra Paudel', 'approved', 4.30, 9,  'hilltop@gmail.com',        'Gorkha',     'Gorkha',      '100234520', 2, 'Haircut, Shave, Beard Trim, Facial, Kids Cut',          'Hill top barbers bringing professional grooming to Gorkha district.',    'Verified and approved.', NOW(), NOW(), NOW());

-- ============================================================
-- UPDATE users.barber_shop_id for barber_shop owners (IDs 2–21)
-- ============================================================
UPDATE `users` SET `barber_shop_id` = 1  WHERE `id` = 2;
UPDATE `users` SET `barber_shop_id` = 2  WHERE `id` = 3;
UPDATE `users` SET `barber_shop_id` = 3  WHERE `id` = 4;
UPDATE `users` SET `barber_shop_id` = 4  WHERE `id` = 5;
UPDATE `users` SET `barber_shop_id` = 5  WHERE `id` = 6;
UPDATE `users` SET `barber_shop_id` = 6  WHERE `id` = 7;
UPDATE `users` SET `barber_shop_id` = 7  WHERE `id` = 8;
UPDATE `users` SET `barber_shop_id` = 8  WHERE `id` = 9;
UPDATE `users` SET `barber_shop_id` = 9  WHERE `id` = 10;
UPDATE `users` SET `barber_shop_id` = 10 WHERE `id` = 11;
UPDATE `users` SET `barber_shop_id` = 11 WHERE `id` = 12;
UPDATE `users` SET `barber_shop_id` = 12 WHERE `id` = 13;
UPDATE `users` SET `barber_shop_id` = 13 WHERE `id` = 14;
UPDATE `users` SET `barber_shop_id` = 14 WHERE `id` = 15;
UPDATE `users` SET `barber_shop_id` = 15 WHERE `id` = 16;
UPDATE `users` SET `barber_shop_id` = 16 WHERE `id` = 17;
UPDATE `users` SET `barber_shop_id` = 17 WHERE `id` = 18;
UPDATE `users` SET `barber_shop_id` = 18 WHERE `id` = 19;
UPDATE `users` SET `barber_shop_id` = 19 WHERE `id` = 20;
UPDATE `users` SET `barber_shop_id` = 20 WHERE `id` = 21;

-- ============================================================
-- BARBERS (20 rows — standalone, no user_id)
-- Distributed across 20 shops (1 barber per shop)
-- ============================================================
INSERT INTO `barbers` (`id`, `barber_shop_id`, `name`, `phone`, `email`, `experience_years`, `bio`, `profile_image`, `status`, `unavailable_reason`, `average_rating`, `total_reviews`, `created_at`, `updated_at`) VALUES

(1,  1,  'Hari Gurung',       '9851100001', 'hari.b@classiccut.com.np',      5,  'Specialist in classic and fade cuts. 5 years experience at Lakeside.',          NULL, 'active', NULL, 4.50, 10, NOW(), NOW()),
(2,  2,  'Santosh Thapa',     '9851100002', 'santosh.b@newlook.com.np',      7,  'Expert in modern styling and beard grooming. Kathmandu trained.',               NULL, 'active', NULL, 4.20, 18, NOW(), NOW()),
(3,  3,  'Bibek Rai',         '9851100003', 'bibek.b@royalbarber.com.np',    4,  'Experienced in traditional cuts and straight-razor shaves.',                    NULL, 'active', NULL, 4.00, 7,  NOW(), NOW()),
(4,  4,  'Anil Shrestha',     '9851100004', 'anil.b@stylezone.com.np',       6,  'Hair color and modern fade specialist based in Butwal.',                        NULL, 'active', NULL, 4.30, 13, NOW(), NOW()),
(5,  5,  'Raju Magar',        '9851100005', 'raju.b@gentstudio.com.np',      8,  'Senior stylist with expertise in premium grooming and skin treatments.',        NULL, 'active', NULL, 4.60, 16, NOW(), NOW()),
(6,  6,  'Sunil Tamang',      '9851100006', 'sunil.b@smartcuts.com.np',      3,  'Friendly barber specializing in budget-friendly cuts and kids haircuts.',       NULL, 'active', NULL, 3.90, 5,  NOW(), NOW()),
(7,  7,  'Sagar Limbu',       '9851100007', 'sagar.b@urbanbarber.com.np',    6,  'Modern barber with skills in color treatment and urban style cuts.',            NULL, 'active', NULL, 4.40, 9,  NOW(), NOW()),
(8,  8,  'Rohan Adhikari',    '9851100008', 'rohan.b@trendsetters.com.np',   4,  'Passionate about contemporary trends and facial grooming.',                     NULL, 'active', NULL, 4.10, 8,  NOW(), NOW()),
(9,  9,  'Naresh Karki',      '9851100009', 'naresh.b@kingsbarber.com.np',   9,  'Veteran barber with extensive experience in classic Nepali styles.',            NULL, 'active', NULL, 4.20, 12, NOW(), NOW()),
(10, 10, 'Deepak Pandey',     '9851100010', 'deepak.b@elitegents.com.np',    5,  'Skilled in beard shaping and traditional hot-towel shaves.',                    NULL, 'active', NULL, 3.80, 6,  NOW(), NOW()),
(11, 11, 'Milan Basnet',      '9851100011', 'milan.b@freshlook.com.np',      4,  'Clean and precise cuts, great with kids and elderly customers.',                 NULL, 'active', NULL, 4.00, 4,  NOW(), NOW()),
(12, 12, 'Prakash Pokhrel',   '9851100012', 'prakash.b@barberhouse.com.np',  7,  'Hair color and facial specialist with international training.',                  NULL, 'active', NULL, 4.30, 9,  NOW(), NOW()),
(13, 13, 'Ashok Khadka',      '9851100013', 'ashok.b@scissorkings.com.np',   3,  'Young and enthusiastic barber focused on trendy cuts.',                         NULL, 'active', NULL, 3.70, 3,  NOW(), NOW()),
(14, 14, 'Ganesh Bhandari',   '9851100014', 'ganesh.b@groomhub.com.np',      5,  'Complete grooming professional with a calm and friendly approach.',              NULL, 'active', NULL, 4.00, 5,  NOW(), NOW()),
(15, 15, 'Bishnu Ghimire',    '9851100015', 'bishnu.b@janakibarbers.com.np', 6,  'Traditional and modern cut expert in Janakpur region.',                         NULL, 'active', NULL, 4.20, 11, NOW(), NOW()),
(16, 16, 'Pawan Gautam',      '9851100016', 'pawan.b@maxstyle.com.np',       8,  'Premium stylist near Lumbini area. Expert in hair color and texture.',          NULL, 'active', NULL, 4.50, 14, NOW(), NOW()),
(17, 17, 'Suraj Dhakal',      '9851100017', 'suraj.b@mountaincuts.com.np',   3,  'Simple and affordable cuts for the Baglung hill community.',                    NULL, 'active', NULL, 3.90, 4,  NOW(), NOW()),
(18, 18, 'Kiran Tiwari',      '9851100018', 'kiran.b@primebarbers.com.np',   5,  'Beard and fade expert serving Syangja district.',                               NULL, 'active', NULL, 4.10, 7,  NOW(), NOW()),
(19, 19, 'Bijay Koirala',     '9851100019', 'bijay.b@goldscissors.com.np',   6,  'Gold standard barber with expertise in Nepali and modern styles.',              NULL, 'active', NULL, 4.00, 6,  NOW(), NOW()),
(20, 20, 'Rabindra Paudel',   '9851100020', 'rabindra.b@hilltop.com.np',     7,  'Hill Top senior barber. Expert in classic cuts, shaves and facials.',           NULL, 'active', NULL, 4.30, 8,  NOW(), NOW());

-- ============================================================
-- SERVICES (100 rows — 5 per shop)
-- category_id: 1=Haircut, 2=Shave, 3=Beard Trim, 4=Hair Color, 5=Facial, 6=Hair Wash, 7=Kids Cut
-- ============================================================
INSERT INTO `services` (`id`, `barber_shop_id`, `category_id`, `name`, `description`, `price`, `duration`, `status`, `created_at`, `updated_at`) VALUES

-- Shop 1: Classic Cut Pokhara
(1,  1, 1, 'Classic Haircut',       'Clean and precise classic haircut for all hair types.',                           200, 30, 'active', NOW(), NOW()),
(2,  1, 2, 'Hot Towel Shave',       'Relaxing hot towel straight-razor shave.',                                        150, 20, 'active', NOW(), NOW()),
(3,  1, 3, 'Beard Trim & Shape',    'Professional beard trimming and shaping.',                                        120, 20, 'active', NOW(), NOW()),
(4,  1, 6, 'Hair Wash & Dry',       'Refreshing hair wash with conditioner and blow dry.',                              100, 20, 'active', NOW(), NOW()),
(5,  1, 7, 'Kids Haircut',          'Gentle and fun haircut for children under 12.',                                    150, 20, 'active', NOW(), NOW()),

-- Shop 2: New Look Salon Kathmandu
(6,  2, 1, 'Fade Haircut',          'Modern fade cut, skin fade or taper fade.',                                        300, 40, 'active', NOW(), NOW()),
(7,  2, 3, 'Beard Design',          'Creative beard design and shaping.',                                               180, 25, 'active', NOW(), NOW()),
(8,  2, 4, 'Global Hair Color',     'Full head hair color with premium dye.',                                          1200, 90, 'active', NOW(), NOW()),
(9,  2, 5, 'Deep Cleansing Facial', 'Deep cleansing facial for men using natural products.',                            500, 45, 'active', NOW(), NOW()),
(10, 2, 1, 'Scissor Cut & Style',   'Full scissor cut with styling and finish.',                                        250, 35, 'active', NOW(), NOW()),

-- Shop 3: Royal Barber Biratnagar
(11, 3, 1, 'Regular Haircut',       'Standard haircut for everyday style.',                                             150, 25, 'active', NOW(), NOW()),
(12, 3, 2, 'Clean Shave',           'Smooth clean shave with fresh blade.',                                             120, 15, 'active', NOW(), NOW()),
(13, 3, 3, 'Mustache Trim',         'Precision trimming of mustache and upper lip area.',                                80, 10, 'active', NOW(), NOW()),
(14, 3, 7, 'Kids Trim',             'Quick and neat trim for children.',                                                130, 15, 'active', NOW(), NOW()),
(15, 3, 1, 'Crew Cut',              'Short military-style crew cut.',                                                   160, 20, 'active', NOW(), NOW()),

-- Shop 4: Style Zone Butwal
(16, 4, 1, 'Trendy Haircut',        'Latest trending hairstyle cut and finish.',                                        250, 35, 'active', NOW(), NOW()),
(17, 4, 4, 'Highlights',            'Partial or full highlights using quality color.',                                   800, 60, 'active', NOW(), NOW()),
(18, 4, 5, 'Gold Facial',           'Gold-infused facial treatment for glowing skin.',                                   600, 50, 'active', NOW(), NOW()),
(19, 4, 6, 'Hair Spa',              'Deep conditioning hair spa treatment.',                                             350, 40, 'active', NOW(), NOW()),
(20, 4, 1, 'Undercut',              'Modern undercut style cut.',                                                       220, 30, 'active', NOW(), NOW()),

-- Shop 5: The Gents Studio Chitwan
(21, 5, 1, 'Premium Haircut',       'Premium haircut with hot towel and styling.',                                       300, 45, 'active', NOW(), NOW()),
(22, 5, 2, 'Royal Shave',           'Premium shave with pre-shave oil and aftershave.',                                  200, 25, 'active', NOW(), NOW()),
(23, 5, 3, 'Full Beard Package',    'Complete beard wash, trim and shape package.',                                      250, 30, 'active', NOW(), NOW()),
(24, 5, 5, 'Anti-Ageing Facial',    'Anti-ageing facial with serum and massage.',                                        700, 60, 'active', NOW(), NOW()),
(25, 5, 4, 'Hair Dye (Single)',     'Single-tone hair dye application.',                                                 600, 50, 'active', NOW(), NOW()),

-- Shop 6: Smart Cuts Hetauda
(26, 6, 1, 'Basic Haircut',         'Affordable and clean basic haircut.',                                               150, 20, 'active', NOW(), NOW()),
(27, 6, 2, 'Quick Shave',           'Fast and clean shave service.',                                                     100, 10, 'active', NOW(), NOW()),
(28, 6, 7, 'Child Haircut',         'Fun and neat haircut for children.',                                                120, 15, 'active', NOW(), NOW()),
(29, 6, 6, 'Shampoo & Wash',        'Hair shampoo and rinse service.',                                                    80, 15, 'active', NOW(), NOW()),
(30, 6, 1, 'Buzz Cut',              'Short buzz cut for a clean fresh look.',                                            120, 15, 'active', NOW(), NOW()),

-- Shop 7: Urban Barber Dharan
(31, 7, 1, 'Urban Fade',            'Modern urban fade with a sharp line.',                                              280, 40, 'active', NOW(), NOW()),
(32, 7, 3, 'Beard Sculpt',          'Detailed beard sculpting for a defined look.',                                      200, 25, 'active', NOW(), NOW()),
(33, 7, 4, 'Fashion Color',         'Bold fashion color for a standout look.',                                          1000, 75, 'active', NOW(), NOW()),
(34, 7, 7, 'Junior Cut',            'Neat junior cut for boys up to 14 years.',                                          150, 20, 'active', NOW(), NOW()),
(35, 7, 1, 'Side Part Cut',         'Classic side part cut with pomade finish.',                                         220, 30, 'active', NOW(), NOW()),

-- Shop 8: Trend Setters Itahari
(36, 8, 1, 'Textured Cut',          'Modern textured crop or quiff cut.',                                               250, 35, 'active', NOW(), NOW()),
(37, 8, 2, 'Straight Razor Shave',  'Traditional straight razor shave with hot towel.',                                  170, 20, 'active', NOW(), NOW()),
(38, 8, 3, 'Beard Maintenance',     'Regular beard trim and oil treatment.',                                              150, 20, 'active', NOW(), NOW()),
(39, 8, 5, 'Refreshing Facial',     'Refreshing citrus facial for men.',                                                  450, 40, 'active', NOW(), NOW()),
(40, 8, 1, 'Long Hair Trim',        'Trim and style for men with longer hair.',                                           200, 25, 'active', NOW(), NOW()),

-- Shop 9: Kings Barber Birgunj
(41, 9, 1, 'Kings Cut',             'Signature kings-style precision cut.',                                              250, 35, 'active', NOW(), NOW()),
(42, 9, 2, 'Foam Shave',            'Classic foam and blade shave.',                                                     130, 15, 'active', NOW(), NOW()),
(43, 9, 4, 'Balayage',              'Natural-looking balayage color technique.',                                        1500, 90, 'active', NOW(), NOW()),
(44, 9, 6, 'Hair Treatment Wash',   'Therapeutic hair wash with anti-dandruff shampoo.',                                 120, 20, 'active', NOW(), NOW()),
(45, 9, 7, 'Kids Smart Cut',        'Stylish and comfortable cut for kids.',                                              140, 15, 'active', NOW(), NOW()),

-- Shop 10: Elite Gents Nepalgunj
(46, 10, 1, 'Elite Haircut',        'Classic elite cut with precision finish.',                                          200, 30, 'active', NOW(), NOW()),
(47, 10, 3, 'Beard & Brow Trim',    'Beard trimming and eyebrow shaping combo.',                                         180, 25, 'active', NOW(), NOW()),
(48, 10, 2, 'Classic Shave',        'Old-school classic shave experience.',                                              130, 15, 'active', NOW(), NOW()),
(49, 10, 5, 'Charcoal Facial',      'Charcoal-based deep cleansing facial.',                                             400, 45, 'active', NOW(), NOW()),
(50, 10, 1, 'Box Fade',             'Sharp box fade cut.',                                                               220, 30, 'active', NOW(), NOW()),

-- Shop 11: Fresh Look Dhangadhi
(51, 11, 1, 'Standard Cut',         'Regular clean haircut service.',                                                    150, 20, 'active', NOW(), NOW()),
(52, 11, 2, 'Fresh Shave',          'Quick and refreshing shave.',                                                       110, 12, 'active', NOW(), NOW()),
(53, 11, 7, 'Kids Cut',             'Safe and friendly kids haircut.',                                                    120, 15, 'active', NOW(), NOW()),
(54, 11, 6, 'Head Wash',            'Basic head wash and rinse.',                                                         80, 15, 'active', NOW(), NOW()),
(55, 11, 1, 'Medium Trim',          'Medium length trim and clean-up.',                                                  160, 20, 'active', NOW(), NOW()),

-- Shop 12: The Barber House Damak
(56, 12, 1, 'Signature Cut',        'House signature precision haircut.',                                                250, 35, 'active', NOW(), NOW()),
(57, 12, 3, 'Beard Trim Pack',      'Complete beard grooming package.',                                                  200, 25, 'active', NOW(), NOW()),
(58, 12, 4, 'Ombre Color',          'Trendy ombre hair color service.',                                                 1800, 100, 'active', NOW(), NOW()),
(59, 12, 5, 'Brightening Facial',   'Brightening and moisturizing facial treatment.',                                    550, 50, 'active', NOW(), NOW()),
(60, 12, 6, 'Deep Hair Wash',       'Deep conditioning hair wash and dry.',                                               150, 25, 'active', NOW(), NOW()),

-- Shop 13: Scissor Kings Ghorahi
(61, 13, 1, 'Regular Cut',          'Standard affordable regular haircut.',                                              150, 20, 'active', NOW(), NOW()),
(62, 13, 2, 'Simple Shave',         'Basic clean shave with fresh blade.',                                               100, 12, 'active', NOW(), NOW()),
(63, 13, 7, 'Boys Cut',             'Quick neat cut for boys.',                                                          120, 15, 'active', NOW(), NOW()),
(64, 13, 1, 'Layer Cut',            'Layered cut for a natural look.',                                                   180, 25, 'active', NOW(), NOW()),
(65, 13, 3, 'Basic Beard Trim',     'Simple beard trim and line-up.',                                                    100, 15, 'active', NOW(), NOW()),

-- Shop 14: Grooming Hub Tulsipur
(66, 14, 1, 'Hub Haircut',          'Quality haircut with a modern touch.',                                              200, 30, 'active', NOW(), NOW()),
(67, 14, 3, 'Beard Shaping',        'Careful beard line-up and shaping.',                                                160, 20, 'active', NOW(), NOW()),
(68, 14, 2, 'Shave & Style',        'Shave followed by face moisturizing.',                                              150, 20, 'active', NOW(), NOW()),
(69, 14, 6, 'Scalp Treatment',      'Scalp oil massage and rinse.',                                                      180, 25, 'active', NOW(), NOW()),
(70, 14, 1, 'Crop Cut',             'Short crop cut with textured top.',                                                 180, 25, 'active', NOW(), NOW()),

-- Shop 15: Janaki Barbers Janakpur
(71, 15, 1, 'Traditional Cut',      'Classic Nepali traditional haircut style.',                                         170, 25, 'active', NOW(), NOW()),
(72, 15, 2, 'Ritual Shave',         'Traditional shave with hot towel.',                                                 140, 18, 'active', NOW(), NOW()),
(73, 15, 3, 'Beard Trim',           'Neat beard trim and shape.',                                                        130, 15, 'active', NOW(), NOW()),
(74, 15, 7, 'Kids Cut',             'Careful and patient kids haircut.',                                                  130, 15, 'active', NOW(), NOW()),
(75, 15, 6, 'Hair Wash',            'Refreshing hair wash service.',                                                      90, 15, 'active', NOW(), NOW()),

-- Shop 16: Max Style Bhairahawa
(76, 16, 1, 'Max Haircut',          'Signature max-style cut and finish.',                                               280, 40, 'active', NOW(), NOW()),
(77, 16, 4, 'Color & Tone',         'Full head color and toning service.',                                              1400, 90, 'active', NOW(), NOW()),
(78, 16, 3, 'Beard Grooming',       'Premium beard grooming with beard oil.',                                            220, 25, 'active', NOW(), NOW()),
(79, 16, 5, 'Skin Brightening',     'Skin brightening facial near Lumbini.',                                             650, 55, 'active', NOW(), NOW()),
(80, 16, 6, 'Conditioning Wash',    'Deep conditioning hair wash and blow dry.',                                          160, 25, 'active', NOW(), NOW()),

-- Shop 17: Mountain Cuts Baglung
(81, 17, 1, 'Simple Haircut',       'Simple clean haircut for mountain folks.',                                          150, 20, 'active', NOW(), NOW()),
(82, 17, 2, 'Blade Shave',          'Clean blade shave with foam.',                                                      100, 12, 'active', NOW(), NOW()),
(83, 17, 7, 'Child Cut',            'Gentle child haircut service.',                                                     120, 15, 'active', NOW(), NOW()),
(84, 17, 1, 'Short Back & Sides',   'Short back and sides with scissor top.',                                            160, 20, 'active', NOW(), NOW()),
(85, 17, 3, 'Quick Beard Trim',     'Quick and neat beard trim.',                                                        100, 12, 'active', NOW(), NOW()),

-- Shop 18: Prime Barbers Waling
(86, 18, 1, 'Prime Cut',            'Quality prime haircut with neat finish.',                                           200, 30, 'active', NOW(), NOW()),
(87, 18, 3, 'Beard Line-Up',        'Sharp beard line-up and trim.',                                                     160, 20, 'active', NOW(), NOW()),
(88, 18, 2, 'Clean Shave',          'Refreshing clean shave.',                                                           130, 15, 'active', NOW(), NOW()),
(89, 18, 4, 'Streaks & Highlights', 'Stylish streaks and highlights.',                                                   700, 60, 'active', NOW(), NOW()),
(90, 18, 1, 'Mid Fade',             'Clean mid fade cut.',                                                               220, 30, 'active', NOW(), NOW()),

-- Shop 19: Gold Scissors Damauli
(91, 19, 1, 'Gold Haircut',         'Premium gold-service haircut.',                                                     220, 30, 'active', NOW(), NOW()),
(92, 19, 2, 'Gold Shave',           'Smooth gold shave with aftershave balm.',                                           150, 18, 'active', NOW(), NOW()),
(93, 19, 3, 'Beard Trim & Oil',     'Beard trim and argan oil treatment.',                                               170, 20, 'active', NOW(), NOW()),
(94, 19, 6, 'Refreshing Wash',      'Scalp and hair refreshing wash.',                                                   100, 15, 'active', NOW(), NOW()),
(95, 19, 7, 'Kids Haircut',         'Comfortable haircut for kids.',                                                     130, 15, 'active', NOW(), NOW()),

-- Shop 20: Hill Top Barbers Gorkha
(96, 20,  1, 'Hill Top Cut',        'Signature hilltop precision haircut.',                                              240, 35, 'active', NOW(), NOW()),
(97, 20,  2, 'Hot Shave',           'Classic hot towel shave.',                                                          160, 18, 'active', NOW(), NOW()),
(98, 20,  3, 'Beard Style',         'Full beard styling and trim.',                                                       200, 25, 'active', NOW(), NOW()),
(99, 20,  5, 'Herbal Facial',       'Herbal and natural ingredient facial.',                                              500, 50, 'active', NOW(), NOW()),
(100,20,  7, 'Junior Cut',          'Friendly junior cut for kids and teens.',                                            150, 20, 'active', NOW(), NOW());

-- ============================================================
-- WORKING HOURS (140 rows — 7 days × 20 shops)
-- 0=Sunday, 1=Monday, ..., 5=Friday open; 6=Saturday closed
-- ============================================================
INSERT INTO `working_hours` (`id`, `barber_shop_id`, `day`, `open_time`, `close_time`, `is_closed`, `created_at`, `updated_at`) VALUES
(1,  1, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(2,  1, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(3,  1, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(4,  1, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(5,  1, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(6,  1, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(7,  1, 6, NULL, NULL, 1, NOW(), NOW()),
(8,  2, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(9,  2, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(10, 2, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(11, 2, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(12, 2, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(13, 2, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(14, 2, 6, NULL, NULL, 1, NOW(), NOW()),
(15, 3, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(16, 3, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(17, 3, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(18, 3, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(19, 3, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(20, 3, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(21, 3, 6, NULL, NULL, 1, NOW(), NOW()),
(22, 4, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(23, 4, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(24, 4, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(25, 4, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(26, 4, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(27, 4, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(28, 4, 6, NULL, NULL, 1, NOW(), NOW()),
(29, 5, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(30, 5, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(31, 5, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(32, 5, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(33, 5, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(34, 5, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(35, 5, 6, NULL, NULL, 1, NOW(), NOW()),
(36, 6, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(37, 6, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(38, 6, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(39, 6, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(40, 6, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(41, 6, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(42, 6, 6, NULL, NULL, 1, NOW(), NOW()),
(43, 7, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(44, 7, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(45, 7, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(46, 7, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(47, 7, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(48, 7, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(49, 7, 6, NULL, NULL, 1, NOW(), NOW()),
(50, 8, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(51, 8, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(52, 8, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(53, 8, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(54, 8, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(55, 8, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(56, 8, 6, NULL, NULL, 1, NOW(), NOW()),
(57, 9, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(58, 9, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(59, 9, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(60, 9, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(61, 9, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(62, 9, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(63, 9, 6, NULL, NULL, 1, NOW(), NOW()),
(64, 10, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(65, 10, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(66, 10, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(67, 10, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(68, 10, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(69, 10, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(70, 10, 6, NULL, NULL, 1, NOW(), NOW()),
(71, 11, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(72, 11, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(73, 11, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(74, 11, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(75, 11, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(76, 11, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(77, 11, 6, NULL, NULL, 1, NOW(), NOW()),
(78, 12, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(79, 12, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(80, 12, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(81, 12, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(82, 12, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(83, 12, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(84, 12, 6, NULL, NULL, 1, NOW(), NOW()),
(85, 13, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(86, 13, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(87, 13, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(88, 13, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(89, 13, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(90, 13, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(91, 13, 6, NULL, NULL, 1, NOW(), NOW()),
(92, 14, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(93, 14, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(94, 14, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(95, 14, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(96, 14, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(97, 14, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(98, 14, 6, NULL, NULL, 1, NOW(), NOW()),
(99, 15, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(100,15, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(101,15, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(102,15, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(103,15, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(104,15, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(105,15, 6, NULL, NULL, 1, NOW(), NOW()),
(106,16, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(107,16, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(108,16, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(109,16, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(110,16, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(111,16, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(112,16, 6, NULL, NULL, 1, NOW(), NOW()),
(113,17, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(114,17, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(115,17, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(116,17, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(117,17, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(118,17, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(119,17, 6, NULL, NULL, 1, NOW(), NOW()),
(120,18, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(121,18, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(122,18, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(123,18, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(124,18, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(125,18, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(126,18, 6, NULL, NULL, 1, NOW(), NOW()),
(127,19, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(128,19, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(129,19, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(130,19, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(131,19, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(132,19, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(133,19, 6, NULL, NULL, 1, NOW(), NOW()),
(134,20, 0, '09:00:00', '18:00:00', 0, NOW(), NOW()),(135,20, 1, '09:00:00', '18:00:00', 0, NOW(), NOW()),(136,20, 2, '09:00:00', '18:00:00', 0, NOW(), NOW()),(137,20, 3, '09:00:00', '18:00:00', 0, NOW(), NOW()),(138,20, 4, '09:00:00', '18:00:00', 0, NOW(), NOW()),(139,20, 5, '09:00:00', '18:00:00', 0, NOW(), NOW()),(140,20, 6, NULL, NULL, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
