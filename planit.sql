-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2018 at 01:54 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `planit`
--

-- --------------------------------------------------------

--
-- Table structure for table `rse_organization`
--

CREATE TABLE IF NOT EXISTS `rse_organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_title` varchar(512) NOT NULL,
  `org_address` varchar(512) NOT NULL,
  `org_phone` varchar(50) NOT NULL,
  `user_login` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `org_email` varchar(512) NOT NULL,
  `time_at_save` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `org_type` varchar(200) DEFAULT NULL,
  `free_trainees` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `rse_organization`
--

-- --------------------------------------------------------

--
-- Table structure for table `rse_organization_package`
--

CREATE TABLE IF NOT EXISTS `rse_organization_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `package` int(11) NOT NULL,
  `time_at_save` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rse_trainee`
--

CREATE TABLE IF NOT EXISTS `rse_trainee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_mobile_no` varchar(100) DEFAULT NULL,
  `user_password` varchar(500) NOT NULL,
  `time_at_save` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `org_id` int(11) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `moodle_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `rse_trainee`
--


-- --------------------------------------------------------

--
-- Table structure for table `rse_trainee_fee`
--

CREATE TABLE IF NOT EXISTS `rse_trainee_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_type` int(11) DEFAULT NULL,
  `fee_per_trainee` decimal(10,0) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `time_at_save` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rse_trainee_fee`
--

-- --------------------------------------------------------

--
-- Table structure for table `rse_transactions`
--

CREATE TABLE IF NOT EXISTS `rse_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(500) NOT NULL,
  `org_id` int(11) NOT NULL,
  `no_of_trainees` int(11) NOT NULL,
  `fee_per_trainee` decimal(10,0) NOT NULL,
  `is_completed` int(11) NOT NULL DEFAULT '0',
  `discount` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `time_at_save` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tr_amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rse_transactions`
--


-- --------------------------------------------------------

--
-- Table structure for table `rse_user`
--

CREATE TABLE IF NOT EXISTS `rse_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_mobile_no` varchar(100) DEFAULT NULL,
  `user_password` varchar(500) NOT NULL,
  `time_at_save` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rse_user`
--

INSERT INTO `rse_user` (`id`, `user_login`, `user_name`, `user_email`, `user_mobile_no`, `user_password`, `time_at_save`, `is_active`, `user_id`) VALUES
(3, 'shahid', 'Shahid Baig', 'void@gmail.com', '123456789', '123', '2017-11-12 14:51:20', 1, 1),
(4, 'ali', 'ali', 'ali@gmail.com', '123', '123', '2017-11-12 15:25:26', 1, 1),
(6, 'asif', 'Asif', 'planit@pglobal.com', '00000000', '123', '2017-12-04 08:17:45', 1, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
