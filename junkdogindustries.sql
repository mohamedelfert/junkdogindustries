-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2019 at 04:03 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `junkdogindustries`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(4, 'الإسكندرية'),
(3, 'الجيزة'),
(1, 'الغربية'),
(2, 'القاهره'),
(5, 'القليوبية'),
(6, 'المنوفية');

-- --------------------------------------------------------

--
-- Table structure for table `clints`
--

CREATE TABLE `clints` (
  `clint_id` int(11) NOT NULL,
  `username` varchar(75) DEFAULT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `email` varchar(35) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `mobail_1` int(11) DEFAULT NULL,
  `mobail_2` int(11) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `photo` varchar(275) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clints`
--

INSERT INTO `clints` (`clint_id`, `username`, `fullname`, `email`, `password`, `mobail_1`, `mobail_2`, `address`, `photo`, `gender`, `city_id`, `role`) VALUES
(1, 'admin', 'mohamed ibrahiem elfert', 'admin@admin.com', 'admin', 1141521054, 1092648632, 'ibshawie elmalq _ qutor _ ghrbia', 'images/avatar/user5c7feadd06b834.68622598.jpg', 'male', 1, 'admin'),
(2, 'medo', 'mohamed elfert', 'medo@yahoo.com', '123', 1092648632, NULL, 'tanta', 'images/avatar/user5c87de03435f94.58314118.jpg', 'male', 1, 'user'),
(3, 'yosef', 'yosef khaled esa', 'yosef_20@yahoo.com', '123', 1092648633, NULL, 'dfsdfsdf', 'images/avatar/user5c894877dede06.68403062.jpg', 'male', 3, 'user'),
(4, 'ahmed', 'ahmed hamed medo', 'ahmed@yahoo.com', 'ahmed123', 1092648634, 1092648631, 'dsfsdf', 'images/avatar/user5c92ccd9235727.52226982.png', 'female', 5, 'user'),
(6, 'hnan', 'hnan hnan hnan', 'hnan@yahoo.com', '123456', 1124102541, NULL, 'hnan', 'images/avatar/user5c9264ba6ea427.20773949.png', 'female', 2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comm_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `clint_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `comm_date` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(40) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(255) NOT NULL,
  `responce` varchar(255) NOT NULL,
  `clint_id` int(11) NOT NULL,
  `m_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `name`, `email`, `subject`, `message`, `responce`, `clint_id`, `m_date`) VALUES
(1, 'Nasim Melton', 'vehizi@mailinator.net', 'Qui accusamus eu sin', 'Debitis accusamus es', '', 0, ''),
(2, 'Tatum Maddox', 'titolywa@mailinator.net', 'Velit quia in atque ', 'Doloremque doloribus', '', 0, ''),
(3, 'Tanek Cooper', 'duzedotode@mailinator.net', 'Excepteur ut accusan', 'Aut est voluptatem', '', 0, ''),
(4, 'Vera Greene', 'bejuc@mailinator.com', 'Non fugiat mollitia', 'Porro reprehenderit', '', 0, ''),
(5, 'Gil Jacobson', 'ravakaf@mailinator.net', 'Dolorem reprehenderi', 'Sunt commodo libero ', '', 0, ''),
(6, 'mohamed ibrahiem elfert', 'admin@admin.com', 'yrty', 'trytry', '', 1, '2019-03-16 : 09-36-14pm'),
(7, 'mohamed ibrahiem elfert', 'admin@admin.com', 'fgdg', 'fdgfg', '', 1, '2019-03-16 : 09-43-54pm'),
(8, 'fsdf', 'fdsfds@fdsf.fsdf', 'fsdf', 'dfds', '', 0, ''),
(9, 'fdsf', 'fgdg@dsf.fsdf', 'fsd', 'dsff', '', 0, ''),
(10, 'dfdsf', 'gfg@fds.fsdf', 'fdg', 'fgdg', '', 0, '2019-03-16 : 09-47-34pm'),
(11, 'gfdgdfg', 'fgdg@dsf.fsdf', 'fsdf', 'dsf', '', 0, '2019-03-16 : 09-48-59pm');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `clint_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `pro_id`, `clint_id`, `quantity`, `order_date`) VALUES
(1, 1, 2, 3, '19-03-19 : 03-38-02');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `or_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `order_pro_clint`
--
CREATE TABLE `order_pro_clint` (
`order_id` int(11)
,`pro_id` int(11)
,`clint_id` int(11)
,`quantity` int(11)
,`order_date` varchar(50)
,`username` varchar(75)
,`FullName` varchar(150)
,`email` varchar(35)
,`mobail_1` int(11)
,`address` varchar(150)
,`product_id` int(11)
,`product_name` varchar(50)
,`price` float
,`custom` bit(1)
,`status` varchar(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `layers` int(4) DEFAULT NULL,
  `dimension_h` int(4) DEFAULT NULL,
  `dimension_w` int(4) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `pcb_thickness` int(4) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `custom` bit(1) DEFAULT NULL,
  `pro_date` varchar(50) DEFAULT NULL,
  `clint_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `image`, `status`, `file`, `layers`, `dimension_h`, `dimension_w`, `quantity`, `pcb_thickness`, `color`, `notes`, `custom`, `pro_date`, `clint_id`) VALUES
