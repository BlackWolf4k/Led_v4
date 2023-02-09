-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 09, 2023 at 05:36 PM
-- Server version: 10.9.4-MariaDB
-- PHP Version: 8.2.1

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
  `path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `id` int(11) NOT NULL,
  `number_of_leds` int(5) NOT NULL,
  `offline_animation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the boards shared informations';

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE `cluster` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the groups';

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

-- --------------------------------------------------------

--
-- Table structure for table `pattern`
--

CREATE TABLE `pattern` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the possible patterns of the animations';

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations about a playlist';

-- --------------------------------------------------------

--
-- Table structure for table `relation_animation_sub_playlist`
--

CREATE TABLE `relation_animation_sub_playlist` (
  `id_animation` int(11) NOT NULL,
  `id_sub_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relationates the animations with a sub playlist';

-- --------------------------------------------------------

--
-- Table structure for table `relation_user_cluster`
--

CREATE TABLE `relation_user_cluster` (
  `id_user` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relationates the users and the clusters';

-- --------------------------------------------------------

--
-- Table structure for table `sub_playlist`
--

CREATE TABLE `sub_playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations about a sub playlist';

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` int(11) NOT NULL,
  `username` int(11) NOT NULL,
  `password` int(11) NOT NULL,
  `token` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Contains the informations of a user';

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `board`
--
ALTER TABLE `board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cluster`
--
ALTER TABLE `cluster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pattern`
--
ALTER TABLE `pattern`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_playlist`
--
ALTER TABLE `sub_playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id`);

--
-- Constraints for table `relation_animation_sub_playlist`
--
ALTER TABLE `relation_animation_sub_playlist`
  ADD CONSTRAINT `relation_animation_sub_playlist_ibfk_1` FOREIGN KEY (`id_animation`) REFERENCES `animation` (`id`),
  ADD CONSTRAINT `relation_animation_sub_playlist_ibfk_2` FOREIGN KEY (`id_sub_playlist`) REFERENCES `sub_playlist` (`id`);

--
-- Constraints for table `relation_user_cluster`
--
ALTER TABLE `relation_user_cluster`
  ADD CONSTRAINT `relation_user_cluster_ibfk_1` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id`),
  ADD CONSTRAINT `relation_user_cluster_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `sub_playlist`
--
ALTER TABLE `sub_playlist`
  ADD CONSTRAINT `sub_playlist_ibfk_1` FOREIGN KEY (`id_playlist`) REFERENCES `playlist` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
