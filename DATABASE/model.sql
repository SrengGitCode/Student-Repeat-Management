--
-- Database: `studentreport`
--

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `bdate` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- -------------------------------------------------------- 

--
-- Table structure for table `repeat_records`
--

CREATE TABLE IF NOT EXISTS `repeat_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id_fk` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `failed_year` varchar(10) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `passed` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id_fk`) REFERENCES `student`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', 'admin', 'Jeff');
