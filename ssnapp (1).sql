-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 11:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssnapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cricket_matches`
--

CREATE TABLE `cricket_matches` (
  `match_id` int(11) NOT NULL,
  `match_details` varchar(255) NOT NULL,
  `team_a_name` varchar(50) NOT NULL,
  `team_a_scorecard` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_a_scorecard`)),
  `team_b_name` varchar(50) NOT NULL,
  `team_b_scorecard` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_b_scorecard`)),
  `status` enum('Upcoming','Live','Completed') DEFAULT 'Upcoming',
  `description` text DEFAULT NULL,
  `match_banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cricket_matches`
--

INSERT INTO `cricket_matches` (`match_id`, `match_details`, `team_a_name`, `team_a_scorecard`, `team_b_name`, `team_b_scorecard`, `status`, `description`, `match_banner`) VALUES
(1, 'T20 World Cup Final', 'India', '\"IND: 176\\/7 (20.0)\\nVirat Kohli: 76(59)\\nAxar Patel: 47(31)\"', 'Australia', '\"AUS: 169\\/8 (20.0)\\nTravis Head: 43(25)\\nHardik Pandya: 3\\/20\"', 'Completed', 'A thrilling final at the Kensington Oval. India secured their second T20 World Cup title after a nail-biting finish against Australia.', 'assets/images/cricket_banner.png'),
(2, 'IPL 2024: MI vs CSK', 'Mumbai Indians', '\"Yet to bat\"', 'Chennai Super Kings', '\"Yet to bat\"', 'Upcoming', 'The El Clasico of IPL. Five-time champions Mumbai Indians take on defending champions Chennai Super Kings at the iconic Wankhede Stadium.', 'assets/images/cricket_banner.png');

-- --------------------------------------------------------

--
-- Table structure for table `f1_races`
--

CREATE TABLE `f1_races` (
  `race_id` int(11) NOT NULL,
  `race_details` varchar(255) NOT NULL,
  `leaderboard` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`leaderboard`)),
  `status` enum('Upcoming','Live','Completed') DEFAULT 'Upcoming',
  `description` text DEFAULT NULL,
  `race_banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `f1_races`
--

INSERT INTO `f1_races` (`race_id`, `race_details`, `leaderboard`, `status`, `description`, `race_banner`) VALUES
(1, 'Monaco Grand Prix', '\"1. Max Verstappen (Red Bull)\\n2. Lando Norris (McLaren)\\n3. Charles Leclerc (Ferrari)\"', 'Upcoming', 'The jewel in the F1 crown. A tight, twisting street circuit where precision is everything.', 'assets/images/f1_banner.png');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `football_matches`
--

CREATE TABLE `football_matches` (
  `match_id` int(11) NOT NULL,
  `match_details` varchar(255) NOT NULL,
  `team_a_name` varchar(50) NOT NULL,
  `team_a_scorecard` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_a_scorecard`)),
  `team_b_name` varchar(50) NOT NULL,
  `team_b_scorecard` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_b_scorecard`)),
  `status` enum('Upcoming','Live','Completed') DEFAULT 'Upcoming',
  `description` text DEFAULT NULL,
  `match_banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `football_matches`
--

INSERT INTO `football_matches` (`match_id`, `match_details`, `team_a_name`, `team_a_scorecard`, `team_b_name`, `team_b_scorecard`, `status`, `description`, `match_banner`) VALUES
(1, 'Champions League Final', 'Real Madrid', '\"Real Madrid: 2 (Carvajal 74\', Vinicius Jr 83\')\"', 'Dortmund', '\"Dortmund: 0\"', 'Completed', 'Real Madrid extended their record with a 15th European Cup title, defeating Borussia Dortmund at Wembley Stadium.', 'assets/images/football_banner.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `watchlist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`watchlist`)),
  `profile_image` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `remember_token`, `watchlist`, `profile_image`, `created_at`) VALUES
(1, 'suhaas', 'suhaas.n@somaiya.edu', '$2y$10$AHxexnSR2Mbbp/QWOBzuEu4PXOGVyH0tejgXrzS5QsqDO8J.jVSH2', '23491a51608e1718ed78348ecd482f6218550feac51085574dfa67f3655dc20e', NULL, 'default.png', '2026-03-26 12:08:00'),
(2, 'WE23', 'SHEETAL@GMAIL.COM', '$2y$10$wQBgdsS.15Tuf42NUnAOCOS92zMgPpBO17nnbObO8Frj4bcsL0gH6', NULL, NULL, 'default.png', '2026-03-27 09:45:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cricket_matches`
--
ALTER TABLE `cricket_matches`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `f1_races`
--
ALTER TABLE `f1_races`
  ADD PRIMARY KEY (`race_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `football_matches`
--
ALTER TABLE `football_matches`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cricket_matches`
--
ALTER TABLE `cricket_matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `f1_races`
--
ALTER TABLE `f1_races`
  MODIFY `race_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `football_matches`
--
ALTER TABLE `football_matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
