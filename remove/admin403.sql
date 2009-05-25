-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 25, 2009 at 05:01 AM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `admin403`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `requestor_id` int(11) NOT NULL DEFAULT '0',
  `requestor_type` enum('Employer','Employee','Vendor') DEFAULT NULL,
  `action_type` enum('Employer','Employee','Vendor') DEFAULT NULL,
  `wf_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approve','Decline','Cancel') DEFAULT NULL,
  `reasons` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_name` varchar(50) DEFAULT NULL,
  `action_description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`action_id`, `id`, `pid`, `title`, `requestor_id`, `requestor_type`, `action_type`, `wf_id`, `status`, `reasons`, `date_created`, `action_name`, `action_description`) VALUES
(1, 2, 0, 'Employee Reduction Try 1', 2, 'Employee', 'Employer', 2, 'Pending', NULL, '2008-11-23 22:59:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `name`, `address`, `phone`) VALUES
(1, 'asimonson@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin_system_settings`
--

CREATE TABLE IF NOT EXISTS `admin_system_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL DEFAULT '0',
  `annual_age_limit` float(12,2) DEFAULT NULL,
  `annual_pretax_limit` float(12,2) DEFAULT NULL,
  `annual_roth_limit` float(12,2) DEFAULT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admin_system_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(150) DEFAULT NULL,
  `message` text,
  `email_type` enum('Text','HTML') NOT NULL DEFAULT 'Text',
  `ref` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `email`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `married` enum('Yes','No') DEFAULT NULL,
  `sex` enum('M','F') DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `created_dt` int(11) NOT NULL DEFAULT '0',
  `modified_dt` int(11) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  `catchup` float(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `password`, `email`, `firstname`, `middlename`, `lastname`, `ssn`, `employer_id`, `address`, `married`, `sex`, `hire_date`, `termination_date`, `dob`, `account_number`, `phone`, `fax`, `created_dt`, `modified_dt`, `status`, `catchup`) VALUES
(1, '5f4dcc3b5aa765d61d8327deb882cf99', 'employee@mkgalaxy.com', 'employee', 'k', 'k', 'bb03912d604106ce95e7f1df7afd0ffc', 1, NULL, 'Yes', 'M', NULL, NULL, NULL, NULL, NULL, NULL, 1227359789, 0, 1, 0.00),
(2, '088495f30901580ddd5171531cd26649', 'juhikh@yahoo.com', 'Jane', NULL, 'Doe', 'a2afd722296e350f7d81dcce51f5f476', 2, NULL, 'Yes', 'M', '0000-00-00', NULL, 'c6f6fa55abc425200e5b71642967343a', '6ec6fed2cfb432e5', NULL, NULL, 1227479962, 0, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `employee_contribution`
--

CREATE TABLE IF NOT EXISTS `employee_contribution` (
  `contribution_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `contribution_date` date DEFAULT NULL,
  `sra_pretax` float(12,2) DEFAULT NULL,
  `sra_roth` float(12,2) DEFAULT NULL,
  PRIMARY KEY (`contribution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_contribution`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee_contribution_details`
--

CREATE TABLE IF NOT EXISTS `employee_contribution_details` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `cdate` date DEFAULT NULL,
  `ssn` varchar(50) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `pretax` float(12,2) DEFAULT NULL,
  `roth` float(12,2) DEFAULT NULL,
  `pretax_refund` float(12,2) DEFAULT NULL,
  `roth_refund` float(12,2) DEFAULT NULL,
  `duplicate_record` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transaction_id`),
  UNIQUE KEY `cdate` (`cdate`,`ssn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_contribution_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee_contribution_transaction`
--

CREATE TABLE IF NOT EXISTS `employee_contribution_transaction` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_date` date DEFAULT NULL,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `totalrecords` int(11) NOT NULL DEFAULT '0',
  `totalamount` float(12,2) NOT NULL DEFAULT '0.00',
  `totalamountprocessed` float(12,2) NOT NULL DEFAULT '0.00',
  `totalamountrefunded` float(12,2) NOT NULL DEFAULT '0.00',
  `totalrecordsprocessedsuccessfully` int(11) NOT NULL DEFAULT '0',
  `totalrecordsrejected` int(11) NOT NULL DEFAULT '0',
  `recordsalreadyuploaded` int(11) NOT NULL DEFAULT '0',
  `totalroth` float(12,2) NOT NULL DEFAULT '0.00',
  `totalrothprocessed` float(12,2) NOT NULL DEFAULT '0.00',
  `totalrothrefunded` float(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_contribution_transaction`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee_history`
--

CREATE TABLE IF NOT EXISTS `employee_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `hardship_date` int(11) DEFAULT NULL,
  `loan_closed_date` int(11) DEFAULT NULL,
  `loan_issue_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee_vendor`
--

CREATE TABLE IF NOT EXISTS `employee_vendor` (
  `emp_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`emp_plan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_vendor`
--


-- --------------------------------------------------------

--
-- Table structure for table `employee_vendor_deleted`
--

CREATE TABLE IF NOT EXISTS `employee_vendor_deleted` (
  `vendor_del_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `del_date` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_del_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_vendor_deleted`
--


-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE IF NOT EXISTS `employer` (
  `employer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `loan_provision` enum('Y','N') DEFAULT NULL,
  `service_provision` enum('Y','N') DEFAULT NULL,
  `hardship_provision` enum('Y','N') DEFAULT NULL,
  `exchanges` enum('Y','N') DEFAULT NULL,
  `transfers_in` enum('Y','N') DEFAULT NULL,
  `transfers_out` enum('Y','N') DEFAULT NULL,
  `roth_provision` enum('Y','N') DEFAULT NULL,
  `service_eligible_limit` float(12,2) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `created_dt` int(11) NOT NULL DEFAULT '0',
  `modified_dt` int(11) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`employer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`employer_id`, `name`, `phone`, `email`, `address`, `loan_provision`, `service_provision`, `hardship_provision`, `exchanges`, `transfers_in`, `transfers_out`, `roth_provision`, `service_eligible_limit`, `password`, `created_dt`, `modified_dt`, `status`) VALUES
(1, 'employer', NULL, 'employer@mkgalaxy.com', NULL, 'N', 'N', 'N', 'N', 'N', 'N', 'N', NULL, '5f4dcc3b5aa765d61d8327deb882cf99', 1227359343, 0, 1),
(2, 'Yash', NULL, 'yash0708@hotmail.com', NULL, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 5000.00, '088495f30901580ddd5171531cd26649', 1227479272, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employer_access`
--

CREATE TABLE IF NOT EXISTS `employer_access` (
  `compliance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `compliance_designee_name` varchar(50) DEFAULT NULL,
  `compliance_designee_email` varchar(150) DEFAULT NULL,
  `compliance_designee_phone` varchar(20) DEFAULT NULL,
  `compliance_designee_password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`compliance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employer_access`
--


-- --------------------------------------------------------

--
-- Table structure for table `employer_documents`
--

CREATE TABLE IF NOT EXISTS `employer_documents` (
  `docu_id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  `real_filename` varchar(255) DEFAULT NULL,
  `display` varchar(50) DEFAULT NULL,
  `comments` text,
  `extension` varchar(10) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `filetype` varchar(255) DEFAULT NULL,
  `upload_dt` int(11) DEFAULT NULL,
  PRIMARY KEY (`docu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employer_documents`
--


-- --------------------------------------------------------

--
-- Table structure for table `employer_vendor`
--

CREATE TABLE IF NOT EXISTS `employer_vendor` (
  `emp_ven_id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`emp_ven_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employer_vendor`
--

INSERT INTO `employer_vendor` (`emp_ven_id`, `employer_id`, `plan_id`, `vendor_id`, `active`) VALUES
(1, 2, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employer_vendors_contact`
--

CREATE TABLE IF NOT EXISTS `employer_vendors_contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `contact_name` varchar(50) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `group_plan_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employer_vendors_contact`
--

INSERT INTO `employer_vendors_contact` (`contact_id`, `employer_id`, `vendor_id`, `plan_id`, `contact_name`, `contact_email`, `contact_phone`, `group_plan_number`) VALUES
(1, 2, 1, 1, 'ABC', 'abc@mkagalaxy.com', '123-456-7890', 'GP0142');

-- --------------------------------------------------------

--
-- Table structure for table `employer_vendor_deleted`
--

CREATE TABLE IF NOT EXISTS `employer_vendor_deleted` (
  `vendor_del_id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `del_date` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_del_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employer_vendor_deleted`
--


-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE IF NOT EXISTS `monitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `status` enum('up','down') DEFAULT NULL,
  `checktime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=358 ;

--
-- Dumping data for table `monitor`
--

INSERT INTO `monitor` (`id`, `site_id`, `status`, `checktime`) VALUES
(1, 1, 'up', '2008-11-23 14:47:11'),
(2, 1, 'up', '2008-11-23 15:57:11'),
(3, 1, 'up', '2008-11-23 16:12:12'),
(4, 1, 'up', '2008-11-23 19:27:28'),
(5, 1, 'up', '2008-11-23 22:23:38'),
(6, 1, 'up', '2008-11-23 22:38:51'),
(7, 1, 'up', '2008-11-23 22:54:36'),
(8, 1, 'up', '2008-11-24 07:53:27'),
(9, 1, 'up', '2008-11-24 12:42:35'),
(10, 1, 'up', '2008-11-24 16:38:47'),
(11, 1, 'up', '2008-11-24 21:09:01'),
(12, 1, 'up', '2008-11-25 03:48:51'),
(13, 1, 'up', '2008-11-25 04:15:32'),
(14, 1, 'up', '2008-11-25 15:26:10'),
(15, 1, 'up', '2008-11-25 16:58:07'),
(16, 1, 'up', '2008-11-25 17:49:35'),
(17, 1, 'up', '2008-11-25 20:43:01'),
(18, 1, 'up', '2008-11-25 20:59:26'),
(19, 1, 'up', '2008-11-25 23:31:49'),
(20, 1, 'up', '2008-11-25 23:48:23'),
(21, 1, 'up', '2008-11-26 07:53:35'),
(22, 1, 'up', '2008-11-26 15:27:13'),
(23, 1, 'up', '2008-11-26 15:51:21'),
(24, 1, 'up', '2008-11-26 16:38:54'),
(25, 1, 'up', '2008-11-26 17:17:04'),
(26, 1, 'up', '2008-11-26 20:10:01'),
(27, 1, 'up', '2008-11-27 05:35:45'),
(28, 1, 'up', '2008-11-27 09:25:26'),
(29, 1, 'up', '2008-11-27 18:03:48'),
(30, 1, 'up', '2008-11-27 19:35:05'),
(31, 1, 'up', '2008-11-27 20:10:29'),
(32, 1, 'up', '2008-11-27 22:29:52'),
(33, 1, 'up', '2008-11-28 13:02:36'),
(34, 1, 'up', '2008-11-29 04:39:01'),
(35, 1, 'up', '2008-11-29 05:51:26'),
(36, 1, 'up', '2008-12-01 22:50:36'),
(37, 1, 'up', '2008-12-02 01:46:15'),
(38, 1, 'up', '2008-12-02 14:42:32'),
(39, 1, 'up', '2008-12-02 16:23:44'),
(40, 1, 'up', '2008-12-02 22:22:37'),
(41, 1, 'up', '2008-12-02 23:53:27'),
(42, 1, 'up', '2008-12-03 01:55:41'),
(43, 1, 'up', '2008-12-03 04:00:20'),
(44, 1, 'up', '2008-12-03 06:11:06'),
(45, 1, 'up', '2008-12-03 14:50:58'),
(46, 1, 'up', '2008-12-03 17:00:37'),
(47, 1, 'up', '2008-12-03 21:35:29'),
(48, 1, 'up', '2008-12-04 02:49:10'),
(49, 1, 'up', '2008-12-04 11:42:07'),
(50, 1, 'up', '2008-12-04 16:39:41'),
(51, 1, 'up', '2008-12-04 19:35:24'),
(52, 1, 'up', '2008-12-04 21:47:04'),
(53, 1, 'up', '2008-12-05 00:23:43'),
(54, 1, 'up', '2008-12-05 00:54:04'),
(55, 1, 'up', '2008-12-05 16:42:40'),
(56, 1, 'up', '2008-12-06 16:44:55'),
(57, 1, 'up', '2008-12-07 17:22:19'),
(58, 1, 'up', '2008-12-07 20:22:06'),
(59, 1, 'up', '2008-12-07 23:45:00'),
(60, 1, 'up', '2008-12-08 02:58:30'),
(61, 1, 'up', '2008-12-08 05:43:25'),
(62, 1, 'up', '2008-12-08 13:30:25'),
(63, 1, 'up', '2008-12-08 14:16:31'),
(64, 1, 'up', '2008-12-08 14:41:50'),
(65, 1, 'up', '2008-12-08 16:14:15'),
(66, 1, 'up', '2008-12-08 18:06:26'),
(67, 1, 'up', '2008-12-09 12:32:51'),
(68, 1, 'up', '2008-12-09 13:14:05'),
(69, 1, 'up', '2008-12-09 21:49:26'),
(70, 1, 'up', '2008-12-10 16:14:21'),
(71, 1, 'up', '2008-12-10 22:49:46'),
(72, 1, 'up', '2008-12-11 06:36:48'),
(73, 1, 'up', '2008-12-11 14:13:33'),
(74, 1, 'up', '2008-12-13 10:20:22'),
(75, 1, 'up', '2008-12-13 13:25:25'),
(76, 1, 'up', '2008-12-13 23:16:00'),
(77, 1, 'up', '2008-12-14 01:36:56'),
(78, 1, 'up', '2008-12-14 06:24:24'),
(79, 1, 'up', '2008-12-14 10:48:36'),
(80, 1, 'up', '2008-12-14 11:11:30'),
(81, 1, 'up', '2008-12-14 13:35:05'),
(82, 1, 'up', '2008-12-14 23:58:51'),
(83, 1, 'up', '2008-12-15 13:50:56'),
(84, 1, 'up', '2008-12-15 16:54:17'),
(85, 1, 'up', '2008-12-15 18:18:06'),
(86, 1, 'up', '2008-12-15 20:56:03'),
(87, 1, 'up', '2008-12-16 00:36:03'),
(88, 1, 'up', '2008-12-16 16:47:09'),
(89, 1, 'up', '2008-12-16 18:02:16'),
(90, 1, 'up', '2008-12-17 16:36:58'),
(91, 1, 'up', '2008-12-18 10:11:52'),
(92, 1, 'up', '2008-12-18 12:33:49'),
(93, 1, 'up', '2008-12-18 14:56:00'),
(94, 1, 'up', '2008-12-18 19:39:57'),
(95, 1, 'up', '2008-12-19 02:46:27'),
(96, 1, 'up', '2008-12-19 14:11:01'),
(97, 1, 'up', '2008-12-19 16:31:42'),
(98, 1, 'up', '2008-12-19 19:31:24'),
(99, 1, 'up', '2008-12-19 20:09:14'),
(100, 1, 'up', '2008-12-19 22:14:40'),
(101, 1, 'up', '2008-12-20 04:22:40'),
(102, 1, 'up', '2008-12-20 13:18:29'),
(103, 1, 'up', '2008-12-21 00:55:46'),
(104, 1, 'up', '2008-12-21 01:11:01'),
(105, 1, 'up', '2008-12-21 14:42:05'),
(106, 1, 'up', '2008-12-22 02:16:21'),
(107, 1, 'up', '2008-12-22 15:01:46'),
(108, 1, 'up', '2008-12-22 16:59:55'),
(109, 1, 'up', '2008-12-22 20:11:19'),
(110, 1, 'up', '2008-12-22 22:23:11'),
(111, 1, 'up', '2008-12-23 00:58:40'),
(112, 1, 'up', '2008-12-23 10:34:14'),
(113, 1, 'up', '2008-12-23 17:12:41'),
(114, 1, 'up', '2008-12-23 20:47:27'),
(115, 1, 'up', '2008-12-24 11:46:23'),
(116, 1, 'up', '2008-12-24 12:05:07'),
(117, 1, 'up', '2008-12-24 19:30:24'),
(118, 1, 'up', '2008-12-26 19:54:51'),
(119, 1, 'up', '2008-12-26 22:55:14'),
(120, 1, 'up', '2008-12-27 01:18:53'),
(121, 1, 'up', '2008-12-27 03:42:43'),
(122, 1, 'up', '2008-12-27 10:54:21'),
(123, 1, 'up', '2008-12-27 13:18:41'),
(124, 1, 'up', '2008-12-27 15:57:35'),
(125, 1, 'up', '2008-12-27 20:45:54'),
(126, 1, 'up', '2008-12-28 09:57:26'),
(127, 1, 'up', '2008-12-28 14:26:25'),
(128, 1, 'up', '2008-12-28 19:19:28'),
(129, 1, 'up', '2008-12-29 17:24:45'),
(130, 1, 'up', '2008-12-30 22:58:59'),
(131, 1, 'up', '2008-12-30 23:51:36'),
(132, 1, 'up', '2008-12-31 01:23:28'),
(133, 1, 'up', '2008-12-31 03:36:47'),
(134, 1, 'up', '2008-12-31 08:37:10'),
(135, 1, 'up', '2008-12-31 13:26:04'),
(136, 1, 'up', '2008-12-31 16:50:33'),
(137, 1, 'up', '2009-01-01 10:22:33'),
(138, 1, 'up', '2009-01-01 22:52:11'),
(139, 1, 'up', '2009-01-02 01:48:06'),
(140, 1, 'up', '2009-01-02 19:15:49'),
(141, 1, 'up', '2009-01-03 23:14:18'),
(142, 1, 'up', '2009-01-04 01:52:41'),
(143, 1, 'up', '2009-01-04 04:34:02'),
(144, 1, 'up', '2009-01-04 09:56:19'),
(145, 1, 'up', '2009-01-04 10:22:21'),
(146, 1, 'up', '2009-01-04 12:38:24'),
(147, 1, 'up', '2009-01-05 03:07:57'),
(148, 1, 'up', '2009-01-05 08:53:01'),
(149, 1, 'up', '2009-01-05 16:54:26'),
(150, 1, 'up', '2009-01-05 21:07:12'),
(151, 1, 'up', '2009-01-06 16:08:55'),
(152, 1, 'up', '2009-01-06 19:37:12'),
(153, 1, 'up', '2009-01-07 12:18:42'),
(154, 1, 'up', '2009-01-07 16:04:50'),
(155, 1, 'up', '2009-01-07 17:19:56'),
(156, 1, 'up', '2009-01-08 06:22:49'),
(157, 1, 'up', '2009-01-08 08:54:31'),
(158, 1, 'up', '2009-01-08 11:30:36'),
(159, 1, 'up', '2009-01-08 19:19:26'),
(160, 1, 'up', '2009-01-09 01:59:33'),
(161, 1, 'up', '2009-01-09 04:47:31'),
(162, 1, 'up', '2009-01-09 07:47:19'),
(163, 1, 'up', '2009-01-10 16:01:19'),
(164, 1, 'up', '2009-01-10 23:45:42'),
(165, 1, 'up', '2009-01-12 17:07:26'),
(166, 1, 'up', '2009-01-12 17:35:48'),
(167, 1, 'up', '2009-01-12 19:28:28'),
(168, 1, 'up', '2009-01-12 21:53:02'),
(169, 1, 'up', '2009-01-13 00:06:53'),
(170, 1, 'up', '2009-01-13 05:04:03'),
(171, 1, 'up', '2009-01-13 07:25:57'),
(172, 1, 'up', '2009-01-13 13:40:33'),
(173, 1, 'up', '2009-01-13 16:32:56'),
(174, 1, 'up', '2009-01-13 18:17:31'),
(175, 1, 'up', '2009-01-13 20:07:00'),
(176, 1, 'up', '2009-01-13 23:33:40'),
(177, 1, 'up', '2009-01-14 07:17:02'),
(178, 1, 'up', '2009-01-14 21:19:58'),
(179, 1, 'up', '2009-01-15 19:23:21'),
(180, 1, 'up', '2009-01-15 20:19:22'),
(181, 1, 'up', '2009-01-16 17:58:47'),
(182, 1, 'up', '2009-01-17 14:19:24'),
(183, 1, 'up', '2009-01-17 16:31:26'),
(184, 1, 'up', '2009-01-17 18:45:12'),
(185, 1, 'up', '2009-01-17 20:15:24'),
(186, 1, 'up', '2009-01-18 01:30:04'),
(187, 1, 'up', '2009-01-18 03:46:03'),
(188, 1, 'up', '2009-01-18 13:57:08'),
(189, 1, 'up', '2009-01-19 04:04:13'),
(190, 1, 'up', '2009-01-19 18:19:19'),
(191, 1, 'up', '2009-01-19 18:59:49'),
(192, 1, 'up', '2009-01-19 20:10:44'),
(193, 1, 'up', '2009-01-21 02:01:19'),
(194, 1, 'up', '2009-01-21 09:58:33'),
(195, 1, 'up', '2009-01-21 17:24:50'),
(196, 1, 'up', '2009-01-21 20:56:26'),
(197, 1, 'up', '2009-01-21 21:11:56'),
(198, 1, 'up', '2009-01-21 22:23:02'),
(199, 1, 'up', '2009-01-22 10:07:29'),
(200, 1, 'up', '2009-01-22 12:13:14'),
(201, 1, 'up', '2009-01-22 14:23:37'),
(202, 1, 'up', '2009-01-22 23:04:58'),
(203, 1, 'up', '2009-01-24 00:35:59'),
(204, 1, 'up', '2009-01-24 03:07:29'),
(205, 1, 'up', '2009-01-24 13:45:14'),
(206, 1, 'up', '2009-01-24 16:50:53'),
(207, 1, 'up', '2009-01-25 05:54:11'),
(208, 1, 'up', '2009-01-26 16:36:13'),
(209, 1, 'up', '2009-01-26 18:28:20'),
(210, 1, 'up', '2009-01-26 18:59:31'),
(211, 1, 'up', '2009-01-26 19:52:29'),
(212, 1, 'up', '2009-01-26 22:31:44'),
(213, 1, 'up', '2009-01-27 01:16:16'),
(214, 1, 'up', '2009-01-27 09:29:31'),
(215, 1, 'up', '2009-01-27 14:19:59'),
(216, 1, 'up', '2009-01-27 15:31:32'),
(217, 1, 'up', '2009-01-28 09:14:45'),
(218, 1, 'up', '2009-01-28 15:11:27'),
(219, 1, 'up', '2009-01-28 18:37:41'),
(220, 1, 'up', '2009-01-28 20:33:30'),
(221, 1, 'up', '2009-01-28 21:23:13'),
(222, 1, 'up', '2009-01-29 01:17:03'),
(223, 1, 'up', '2009-01-29 02:36:22'),
(224, 1, 'up', '2009-01-30 00:59:27'),
(225, 1, 'up', '2009-01-30 06:41:08'),
(226, 1, 'up', '2009-01-30 22:14:09'),
(227, 1, 'up', '2009-01-31 10:53:41'),
(228, 1, 'up', '2009-01-31 13:42:14'),
(229, 1, 'up', '2009-02-01 01:56:25'),
(230, 1, 'up', '2009-02-01 06:22:36'),
(231, 1, 'up', '2009-02-01 12:40:35'),
(232, 1, 'up', '2009-02-02 08:55:04'),
(233, 1, 'up', '2009-02-03 05:24:12'),
(234, 1, 'up', '2009-02-04 18:12:11'),
(235, 1, 'up', '2009-02-06 01:05:55'),
(236, 1, 'up', '2009-02-06 03:18:03'),
(237, 1, 'up', '2009-02-06 05:25:29'),
(238, 1, 'up', '2009-02-07 10:53:05'),
(239, 1, 'up', '2009-02-07 12:44:23'),
(240, 1, 'up', '2009-02-09 14:36:05'),
(241, 1, 'up', '2009-02-09 14:55:02'),
(242, 1, 'up', '2009-02-10 06:59:00'),
(243, 1, 'up', '2009-02-10 15:13:26'),
(244, 1, 'up', '2009-02-11 01:07:06'),
(245, 1, 'up', '2009-02-11 08:00:12'),
(246, 1, 'up', '2009-02-11 12:05:35'),
(247, 1, 'up', '2009-02-12 03:01:02'),
(248, 1, 'up', '2009-02-13 13:24:40'),
(249, 1, 'up', '2009-02-14 05:28:38'),
(250, 1, 'up', '2009-02-14 13:16:15'),
(251, 1, 'up', '2009-02-14 15:03:16'),
(252, 1, 'up', '2009-02-14 20:43:31'),
(253, 1, 'up', '2009-02-15 00:12:47'),
(254, 1, 'up', '2009-02-15 03:25:41'),
(255, 1, 'down', '2009-02-15 18:04:51'),
(256, 1, 'up', '2009-02-16 14:28:41'),
(257, 1, 'up', '2009-02-16 19:15:04'),
(258, 1, 'up', '2009-02-16 22:22:08'),
(259, 1, 'up', '2009-02-17 15:00:02'),
(260, 1, 'up', '2009-02-17 18:07:37'),
(261, 1, 'up', '2009-02-18 05:07:33'),
(262, 1, 'up', '2009-02-18 23:31:42'),
(263, 1, 'up', '2009-02-19 10:33:23'),
(264, 1, 'up', '2009-02-19 16:32:03'),
(265, 1, 'up', '2009-02-20 08:58:22'),
(266, 1, 'up', '2009-02-20 10:01:43'),
(267, 1, 'up', '2009-02-20 14:58:48'),
(268, 1, 'up', '2009-02-23 01:46:55'),
(269, 1, 'up', '2009-02-23 15:22:01'),
(270, 1, 'up', '2009-02-23 19:44:15'),
(271, 1, 'up', '2009-02-24 04:05:54'),
(272, 1, 'up', '2009-02-25 09:26:59'),
(273, 1, 'up', '2009-02-25 11:24:44'),
(274, 1, 'up', '2009-02-27 02:43:57'),
(275, 1, 'up', '2009-02-27 11:38:01'),
(276, 1, 'up', '2009-02-28 05:09:26'),
(277, 1, 'up', '2009-02-28 07:34:52'),
(278, 1, 'up', '2009-02-28 21:35:42'),
(279, 1, 'up', '2009-03-01 05:05:58'),
(280, 1, 'up', '2009-03-02 15:56:46'),
(281, 1, 'up', '2009-03-03 05:03:38'),
(282, 1, 'up', '2009-03-04 23:19:34'),
(283, 1, 'up', '2009-03-05 00:22:56'),
(284, 1, 'up', '2009-03-06 00:38:46'),
(285, 1, 'up', '2009-03-07 03:56:51'),
(286, 1, 'up', '2009-03-09 15:53:29'),
(287, 1, 'up', '2009-03-10 11:32:14'),
(288, 1, 'up', '2009-03-11 07:52:59'),
(289, 1, 'up', '2009-03-11 12:14:11'),
(290, 1, 'up', '2009-03-11 13:28:39'),
(291, 1, 'up', '2009-03-12 08:52:48'),
(292, 1, 'up', '2009-03-14 15:55:45'),
(293, 1, 'up', '2009-03-16 14:15:14'),
(294, 1, 'up', '2009-03-17 04:07:39'),
(295, 1, 'up', '2009-03-19 01:37:32'),
(296, 1, 'up', '2009-03-19 20:47:55'),
(297, 1, 'up', '2009-03-23 02:21:44'),
(298, 1, 'up', '2009-03-23 08:56:41'),
(299, 1, 'up', '2009-03-23 09:40:57'),
(300, 1, 'up', '2009-03-23 15:39:05'),
(301, 1, 'up', '2009-03-23 17:59:29'),
(302, 1, 'up', '2009-03-24 16:42:11'),
(303, 1, 'up', '2009-03-28 06:57:59'),
(304, 1, 'up', '2009-03-28 11:13:35'),
(305, 1, 'up', '2009-03-28 19:34:09'),
(306, 1, 'up', '2009-03-29 17:01:02'),
(307, 1, 'up', '2009-03-30 15:33:03'),
(308, 1, 'up', '2009-04-01 18:16:05'),
(309, 1, 'up', '2009-04-02 01:28:13'),
(310, 1, 'up', '2009-04-02 11:49:24'),
(311, 1, 'up', '2009-04-05 06:55:04'),
(312, 1, 'up', '2009-04-05 23:18:23'),
(313, 1, 'up', '2009-04-06 15:36:50'),
(314, 1, 'up', '2009-04-07 09:27:19'),
(315, 1, 'up', '2009-04-07 18:35:57'),
(316, 1, 'up', '2009-04-08 13:10:02'),
(317, 1, 'up', '2009-04-08 20:11:16'),
(318, 1, 'up', '2009-04-09 02:39:21'),
(319, 1, 'up', '2009-04-11 15:49:40'),
(320, 1, 'up', '2009-04-11 20:49:04'),
(321, 1, 'up', '2009-04-12 14:55:12'),
(322, 1, 'up', '2009-04-13 15:06:09'),
(323, 1, 'up', '2009-04-13 15:38:32'),
(324, 1, 'up', '2009-04-13 18:06:17'),
(325, 1, 'up', '2009-04-13 19:04:26'),
(326, 1, 'up', '2009-04-14 03:25:34'),
(327, 1, 'up', '2009-04-14 15:21:40'),
(328, 1, 'up', '2009-04-14 22:52:13'),
(329, 1, 'up', '2009-04-15 21:00:34'),
(330, 1, 'up', '2009-04-16 14:56:05'),
(331, 1, 'up', '2009-04-20 15:21:33'),
(332, 1, 'up', '2009-04-21 19:50:25'),
(333, 1, 'up', '2009-04-23 18:58:37'),
(334, 1, 'up', '2009-04-25 16:41:33'),
(335, 1, 'up', '2009-04-26 00:30:19'),
(336, 1, 'up', '2009-04-26 11:20:01'),
(337, 1, 'up', '2009-04-27 15:59:23'),
(338, 1, 'up', '2009-05-01 00:08:35'),
(339, 1, 'up', '2009-05-01 10:59:42'),
(340, 1, 'up', '2009-05-02 03:18:09'),
(341, 1, 'up', '2009-05-04 11:51:28'),
(342, 1, 'up', '2009-05-04 15:51:49'),
(343, 1, 'up', '2009-05-05 07:21:28'),
(344, 1, 'up', '2009-05-05 09:57:36'),
(345, 1, 'up', '2009-05-06 04:57:44'),
(346, 1, 'up', '2009-05-07 07:32:17'),
(347, 1, 'up', '2009-05-08 23:48:34'),
(348, 1, 'up', '2009-05-11 16:18:57'),
(349, 1, 'up', '2009-05-12 20:51:16'),
(350, 1, 'up', '2009-05-13 19:47:54'),
(351, 1, 'up', '2009-05-14 02:59:07'),
(352, 1, 'up', '2009-05-14 06:21:19'),
(353, 1, 'up', '2009-05-15 11:43:30'),
(354, 1, 'up', '2009-05-16 08:35:07'),
(355, 1, 'up', '2009-05-18 05:57:28'),
(356, 1, 'up', '2009-05-18 16:38:25'),
(357, 1, 'up', '2009-05-22 17:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `monitor_sites`
--

CREATE TABLE IF NOT EXISTS `monitor_sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `lastmodified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `monitor_sites`
--

INSERT INTO `monitor_sites` (`site_id`, `site`, `keyword`, `lastmodified`) VALUES
(1, 'https://admin403b.com', NULL, 1243012887);

-- --------------------------------------------------------

--
-- Table structure for table `report_login`
--

CREATE TABLE IF NOT EXISTS `report_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` enum('admin','employee','employer','vendor') DEFAULT NULL,
  `logindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `report_login`
--

INSERT INTO `report_login` (`id`, `uid`, `type`, `logindate`) VALUES
(1, 1, 'admin', '2008-11-22 18:21:32'),
(2, 1, 'admin', '2008-11-23 14:47:50'),
(3, 1, 'admin', '2008-11-23 22:23:52'),
(4, 1, 'admin', '2008-11-23 22:26:24'),
(5, 2, 'employer', '2008-11-23 22:50:41'),
(6, 2, 'employee', '2008-11-23 22:56:17'),
(7, 1, 'admin', '2008-11-25 03:54:05'),
(8, 1, 'admin', '2008-11-27 05:35:51'),
(9, 1, 'admin', '2008-11-27 18:07:34'),
(10, 2, 'employee', '2008-11-29 05:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(32) NOT NULL,
  `created_dt` datetime DEFAULT NULL,
  `login_type` enum('Admin','Employer','Employee','Vendor','Designee') DEFAULT NULL,
  `acting_as` enum('Admin','Employer','Employee','Vendor','Designee') DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `created_dt`, `login_type`, `acting_as`, `id`) VALUES
(1, 'asimonson@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-04-16 14:20:24', 'Admin', NULL, 1),
(2, 'vendor@mkgalaxy.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-11-22 12:46:49', 'Vendor', NULL, 1),
(3, 'employer@mkgalaxy.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-11-22 13:16:10', 'Employer', NULL, 1),
(4, 'employee@mkgalaxy.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-11-22 13:19:37', 'Employee', NULL, 1),
(5, 'yash0708@hotmail.com', '088495f30901580ddd5171531cd26649', '2008-11-23 22:28:41', 'Employer', NULL, 2),
(6, 'juhikh@yahoo.com', '088495f30901580ddd5171531cd26649', '2008-11-23 22:42:08', 'Employee', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `remittance_address` varchar(255) DEFAULT NULL,
  `created_dt` int(11) NOT NULL DEFAULT '0',
  `modified_dt` int(11) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  `employer_access` varchar(255) DEFAULT NULL,
  `employee_access` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `name`, `password`, `email`, `phone`, `fax`, `url`, `remittance_address`, `created_dt`, `modified_dt`, `status`, `employer_access`, `employee_access`) VALUES
(1, 'vendor1', '5f4dcc3b5aa765d61d8327deb882cf99', 'vendor@mkgalaxy.com', NULL, NULL, NULL, NULL, 1227357994, 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_documents`
--

CREATE TABLE IF NOT EXISTS `vendor_documents` (
  `docu_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  `real_filename` varchar(255) DEFAULT NULL,
  `display` varchar(50) DEFAULT NULL,
  `comments` text CHARACTER SET latin1 COLLATE latin1_general_ci,
  `extension` varchar(10) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `filetype` varchar(255) DEFAULT NULL,
  `upload_dt` int(11) DEFAULT NULL,
  PRIMARY KEY (`docu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vendor_documents`
--


-- --------------------------------------------------------

--
-- Table structure for table `vendor_plan`
--

CREATE TABLE IF NOT EXISTS `vendor_plan` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `plan_name` varchar(50) DEFAULT NULL,
  `plan_code` varchar(50) DEFAULT NULL,
  `plan_desc` text,
  `plan_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `vendor_plan`
--

INSERT INTO `vendor_plan` (`plan_id`, `vendor_id`, `plan_name`, `plan_code`, `plan_desc`, `plan_link`) VALUES
(1, 1, 'product 1', 'product1', 'product 1', 'http://yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `workflow`
--

CREATE TABLE IF NOT EXISTS `workflow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `requestor_type` enum('Employer','Employee','Vendor') DEFAULT NULL,
  `approver_type` enum('Employer','Employee','Vendor') DEFAULT NULL,
  `employer_list` text,
  `forward_enable` enum('Y','N') DEFAULT NULL,
  `forward_type` int(1) DEFAULT NULL COMMENT '1=employer, 2=employee, 3=vendor, 4=employee, employer 5=employer, vendor 6=employee, vendor, 7=employer, employee, vendor',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `workflow`
--

INSERT INTO `workflow` (`id`, `name`, `description`, `requestor_type`, `approver_type`, `employer_list`, `forward_enable`, `forward_type`) VALUES
(2, 'Excess Contribution', 'Used to refund exceess contribution once limit is exceeded.', 'Employee', 'Employer', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workflow_documents`
--

CREATE TABLE IF NOT EXISTS `workflow_documents` (
  `docu_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  `real_filename` varchar(255) DEFAULT NULL,
  `display` varchar(50) DEFAULT NULL,
  `comments` text,
  `extension` varchar(10) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `filetype` varchar(255) DEFAULT NULL,
  `upload_dt` int(11) DEFAULT NULL,
  PRIMARY KEY (`docu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `workflow_documents`
--


-- --------------------------------------------------------

--
-- Table structure for table `workflow_employer_list`
--

CREATE TABLE IF NOT EXISTS `workflow_employer_list` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL DEFAULT '0',
  `employer_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `workflow_employer_list`
--

