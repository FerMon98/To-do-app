-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 09:22 AM
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
-- Database: `gestor_tarea_fer`
--

-- --------------------------------------------------------

--
-- Table structure for table `tareas`
--

CREATE TABLE `tareas` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(75) NOT NULL,
  `task_description` varchar(500) NOT NULL,
  `task_status` varchar(20) NOT NULL,
  `due_date` date NOT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tareas`
--

INSERT INTO `tareas` (`task_id`, `task_name`, `task_description`, `task_status`, `due_date`, `is_important`) VALUES
(1, 'Test', 'Test1', 'pending', '2024-10-16', 1),
(2, 'Test', 'Test2', 'archived', '2024-10-15', 0),
(3, 'Test', 'Test3', 'pending', '2024-10-16', 1),
(4, 'Test', 'Test4', 'completed', '2024-10-18', 0),
(5, 'Test', 'Test5', 'completed', '2024-10-16', 1),
(16, 'Test', 'Test8', 'inProgress', '2024-10-16', 0),
(17, 'Test', 'Test 6', 'completed', '2024-10-18', 0),
(18, 'Test', 'Test 7', 'inProgress', '2024-10-17', 0),
(19, 'Test Long modify', 'Testing modify', 'completed', '2024-10-16', 0),
(20, 'TestImportanta', 'See if it shows important', 'inProgress', '2024-10-16', 1),
(21, 'TestImportant2', 'See if it shows important', 'archived', '2024-10-15', 1),
(22, 'Test10', 'See if it shows', 'pending', '2024-10-16', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tareas`
--
ALTER TABLE `tareas`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