(1, 'product 1 ', 100, 'images/products/post58efd55b450ee.jpg', 'Published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'We are a company specialized in the manufacture of electronic slides always glad to serve you Based on the work of Osama Ibrahim El-Fert Engineer Mechatroniat', b'0', '2019-03-20 : 07-18-16pm', NULL),
(2, 'product 2', 300, 'images/no-image-post.png', 'Published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'We are a company specialized in the manufacture of electronic slides always glad to serve you Based on the work of Osama Ibrahim El-Fert Engineer Mechatroniat', b'0', '2019-03-18 : 08-22-13pm', NULL),
(3, 'product 3', 300, 'images/products/post58efd34363dca.jpg', 'Published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'We are a company specialized in the manufacture of electronic slides always glad to serve you Based on the work of Osama Ibrahim El-Fert Engineer Mechatroniat', b'0', '2019-03-18 : 03-58-08pm', NULL),
(4, 'product 4', 50, 'images/products/post58efd43996f0d.jpg', 'Published', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'We are a company specialized in the manufacture of electronic slides always glad to serve you Based on the work of Osama Ibrahim El-Fert Engineer Mechatroniat', b'0', '2019-03-20 : 07-17-36pm', NULL),
(5, NULL, NULL, NULL, NULL, 'request_file/file5c8fb4a45b1168.90401574.rar', 1, 150, 140, 2, NULL, 'green', 'عايز الشريحة دي وعايز اعرف التكلفة كام ', b'1', '2019-03-18 : 04-09-24pm', 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `stting_id` int(11) NOT NULL,
  `site_name` varchar(40) NOT NULL,
  `site_logo` varchar(255) NOT NULL,
  `slide` varchar(20) NOT NULL,
  `slide_value` int(11) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `instegram` varchar(255) NOT NULL,
  `about_us` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`stting_id`, `site_name`, `site_logo`, `slide`, `slide_value`, `facebook`, `twitter`, `instegram`, `about_us`) VALUES
(1, 'JunkDog Industries', 'images/logo.png', '', 3, 'https://ar-ar.facebook.com', 'https://twitter.com/?lang=ar', 'https://www.instagram.com', 'We are a company specialized in the manufacture of electronic slides always glad to serve you Based on the work of Osama Ibrahim El-Fert Engineer Mechatroniat');

-- --------------------------------------------------------

--
-- Structure for view `order_pro_clint`
--
DROP TABLE IF EXISTS `order_pro_clint`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `order_pro_clint`  AS  select `orders`.`order_id` AS `order_id`,`orders`.`pro_id` AS `pro_id`,`orders`.`clint_id` AS `clint_id`,`orders`.`quantity` AS `quantity`,`orders`.`order_date` AS `order_date`,`clints`.`username` AS `username`,`clints`.`fullname` AS `FullName`,`clints`.`email` AS `email`,`clints`.`mobail_1` AS `mobail_1`,`clints`.`address` AS `address`,`products`.`product_id` AS `product_id`,`products`.`product_name` AS `product_name`,`products`.`price` AS `price`,`products`.`custom` AS `custom`,`products`.`status` AS `status` from ((`orders` join `products` on((`orders`.`pro_id` = `products`.`product_id`))) join `clints` on((`orders`.`clint_id` = `clints`.`clint_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `c_name` (`name`);

--
-- Indexes for table `clints`
--
ALTER TABLE `clints`
  ADD PRIMARY KEY (`clint_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`stting_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `clints`
--
ALTER TABLE `clints`
  MODIFY `clint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `stting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
