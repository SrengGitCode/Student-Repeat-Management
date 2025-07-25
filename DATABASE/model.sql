-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2025 at 04:28 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `model`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `degree_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `degree_id`, `course_name`) VALUES
(1, 1, 'Computer Graphic Design'),
(2, 1, 'Cyber Security'),
(3, 1, 'Software Engineering'),
(4, 2, 'Computer Graphic Design'),
(5, 2, 'Cyber Security'),
(6, 2, 'Software Engineering'),
(9, 5, 'Computer Graphic Design');

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `id` int(11) NOT NULL,
  `degree_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`id`, `degree_name`) VALUES
(1, 'Associate Degree'),
(2, 'Bachelor Degree'),
(5, 'Doctorate Degree'),
(3, 'Master Degree');

-- --------------------------------------------------------

--
-- Table structure for table `repeat_records`
--

CREATE TABLE `repeat_records` (
  `id` int(11) NOT NULL,
  `student_id_fk` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `failed_year` varchar(10) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `repeat_records`
--

INSERT INTO `repeat_records` (`id`, `student_id_fk`, `subject_name`, `failed_year`, `academic_year`, `semester`, `passed`, `notes`) VALUES
(1, 1, 'C++ Programming I', '1', '2024-2025', '1', 0, 'Missed the final exam.'),
(2, 2, 'C++ Programming II', '1', '2024-2025', '2', 0, 'Struggled with pointers.'),
(3, 2, 'Computer Networking', '1', '2024-2025', '2', 0, ''),
(4, 2, 'Network Administrator I', '2', '2025-2026', '1', 0, 'Needs more practical lab time.'),
(5, 2, 'Web-Based Development I', '2', '2025-2026', '1', 0, 'Difficulty with server-side concepts.'),
(6, 3, 'Computer Graphic Design I', '1', '2024-2025', '1', 1, 'Initially failed, but passed the re-exam.'),
(7, 3, 'Web-Based Development I', '1', '2024-2025', '2', 0, 'Needs to review HTML and CSS basics.'),
(8, 3, 'Basic Character Animation', '1', '2024-2025', '2', 0, ''),
(9, 5, 'Fundamental Network Security', '1', '2025-2026', '2', 1, 'Passed on the second attempt.'),
(10, 7, 'Database Management System I', '2', '2026-2027', '1', 0, ''),
(11, 7, 'Data Structure and Algorithms', '2', '2026-2027', '1', 0, 'Complex topic, requires extra tutoring.'),
(12, 8, 'Ethical Hacking', '3', '2025-2026', '2', 0, 'Low attendance.'),
(13, 9, 'UX / UI Design', '3', '2024-2025', '2', 0, ''),
(14, 3, 'Digital Studio Photography & Drone', '2', '2025-2026', '1', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `bdate` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `student_id`, `name`, `last_name`, `course_id`, `gender`, `bdate`, `address`, `contact`) VALUES
(1, 'SE240101', 'David', 'Chea', 6, 'Male', '2004-05-15', '123 Main St, Phnom Penh', '012345678'),
(2, 'CS240202', 'Linda', 'Sok', 5, 'Female', '2003-08-22', '456 Oak Ave, Kandal', '098765432'),
(3, 'CGD240303', 'Bunna', 'Kim', 4, 'Male', '2004-01-30', '789 Pine Ln, Siem Reap', '011223344'),
(4, 'SE240404', 'Sreyneang', 'Vong', 6, 'Female', '2003-11-10', '101 Maple Dr, Battambang', '099887766'),
(5, 'CS240505', 'Michael', 'Thy', 2, 'Male', '2005-03-25', '212 Birch Rd, Kampong Cham', '088776655'),
(7, 'SE240707', 'Rithy', 'Prak', 3, 'Male', '2005-09-05', '444 Rose Ave, Phnom Penh', '066554433'),
(8, 'CS240808', 'Sophea', 'Chen', 5, 'Female', '2003-02-12', '555 Lotus Blvd, Takeo', '015987654'),
(9, 'CGD240303', 'Isabelle Nunez', 'Randall', 4, 'Female', '2006-06-17', 'toul kork', '0132413241');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `course_id`, `year`, `semester`, `subject_name`) VALUES
(6, 1, 1, 2, 'Advanced MS Application'),
(7, 1, 1, 2, 'Computer Graphic Design II'),
(8, 1, 1, 2, 'Basic Character Animation'),
(9, 1, 1, 2, 'Video Proshow Production'),
(10, 1, 1, 2, 'Web-Based Development I'),
(11, 1, 2, 1, 'Social Media & Innovation'),
(12, 1, 2, 1, 'Basic Drawing Animation'),
(13, 1, 2, 1, 'Multimedia Studio'),
(14, 1, 2, 1, 'Professional Computer Graphic III'),
(15, 1, 2, 1, 'Digital Studio Photography & Drone'),
(16, 1, 2, 2, 'Drawing for Media'),
(17, 1, 2, 2, 'Web Technologies Design'),
(18, 1, 2, 2, 'Multimedia Studio II'),
(19, 1, 2, 2, 'Multimedia Sound Effect'),
(20, 1, 2, 2, 'Computer Networking'),
(21, 2, 1, 1, 'English for Computer'),
(22, 2, 1, 1, 'Computer Repairs and Maintenance'),
(23, 2, 1, 1, 'Principles of Economics'),
(24, 2, 1, 1, 'Computer Graphic Design I'),
(25, 2, 1, 1, 'C++ Programming I'),
(26, 2, 1, 2, 'Advanced MS Application'),
(27, 2, 1, 2, 'Fundamental Network Security'),
(28, 2, 1, 2, 'C++ Programming II'),
(29, 2, 1, 2, 'Mathematics for Computing'),
(30, 2, 1, 2, 'Computer Networking'),
(31, 2, 2, 1, 'Network Programming I'),
(32, 2, 2, 1, 'Web-Based Development I'),
(33, 2, 2, 1, 'Cyber Security Law'),
(34, 2, 2, 1, 'Operating System'),
(35, 2, 2, 1, 'Network Administrator I'),
(36, 2, 2, 2, 'Network Programming II'),
(37, 2, 2, 2, 'Web Development System II'),
(38, 2, 2, 2, 'Network Administrator II'),
(39, 2, 2, 2, 'Cisco Networking Course I'),
(40, 2, 2, 2, 'Computer Forensics Tools and Security'),
(41, 3, 1, 1, 'English for Computer'),
(42, 3, 1, 1, 'Computer Repairs and Maintenance'),
(43, 3, 1, 1, 'Principles of Economics'),
(44, 3, 1, 1, 'Computer Graphic Design I'),
(45, 3, 1, 1, 'C++ Programming I'),
(46, 3, 1, 2, 'Advanced MS Application'),
(47, 3, 1, 2, 'Web Page Dynamic'),
(48, 3, 1, 2, 'C++ Programming II'),
(49, 3, 1, 2, 'Mathematics for Computing'),
(50, 3, 2, 1, 'Database Management System I'),
(51, 3, 2, 1, 'Web Development II'),
(52, 3, 2, 1, 'C# Programming I'),
(53, 3, 2, 1, 'Data Structure and Algorithms'),
(54, 3, 2, 1, 'Framework Web Page Development'),
(55, 3, 2, 2, 'Client/Server Application'),
(56, 3, 2, 2, 'Professional Web Development System'),
(57, 3, 2, 2, 'C# Programming Language II'),
(58, 3, 2, 2, 'Back-End Development with API'),
(59, 3, 2, 2, 'Network Administrator'),
(65, 4, 1, 2, 'Advanced MS Application'),
(66, 4, 1, 2, 'Computer Graphic Design II'),
(67, 4, 1, 2, 'Basic Character Animation'),
(68, 4, 1, 2, 'Video Proshow Production'),
(69, 4, 1, 2, 'Web-Based Development I'),
(70, 4, 2, 1, 'Social Media & Innovation'),
(71, 4, 2, 1, 'Basic Drawing Animation'),
(72, 4, 2, 1, 'Multimedia Studio'),
(73, 4, 2, 1, 'Professional Computer Graphic III'),
(74, 4, 2, 1, 'Digital Studio Photography & Drone'),
(75, 4, 2, 2, 'Drawing for Media'),
(76, 4, 2, 2, 'Web Technologies Design'),
(77, 4, 2, 2, 'Multimedia Studio II'),
(78, 4, 2, 2, 'Multimedia Sound Effect'),
(79, 4, 2, 2, 'Computer Networking'),
(80, 4, 3, 1, 'Computer Animation 2D Design I'),
(81, 4, 3, 1, 'E-Commerce'),
(82, 4, 3, 1, 'Computer Animation 3D Design I'),
(83, 4, 3, 1, 'Motion Graphics Design'),
(84, 4, 3, 1, 'Digital Marketing'),
(85, 4, 3, 2, 'Computer Animation 2D Design II'),
(86, 4, 3, 2, 'Conceptual Design'),
(87, 4, 3, 2, 'Autodesk 3D Max'),
(88, 4, 3, 2, 'Computer Animation 3D Design II'),
(89, 4, 3, 2, 'UX / UI Design'),
(90, 4, 4, 1, 'Interaction Design'),
(91, 4, 4, 1, 'Autodesk Maya'),
(92, 4, 4, 1, 'Sketchup 3D Design'),
(93, 4, 4, 1, 'Research Methodology'),
(94, 4, 4, 1, 'Storyborad Design'),
(95, 4, 4, 2, 'Cinema 4D'),
(96, 4, 4, 2, 'Creative Advertising'),
(97, 4, 4, 2, 'Multimedia Project Management'),
(98, 4, 4, 2, 'Bachelor\'s Thesis'),
(99, 5, 1, 1, 'English for Computer'),
(100, 5, 1, 1, 'Computer Repairs and Maintenance'),
(101, 5, 1, 1, 'Principles of Economics'),
(102, 5, 1, 1, 'Computer Graphic Design I'),
(103, 5, 1, 1, 'C++ Programming I'),
(104, 5, 1, 2, 'Advanced MS Application'),
(105, 5, 1, 2, 'Fundamental Network Security'),
(106, 5, 1, 2, 'C++ Programming II'),
(107, 5, 1, 2, 'Mathematics for Computing'),
(108, 5, 1, 2, 'Computer Networking'),
(109, 5, 2, 1, 'Network Programming I'),
(110, 5, 2, 1, 'Web-Based Development I'),
(111, 5, 2, 1, 'Cyber Security Law'),
(112, 5, 2, 1, 'Operating System'),
(113, 5, 2, 1, 'Network Administrator I'),
(114, 5, 2, 2, 'Network Programming II'),
(115, 5, 2, 2, 'Web Development System II'),
(116, 5, 2, 2, 'Network Administrator II'),
(117, 5, 2, 2, 'Cisco Networking Course I'),
(118, 5, 2, 2, 'Computer Forensics Tools and Security'),
(119, 5, 3, 1, 'Client / Server Application'),
(120, 5, 3, 1, 'Cisco Networking Course II'),
(121, 5, 3, 1, 'Cloud Computing'),
(122, 5, 3, 1, 'Linux System Administrator I'),
(123, 5, 3, 1, 'Network Security'),
(124, 5, 3, 2, 'Linux System Administrator II'),
(125, 5, 3, 2, 'Cisco Networking Course III'),
(126, 5, 3, 2, 'Ethical Hacking'),
(127, 5, 3, 2, 'Firewall Security'),
(128, 5, 3, 2, 'Cyber Security Operations'),
(129, 5, 4, 1, 'Ethical in Cyber Security'),
(130, 5, 4, 1, 'Telecom Technology Concepts'),
(131, 5, 4, 1, 'Artificial Intelligence Concepts'),
(132, 5, 4, 1, 'Research Methodology'),
(133, 5, 4, 1, 'Network and Internet Forensics'),
(134, 5, 4, 2, 'Information Security Management'),
(135, 5, 4, 2, 'Management Information System'),
(136, 5, 4, 2, 'Network Project Management'),
(137, 5, 4, 2, 'Bachelor\'s Thesis'),
(138, 6, 1, 1, 'English for Computer'),
(139, 6, 1, 1, 'Computer Repairs and Maintenance'),
(140, 6, 1, 1, 'Principles of Economics'),
(141, 6, 1, 1, 'Computer Graphic Design I'),
(142, 6, 1, 1, 'C++ Programming I'),
(143, 6, 1, 2, 'Advanced MS Application'),
(144, 6, 1, 2, 'Web Page Dynamic'),
(145, 6, 1, 2, 'C++ Programming II'),
(146, 6, 1, 2, 'Mathematics for Computing'),
(147, 6, 2, 1, 'Database Management System I'),
(148, 6, 2, 1, 'Web Development II'),
(149, 6, 2, 1, 'C# Programming I'),
(150, 6, 2, 1, 'Data Structure and Algorithms'),
(151, 6, 2, 1, 'Framework Web Page Development'),
(152, 6, 2, 2, 'Client/Server Application'),
(153, 6, 2, 2, 'Professional Web Development System'),
(154, 6, 2, 2, 'C# Programming Language II'),
(155, 6, 2, 2, 'Back-End Development with API'),
(156, 6, 2, 2, 'Network Administrator'),
(157, 6, 3, 1, 'Oracle Database I'),
(158, 6, 3, 1, 'E-Commerce'),
(159, 6, 3, 1, 'Java Programming I'),
(160, 6, 3, 1, 'Object Oriented Programming I with C#'),
(161, 6, 3, 1, 'System Analysis & Design'),
(162, 6, 3, 2, 'Advanced Oracle Database II'),
(163, 6, 3, 2, 'Mobile App Development I'),
(164, 6, 3, 2, 'Java Programming II'),
(165, 6, 3, 2, 'Object Oriented Programming II with C#'),
(166, 6, 3, 2, 'UX/UI Design'),
(167, 6, 4, 1, 'Python Programming I'),
(168, 6, 4, 1, 'Mobile App Development II'),
(169, 6, 4, 1, 'Artificial Intelligence Concepts'),
(170, 6, 4, 1, 'Research Methodology'),
(171, 6, 4, 1, 'Cloud Technologies'),
(172, 6, 4, 2, 'Advanced Python Programming II'),
(173, 6, 4, 2, 'Software Project Management'),
(174, 6, 4, 2, 'Management Information System'),
(175, 6, 4, 2, 'Bachelor\'s Thesis'),
(176, 1, 3, 1, 'Computer Animation 2D Design I'),
(177, 1, 3, 1, 'E-Commerce'),
(178, 1, 3, 1, 'Computer Animation 3D Design I'),
(179, 1, 3, 1, 'Motion Graphics Design'),
(180, 1, 3, 1, 'Digital Marketing'),
(181, 1, 3, 2, 'Computer Animation 2D Design II'),
(182, 1, 3, 2, 'Conceptual Design'),
(183, 1, 3, 2, 'Autodesk 3D Max'),
(184, 1, 3, 2, 'Computer Animation 3D Design II'),
(185, 1, 3, 2, 'UX / UI Design'),
(186, 1, 4, 1, 'Interaction Design'),
(187, 1, 4, 1, 'Autodesk Maya'),
(188, 1, 4, 1, 'Sketchup 3D Design'),
(189, 1, 4, 1, 'Research Methodology'),
(190, 1, 4, 1, 'Storyborad Design'),
(191, 1, 4, 2, 'Cinema 4D'),
(192, 1, 4, 2, 'Creative Advertising'),
(193, 1, 4, 2, 'Multimedia Project Management'),
(194, 1, 4, 2, 'Bachelor\'s Thesis'),
(195, 2, 3, 1, 'Client / Server Application'),
(196, 2, 3, 1, 'Cisco Networking Course II'),
(197, 2, 3, 1, 'Cloud Computing'),
(198, 2, 3, 1, 'Linux System Administrator I'),
(199, 2, 3, 1, 'Network Security'),
(200, 2, 3, 2, 'Linux System Administrator II'),
(201, 2, 3, 2, 'Cisco Networking Course III'),
(202, 2, 3, 2, 'Ethical Hacking'),
(203, 2, 3, 2, 'Firewall Security'),
(204, 2, 3, 2, 'Cyber Security Operations'),
(205, 2, 4, 1, 'Ethical in Cyber Security'),
(206, 2, 4, 1, 'Telecom Technology Concepts'),
(207, 2, 4, 1, 'Artificial Intelligence Concepts'),
(208, 2, 4, 1, 'Research Methodology'),
(209, 2, 4, 1, 'Network and Internet Forensics'),
(210, 2, 4, 2, 'Information Security Management'),
(211, 2, 4, 2, 'Management Information System'),
(212, 2, 4, 2, 'Network Project Management'),
(213, 2, 4, 2, 'Bachelor\'s Thesis'),
(214, 3, 3, 1, 'Oracle Database I'),
(215, 3, 3, 1, 'E-Commerce'),
(216, 3, 3, 1, 'Java Programming I'),
(217, 3, 3, 1, 'Object Oriented Programming I with C#'),
(218, 3, 3, 1, 'System Analysis & Design'),
(219, 3, 3, 2, 'Advanced Oracle Database II'),
(220, 3, 3, 2, 'Mobile App Development I'),
(221, 3, 3, 2, 'Java Programming II'),
(222, 3, 3, 2, 'Object Oriented Programming II with C#'),
(223, 3, 3, 2, 'UX/UI Design'),
(224, 3, 4, 1, 'Python Programming I'),
(225, 3, 4, 1, 'Mobile App Development II'),
(226, 3, 4, 1, 'Artificial Intelligence Concepts'),
(227, 3, 4, 1, 'Research Methodology'),
(228, 3, 4, 1, 'Cloud Technologies'),
(229, 3, 4, 2, 'Advanced Python Programming II'),
(230, 3, 4, 2, 'Software Project Management'),
(231, 3, 4, 2, 'Management Information System'),
(232, 3, 4, 2, 'Bachelor\'s Thesis'),
(238, 1, 1, 1, 'English for Computer'),
(239, 1, 1, 1, 'Computer Repairs and Maintenance'),
(240, 1, 1, 1, 'Principles of Economics'),
(241, 1, 1, 1, 'Computer Graphic Design I'),
(242, 1, 1, 1, 'C++ Programming I'),
(247, 4, 1, 1, 'English for Computer'),
(248, 4, 1, 1, 'Computer Repairs and Maintenance'),
(249, 4, 1, 1, 'Principles of Economics'),
(250, 4, 1, 1, 'Computer Graphic Design I'),
(251, 4, 1, 1, 'C++ Programming I');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_students`
--

CREATE TABLE `transfer_students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `bdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `previous_university` varchar(255) NOT NULL,
  `previous_major` varchar(255) NOT NULL,
  `transfer_date` date NOT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transfer_students`
