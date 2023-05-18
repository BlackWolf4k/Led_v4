-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2023 at 09:23 PM
-- Server version: 10.5.18-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sleds`
--

-- --------------------------------------------------------

--
-- Table structure for table `animation`
--

CREATE TABLE `animation` (
  `id` int(11) NOT NULL,
  `id_pattern` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `leds_number` int(11) NOT NULL,
  `phases` int(11) NOT NULL,
  `delay` int(3) NOT NULL,
  `repeat` int(3) NOT NULL,
  `file_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animation`
--

INSERT INTO `animation` (`id`, `id_pattern`, `id_playlist`, `name`, `leds_number`, `phases`, `delay`, `repeat`, `file_name`) VALUES
(1, 1, 1, 'Test_one', 10, 10, 10, 255, 'admin/1678049606.dat'),
(2, 1, 1, 'Test_two', 10, 10, 20, 255, 'admin/1678049606.dat'),
(3, 1, 1, 'Test_three', 10, 10, 30, 255, 'admin/1678049606.dat'),
(10, 1, 1, 'prova', 10, 10, 1000, 128, 'admin/1678035521.dat'),
(11, 1, 1, 'prova', 20, 5, 600, 136, 'admin/1678049606.dat'),
(14, 1, 1, 'Default', 999, 10, 1000, 255, 'admin/1684060564.dat'),
(17, 1, 21, 'Default', 999, 10, 1000, 255, 'default_animation.dat'),
(18, 1, 1, 'Full_Purple', 80, 10, 1000, 128, 'admin/1684108062.dat');

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `id` int(11) NOT NULL,
  `leds_number` int(5) NOT NULL,
  `notify` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the boards shared informations';

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`id`, `leds_number`, `notify`) VALUES
(1, 10, 1),
(2, 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE `cluster` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the groups';

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`id`, `name`) VALUES
(1, 'Home'),
(9, 'Home'),
(10, 'Home'),
(11, 'Home'),
(12, 'Home'),
(13, 'Home');

-- --------------------------------------------------------

--
-- Table structure for table `light`
--

CREATE TABLE `light` (
  `id` int(11) NOT NULL,
  `id_board` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `id_animation` int(11) NOT NULL,
  `id_sub_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations about a light';

--
-- Dumping data for table `light`
--

