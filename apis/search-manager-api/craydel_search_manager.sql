-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2022 at 11:12 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `craydel_search_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `extracted_key_phrases`
--

CREATE TABLE `extracted_key_phrases` (
  `id` bigint(20) NOT NULL,
  `course_code` text NOT NULL,
  `phrases` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `extracted_key_phrases_index_list`
--

CREATE TABLE `extracted_key_phrases_index_list` (
  `id` int(11) NOT NULL,
  `objectID` varchar(255) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `course_overview` text DEFAULT NULL,
  `discipline` varchar(255) DEFAULT NULL,
  `course_type` varchar(255) DEFAULT NULL,
  `graduate_level` varchar(255) DEFAULT NULL,
  `attendance_type` varchar(255) DEFAULT NULL,
  `learning_mode` varchar(255) DEFAULT NULL,
  `course_requirements` text DEFAULT NULL,
  `enrollment_details` int(11) DEFAULT NULL,
  `accredited_by` varchar(255) DEFAULT NULL,
  `institution_code` varchar(255) DEFAULT NULL,
  `is_featured` int(11) DEFAULT NULL,
  `standard_fee_payable_usd` bigint(11) DEFAULT NULL,
  `popularity` float DEFAULT NULL,
  `institution_ranking` float DEFAULT NULL,
  `course_rating` float DEFAULT NULL,
  `course_duration` int(11) DEFAULT NULL,
  `url_course_slug` text DEFAULT NULL,
  `course_name_slug` text DEFAULT NULL,
  `institution_continent` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `standard_fee_payable` int(11) DEFAULT NULL,
  `course_small_image` text DEFAULT NULL,
  `course_image` text DEFAULT NULL,
  `course_structure_breakdown` text DEFAULT NULL,
  `course_duration_category` varchar(255) DEFAULT NULL,
  `foreign_student_fee_payable_usd` varchar(255) DEFAULT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `accredited_by_acronym` text DEFAULT NULL,
  `accreditation_organization_url` text DEFAULT NULL,
  `maximum_scholarship_available` int(11) DEFAULT NULL,
  `standard_fee_billing_type` varchar(255) DEFAULT NULL,
  `standard_first_year_fee_payable_usd` int(11) DEFAULT NULL,
  `foreign_student_first_year_fee_payable_usd` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `extracted_key_phrases_reporting`
--

CREATE TABLE `extracted_key_phrases_reporting` (
  `id` int(11) NOT NULL,
  `searched_phrase` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `unique_extracted_key_phrases`
--

CREATE TABLE `unique_extracted_key_phrases` (
  `id` bigint(20) NOT NULL,
  `selected_key_phrases_id` bigint(20) NOT NULL,
  `phrases_slug` text NOT NULL,
  `phrases` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `extracted_key_phrases`
--
ALTER TABLE `extracted_key_phrases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extracted_key_phrases_index_list`
--
ALTER TABLE `extracted_key_phrases_index_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extracted_key_phrases_reporting`
--
ALTER TABLE `extracted_key_phrases_reporting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unique_extracted_key_phrases`
--
ALTER TABLE `unique_extracted_key_phrases`
<<<<<<< HEAD
<<<<<<< HEAD
  ADD PRIMARY KEY (`id`);
=======
  ADD PRIMARY KEY (`id`),
>>>>>>> 7bc9789... KeyPhrases Extractor Merge
=======
  ADD PRIMARY KEY (`id`);
>>>>>>> a487bfd... Included the Autocomplete End-point
  ADD UNIQUE KEY `phrases_slug` (`phrases_slug`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `extracted_key_phrases`
--
ALTER TABLE `extracted_key_phrases`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extracted_key_phrases_index_list`
--
ALTER TABLE `extracted_key_phrases_index_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extracted_key_phrases_reporting`
--
ALTER TABLE `extracted_key_phrases_reporting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unique_extracted_key_phrases`
--
ALTER TABLE `unique_extracted_key_phrases`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
