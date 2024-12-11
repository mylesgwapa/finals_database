-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 02:30 PM
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
-- Database: `myles`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `author` varchar(255) NOT NULL,
  `year_publish` year(4) NOT NULL,
  `num_available` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `category`, `author`, `year_publish`, `num_available`, `image_url`) VALUES
(2, 'Brief History of Time ', 'Science', 'Stephen Hawking', '1988', 3, 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQKIUSKGtFOyTklY6yiZ1BMDxFWGDrpZ1DnyEAeTEHQ5RFXO7wX'),
(3, 'Immortal Life', 'Fiction', 'Rebeca Iskloot', '2011', 1, 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQffNLinUkqZZIkU65uSwI8byrirjIly0WCejAjwm8d5D5TlJtK'),
(4, 'The Mismeasure Man', 'Romance', 'Stephen Gould', '1981', 2, 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcTKQpGroCL9oNHTxLa6i_mOsmZOXEdHAtpCTxC7KHvvLxEgrNkO'),
(5, 'A Short History of Evrything', 'Science', 'Bill Bryson', '2000', 5, 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSDt_DCSLzJsdZgqqOu5BUrGNJL-8QClmTAr3EesT16yMYo1GAN'),
(6, 'Silent Spring', 'Romance', 'Rachel Carson', '1962', 5, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7vFcAXvgscBosHvFY-lKVSA9XIXXarDVcm3vyQox3YJSCONfI'),
(7, 'Everything is Predictable', 'Science', 'Tom Chivers', '1980', 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTE4_UJJZ9CBHu5PUJ7dFcnI5LTAUHBWnxSzgJHNWcAPPtMsbua');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reserved_books`
--

CREATE TABLE `reserved_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `reserve_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reserved_books`
--
ALTER TABLE `reserved_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reserved_books`
--
ALTER TABLE `reserved_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `reserved_books`
--
ALTER TABLE `reserved_books`
  ADD CONSTRAINT `reserved_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
