-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2025 at 09:52 PM
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
-- Database: `careerhive`
--

-- --------------------------------------------------------

--
-- Table structure for table `acquired`
--

CREATE TABLE `acquired` (
  `askill` int(11) NOT NULL,
  `auser` int(11) NOT NULL,
  `alevel` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `cpost` int(11) NOT NULL,
  `cuser` int(11) NOT NULL,
  `text` varchar(128) NOT NULL,
  `ctime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`cpost`, `cuser`, `text`, `ctime`) VALUES
(11, 7, 'Wow! Very Nice! Good Job baby', '2025-01-21 23:00:28'),
(11, 8, 'test another comment', '2025-01-22 21:55:34'),
(17, 3, 'test hi hello', '2025-01-22 22:30:44'),
(11, 8, 'fuck kendrick lamar', '2025-01-22 23:01:31'),
(11, 7, 'hello test', '2025-01-26 13:19:30'),
(11, 8, 'test hello', '2025-01-26 13:19:30'),
(11, 3, 'hello mohammad', '2025-01-26 13:19:30'),
(19, 8, 'hello', '2025-01-31 21:16:21'),
(19, 8, 'test post comment', '2025-02-02 10:13:18'),
(28, 3, 'penis', '2025-02-02 11:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `csender` int(11) NOT NULL,
  `creceiver` int(11) NOT NULL,
  `cstatus` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`csender`, `creceiver`, `cstatus`) VALUES
(7, 3, 'accepted'),
(7, 8, 'accepted'),
(11, 3, 'accepted'),
(3, 8, 'accepted'),
(11, 7, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `finstitutions` int(11) NOT NULL,
  `fusers` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `iid` int(11) NOT NULL,
  `itype` varchar(128) NOT NULL,
  `iimage` blob DEFAULT NULL,
  `iname` blob NOT NULL,
  `icover` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `lpost` int(11) NOT NULL,
  `luser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`lpost`, `luser`) VALUES
(13, 8),
(14, 7),
(13, 7),
(11, 7),
(14, 3),
(16, 7),
(18, 7),
(13, 11),
(18, 11),
(11, 11),
(17, 3),
(19, 3),
(19, 8);

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `mmajor` int(11) NOT NULL,
  `muser` int(11) NOT NULL,
  `minstiution` int(11) NOT NULL,
  `mstart` date NOT NULL,
  `mend` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `mid` int(11) NOT NULL,
  `mtype` varchar(24) NOT NULL,
  `mname` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `pid` int(11) NOT NULL,
  `pauthor` int(11) NOT NULL,
  `ptext` varchar(1024) NOT NULL,
  `pimage` varchar(256) DEFAULT NULL,
  `pcreation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`pid`, `pauthor`, `ptext`, `pimage`, `pcreation`) VALUES
(11, 3, 'Hello, my name is Ralph.\nFuck you.', 'media/post-image-2.png', '2025-01-19 13:21:27'),
(13, 3, 'helooooo', NULL, '2025-01-19 20:55:17'),
(14, 8, 'hello everyone! i am gay', NULL, '2025-01-19 21:34:45'),
(16, 3, 'fuck me', NULL, '2025-01-21 21:13:52'),
(17, 8, 'heeeeeeyyyyyyyooooooooo', NULL, '2025-01-21 22:01:35'),
(18, 3, 'BACKEND MAKES ME HARD', NULL, '2025-01-22 23:45:26'),
(19, 7, 'i love dick!!!', NULL, '2025-01-25 10:33:34'),
(28, 8, 'test1456', 'media/679e86717855e0.13924133.png', '2025-02-01 22:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `saveditems`
--

CREATE TABLE `saveditems` (
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skillid` int(11) NOT NULL,
  `skillname` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `ufname` varchar(24) NOT NULL,
  `ulname` varchar(24) NOT NULL,
  `uemail` varchar(48) NOT NULL,
  `upass` varchar(1024) NOT NULL,
  `uimage` varchar(256) DEFAULT NULL,
  `udescription` varchar(1024) DEFAULT NULL,
  `ucover` blob DEFAULT NULL,
  `utitle` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `ufname`, `ulname`, `uemail`, `upass`, `uimage`, `udescription`, `ucover`, `utitle`) VALUES
