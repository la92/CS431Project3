-- Host: localhost

-- USE gic5;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `University`
--

-- --------------------------------------------------------

--
-- Table structure for table `Access`
--

CREATE TABLE IF NOT EXISTS `Access` (
  `UserId` int(2) NOT NULL,
  `Access` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Access`
--

INSERT INTO `Access` (`UserId`, `Access`) VALUES
(1, 'student'),
(2, 'student'),
(3, 'student'),
(4, 'student'),
(5, 'student'),
(6, 'faculty'),
(7, 'faculty'),
(8, 'staff'),
(9, 'staff'),
(10, 'executive'),
(11, 'executive');

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS `Courses` (
  `ClassId` int(11) NOT NULL AUTO_INCREMENT,
  `Course` varchar(10) NOT NULL,
  `CourseTitle` varchar(50) NOT NULL,
  `FacultyId` int(11) NOT NULL,
  PRIMARY KEY (`ClassId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`ClassId`, `Course`, `CourseTitle`, `FacultyId`) VALUES
(1, 'CS 100', 'Roadmap to Computing', 6),
(2, 'CS 100', 'Introduction to CS', 6),
(3, 'PHYS 111', 'Physics I', 7),
(4, 'PHYS 121', 'Physics II', 7),
(5, 'MATH 111', 'Calculus I', 7),
(6, 'MATH 112', 'Calculus II', 7);

-- --------------------------------------------------------

--
-- Table structure for table `Registrations`
--

CREATE TABLE IF NOT EXISTS `Registrations` (
  `UserId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Registrations`
--

INSERT INTO `Registrations` (`UserId`, `ClassId`) VALUES
(1, 7),
(1, 8),
(1, 12),
(7, 7),
(7, 8),
(7, 10),
(7, 14),
(7, 12),
(6, 12),
(6, 8),
(6, 14);

-- --------------------------------------------------------

--
-- Table structure for table `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `UserId` int(11) NOT NULL,
  `Hash` varchar(64) NOT NULL,
  `Expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Sessions`
--

INSERT INTO `Sessions` (`UserId`, `Hash`, `Expiration`) VALUES
(6, 'b41bce0896636e2d973684b2cb3d5ea33a36de884071ea8f4fa1f11e0e271eda', 1383438695);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) NOT NULL,
  `Email` varchar(254) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `PasswordHash` varchar(60) NOT NULL,
  `DateOfBirth` date NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserId`, `Username`, `Email`, `FirstName`, `LastName`, `PasswordHash`, `DateOfBirth`) VALUES
(1, 'sc30', 'sc30@njit.edu', 'Stephen', 'Curry', '$2a$07$WUjyhhmnFURyEJlHdF1Hduo4NcQfvcJEtk01ZffiYDnjz2wqhz0gS', '1988-03-14'),
(2, 'kh52', 'kh52@njit.edu', 'Kevin', 'Hart', '$2a$07$WUjyhhmnFURyEJlHdF1Hduo4NcQfvcJEtk01ZffiYDnjz2wqhz0gS', '1979-07-06'),
(3, 'bg1', 'bg1@njit.edu', 'Bill', 'Gates', '$2a$07$WUjyhhmnFURyEJlHdF1Hduo4NcQfvcJEtk01ZffiYDnjz2wqhz0gS', '1955-10-28'),
(4, 'sj11', 'sj11@njit.edu', 'Scarelett', 'Johansson', '$2a$07$WUjyhhmnFURyEJlHdF1Hduo4NcQfvcJEtk01ZffiYDnjz2wqhz0gS', '1984-11-22'),
(5, 'lm10', 'lm10@njit.edu', 'Lionel', 'Messi', '$2a$07$WUjyhhmnFURyEJlHdF1Hduo4NcQfvcJEtk01ZffiYDnjz2wqhz0gS', '1987-06-24'),
(6, 'in65', 'in65@njit.edu', 'Issac', 'Newton', '$2a$07$/PdmQ9.jkuyRnUkcEGNExeHlsOFpCJTD4Ke.QwSGSEmC4GU5y2b0S', '1965-01-04'),
(7, 'gb55', 'gb55@njit.edu', 'George', 'Blank', '$2a$07$/PdmQ9.jkuyRnUkcEGNExeHlsOFpCJTD4Ke.QwSGSEmC4GU5y2b0S', '1955-01-20'),
(8, 'ek78', 'ek78@njit.edu', 'Ema', 'Kallaghan', '$2a$07$S/7QSOuTxwR2BnytanRO8..SfVFDoFyMPo7ZI1aHv2273zhAAhV4e', '1978-12-23'),
(9, 'am58', 'am58@njit.edu', 'Alexander', 'Mahone', '$2a$07$S/7QSOuTxwR2BnytanRO8..SfVFDoFyMPo7ZI1aHv2273zhAAhV4e', '1958-02-12'),
(10, 'jg79', 'jg79@njit.edu', 'Joel', 'Groove', '$2a$07$ntqJVPHid8lrB19AORVzLeP7imfjlLDcbhsuPoZS7VA0i5/GeLyK.', '1979-10-03'),
(11, 'kk56', 'kk56@njit.edu', 'Krista', 'Klear', '$2a$07$ntqJVPHid8lrB19AORVzLeP7imfjlLDcbhsuPoZS7VA0i5/GeLyK.', '1956-07-12');




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
