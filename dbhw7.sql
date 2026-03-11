-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 11, 2026 at 08:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbhw7`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `product_id`, `quantity`) VALUES
(1, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(3, 'BANG & OLUFSEN'),
(5, 'SONOS'),
(6, 'Marshall');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment` varchar(100) DEFAULT NULL,
  `pay_status` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `date_transfer` date DEFAULT NULL,
  `time_transfer` time DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `delivery` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `payment`, `pay_status`, `order_date`, `date_transfer`, `time_transfer`, `status`, `delivery`) VALUES
(2, 3, 'COD', 'Paid', '2026-03-10 00:00:00', NULL, NULL, 'completed', 'Steve Roger จังหวัด สุโขทัย'),
(3, 3, 'COD', 'unpaid', '2026-03-10 00:00:00', NULL, NULL, 'cancelled', 'Steve Roger จังหวัด สุโขทัย'),
(4, 3, 'Bank Transfer', 'Paid', '2026-03-11 00:00:00', NULL, NULL, 'completed', 'Steve Roger 123/45 จังหวัด สุโขทัย 12110');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_id`, `product_id`, `quantity`) VALUES
(2, 12, 1),
(3, 21, 10),
(4, 27, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `detail` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `remain` int(11) DEFAULT 0,
  `img_files` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `detail`, `price`, `remain`, `img_files`, `category_id`) VALUES
(10, 'Beosound A9', 'Powerful, minimalist speaker\r\nSound and shape unite in this iconic, customisable speaker. Seven drivers bring the power. A century of craft brings the form.', 150000.00, 20, '10-1.webp,10-2.webp,10-3.webp,10-4.webp,10-5.webp', 3),
(11, 'Beosound Balance', 'Outstanding living room speaker.\r\nOpulent, tactile materials crafted to last. Room-filling, omnidirectional sound. Beosound Balance is a sculptural statement with the smarts to match.', 114900.00, 20, '11-1.webp,11-2.webp,11-3.webp,11-4.webp,11-5.webp,11-6.webp,11-7.webp,11-8.webp', 3),
(12, 'Beosound Emerge', 'Compact WiFi home speaker, A slim bookshelf speaker that fits in anywhere and reaches you everywhere in the room with full-range, ultra-wide sound. ', 47460.00, 29, '12-1.webp,12-2.webp,12-3.webp,12-4.webp,12-5.webp,12-6.webp,12-7.webp,12-8.webp,12-9.webp,12-10.webp', 3),
(13, 'Beosound Level', 'Portable WiFi Speaker,\r\nThe Beosound Level wifi home speaker is our promise of designing future-proof products built to last. You go, Level follows. Bringing power, portability and connectivity wherever it\'s needed. Standing up or laying down, it keeps your favourite songs by your side.', 71190.00, 40, '13-1.webp,13-2.webp,13-3.webp,13-4.webp,13-5.webp,13-6.webp,13-7.webp,13-8.webp', 3),
(14, 'Beosound 2', 'Elegant home speaker,\r\nCaptivating no matter where you place it, this powerful home speaker sounds as beautiful as it looks. ', 183512.00, 30, '14-1.webp,14-2.webp,14-3.webp,14-4.webp,14-5.webp', 3),
(15, 'Beolab 8', 'Compact wifi speaker.\r\nCompact and powerful as a standalone, stereo, or surround sound speaker. With adaptive room compensation, beam width control and room mapping, these are small speakers with big sound. Choose between table, floor, wall, or ceiling mounting to suit your style and needs.', 284760.00, 0, '15-1.webp,15-2.webp,15-3.webp,15-4.webp', 3),
(16, 'Beolab 28', 'Hi-fidelity wireless home speakers.\r\nWall mounted. Floor-standing. It’s your choice. Slim, iconic wireless stereo speakers that enhance every space with hi-res sound. Designed with the future in mind.\r\nEffortlessly connect your Bang & Olufsen speakers to any TV withBeoconnect Core', 753032.00, 10, '16-1.webp,16-2.webp,16-3.webp,16-4.webp,16-5.webp,16-6.webp,16-7.webp,16-8.webp,16-9.webp,16-10.webp', 3),
(17, 'Beolab 50', 'Advanced active loudspeakers.\r\nHigh-end powered speakers, blending best in class audio technology with graceful aesthetics and meticulous craftsmanship. Superior sound, combined with mindfully selected materials and design creates a statement piece for any audiophile space. \r\n', 2524900.00, 10, '17-1.webp,17-2.webp,17-3.webp,17-4.webp,17-5.webp,17-6.webp,17-7.webp,17-8.webp,17-9.webp,17-10.webp,17-11.webp', 3),
(18, 'Beolab 90', 'The ultimate floor standing speakers.\r\nUnprecedented power and acoustic performance. Forged from Scandinavian aluminium and oak, these high end floor standing speakers seamlessly adapt to your living space and music preferences. Experience the best sound, every time. \r\nEffortlessly connect your Bang & Olufsen speakers to any TV withBeoconnect Core.', 6701300.00, 10, '18-1.webp,18-2.webp,18-3.webp,18-4.webp,18-5.webp,18-6.webp,18-7.webp,18-8.webp,18-9.webp,18-10.webp,18-11.webp,18-12.webp', 3),
(19, 'Beosound Shape', 'Modular, wall-mounted speakers.\r\nImmersive sound, customisable design and integrated noise dampers for design-conscious music lovers. Modular speakers built to grow with you. \r\nPrices listed represent starting prices. Installation and cables are not included, as they depend on the individual setup.', 243699.00, 40, '19-1.webp,19-2.webp,19-3.webp,19-4.webp', 3),
(20, 'Beosystem 3000c', 'A recreated classic.\r\nThe iconic Beogram 3000 series, upgraded into one modern-day turntable hifi system. Limited edition of 100 units.\r\nExclusively available in select Bang & Olufsen stores in Europe, USA, Canada, Hong Kong and Singapore. Contact your nearest store for availability.', 949299.00, 5, '20-1.webp,20-2.webp,20-3.webp,20-4.webp,20-5.webp,20-6.webp,20-7.webp,20-8.webp,20-9.webp,20-10.webp,20-11.webp,20-12.webp', 3),
(21, 'Beolab 19', 'Precise subwoofer speaker.\r\nHigh-end wireless subwoofer bringing powerful bass and a unique sculptural design to your living room.\r\nEffortlessly connect your Bang & Olufsen speakers to any TV withBeoconnect Core', 150299.00, 20, '21-1.webp,21-2.webp,21-3.webp,21-4.webp,21-5.webp,21-6.webp,21-7.webp,21-8.webp,21-9.webp,21-10.webp,21-11.webp,21-12.webp,21-13.webp', 3),
(22, 'Arc Ultra', 'Premium Smart Soundbar.\r\nArc Ultra is the sleekest and most powerful soundbar Sonos has ever created. With an all-new acoustic architecture powered by 14 Sonos-engineered drivers and advanced technologies like Sound Motion™, Arc Ultra fills every inch of the room and precisely places sounds all around you for an entertainment experience that feels out of this world.', 39490.00, 20, '22-1.png,22-2.webp,22-3.webp,22-4.webp,22-5.webp,22-6.webp,22-7.webp,22-8.webp', 5),
(23, '9.1.4 Set with Arc Ultra and Sub 4', '-', 68180.00, 10, '23-1.png,23-2.webp,23-3.webp,23-4.webp,23-5.webp,23-6.webp,23-7.webp,23-8.webp,23-9.webp', 5),
(24, '9.1.4 Set with Arc Ultra, Sub 4 and Era 300 Pair', '-', 103160.00, 3, '24-1.avif,24-2.webp,24-3.webp,24-4.webp,24-5.webp,24-6.webp,24-7.webp,24-8.webp,24-9.webp', 5),
(25, '5.1 Set with Beam (Gen 2), Sub Mini and Era 100 Pair', '-', 53260.00, 10, '25-1.png,25-2.webp,25-3.webp,25-4.webp,25-5.webp,25-6.webp,25-7.webp,25-8.webp,25-9.webp', 5),
(26, 'BROMLEY 750', 'Powerful party speaker with True Stereophonic 360° sound', 41100.00, 3, '26-1.webp,26-2.avif,26-3.avif,26-4.avif,26-5.avif,26-6.avif,26-7.avif', 6),
(27, 'HESTON 120', 'Soundbar with Dolby Atmos immersive sound', 41100.00, 2, '27-1.webp,27-2.avif,27-3.avif,27-4.avif,27-5.avif,27-6.avif,27-7.avif', 6),
(28, 'HESTON SUB 200', 'Wireless subwoofer with Bluetooth LE-audio', 18100.00, 4, '28-1.webp,28-2.avif,28-3.avif,28-4.avif,28-5.avif,28-6.avif', 6),
(29, 'ACTON III', 'Compact Bluetooth speaker delivering room-filling sound', 9499.00, 2, '29-1.png,29-2.avif,29-3.avif,29-4.avif,29-5.avif,29-6.avif,29-7.avif,29-8.avif', 6),
(30, 'WOBURN III', 'Bluetooth speaker delivering home-shaking Marshall signature sound', 18990.00, 6, '30-1.png,30-2.avif,30-3.avif,30-4.avif,30-5.avif,30-6.avif,30-7.avif,30-8.avif,30-9.avif', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `gender`, `age`, `province`, `email`, `role`) VALUES
(1, 'admin', '1234', 'Admin', 'System', 'Male', 30, 'กระบี่', 'admin@system.com', 'admin'),
(3, 'user2', '1234WASDwasd', 'Steve', 'Roger', 'ชาย', 48, 'สุโขทัย', 'sroger@mail.com', 'customer'),
(4, 'admin2', '1234WASDwasd', 'Chaichana', 'Klaising', 'ชาย', 40, 'กรุงเทพมหานคร', 'chaichana@mail.com', 'admin'),
(5, 'Phanlop', '1234WASDwasd', 'Phanlop', 'Boonluea', 'ชาย', 22, 'ชลบุรี', 'phanlop.auto@gmail.com', 'customer'),
(6, 'admin3', '1234WASDwasd', 'Sakda', 'Baokam', 'ชาย', 25, 'ประจวบคีรีขันธ์', 'sakda@mail.com', 'manager'),
(7, 'admin4', '1234WASDwasd', 'Nadech', 'Kukimiya', 'ชาย', 20, 'กระบี่', 'dwadwad@mail.com', 'admin'),
(8, 'customer2', '1234WASDwasd', 'Alex', 'Theeradech', 'ชาย', 30, 'เชียงราย', 'alext@mail.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `fk_details_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