(3, 'Ralph', 'Abou Jaoude', 'ralph.aboujaoude@st.ul.edu.lb', '$2y$10$uvTLFTMgD6jyx7G1q7ZbreI.nW9ru/9ZnNZ6ug8W3agFu0RpJzmhq', 'media/679e75fd6adaa5.36362315.jpg', 'Test', NULL, 'Junior Software Engineer'),
(7, 'Antonio', 'Helou', 'helouantonio@gmail.com', '$2y$10$GCUSydRDQ.PWpfyPApO1M./rGSIjP92QcElRXt9wsO/sXn/q.ABvG', 'media/noprofile.jpg', NULL, NULL, 'House Slave'),
(8, 'Mohammad', 'Hammoud', 'mohammoud@gmail.com', '$2y$10$ab/rUva6Nd0oX90hxnMlTecFvpl/D.UpTmsbL..XGa/9ZhAXSURo.', 'media/noprofile.jpg', NULL, NULL, 'Strip Club Hooker'),
(11, 'Zain', 'Al Abidin Ibrahim', 'zain@gmail.com', '$2y$10$aQ63Ntx/04weDTo77byxdeBZN/CpepQwM9ld2a.Tsl3wTfOzge6Ra', 'media/noprofile.jpg', NULL, NULL, '');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `insert_no_image` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    SET NEW.uimage = 'media/noimage.jpg';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE `work` (
  `winstitution` int(11) NOT NULL,
  `wuser` int(11) NOT NULL,
  `wstarted` datetime NOT NULL,
  `wended` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acquired`
--
ALTER TABLE `acquired`
  ADD KEY `auser` (`auser`),
  ADD KEY `askill` (`askill`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD KEY `comments_ibfk_1` (`cpost`),
  ADD KEY `comments_ibfk_2` (`cuser`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD KEY `creceiver` (`creceiver`),
  ADD KEY `csender` (`csender`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD KEY `finstitutions` (`finstitutions`),
  ADD KEY `fusers` (`fusers`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD KEY `likes_ibfk_1` (`lpost`),
  ADD KEY `likes_ibfk_2` (`luser`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD KEY `muser` (`muser`),
  ADD KEY `minstiution` (`minstiution`),
  ADD KEY `mmajor` (`mmajor`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `pauthor` (`pauthor`);

--
-- Indexes for table `saveditems`
--
ALTER TABLE `saveditems`
  ADD KEY `pid` (`pid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skillid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `Iemail` (`uemail`);

--
-- Indexes for table `work`
--
ALTER TABLE `work`
  ADD KEY `wuser` (`wuser`),
  ADD KEY `winstitution` (`winstitution`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skillid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acquired`
--
ALTER TABLE `acquired`
  ADD CONSTRAINT `acquired_ibfk_1` FOREIGN KEY (`auser`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `acquired_ibfk_2` FOREIGN KEY (`askill`) REFERENCES `skills` (`skillid`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`cpost`) REFERENCES `posts` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`cuser`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `connections`
--
ALTER TABLE `connections`
  ADD CONSTRAINT `connections_ibfk_1` FOREIGN KEY (`creceiver`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `connections_ibfk_2` FOREIGN KEY (`csender`) REFERENCES `users` (`uid`);

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`finstitutions`) REFERENCES `institutions` (`iid`),
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`fusers`) REFERENCES `users` (`uid`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`lpost`) REFERENCES `posts` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`luser`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `major_ibfk_1` FOREIGN KEY (`muser`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `major_ibfk_2` FOREIGN KEY (`minstiution`) REFERENCES `institutions` (`iid`),
  ADD CONSTRAINT `major_ibfk_3` FOREIGN KEY (`mmajor`) REFERENCES `majors` (`mid`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`pauthor`) REFERENCES `users` (`uid`);

--
-- Constraints for table `saveditems`
--
ALTER TABLE `saveditems`
  ADD CONSTRAINT `saveditems_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `posts` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `saveditems_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `work`
--
ALTER TABLE `work`
  ADD CONSTRAINT `work_ibfk_1` FOREIGN KEY (`wuser`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `work_ibfk_2` FOREIGN KEY (`winstitution`) REFERENCES `institutions` (`iid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
