-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 12, 2025 at 03:45 PM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `staff_id`, `appointment_date`, `description`, `status`, `doctor_id`) VALUES
(4, 2, 1, '2025-05-12', 'tralalero tralala', 'Scheduled', 0),
(5, 1, 1, '2025-05-12', 'lalaal', 'Scheduled', 0),
(6, 4, 2, '2025-06-01', 'Routine cardiac checkup', 'Scheduled', 0),
(7, 5, 2, '2025-06-02', 'Post-operative follow-up', 'Completed', 0),
(8, 6, 1, '2025-06-03', 'Vaccination appointment', 'Cancelled', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text DEFAULT NULL,
  `record_date` date NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `diagnosis`, `treatment`, `record_date`, `doctor_id`) VALUES
(1, 1, '13234', '12342', '2025-05-12', 1),
(2, 4, 'Hypertension Stage 1', 'Prescribed Lisinopril 10mg daily', '2025-05-15', 2),
(3, 5, 'Appendicitis', 'Appendectomy performed', '2025-05-16', 2),
(4, 6, 'Influenza Type A', 'Tamiflu prescribed, rest recommended', '2025-05-17', 2);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `blood_type` varchar(3) DEFAULT NULL,
  `allergies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `age`, `gender`, `address`, `admission_date`, `user_id`, `date_of_birth`, `blood_type`, `allergies`) VALUES
(1, 'krisi', 12, 'Male', 'rr.1234', '2025-05-12', 1, NULL, NULL, NULL),
(2, 'krisi', 12, 'Male', '1234', '2025-05-12', 1, NULL, NULL, NULL),
(3, 'optimus', 76, 'Other', 'rr.Prokop Myzeqari', '2025-08-12', 1, NULL, NULL, NULL),
(4, 'Sarah Connor', 37, 'Female', '123 Main St', '2025-05-15', NULL, '1988-03-12', 'O+', 'Penicillin'),
(5, 'John Smith', 45, 'Male', '456 Oak Ave', '2025-05-16', NULL, '1980-11-25', 'A-', 'Shellfish'),
(6, 'Emma Davis', 28, 'Female', '789 Pine Rd', '2025-05-17', NULL, '1997-02-14', 'B+', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL CHECK (`position` in ('doctor','nurse','administrator','technician')),
  `department` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `position`, `department`, `license_number`, `user_id`) VALUES
(1, 'Oramemire', 'Nurse', '313', NULL, 2),
(2, 'doktori', 'Doctor', '313', NULL, 3),
(4, 'Dr. John Smith', 'doctor', 'Cardiology', NULL, 6),
(5, 'Amy Clark', 'nurse', 'Emergency', 'RN-987654', 7),
(6, 'Dr. James Wilson', 'doctor', 'Cardiology', 'MD-123456', 8),
(7, 'Mike Johnson', 'technician', 'Radiology', 'RT-456789', 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'staff',
  `user_type` enum('admin','staff') DEFAULT 'staff',
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `user_type`, `email`, `phone`, `active`, `created_at`) VALUES
(1, 'admin', '$2y$10$CClbWEqM7CXfEj5QQ2nG1.0t6uWC/3gxpRBvq6.V1WpexZVw2Az7a', 'staff', 'admin', NULL, NULL, 1, '2025-05-12 00:01:26'),
(2, 'orame', '$2y$10$vwlxCHILthifmdwrZRHpgu63p6xs7jWQLImPw.OdF85cQBiooUHc.', 'staff', 'staff', NULL, NULL, 1, '2025-05-12 00:01:26'),
(3, 'ome', '$2y$10$RH3INdqB1HG7Ejs4xdpgY.VIZ2SRpwHpKzlJHLYXVQFoq7gVGC9kG', 'doctor', 'staff', NULL, NULL, 1, '2025-05-12 00:42:45'),
(4, 'onon', '$2y$10$uI7X3ShDFwcC8SWXWuvGeO2DpPhWgEmKaAa7ZVrBTK5IYGVVQsYUu', 'doctor', 'staff', NULL, NULL, 1, '2025-05-12 00:52:45'),
(6, 'dr_smith', 'hashed_password', 'doctor', 'staff', NULL, NULL, 1, '2025-05-12 00:58:40'),
(7, 'nurse_amy', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nurse', 'staff', 'amy.clark@hospital.com', '555-0101', 1, '2025-05-12 15:38:48'),
(8, 'dr_wilson', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'doctor', 'staff', 'wilson.cardio@hospital.com', '555-0202', 1, '2025-05-12 15:38:48'),
(9, 'tech_mike', '$2y$10$S4TYb8k7N4VqLk7qyd5Zz.3EE9f7rRZ1fUYgWqD7nCvXU7X9zL5mG', 'technician', 'staff', 'mike.tech@hospital.com', '555-0303', 1, '2025-05-12 15:38:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `medical_records_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
