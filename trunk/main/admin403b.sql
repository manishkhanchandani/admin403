-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: mysql1061.servage.net
-- Generation Time: Sep 04, 2008 at 06:07 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `employer11`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `actions`
-- 

CREATE TABLE `actions` (
  `action_id` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  `title` varchar(200) collate latin1_general_ci default NULL,
  `requestor_id` int(11) NOT NULL default '0',
  `requestor_type` enum('Employer','Employee','Vendor') collate latin1_general_ci default NULL,
  `action_type` enum('Employer','Employee','Vendor') collate latin1_general_ci default NULL,
  `wf_id` int(11) default NULL,
  `status` enum('Pending','Approve','Decline','Cancel') collate latin1_general_ci default NULL,
  `reasons` text collate latin1_general_ci,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `action_name` varchar(50) collate latin1_general_ci default NULL,
  `action_description` varchar(200) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `actions`
-- 

INSERT INTO `actions` (`action_id`, `id`, `pid`, `title`, `requestor_id`, `requestor_type`, `action_type`, `wf_id`, `status`, `reasons`, `date_created`, `action_name`, `action_description`) VALUES 
(1, 1, 0, 'SRA Add Request', 1, 'Employee', 'Employer', 3, 'Approve', NULL, '2008-08-12 23:06:48', NULL, NULL),
(2, 1, 0, 'SRA Delete Request', 1, 'Employee', 'Employer', 5, 'Approve', NULL, '2008-08-13 00:29:02', NULL, NULL),
(3, 1, 0, 'Change Request', 1, 'Employee', 'Employer', 4, 'Approve', NULL, '2008-08-13 00:47:22', NULL, NULL),
(4, 1, 3, 'Change Request', 1, 'Employer', 'Vendor', 4, 'Pending', NULL, '2008-08-13 00:53:41', 'SRA Change Request', 'Workflow request to modify current SRA agreement.'),
(5, 1, 0, '50.00', 27, 'Employee', 'Employer', 4, 'Pending', NULL, '2008-08-13 01:50:54', NULL, NULL),
(6, 3, 0, 'Salary Reduction for John', 28, 'Employee', 'Employer', 3, 'Pending', NULL, '2008-08-24 05:23:54', NULL, NULL),
(7, 1, 0, 'SRA Add Request Test', 5, 'Employee', 'Employer', 3, 'Pending', NULL, '2008-09-02 16:58:42', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `admin`
-- 

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL auto_increment,
  `email` varchar(150) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) default NULL,
  `address` varchar(255) default NULL,
  `phone` varchar(50) default NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `admin`
-- 

INSERT INTO `admin` (`admin_id`, `email`, `password`, `name`, `address`, `phone`) VALUES 
(1, 'admin@mkgalaxy.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin', 'admin', 'admin');

-- --------------------------------------------------------

-- 
-- Table structure for table `admin_system_settings`
-- 

CREATE TABLE `admin_system_settings` (
  `setting_id` int(11) NOT NULL auto_increment,
  `year` int(4) NOT NULL default '0',
  `annual_age_limit` float(12,2) default NULL,
  `annual_pretax_limit` float(12,2) default NULL,
  `annual_roth_limit` float(12,2) default NULL,
  PRIMARY KEY  (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `admin_system_settings`
-- 

INSERT INTO `admin_system_settings` (`setting_id`, `year`, `annual_age_limit`, `annual_pretax_limit`, `annual_roth_limit`) VALUES 
(1, 2008, 15000.00, 15000.00, 5000.00),
(2, 2009, 15500.00, 18000.00, 5500.00);

-- --------------------------------------------------------

-- 
-- Table structure for table `email`
-- 

CREATE TABLE `email` (
  `email_id` int(11) NOT NULL auto_increment,
  `subject` varchar(150) collate latin1_general_ci default NULL,
  `message` text collate latin1_general_ci,
  `email_type` enum('Text','HTML') collate latin1_general_ci NOT NULL default 'Text',
  `ref` varchar(50) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`email_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `email`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employee`
-- 

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL auto_increment,
  `password` varchar(32) collate latin1_general_ci default NULL,
  `email` varchar(150) collate latin1_general_ci default NULL,
  `firstname` varchar(25) collate latin1_general_ci default NULL,
  `middlename` varchar(25) collate latin1_general_ci default NULL,
  `lastname` varchar(25) collate latin1_general_ci default NULL,
  `ssn` varchar(50) collate latin1_general_ci default NULL,
  `employer_id` int(11) NOT NULL default '0',
  `address` varchar(255) collate latin1_general_ci default NULL,
  `married` enum('Yes','No') collate latin1_general_ci default NULL,
  `sex` enum('M','F') collate latin1_general_ci default NULL,
  `hire_date` date default NULL,
  `termination_date` date default NULL,
  `dob` date default NULL,
  `account_number` varchar(50) collate latin1_general_ci default NULL,
  `phone` varchar(50) collate latin1_general_ci default NULL,
  `fax` varchar(50) collate latin1_general_ci default NULL,
  `created_dt` int(11) NOT NULL default '0',
  `modified_dt` int(11) NOT NULL default '0',
  `status` int(2) NOT NULL default '1',
  `catchup` float(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee`
-- 

INSERT INTO `employee` (`employee_id`, `password`, `email`, `firstname`, `middlename`, `lastname`, `ssn`, `employer_id`, `address`, `married`, `sex`, `hire_date`, `termination_date`, `dob`, `account_number`, `phone`, `fax`, `created_dt`, `modified_dt`, `status`, `catchup`) VALUES 
(1, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee1@verityinvest.com', 'Employee', 'Number', 'One', '123-45-0001', 1, NULL, NULL, NULL, '2008-02-01', NULL, '1978-02-01', '10000', 'password', NULL, 1218581730, 0, 1, 0.00),
(2, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee2@verityinvest.com', 'Employee', 'Number', 'Two', '123-45-0002', 1, NULL, NULL, NULL, '2006-03-02', NULL, '1974-03-02', '20000', 'password', NULL, 1218581730, 0, 1, 0.00),
(3, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee3@verityinvest.com', 'Employee', 'Number', 'Three', '123-45-0003', 1, NULL, NULL, NULL, '2002-04-03', NULL, '1970-04-03', '30000', 'password', NULL, 1218581730, 0, 1, 0.00),
(4, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee4@verityinvest.com', 'Employee', 'Number', 'Four', '123-45-0004', 1, NULL, NULL, NULL, '2001-05-04', NULL, '1966-05-04', '40000', 'password', NULL, 1218581730, 0, 1, 0.00),
(5, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee5@verityinvest.com', 'Employee', 'Number', 'Five', '123-45-0005', 1, NULL, NULL, NULL, '2001-06-05', NULL, '1962-06-05', '50000', 'password', NULL, 1218581730, 0, 1, 0.00),
(6, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee6@verityinvest.com', 'Employee', 'Number', 'Six', '123-45-0006', 1, NULL, NULL, NULL, '2000-07-06', NULL, '1964-07-06', '60000', 'password', NULL, 1218581730, 0, 1, 0.00),
(7, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee7@verityinvest.com', 'Employee', 'Number', 'Seven', '123-45-0007', 1, NULL, NULL, NULL, '1996-08-07', NULL, '1964-08-07', '70000', 'password', NULL, 1218581730, 0, 1, 0.00),
(8, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee8@verityinvest.com', 'Employee', 'Number', 'Eight', '123-45-0008', 1, NULL, NULL, NULL, '1990-09-08', NULL, '1964-09-08', '80000', 'password', NULL, 1218581730, 0, 1, 0.00),
(9, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee9@verityinvest.com', 'Employee', 'Number', 'Nine', '123-45-0009', 1, NULL, NULL, NULL, '1988-10-09', NULL, '1960-10-09', '90000', 'password', NULL, 1218581730, 0, 1, 0.00),
(10, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee10@verityinvest.com', 'Employee', 'Number', 'Ten', '123-45-0010', 1, NULL, NULL, NULL, '1986-11-10', NULL, '1956-11-10', '10000', 'password', NULL, 1218581731, 0, 1, 0.00),
(11, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee11@verityinvest.com', 'Employee', 'Number', 'Eleven', '123-45-0011', 1, NULL, NULL, NULL, '1984-12-11', NULL, '1952-12-11', '11000', 'password', NULL, 1218581731, 0, 1, 0.00),
(12, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee12@verityinvest.com', 'Employee', 'Number', 'Twelve', '123-45-0012', 1, NULL, NULL, NULL, '0000-00-00', NULL, '0000-00-00', '12000', 'password', NULL, 1218581731, 0, 1, 0.00),
(13, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee13@verityinvest.com', 'Employee', 'Number', 'Thirteen', '123-45-0013', 1, NULL, NULL, NULL, '1984-02-01', NULL, '1978-02-01', '13000', 'password', NULL, 1218581731, 0, 1, 0.00),
(14, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee14@verityinvest.com', 'Employee', 'Number', 'Fourteen', '123-45-0014', 1, NULL, NULL, NULL, '1986-03-02', NULL, '1974-03-02', '14000', 'password', NULL, 1218581731, 0, 1, 0.00),
(15, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee15@verityinvest.com', 'Employee', 'Number', 'Fifteen', '123-45-0015', 1, NULL, NULL, NULL, '1988-04-03', NULL, '1970-04-03', '15000', 'password', NULL, 1218581731, 0, 1, 0.00),
(16, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee16@verityinvest.com', 'Employee', 'Number', 'Sixteen', '123-45-0016', 1, NULL, NULL, NULL, '1990-05-04', NULL, '1966-05-04', '16000', 'password', NULL, 1218581731, 0, 1, 0.00),
(17, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee17@verityinvest.com', 'Employee', 'Number', 'Seventeen', '123-45-0017', 1, NULL, NULL, NULL, '1992-06-05', NULL, '1962-06-05', '17000', 'password', NULL, 1218581731, 0, 1, 0.00),
(18, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee18@verityinvest.com', 'Employee', 'Number', 'Eighteen', '123-45-0018', 1, NULL, NULL, NULL, '1994-07-06', NULL, '1964-07-06', '18000', 'password', NULL, 1218581731, 0, 1, 0.00),
(19, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee19@verityinvest.com', 'Employee', 'Number', 'Nineteen', '123-45-0019', 1, NULL, NULL, NULL, '1996-08-07', NULL, '1964-08-07', '19000', 'password', NULL, 1218581731, 0, 1, 0.00),
(20, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee20@verityinvest.com', 'Employee', 'Number', 'Twenty', '123-45-0020', 1, NULL, NULL, NULL, '1998-09-08', NULL, '1964-09-08', '20000', 'password', NULL, 1218581731, 0, 1, 0.00),
(21, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee21@verityinvest.com', 'Employee', 'Number', 'Twentyone', '123-45-0021', 1, NULL, NULL, NULL, '2000-10-09', NULL, '1960-10-09', '21000', 'password', NULL, 1218581731, 0, 1, 0.00),
(22, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee22@verityinvest.com', 'Employee', 'Number', 'Twentytwo', '123-45-0022', 1, NULL, NULL, NULL, '2002-11-10', NULL, '1956-11-10', '22000', 'password', NULL, 1218581731, 0, 1, 0.00),
(23, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee23@verityinvest.com', 'Employee', 'Number', 'Twentythree', '123-45-0023', 1, NULL, NULL, NULL, '2004-12-11', NULL, '1952-12-11', '23000', 'password', NULL, 1218581731, 0, 1, 0.00),
(24, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee24@verityinvest.com', 'Employee', 'Number', 'Twentyfour', '123-45-0024', 1, NULL, NULL, NULL, '0000-00-00', NULL, '0000-00-00', '24000', 'password', NULL, 1218581731, 0, 1, 0.00),
(25, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee25@verityinvest.com', 'Employee', 'Number', 'Twentyfive', '123-45-0025', 1, NULL, NULL, NULL, '2008-02-01', NULL, '1980-02-01', '25000', 'password', NULL, 1218581731, 0, 1, 0.00),
(26, '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee26@verityinvest.com', 'Employee', 'Number', 'Twentysix', '123-45-0026', 1, NULL, NULL, NULL, '2005-08-03', NULL, '1985-07-02', '26000', 'password', NULL, 1218581731, 0, 1, 0.00),
(27, '9d280857126d7fdfa5e4a80bede26684', 'employee27@verityinvest.com', 'Employee', 'N', '27', '111-27-1111', 1, NULL, 'Yes', 'M', '0000-00-00', NULL, '0000-00-00', NULL, '111-1111', NULL, 1218591915, 0, 1, 0.00),
(28, '5f4dcc3b5aa765d61d8327deb882cf99', 'emp@websmc.com', 'John', NULL, 'Smith', '456-78-9870', 3, NULL, 'Yes', 'M', '0000-00-00', NULL, '0000-00-00', '123456', '555-2345', NULL, 1219555420, 1219584375, 1, 0.00);

-- --------------------------------------------------------

-- 
-- Table structure for table `employee_contribution`
-- 

CREATE TABLE `employee_contribution` (
  `contribution_id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `employer_id` int(11) NOT NULL default '0',
  `contribution_date` date default NULL,
  `sra_pretax` float(12,2) default NULL,
  `sra_roth` float(12,2) default NULL,
  PRIMARY KEY  (`contribution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee_contribution`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employee_contribution_details`
-- 

CREATE TABLE `employee_contribution_details` (
  `transaction_id` int(11) NOT NULL auto_increment,
  `cdate` date default NULL,
  `ssn` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `employee_id` int(11) default NULL,
  `pretax` float(12,2) default NULL,
  `roth` float(12,2) default NULL,
  `pretax_refund` float(12,2) default NULL,
  `roth_refund` float(12,2) default NULL,
  `duplicate_record` int(1) NOT NULL default '0',
  PRIMARY KEY  (`transaction_id`),
  UNIQUE KEY `cdate` (`cdate`,`ssn`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `employee_contribution_details`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employee_contribution_transaction`
-- 

CREATE TABLE `employee_contribution_transaction` (
  `transaction_id` int(11) NOT NULL auto_increment,
  `transaction_date` date default NULL,
  `employer_id` int(11) NOT NULL default '0',
  `totalrecords` int(11) NOT NULL default '0',
  `totalamount` float(12,2) NOT NULL default '0.00',
  `totalamountprocessed` float(12,2) NOT NULL default '0.00',
  `totalamountrefunded` float(12,2) NOT NULL default '0.00',
  `totalrecordsprocessedsuccessfully` int(11) NOT NULL default '0',
  `totalrecordsrejected` int(11) NOT NULL default '0',
  `recordsalreadyuploaded` int(11) NOT NULL default '0',
  `totalroth` float(12,2) NOT NULL default '0.00',
  `totalrothprocessed` float(12,2) NOT NULL default '0.00',
  `totalrothrefunded` float(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee_contribution_transaction`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employee_history`
-- 

CREATE TABLE `employee_history` (
  `history_id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) default NULL,
  `hardship_date` int(11) default NULL,
  `loan_closed_date` int(11) default NULL,
  `loan_issue_date` int(11) default NULL,
  PRIMARY KEY  (`history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee_history`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employee_vendor`
-- 

CREATE TABLE `employee_vendor` (
  `emp_plan_id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`emp_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee_vendor`
-- 

INSERT INTO `employee_vendor` (`emp_plan_id`, `employee_id`, `plan_id`, `vendor_id`) VALUES 
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 3, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `employee_vendor_deleted`
-- 

CREATE TABLE `employee_vendor_deleted` (
  `vendor_del_id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `del_date` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`vendor_del_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employee_vendor_deleted`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employer`
-- 

CREATE TABLE `employer` (
  `employer_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate latin1_general_ci default NULL,
  `phone` varchar(50) collate latin1_general_ci default NULL,
  `email` varchar(150) collate latin1_general_ci default NULL,
  `address` varchar(255) collate latin1_general_ci default NULL,
  `loan_provision` enum('Y','N') collate latin1_general_ci default NULL,
  `service_provision` enum('Y','N') collate latin1_general_ci default NULL,
  `hardship_provision` enum('Y','N') collate latin1_general_ci default NULL,
  `exchanges` enum('Y','N') collate latin1_general_ci default NULL,
  `transfers_in` enum('Y','N') collate latin1_general_ci default NULL,
  `transfers_out` enum('Y','N') collate latin1_general_ci default NULL,
  `roth_provision` enum('Y','N') collate latin1_general_ci default NULL,
  `service_eligible_limit` float(12,2) default NULL,
  `password` varchar(32) collate latin1_general_ci default NULL,
  `created_dt` int(11) NOT NULL default '0',
  `modified_dt` int(11) NOT NULL default '0',
  `status` int(2) NOT NULL default '1',
  PRIMARY KEY  (`employer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employer`
-- 

INSERT INTO `employer` (`employer_id`, `name`, `phone`, `email`, `address`, `loan_provision`, `service_provision`, `hardship_provision`, `exchanges`, `transfers_in`, `transfers_out`, `roth_provision`, `service_eligible_limit`, `password`, `created_dt`, `modified_dt`, `status`) VALUES 
(1, 'Employer1', '919-423-1987', 'Employer1@verityinvest.com', '123 Tower Dr\r\nDurham, NC 27708\r\n', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 15000.00, '5f4dcc3b5aa765d61d8327deb882cf99', 1218575744, 0, 1),
(2, 'Employer2', '919-423-1989', 'Employer2@verityinvest.com', '8009 Darma St\r\nChapell Hill, NC 27717', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 15000.00, '5f4dcc3b5aa765d61d8327deb882cf99', 1218575835, 0, 1),
(3, 'WebSMC', '555-1234', 'yash0708@hotmail.com', NULL, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 5000.00, '088495f30901580ddd5171531cd26649', 1219555287, 0, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `employer_access`
-- 

CREATE TABLE `employer_access` (
  `compliance_id` int(11) NOT NULL auto_increment,
  `employer_id` int(11) NOT NULL default '0',
  `compliance_designee_name` varchar(50) collate latin1_general_ci default NULL,
  `compliance_designee_email` varchar(150) collate latin1_general_ci default NULL,
  `compliance_designee_phone` varchar(20) collate latin1_general_ci default NULL,
  `compliance_designee_password` varchar(32) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`compliance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employer_access`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employer_documents`
-- 

CREATE TABLE `employer_documents` (
  `docu_id` int(11) NOT NULL auto_increment,
  `employer_id` int(11) NOT NULL default '0',
  `filename` varchar(255) collate latin1_general_ci default NULL,
  `real_filename` varchar(255) collate latin1_general_ci default NULL,
  `display` varchar(50) collate latin1_general_ci default NULL,
  `comments` text collate latin1_general_ci,
  `extension` varchar(10) collate latin1_general_ci default NULL,
  `size` bigint(20) default NULL,
  `filetype` varchar(255) collate latin1_general_ci default NULL,
  `upload_dt` int(11) default NULL,
  PRIMARY KEY  (`docu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employer_documents`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `employer_vendor`
-- 

CREATE TABLE `employer_vendor` (
  `emp_ven_id` int(11) NOT NULL auto_increment,
  `employer_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  PRIMARY KEY  (`emp_ven_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `employer_vendor`
-- 

INSERT INTO `employer_vendor` (`emp_ven_id`, `employer_id`, `plan_id`, `vendor_id`, `active`) VALUES 
(1, 1, 1, 1, 1),
(2, 1, 2, 2, 1),
(3, 2, 3, 3, 1),
(4, 2, 2, 2, 1),
(5, 1, 3, 3, 1),
(6, 3, 1, 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `employer_vendors_contact`
-- 

CREATE TABLE `employer_vendors_contact` (
  `contact_id` int(11) NOT NULL auto_increment,
  `employer_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `contact_name` varchar(50) collate latin1_general_ci default NULL,
  `contact_email` varchar(150) collate latin1_general_ci default NULL,
  `contact_phone` varchar(30) collate latin1_general_ci default NULL,
  `group_plan_number` varchar(100) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employer_vendors_contact`
-- 

INSERT INTO `employer_vendors_contact` (`contact_id`, `employer_id`, `vendor_id`, `plan_id`, `contact_name`, `contact_email`, `contact_phone`, `group_plan_number`) VALUES 
(1, 1, 1, 1, 'Amy Smith', 'asmith@vendor1.com', '919-998-9990', '10003987'),
(2, 1, 2, 2, 'Charlie Datem', 'Cdatem@vendor2.com', '919-789-7896', '100050989'),
(3, 2, 3, 3, 'John Talio', 'jtalio@vendor3.com', '987-981-1278', '009887345'),
(4, 2, 2, 2, 'Jackie Sartem', 'jsartem@vendor2.com', '239-789-6712', '452138764'),
(5, 1, 3, 3, 'Vendor Three', 'vendor3@verityinvest.com', '919-490-6717', NULL),
(6, 3, 1, 1, 'XYZ', 'newemail@websmc.com', '5552345', 'CX01234');

-- --------------------------------------------------------

-- 
-- Table structure for table `employer_vendor_deleted`
-- 

CREATE TABLE `employer_vendor_deleted` (
  `vendor_del_id` int(11) NOT NULL auto_increment,
  `employer_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `del_date` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`vendor_del_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `employer_vendor_deleted`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `email` varchar(150) collate latin1_general_ci NOT NULL,
  `password` varchar(32) collate latin1_general_ci NOT NULL,
  `created_dt` datetime default NULL,
  `login_type` enum('Admin','Employer','Employee','Vendor','Designee') collate latin1_general_ci default NULL,
  `acting_as` enum('Admin','Employer','Employee','Vendor','Designee') collate latin1_general_ci default NULL,
  `id` int(11) default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` (`user_id`, `email`, `password`, `created_dt`, `login_type`, `acting_as`, `id`) VALUES 
(1, 'admin@mkgalaxy.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-04-16 14:20:24', 'Admin', NULL, 1),
(2, 'Employer1@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 02:47:05', 'Employer', NULL, 1),
(3, 'Employer2@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 02:49:18', 'Employer', NULL, 2),
(4, 'Vendor1@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 02:56:45', 'Vendor', NULL, 1),
(5, 'Vendor2@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 02:59:36', 'Vendor', NULL, 2),
(6, 'Vendor3@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 03:01:42', 'Vendor', NULL, 3),
(7, 'Employee1@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 1),
(8, 'Employee2@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 2),
(9, 'Employee3@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 3),
(10, 'Employee4@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 4),
(11, 'Employee5@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 5),
(12, 'Employee6@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 6),
(13, 'Employee7@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 7),
(14, 'Employee8@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 8),
(15, 'Employee9@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:30', 'Employee', NULL, 9),
(16, 'Employee10@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 10),
(17, 'Employee11@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 11),
(18, 'Employee12@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 12),
(19, 'Employee13@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 13),
(20, 'Employee14@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 14),
(21, 'Employee15@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 15),
(22, 'Employee16@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 16),
(23, 'Employee17@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 17),
(24, 'Employee18@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 18),
(25, 'Employee19@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 19),
(26, 'Employee20@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 20),
(27, 'Employee21@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 21),
(28, 'Employee22@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 22),
(29, 'Employee23@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 23),
(30, 'Employee24@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 24),
(31, 'Employee25@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 25),
(32, 'Employee26@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 04:25:31', 'Employee', NULL, 26),
(33, 'employee27@verityinvest.com', '9d280857126d7fdfa5e4a80bede26684', '2008-08-13 07:15:36', 'Employee', NULL, 27),
(34, 'vendor4@verityinvest.com', '5f4dcc3b5aa765d61d8327deb882cf99', '2008-08-13 16:56:32', 'Vendor', NULL, 4),
(35, 'yash0708@hotmail.com', '088495f30901580ddd5171531cd26649', '2008-08-24 10:53:20', 'Employer', NULL, 3),
(36, 'emp@websmc.com', '088495f30901580ddd5171531cd26649', '2008-08-24 10:56:08', 'Employee', NULL, 28);

-- --------------------------------------------------------

-- 
-- Table structure for table `vendor`
-- 

CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate latin1_general_ci NOT NULL,
  `password` varchar(32) collate latin1_general_ci NOT NULL,
  `email` varchar(150) collate latin1_general_ci NOT NULL,
  `phone` varchar(50) collate latin1_general_ci default NULL,
  `fax` varchar(50) collate latin1_general_ci default NULL,
  `url` varchar(255) collate latin1_general_ci default NULL,
  `remittance_address` varchar(255) collate latin1_general_ci default NULL,
  `created_dt` int(11) NOT NULL default '0',
  `modified_dt` int(11) NOT NULL default '0',
  `status` int(2) NOT NULL default '1',
  `employer_access` varchar(255) collate latin1_general_ci default NULL,
  `employee_access` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `vendor`
-- 

INSERT INTO `vendor` (`vendor_id`, `name`, `password`, `email`, `phone`, `fax`, `url`, `remittance_address`, `created_dt`, `modified_dt`, `status`, `employer_access`, `employee_access`) VALUES 
(1, 'Verity Investments, Inc', '5f4dcc3b5aa765d61d8327deb882cf99', 'Vendor1@verityinvest.com', '819-919-8976', '819-919-8977', 'www.Vendor1.com', '123 Vendor1 PkwyDurham, NC 27707', 1218576009, 1218626715, 1, 'www.Vendor1.com', 'www.Vendor1.com'),
(2, 'Provider 1', '5f4dcc3b5aa765d61d8327deb882cf99', 'Vendor2@verityinvest.com', '925-784-7892', '925-7847893', 'www.Vendor2.com', '129 Park Avenue  suite 505Charlotte, NC 27865', 1218576411, 1218626687, 1, 'www.Vendor2.com', 'www.Vendor2.com'),
(3, 'Provider 2', '5f4dcc3b5aa765d61d8327deb882cf99', 'Vendor3@verityinvest.com', '923-567-0987', '923-567-0988', 'www.Vendor3.com', '123 High StColumbus, OH 45345', 1218576641, 1218626727, 1, 'www.Vendor3.com', 'www.Vendor3.com'),
(4, 'Provider 3', '5f4dcc3b5aa765d61d8327deb882cf99', 'vendor4@verityinvest.com', '919-490-6717 x4', NULL, NULL, NULL, 1218626738, 0, 1, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `vendor_documents`
-- 

CREATE TABLE `vendor_documents` (
  `docu_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) NOT NULL default '0',
  `filename` varchar(255) collate latin1_general_ci default NULL,
  `real_filename` varchar(255) collate latin1_general_ci default NULL,
  `display` varchar(50) collate latin1_general_ci default NULL,
  `comments` text collate latin1_general_ci,
  `extension` varchar(10) collate latin1_general_ci default NULL,
  `size` bigint(20) default NULL,
  `filetype` varchar(255) collate latin1_general_ci default NULL,
  `upload_dt` int(11) default NULL,
  PRIMARY KEY  (`docu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `vendor_documents`
-- 

INSERT INTO `vendor_documents` (`docu_id`, `vendor_id`, `filename`, `real_filename`, `display`, `comments`, `extension`, `size`, `filetype`, `upload_dt`) VALUES 
(2, 1, '128255513148a2c3aaf256f.pdf', 'ServiceProviderAgreement3-08.pdf', 'Provider Agreement', '', 'pdf', 140074, 'application/pdf', 1218626475),
(3, 1, '92771583248a2c3b2d0589.pdf', 'ServiceProviderAgreement3-08.pdf', 'Information Sharing', '', 'pdf', 140074, 'application/pdf', 1218626482),
(4, 2, '144600914248a2c402b76ef.pdf', 'ServiceProviderAgreement3-08.pdf', 'Provider Agreement', '', 'pdf', 140074, 'application/pdf', 1218626562),
(5, 3, '190979684048a2c3f1696f5.pdf', 'ServiceProviderAgreement3-08.pdf', 'Information Sharing', '', 'pdf', 140074, 'application/pdf', 1218626545),
(6, 4, '28665182448a2c5254fed4.pdf', 'ServiceProviderAgreement3-08.pdf', 'Service Provider Agreement', '', 'pdf', 140074, 'application/pdf', 1218626853);

-- --------------------------------------------------------

-- 
-- Table structure for table `vendor_plan`
-- 

CREATE TABLE `vendor_plan` (
  `plan_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) NOT NULL default '0',
  `plan_name` varchar(50) collate latin1_general_ci default NULL,
  `plan_code` varchar(50) collate latin1_general_ci default NULL,
  `plan_desc` text collate latin1_general_ci,
  `plan_link` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `vendor_plan`
-- 

INSERT INTO `vendor_plan` (`plan_id`, `vendor_id`, `plan_name`, `plan_code`, `plan_desc`, `plan_link`) VALUES 
(1, 1, 'Managed Portfolios', '10002', NULL, 'www.verityinvest.com'),
(2, 2, 'Mutual Funds', 'V2IF', 'Mutual Funds', 'www.americanfunds.com'),
(3, 3, 'Mutual Funds', 'V3LCI', NULL, 'www.franklintempleton.com'),
(4, 4, 'Mutual Funds', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `workflow`
-- 

CREATE TABLE `workflow` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) character set latin1 collate latin1_general_ci default NULL,
  `description` text character set latin1 collate latin1_general_ci,
  `requestor_type` enum('Employer','Employee','Vendor') character set latin1 collate latin1_general_ci default NULL,
  `approver_type` enum('Employer','Employee','Vendor') character set latin1 collate latin1_general_ci default NULL,
  `employer_list` text character set latin1 collate latin1_general_ci,
  `forward_enable` enum('Y','N') default NULL,
  `forward_type` int(1) default NULL COMMENT '1=employer, 2=employee, 3=vendor, 4=employee, employer 5=employer, vendor 6=employee, vendor, 7=employer, employee, vendor',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `workflow`
-- 

INSERT INTO `workflow` (`id`, `name`, `description`, `requestor_type`, `approver_type`, `employer_list`, `forward_enable`, `forward_type`) VALUES 
(2, 'Excess Contribution', 'Used to refund exceess contribution once limit is exceeded.', 'Employee', 'Employer', NULL, NULL, NULL),
(4, 'SRA Change Request', 'Workflow request to modify current SRA agreement.', 'Employee', 'Employer', '1|2', NULL, NULL),
(5, 'SRA Delete Request', 'Workflow request to initiate process to remove employee from Salary Reduction Agreement.', 'Employee', 'Employer', '1|2', NULL, NULL),
(6, 'Employer2Employee', NULL, 'Employer', 'Employee', '1|2|3', NULL, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `workflow_documents`
-- 

CREATE TABLE `workflow_documents` (
  `docu_id` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `filename` varchar(255) collate latin1_general_ci default NULL,
  `real_filename` varchar(255) collate latin1_general_ci default NULL,
  `display` varchar(50) collate latin1_general_ci default NULL,
  `comments` text collate latin1_general_ci,
  `extension` varchar(10) collate latin1_general_ci default NULL,
  `size` bigint(20) default NULL,
  `filetype` varchar(255) collate latin1_general_ci default NULL,
  `upload_dt` int(11) default NULL,
  PRIMARY KEY  (`docu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `workflow_documents`
-- 

INSERT INTO `workflow_documents` (`docu_id`, `id`, `filename`, `real_filename`, `display`, `comments`, `extension`, `size`, `filetype`, `upload_dt`) VALUES 
(1, 2, '84949018048a213b547e1d.pdf', '403demopdf.pdf', 'Excess Contribution Document', 'Excess Contribution Document', 'pdf', 61131, 'application/pdf', 1218581429),
(3, 4, '26207572448a213e2419bf.pdf', '403demopdf.pdf', 'SRA Modify Reques', 'SRA Modify Request', 'pdf', 61131, 'application/pdf', 1218581474),
(4, 5, '120625432848a214029e05e.pdf', '403demopdf.pdf', 'SRA Delete Request', 'SRA Delete Request', 'pdf', 61131, 'application/pdf', 1218581506);

-- --------------------------------------------------------

-- 
-- Table structure for table `workflow_employer_list`
-- 

CREATE TABLE `workflow_employer_list` (
  `eid` int(11) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `employer_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `workflow_employer_list`
-- 

INSERT INTO `workflow_employer_list` (`eid`, `id`, `employer_id`) VALUES 
(3, 4, 1),
(4, 4, 2),
(9, 5, 2),
(8, 5, 1),
(18, 6, 3),
(17, 6, 2),
(16, 6, 1);
