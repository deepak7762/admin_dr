-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2023 at 06:16 AM
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
-- Database: `dr_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE `tbladmins` (
  `id` int(11) NOT NULL,
  `adminname` varchar(255) DEFAULT NULL,
  `adminemail` varchar(255) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `adminpassword` varchar(255) DEFAULT NULL,
  `Createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastlogindatetime` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `adminpermission` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`id`, `adminname`, `adminemail`, `mobile`, `adminpassword`, `Createdat`, `lastlogindatetime`, `status`, `adminpermission`) VALUES
(1, 'Deepak Sharma', 'deepaksharma@gmail.com', '1234567890', '1234', '2023-10-25 17:34:42', '2023-12-09 20:16:15', 1, 1),
(2, 'Alakh Sharma', 'alakhsharma@yahoo.com', '9876543210', '123', '2023-10-25 17:34:42', '2023-10-28 16:10:08', 1, 2),
(3, 'Deepak Sharma', 'admin31@example.com', '5555555555', 'password3', '2023-10-25 17:34:42', '2023-10-23 08:15:00', 0, 4),
(4, 'Test', 'testmail@dr.com', '9890988090', '123', '2023-10-26 18:17:36', '2023-11-16 21:35:28', 1, 5),
(5, 'Test', 'testmail1@dr.com', '9876564556', '1234', '2023-10-28 10:38:19', NULL, 1, 2),
(6, 'Test', 'testmailpatient@dr.com', '8004533333', '1234', '2023-11-06 16:25:34', '2023-11-16 21:37:14', 1, 4),
(7, 'Test', 'doctor1@gmail.com', '9898787878', '1234', '2023-11-06 16:41:03', '2023-12-09 21:09:03', 1, 3),
(8, 'Doctor', 'doctor2@gmail.com', '8787767676', '1234', '2023-11-06 16:41:41', NULL, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblreports`
--

CREATE TABLE `tblreports` (
  `reportid` int(11) NOT NULL,
  `patientid` int(11) NOT NULL,
  `reportpath` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `is_seen` tinyint(1) DEFAULT 0,
  `doctorid` int(11) DEFAULT NULL,
  `seen_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblreports`
--

INSERT INTO `tblreports` (`reportid`, `patientid`, `reportpath`, `uploaded_at`, `is_seen`, `doctorid`, `seen_at`) VALUES
(3, 3, '/reports/report3.pdf', '2023-01-05 11:20:00', 0, NULL, NULL),
(4, 5, '/reports/report4.pdf', '2023-01-08 17:30:00', 0, NULL, NULL),
(6, 6, '2023_11_09_23_10_47_dummy.pdf', '2023-11-09 23:10:47', 0, NULL, NULL),
(7, 6, '2023_11_09_23_12_15_dummy.pdf', '2023-11-09 23:12:15', 0, NULL, NULL),
(8, 6, '2023_11_09_23_39_47_dummy.pdf', '2023-11-09 23:39:47', 1, 7, '2023-12-16 10:35:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblreports`
--
ALTER TABLE `tblreports`
  ADD PRIMARY KEY (`reportid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmins`
--
ALTER TABLE `tbladmins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblreports`
--
ALTER TABLE `tblreports`
  MODIFY `reportid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
