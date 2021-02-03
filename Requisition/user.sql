-- phpMyAdmin SQL Dump
-- version 4.8.3
--
-- Host: 127.0.0.1
-- Generation Time: Sept 11, 2020 at 08:56 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Requisition`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Table structure for table `user`
--

CREATE TABLE `devices` (
  `deviceid` int(11) NOT NULL,
  `devicetype` varchar(100) NOT NULL,
  `devicemake` varchar(100) NOT NULL,
  `serialno` varchar(100) NOT NULL,
  `borrowername` varchar(100) NOT NULL,
  `borrowdate` varchar(20) NOT NULL,
  `authorizername` varchar(100) NOT NULL,
  `devicestatus` varchar(20) NOT NULL,
  `returndate` varchar(20) NOT NULL,
  `verificationofficer` varchar(100) NOT NULL,
  `devicecondition` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`) VALUES
(1, 'user', 'user12345');

-- --------------------------------------------------------

--
-- Indexes for dumped table
--

ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);


-- --------------------------------------------------------

ALTER TABLE `devices`
  ADD PRIMARY KEY (`deviceid`);


-- --------------------------------------------------------
--
-- AUTO_INCREMENT for dumped table
--

ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `devices`
  MODIFY `deviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
