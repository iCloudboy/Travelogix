-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 19, 2017 at 11:00 AM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.6.30-12~ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `data`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `agency_id` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `pax_id` varchar(11) CHARACTER SET latin1 NOT NULL,
  `booking_id` varchar(20) CHARACTER SET latin1 NOT NULL,
  `tran_id` varchar(10) CHARACTER SET latin1 NOT NULL,
  `inv_type` varchar(1) CHARACTER SET latin1 NOT NULL,
  `inv_tran_date` date NOT NULL,
  `inv_tran_time` varchar(8) CHARACTER SET latin1 NOT NULL,
  `inv_val` float NOT NULL,
  `inv_num` varchar(25) CHARACTER SET latin1 NOT NULL COMMENT 'Invoice number. Links to invoice numbers in transaction tables.',
  UNIQUE KEY `index_agency_cct_inv_num` (`agency_id`,`pax_id`,`booking_id`,`tran_id`,`inv_num`) USING BTREE,
  KEY `pax_id` (`pax_id`),
  KEY `agency_id` (`agency_id`),
  KEY `booking_id` (`booking_id`),
  KEY `tran_id` (`tran_id`),
  KEY `pax_id_2` (`pax_id`,`booking_id`,`tran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
