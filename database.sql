-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2017 at 04:50 PM
-- Server version: 5.6.28-76.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `steph961_baseball`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`steph961`@`localhost` PROCEDURE `update_losses`(IN `losing_team_id` INT(10) UNSIGNED, IN `date` DATE)
    NO SQL
update Season set losses = losses + 1 where team_id = losing_team_id and YEAR(year) = YEAR(date)$$

CREATE DEFINER=`steph961`@`localhost` PROCEDURE `update_wins`(IN `winning_team_id` INT(10) UNSIGNED, IN `date` DATE)
    NO SQL
update Season set wins = wins + 1 where team_id = winning_team_id and YEAR(year) = YEAR(date)$$

--
-- Functions
--
CREATE DEFINER=`steph961`@`localhost` FUNCTION `wins_by_team_season`(`team_id` INT, `season_id` INT) RETURNS int(11)
begin
	declare wins integer;
	select count(g.id) into @wins from Game g, Season s where
		s.team_id = team_id and
		s.id = season_id and
		YEAR(s.year) = YEAR(g.date) and
		(
            (home_team_id = team_id and home_score > away_score) or
            (away_team_id = team_id and away_score > home_score)
        );
	return @wins;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `DetailedGame`
--
CREATE TABLE IF NOT EXISTS `DetailedGame` (
`id` int(10) unsigned
,`home_team_id` int(10) unsigned
,`away_team_id` int(10) unsigned
,`home_team_name` varchar(100)
,`away_team_name` varchar(100)
,`home_score` int(3) unsigned
,`away_score` int(3) unsigned
,`date` date
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `DetailedPlayers`
--
CREATE TABLE IF NOT EXISTS `DetailedPlayers` (
`id` int(10) unsigned
,`name` varchar(100)
,`age` int(3) unsigned
,`height` int(3) unsigned
,`weight` int(3) unsigned
,`handedness` varchar(10)
,`position` varchar(50)
,`team` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `DetailedTeam`
--
CREATE TABLE IF NOT EXISTS `DetailedTeam` (
`id` int(10) unsigned
,`city` varchar(100)
,`name` varchar(100)
,`league` varchar(50)
,`division` varchar(50)
);
-- --------------------------------------------------------

--
-- Table structure for table `Division`
--

CREATE TABLE IF NOT EXISTS `Division` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `league` varchar(50) NOT NULL,
  `division` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `league` (`league`,`division`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `Division`
--

INSERT INTO `Division` (`id`, `league`, `division`) VALUES
(3, 'American', 'Central'),
(1, 'American', 'East'),
(2, 'American', 'West'),
(6, 'National', 'Central'),
(4, 'National', 'East'),
(5, 'National', 'West');

-- --------------------------------------------------------

--
-- Table structure for table `Game`
--

CREATE TABLE IF NOT EXISTS `Game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `away_team_id` int(10) unsigned NOT NULL,
  `home_team_id` int(10) unsigned NOT NULL,
  `away_score` int(3) unsigned NOT NULL,
  `home_score` int(3) unsigned NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `away_team_id` (`away_team_id`),
  KEY `home_team_id` (`home_team_id`),
  KEY `season_id` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `Game`
--

INSERT INTO `Game` (`id`, `away_team_id`, `home_team_id`, `away_score`, `home_score`, `date`) VALUES
(1, 14, 13, 4, 10, '2016-01-01'),
(2, 14, 13, 1, 2, '2017-04-01'),
(3, 13, 14, 2, 3, '2017-03-29'),
(4, 13, 14, 4, 3, '2017-04-05'),
(5, 13, 14, 2, 5, '2017-03-28'),
(7, 14, 16, 4, 2, '2017-03-26'),
(8, 16, 15, 33, 1, '2017-04-12'),
(11, 13, 17, 10, 1, '2016-08-16'),
(12, 13, 17, 1, 2, '2016-10-04'),
(13, 16, 14, 1, 11, '2016-11-09');

--
-- Triggers `Game`
--
DROP TRIGGER IF EXISTS `update_losses_trg`;
DELIMITER //
CREATE TRIGGER `update_losses_trg` AFTER INSERT ON `Game`
 FOR EACH ROW call update_losses(
	case
    when
    	NEW.home_score > NEW.away_score then NEW.away_team_id
    when
    	NEW.home_score < NEW.away_score then NEW.home_team_id
    end, NEW.date)
//
DELIMITER ;
DROP TRIGGER IF EXISTS `update_wins_trg`;
DELIMITER //
CREATE TRIGGER `update_wins_trg` BEFORE INSERT ON `Game`
 FOR EACH ROW call update_wins(
	case
    when
    	NEW.home_score > NEW.away_score then NEW.home_team_id
    when
    	NEW.home_score < NEW.away_score then NEW.away_team_id
    end,
	NEW.date)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PitchingStat`
--

CREATE TABLE IF NOT EXISTS `PitchingStat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `era` decimal(10,0) NOT NULL,
  `whip` decimal(10,0) NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `year` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `PitchingStat`
--

INSERT INTO `PitchingStat` (`id`, `era`, `whip`, `player_id`, `year`) VALUES
(2, '2', '2', 4, '2015-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `Player`
--

CREATE TABLE IF NOT EXISTS `Player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age` int(3) unsigned NOT NULL,
  `height` int(3) unsigned NOT NULL,
  `weight` int(3) unsigned NOT NULL,
  `handedness` varchar(10) NOT NULL,
  `position` varchar(50) NOT NULL,
  `team` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Player`
--

INSERT INTO `Player` (`id`, `name`, `age`, `height`, `weight`, `handedness`, `position`, `team`) VALUES
(1, 'Dustin Pedroia', 31, 68, 161, 'R', '2B', 13),
(2, 'Mitch Moreland', 32, 6, 178, 'L', '3B', 13),
(4, 'David Price', 32, 6, 180, 'R', 'RHP', 13);

-- --------------------------------------------------------

--
-- Table structure for table `Position`
--

CREATE TABLE IF NOT EXISTS `Position` (
  `value` varchar(30) NOT NULL,
  PRIMARY KEY (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Position`
--

INSERT INTO `Position` (`value`) VALUES
('1B'),
('1B Coach'),
('2B'),
('3B'),
('3B coach'),
('C'),
('CF'),
('DH'),
('LF'),
('LHP'),
('Manager'),
('RF'),
('RHP'),
('SS');

-- --------------------------------------------------------

--
-- Table structure for table `PositionStat`
--

CREATE TABLE IF NOT EXISTS `PositionStat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eff_rating` decimal(10,0) NOT NULL,
  `batting_avg` decimal(10,0) NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `year` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `PositionStat`
--

INSERT INTO `PositionStat` (`id`, `eff_rating`, `batting_avg`, `player_id`, `year`) VALUES
(24, '100', '100', 1, '2015-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `Season`
--

CREATE TABLE IF NOT EXISTS `Season` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` date NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `wins` int(3) NOT NULL,
  `losses` int(3) NOT NULL,
  `rank` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `year_2` (`year`,`team_id`),
  KEY `team_id` (`team_id`),
  KEY `year` (`year`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `Season`
--

INSERT INTO `Season` (`id`, `year`, `team_id`, `wins`, `losses`, `rank`) VALUES
(8, '2016-01-01', 13, 100, 63, 1),
(12, '2015-01-01', 13, 220000, 22, 1),
(13, '2016-01-01', 14, 112, 22, 1),
(14, '2014-01-01', 13, 20, 20, 3),
(15, '2015-01-01', 16, 0, 0, 3),
(16, '2016-01-01', 17, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE IF NOT EXISTS `Team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `division_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `division_id` (`division_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `Team`
--

INSERT INTO `Team` (`id`, `city`, `name`, `division_id`) VALUES
(13, 'Boston', 'Red Sox', 1),
(14, 'Los Angeles', 'Dodgers', 5),
(15, 'Washington', 'Nationals', 4),
(16, 'New York', 'Mets', 4),
(17, 'Detroit', 'Tigers', 3);

-- --------------------------------------------------------

--
-- Structure for view `DetailedGame`
--
DROP TABLE IF EXISTS `DetailedGame`;

CREATE ALGORITHM=UNDEFINED DEFINER=`steph961`@`localhost` SQL SECURITY DEFINER VIEW `DetailedGame` AS select `g`.`id` AS `id`,`g`.`home_team_id` AS `home_team_id`,`g`.`away_team_id` AS `away_team_id`,`ht`.`name` AS `home_team_name`,`at`.`name` AS `away_team_name`,`g`.`home_score` AS `home_score`,`g`.`away_score` AS `away_score`,`g`.`date` AS `date` from ((`Game` `g` join `Team` `ht`) join `Team` `at`) where ((`ht`.`id` = `g`.`home_team_id`) and (`at`.`id` = `g`.`away_team_id`));

-- --------------------------------------------------------

--
-- Structure for view `DetailedPlayers`
--
DROP TABLE IF EXISTS `DetailedPlayers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`steph961`@`localhost` SQL SECURITY DEFINER VIEW `DetailedPlayers` AS select `p`.`id` AS `id`,`p`.`name` AS `name`,`p`.`age` AS `age`,`p`.`height` AS `height`,`p`.`weight` AS `weight`,`p`.`handedness` AS `handedness`,`p`.`position` AS `position`,`t`.`name` AS `team` from (`Player` `p` join `Team` `t`) where (`p`.`team` = `t`.`id`);

-- --------------------------------------------------------

--
-- Structure for view `DetailedTeam`
--
DROP TABLE IF EXISTS `DetailedTeam`;

CREATE ALGORITHM=UNDEFINED DEFINER=`steph961`@`localhost` SQL SECURITY DEFINER VIEW `DetailedTeam` AS select `t`.`id` AS `id`,`t`.`city` AS `city`,`t`.`name` AS `name`,`d`.`league` AS `league`,`d`.`division` AS `division` from (`Team` `t` join `Division` `d`) where (`t`.`division_id` = `d`.`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Game`
--
ALTER TABLE `Game`
  ADD CONSTRAINT `away_team` FOREIGN KEY (`away_team_id`) REFERENCES `Team` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `home_team` FOREIGN KEY (`home_team_id`) REFERENCES `Team` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `PitchingStat`
--
ALTER TABLE `PitchingStat`
  ADD CONSTRAINT `pitching_player` FOREIGN KEY (`player_id`) REFERENCES `Player` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Player`
--
ALTER TABLE `Player`
  ADD CONSTRAINT `player_team` FOREIGN KEY (`team`) REFERENCES `Team` (`id`);

--
-- Constraints for table `PositionStat`
--
ALTER TABLE `PositionStat`
  ADD CONSTRAINT `position_player` FOREIGN KEY (`player_id`) REFERENCES `Player` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Season`
--
ALTER TABLE `Season`
  ADD CONSTRAINT `foreign_team` FOREIGN KEY (`team_id`) REFERENCES `Team` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Team`
--
ALTER TABLE `Team`
  ADD CONSTRAINT `team_division` FOREIGN KEY (`division_id`) REFERENCES `Division` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
