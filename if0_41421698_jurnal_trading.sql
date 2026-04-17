-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql301.infinityfree.com
-- Generation Time: Apr 17, 2026 at 06:08 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41421698_jurnal_trading`
--

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE `trades` (
  `id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `session1` enum('profit','lose','skip') DEFAULT NULL,
  `session2` enum('profit','lose','skip') DEFAULT NULL,
  `session3` enum('profit','lose','skip') DEFAULT NULL,
  `session4` enum('profit','lose','skip') DEFAULT NULL,
  `session5` enum('profit','lose','skip') NOT NULL DEFAULT 'skip'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `trades`
--

INSERT INTO `trades` (`id`, `tanggal`, `note`, `foto`, `session1`, `session2`, `session3`, `session4`, `session5`) VALUES
(17, '2025-08-27', '', 'uploads/1774240781_0_27_08_2025.jpg', 'profit', 'profit', 'lose', 'skip', 'skip'),
(18, '2025-08-29', '', 'uploads/1774240876_0_29_08_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(19, '2025-09-01', '', 'uploads/1774240971_0_01_09_2025.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(20, '2025-09-02', '', 'uploads/1774241209_0_02_09_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(21, '2025-09-03', '', 'uploads/1774241278_0_03_09_2025.jpg', 'profit', 'profit', 'lose', 'skip', 'skip'),
(22, '2025-09-04', '', 'uploads/1774241381_0_04_09_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(23, '2025-09-05', 'Jumat Reversal Trading', 'uploads/1774241474_0_05_09_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(24, '2025-09-08', '', 'uploads/1774241589_0_08_09_2025.jpg', 'profit', 'skip', 'profit', 'skip', 'skip'),
(25, '2025-09-09', '', 'uploads/1774241644_0_09_09_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(26, '2025-09-10', '', 'uploads/1774241912_0_10_09_2025.jpg', 'profit', 'profit', 'skip', 'skip', 'skip'),
(27, '2025-09-11', '', 'uploads/1774241980_0_11_09_2025.jpg', 'profit', 'profit', 'lose', 'lose', 'skip'),
(28, '2025-09-12', '', 'uploads/1774242060_0_12_09_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(29, '2025-09-15', '', 'uploads/1774242151_0_15_09_2025_LOSE.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(30, '2025-09-16', '', 'uploads/1774242233_0_16_09_2025_LOSE.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(31, '2025-09-17', '', 'uploads/1774242283_0_17_09_2025_LOSE.jpg', 'profit', 'skip', 'profit', 'skip', 'skip'),
(32, '2025-09-18', '', 'uploads/1774242414_0_18_09_2025_LOSE.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(33, '2025-09-19', '', 'uploads/1774242494_0_19_09_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(35, '2025-09-22', '', 'uploads/1774249127_0_22_09_2025.jpg', 'profit', 'profit', 'skip', 'skip', 'skip'),
(36, '2025-09-23', '', 'uploads/1774249238_0_23_09_2025..jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(37, '2025-09-24', '', 'uploads/1774249302_0_24_09_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(38, '2025-09-25', '', 'uploads/1774249340_0_25_09_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(39, '2025-09-26', '', 'uploads/1774249383_0_26_09_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(40, '2025-09-29', '', 'uploads/1774249421_0_29_09_2025_LOSE.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(41, '2025-09-30', '', 'uploads/1774249454_0_30_09_2025_LOSE.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(42, '2025-10-01', '', 'uploads/1774275143_0_01_10_2025_LOSE.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(43, '2025-10-02', '', 'uploads/1774275247_0_02_10_2025.jpg', 'profit', 'lose', 'profit', 'skip', 'skip'),
(44, '2025-10-03', '', 'uploads/1774275361_0_03_10_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(45, '2025-10-06', '', 'uploads/1774275551_0_06_10_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(46, '2025-10-07', '', 'uploads/1774275588_0_07_10_2025.jpg', 'profit', 'lose', 'profit', 'profit', 'skip'),
(47, '2025-10-08', '', 'uploads/1774275620_0_08_10_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(48, '2025-10-09', '', 'uploads/1774275673_0_09_10_2025.jpg', 'profit', 'profit', 'skip', 'profit', 'skip'),
(49, '2025-10-10', '', 'uploads/1774275784_0_10_10_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(50, '2025-10-14', '', 'uploads/1774275899_0_14_10_2025.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(51, '2025-10-15', '', 'uploads/1774276033_0_15_10_2025.jpg', 'lose', 'skip', 'skip', 'profit', 'skip'),
(52, '2025-10-13', '', 'uploads/1774276089_0_13_10_2025.jpg', 'profit', 'profit', 'skip', 'lose', 'skip'),
(53, '2025-10-16', '', 'uploads/1774276135_0_16_10_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(54, '2025-10-17', '', 'uploads/1774276267_0_17_10_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(55, '2025-10-20', '', 'uploads/1774276324_0_20_10_2025.jpg', 'profit', 'lose', 'skip', 'skip', 'skip'),
(56, '2025-10-21', '', 'uploads/1774276429_0_21_10_2025.jpg', 'lose', 'lose', 'skip', 'lose', 'skip'),
(57, '2025-10-22', '', 'uploads/1774276581_0_22_10_2025.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(58, '2025-10-23', '', 'uploads/1774276663_0_23_10_2025.jpg', 'profit', 'profit', 'skip', 'profit', 'skip'),
(59, '2025-10-24', '', 'uploads/1774276692_0_24_10_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(60, '2025-10-27', '', 'uploads/1774276778_0_27_10_2025.jpg', 'lose', 'lose', 'skip', 'profit', 'skip'),
(61, '2025-10-28', '', 'uploads/1774276845_0_28_10_2025.jpg', 'skip', 'skip', 'lose', 'lose', 'skip'),
(62, '2025-10-29', '', 'uploads/1774276975_0_29_10_2025.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(63, '2025-10-30', '', 'uploads/1774277014_0_30_10_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(64, '2025-10-31', '', 'uploads/1774277057_0_31_10_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(65, '2025-11-03', '', 'uploads/1774314016_0_03_11_2025.jpg', 'profit', 'lose', 'skip', 'profit', 'skip'),
(66, '2025-11-06', '', 'uploads/1774314142_0_06_11_2025.jpg', 'profit', 'profit', 'lose', 'lose', 'skip'),
(67, '2025-11-07', '', 'uploads/1774314229_0_07_11_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(68, '2025-11-10', '', 'uploads/1774314282_0_10_11_2025.jpg', 'profit', 'profit', 'lose', 'skip', 'skip'),
(69, '2025-11-11', '', 'uploads/1774314729_0_11_11_2025.jpg', 'profit', 'profit', 'profit', 'lose', 'skip'),
(70, '2025-11-12', '', 'uploads/1774314790_0_12_11_2025..jpg', 'profit', 'profit', 'lose', 'lose', 'skip'),
(71, '2025-11-13', '', 'uploads/1774314852_0_13_11_2025.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(72, '2025-11-14', '', 'uploads/1774314915_0_14_11_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(73, '2025-11-17', '', 'uploads/1774314997_0_17_11_2025.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(74, '2025-11-18', '', 'uploads/1774315036_0_18_11_2025.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(75, '2025-11-19', '', 'uploads/1774315210_0_19_11_2025.jpg', 'skip', 'skip', 'skip', 'profit', 'skip'),
(76, '2025-11-20', '', 'uploads/1774315257_0_20_11_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(77, '2025-11-21', '', 'uploads/1774315328_0_21_11_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(78, '2025-11-24', '', 'uploads/1774315378_0_24_11_2025.jpg', 'lose', 'lose', 'profit', 'profit', 'skip'),
(79, '2025-11-25', '', 'uploads/1774315521_0_25_11_2025.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(80, '2025-11-26', '', 'uploads/1774315739_0_26_11_2025.jpg', 'profit', 'lose', 'skip', 'skip', 'skip'),
(81, '2025-11-27', '', 'uploads/1774315810_0_27_11_2025.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(82, '2025-11-28', '', 'uploads/1774315847_0_28_11_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(83, '2025-12-01', '', 'uploads/1774324845_0_1_12_2025.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(84, '2025-12-02', '', 'uploads/1774324937_0_2_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(85, '2025-12-03', '', 'uploads/1774324989_0_3_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(86, '2025-12-04', '', 'uploads/1774325062_0_4_12_2025.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(87, '2025-12-05', '', 'uploads/1774325119_0_5_12_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(88, '2025-12-08', '', 'uploads/1774325205_0_8_12_2025.jpg', 'skip', 'skip', 'lose', 'lose', 'skip'),
(89, '2025-12-09', '', 'uploads/1774325339_0_9_12_2025.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(90, '2025-12-10', '', 'uploads/1774325478_0_10_12_2025.jpg', 'skip', 'skip', 'skip', 'skip', 'skip'),
(91, '2025-12-11', '', 'uploads/1774325548_0_11_12_2025.jpg', 'skip', 'skip', 'skip', 'lose', 'skip'),
(92, '2025-12-12', '', 'uploads/1774325593_0_12_12_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(93, '2025-12-15', '', 'uploads/1774325653_0_15_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(94, '2025-12-16', '', 'uploads/1774325964_0_16_12_2025_.jpg,uploads/1774325964_1_16_12_2025.jpg', 'lose', 'lose', 'profit', 'profit', 'skip'),
(95, '2025-12-17', '', 'uploads/1774326028_0_17_12_2025.jpg', 'profit', 'lose', 'profit', 'skip', 'skip'),
(96, '2025-12-18', '', 'uploads/1774326151_0_18_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(97, '2025-12-19', '', 'uploads/1774326197_0_19_12_2025.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(98, '2025-12-22', '', 'uploads/1774326265_0_22_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(99, '2025-12-23', '', 'uploads/1774326360_0_24_12_2025.jpg', 'skip', 'skip', 'skip', 'profit', 'skip'),
(100, '2025-12-26', '', 'uploads/1774326425_0_26_12_2025.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(101, '2025-12-29', '', 'uploads/1774326502_0_29_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(102, '2025-12-30', '', 'uploads/1774326539_0_30_12_2025.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(103, '2025-12-31', '', 'uploads/1774326580_0_31_12_2025.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(104, '2026-01-06', '', 'uploads/1774362093_0_6_01_2026.jpg', 'profit', 'profit', 'profit', 'skip', 'skip'),
(105, '2026-01-07', '', 'uploads/1774362137_0_7_01_2026.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(106, '2026-01-08', '', 'uploads/1774362259_0_8_01_2026.jpg', 'lose', 'lose', 'skip', 'profit', 'skip'),
(107, '2026-01-09', '', 'uploads/1774362325_0_9_01_2026.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(108, '2026-01-12', '', 'uploads/1774362663_0_12_01_2026.jpg', 'profit', 'lose', 'profit', 'lose', 'skip'),
(109, '2026-01-13', '', 'uploads/1774363694_0_13_01_2026.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(110, '2026-01-14', '', 'uploads/1774363775_0_14_01_2026.jpg', 'lose', 'lose', 'profit', 'profit', 'skip'),
(111, '2026-01-15', '', 'uploads/1774363868_0_15_01_2026.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(112, '2026-01-16', '', 'uploads/1774363902_0_16_01_2026.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(113, '2026-02-11', '', 'uploads/1774363970_0_11_02_2026_09_30.jpg', 'lose', 'lose', 'lose', 'profit', 'skip'),
(114, '2026-02-12', '', 'uploads/1774364029_0_12_02_2026_09_30.jpg', 'lose', 'lose', 'profit', 'profit', 'skip'),
(115, '2026-02-13', '', 'uploads/1774364057_0_13_2_2026_09_30.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(116, '2026-02-16', '', 'uploads/1774413968_0_16_02_2025_09_30.jpg', 'skip', 'skip', 'skip', 'profit', 'skip'),
(117, '2026-02-17', '', 'uploads/1774450004_0_17_02_2026_09_30.jpg', 'profit', 'profit', 'skip', 'skip', 'skip'),
(118, '2026-02-18', '', 'uploads/1774450049_0_18_02_2026_09.30.jpg', 'skip', 'skip', 'skip', 'profit', 'skip'),
(119, '2026-02-19', 'Sesi 4 entry setelah candle melewati 1x target profit', 'uploads/1774450089_0_19_02_2026_09.30.jpg', 'profit', 'profit', 'skip', 'profit', 'skip'),
(120, '2026-02-20', '', 'uploads/1774450135_0_20_02_2026_09.30.jpg', 'lose', 'skip', 'skip', 'skip', 'skip'),
(121, '2026-02-23', '', 'uploads/1774450186_0_23_02_2026_09.30.jpg', 'profit', 'lose', 'profit', 'skip', 'skip'),
(122, '2026-03-02', 'Sesi 4 entry setelah candle melewati 1x target profit', 'uploads/1774533906_0_02_03_2026.jpg', 'skip', 'skip', 'lose', 'profit', 'skip'),
(123, '2026-03-03', '', 'uploads/1774534858_0_03_03_2026.jpg', 'profit', 'lose', 'skip', 'skip', 'skip'),
(124, '2026-03-04', '', 'uploads/1774535221_0_04_03_2026.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(125, '2026-03-05', '', 'uploads/1774535325_0_05_03_2026.jpg', 'lose', 'lose', 'skip', 'skip', 'skip'),
(126, '2026-03-06', '', 'uploads/1774535394_0_06_03_2026.jpg', 'profit', 'skip', 'skip', 'skip', 'skip'),
(127, '2026-03-09', '', 'uploads/1774535635_0_09_03_2026.jpg', 'profit', 'profit', 'profit', 'profit', 'skip'),
(128, '2026-03-10', '', 'uploads/1774535692_0_10_03_2026.jpg', 'profit', 'lose', 'profit', 'skip', 'skip'),
(129, '2026-03-11', '', 'uploads/1774535784_0_11_03_2026.jpg', 'lose', 'lose', 'profit', 'skip', 'skip'),
(130, '2026-03-12', '', 'uploads/1774535881_0_12_03_2026.jpg', 'skip', 'skip', 'skip', 'profit', 'skip'),
(131, '2026-03-13', '', 'uploads/1774535994_0_13_03_2026.jpg', 'profit', 'skip', 'skip', 'skip', 'skip');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$5IHA5NoEuIfDG3sYM0UKSuhYhq0LXMUN61N9z1KAiZBTXu1RH0Miu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `trades`
--
ALTER TABLE `trades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