INSERT INTO `light` (`id`, `id_board`, `name`, `id_cluster`, `id_animation`, `id_sub_playlist`) VALUES
(3, 1, 'Bed', 1, 18, 1),
(4, 2, 'Desk', 1, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pattern`
--

CREATE TABLE `pattern` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the possible patterns of the animations';

--
-- Dumping data for table `pattern`
--

INSERT INTO `pattern` (`id`, `name`) VALUES
(1, 'Standard'),
(2, 'Rainbow');

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations about a playlist';

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `name`, `id_cluster`) VALUES
(1, 'Home_Animations', 1),
(21, 'BlackWolf4k', 13);

-- --------------------------------------------------------

--
-- Table structure for table `relation_animation_sub_playlist`
--

CREATE TABLE `relation_animation_sub_playlist` (
  `id_animation` int(11) NOT NULL,
  `id_sub_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relationates the animations with a sub playlist';

--
-- Dumping data for table `relation_animation_sub_playlist`
--

INSERT INTO `relation_animation_sub_playlist` (`id_animation`, `id_sub_playlist`) VALUES
(1, 1),
(2, 1),
(3, 1),
(2, 2),
(3, 2),
(10, 1),
(14, 1),
(17, 3),
(18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `relation_user_cluster`
--

CREATE TABLE `relation_user_cluster` (
  `id_user` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relationates the users and the clusters';

--
-- Dumping data for table `relation_user_cluster`
--

INSERT INTO `relation_user_cluster` (`id_user`, `id_cluster`) VALUES
(1, 1),
(22, 13);

-- --------------------------------------------------------

--
-- Table structure for table `sub_playlist`
--

CREATE TABLE `sub_playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations about a sub playlist';

--
-- Dumping data for table `sub_playlist`
--

INSERT INTO `sub_playlist` (`id`, `name`, `id_playlist`) VALUES
(1, 'BedRoom Animations', 1),
(2, 'WorkRoom', 1),
(3, 'BlackWolf4k', 21);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations of a user';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `token`) VALUES
(1, 'admin@sleds.com', 'admin', '$2y$10$ha7GMP1Co8HdJHeGKsDUJ.Ap8W6pA8hsT5jILQaDB7C5hbSlAgCke', 'e7f361164e28f51e24cec1bd7650886c1e8dbaeb'),
(22, 'blackwolf@sleds.com', 'BlackWolf4k', '$2y$10$moX91eucM1/U6hrlSgGRWeL/.5Fhu1YtZeKYmP84W4EB9t3DFoSh.', '605119cacf096e32e33bd5994d06dbac1e71256a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animation`
--
ALTER TABLE `animation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pattern` (`id_pattern`),
  ADD KEY `id_playlist` (`id_playlist`);

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cluster`
--
ALTER TABLE `cluster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `light`
--
ALTER TABLE `light`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_board` (`id_board`),
  ADD KEY `id_animation` (`id_animation`),
  ADD KEY `id_group` (`id_cluster`),
  ADD KEY `id_sub_playlist` (`id_sub_playlist`);

--
-- Indexes for table `pattern`
--
ALTER TABLE `pattern`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cluster` (`id_cluster`);

--
-- Indexes for table `relation_animation_sub_playlist`
--
ALTER TABLE `relation_animation_sub_playlist`
  ADD KEY `id_animation` (`id_animation`),
  ADD KEY `id_sub_playlist` (`id_sub_playlist`);

--
-- Indexes for table `relation_user_cluster`
--
ALTER TABLE `relation_user_cluster`
  ADD KEY `id_cluster` (`id_cluster`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sub_playlist`
--
ALTER TABLE `sub_playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_playlist` (`id_playlist`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animation`
--
ALTER TABLE `animation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `board`
--
ALTER TABLE `board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cluster`
--
ALTER TABLE `cluster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `light`
--
ALTER TABLE `light`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pattern`
--
ALTER TABLE `pattern`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sub_playlist`
--
ALTER TABLE `sub_playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animation`
--
ALTER TABLE `animation`
  ADD CONSTRAINT `animation_ibfk_1` FOREIGN KEY (`id_pattern`) REFERENCES `pattern` (`id`),
  ADD CONSTRAINT `animation_ibfk_2` FOREIGN KEY (`id_playlist`) REFERENCES `playlist` (`id`);

--
-- Constraints for table `light`
--
ALTER TABLE `light`
  ADD CONSTRAINT `light_ibfk_1` FOREIGN KEY (`id_board`) REFERENCES `board` (`id`),
  ADD CONSTRAINT `light_ibfk_2` FOREIGN KEY (`id_animation`) REFERENCES `animation` (`id`),
  ADD CONSTRAINT `light_ibfk_3` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id`),
  ADD CONSTRAINT `light_ibfk_4` FOREIGN KEY (`id_sub_playlist`) REFERENCES `sub_playlist` (`id`);

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `relation_animation_sub_playlist`
--
ALTER TABLE `relation_animation_sub_playlist`
  ADD CONSTRAINT `relation_animation_sub_playlist_ibfk_1` FOREIGN KEY (`id_animation`) REFERENCES `animation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `relation_animation_sub_playlist_ibfk_2` FOREIGN KEY (`id_sub_playlist`) REFERENCES `sub_playlist` (`id`);

--
-- Constraints for table `relation_user_cluster`
--
ALTER TABLE `relation_user_cluster`
  ADD CONSTRAINT `relation_user_cluster_ibfk_1` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `relation_user_cluster_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_playlist`
--
ALTER TABLE `sub_playlist`
  ADD CONSTRAINT `sub_playlist_ibfk_1` FOREIGN KEY (`id_playlist`) REFERENCES `playlist` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