--

INSERT INTO `transfer_students` (`id`, `student_id`, `name`, `last_name`, `gender`, `bdate`, `address`, `contact`, `photo`, `previous_university`, `previous_major`, `transfer_date`, `document`, `status`, `notes`) VALUES
(1, 'TR-2501', 'Sokha', 'Keo', 'Male', '2003-04-10', '15A, St 271, Phnom Penh', '012 987 654', NULL, 'Royal University of Phnom Penh', 'Computer Science', '2025-07-15', 'sokha_transcript.pdf', 'Approved', 'Transferred with 60 credits.'),
(2, 'TR-2502', 'Channary', 'Mao', 'Female', '2004-09-20', '22B, St 105, Battambang', '097 112 2334', NULL, 'Build Bright University', 'Information Technology', '2025-07-20', 'channary_transcript.pdf', 'Pending', 'Awaiting official transcript verification.'),
(3, 'TR-2503', 'Vibol', 'Srun', 'Male', '2002-11-05', '88C, St 3, Siem Reap', '088 445 5667', NULL, 'Norton University', 'Graphic Design', '2025-06-25', 'vibol_records.pdf', 'Approved', ''),
(4, 'TR-2504', 'Sophea', 'Pich', 'Female', '2004-02-18', '45D, St 110, Kampot', '016 778 8990', NULL, 'Pannasastra University of Cambodia', 'Business Information Systems', '2025-07-01', NULL, 'Rejected', 'Incomplete application. Missing official transcript.'),
(5, 'TR-2505', 'Dara', 'Chan', 'Male', '2003-12-30', '77E, St 63, Phnom Penh', '092 123 4567', NULL, 'American University of Phnom Penh', 'Software Development', '2025-07-18', 'dara_transcript.pdf', 'Approved', 'Excellent academic record.'),
(6, 'CGD240305', 'Keo ', 'Sreng', 'Male', '2025-07-23', '789 Pine Ln, Phnom penh', '078123422', '1753243000_gigachad_1024x1024.jpg', 'BELTEI', 'Software engineering', '2025-07-23', 'test.pdf', 'Approved', 'transfer approved ');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', 'admin', 'Admin User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `degree_id` (`degree_id`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `degree_name` (`degree_name`);

--
-- Indexes for table `repeat_records`
--
ALTER TABLE `repeat_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id_fk` (`student_id_fk`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `transfer_students`
--
ALTER TABLE `transfer_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `degrees`
--
ALTER TABLE `degrees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `repeat_records`
--
ALTER TABLE `repeat_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `transfer_students`
--
ALTER TABLE `transfer_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_course_to_degree` FOREIGN KEY (`degree_id`) REFERENCES `degrees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repeat_records`
--
ALTER TABLE `repeat_records`
  ADD CONSTRAINT `fk_record_to_student` FOREIGN KEY (`student_id_fk`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_to_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_subject_to_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
