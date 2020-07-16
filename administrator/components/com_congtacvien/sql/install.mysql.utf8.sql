-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.26 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table mekozzy.q90rc_congtacvien_luong_nguong
CREATE TABLE IF NOT EXISTS `q90rc_congtacvien_luong_nguong` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT '0',
  `nguong` decimal(18,0) DEFAULT NULL,
  `bonus` decimal(3,1) DEFAULT NULL COMMENT '% thưởng khi đạt ngưỡng',
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table mekozzy.q90rc_congtacvien_luong_nguong: 13 rows
DELETE FROM `q90rc_congtacvien_luong_nguong`;
/*!40000 ALTER TABLE `q90rc_congtacvien_luong_nguong` DISABLE KEYS */;
INSERT INTO `q90rc_congtacvien_luong_nguong` (`id`, `profile_id`, `nguong`, `bonus`, `ordering`) VALUES
	(1, 1, 30000000, 1.0, NULL),
	(2, 1, 50000000, 2.0, NULL),
	(3, 3, 5000000, 15.0, NULL),
	(4, 3, 10000000, 16.0, NULL),
	(5, 3, 15000000, 17.0, NULL),
	(6, 3, 20000000, 18.0, NULL),
	(7, 3, 25000000, 20.0, NULL),
	(8, 1, 100000000, 3.0, NULL),
	(9, 1, 150000000, 3.5, NULL),
	(10, 1, 200000000, 4.0, NULL),
	(11, 1, 250000000, 5.0, NULL),
	(12, 1, 300000000, 6.0, NULL),
	(13, 1, 500000000, 7.0, NULL);
/*!40000 ALTER TABLE `q90rc_congtacvien_luong_nguong` ENABLE KEYS */;

-- Dumping structure for table mekozzy.q90rc_congtacvien_luong_profile
CREATE TABLE IF NOT EXISTS `q90rc_congtacvien_luong_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doituong_code` varchar(20) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '0',
  `description` text,
  `created` date DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table mekozzy.q90rc_congtacvien_luong_profile: 2 rows
DELETE FROM `q90rc_congtacvien_luong_profile`;
/*!40000 ALTER TABLE `q90rc_congtacvien_luong_profile` DISABLE KEYS */;
INSERT INTO `q90rc_congtacvien_luong_profile` (`id`, `doituong_code`, `title`, `description`, `created`, `published`, `ordering`) VALUES
	(1, 'CTVCT', 'CTV huong luong - Default', '', NULL, 1, 0),
	(3, 'CTVTT', 'CTV không luong - Default', '', NULL, 1, 0);
/*!40000 ALTER TABLE `q90rc_congtacvien_luong_profile` ENABLE KEYS */;

-- Dumping structure for table mekozzy.q90rc_congtacvien_vendor
CREATE TABLE IF NOT EXISTS `q90rc_congtacvien_vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `luong_coban` decimal(18,0) NOT NULL DEFAULT '0',
  `thuong_profile_id` int(11) NOT NULL DEFAULT '0',
  `metadata` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table mekozzy.q90rc_congtacvien_vendor: 4 rows
DELETE FROM `q90rc_congtacvien_vendor`;
/*!40000 ALTER TABLE `q90rc_congtacvien_vendor` DISABLE KEYS */;
INSERT INTO `q90rc_congtacvien_vendor` (`id`, `vendor_id`, `luong_coban`, `thuong_profile_id`, `metadata`) VALUES
	(1, 3, 2000000, 3, NULL),
	(2, 2, 1500000, 1, NULL);
/*!40000 ALTER TABLE `q90rc_congtacvien_vendor` ENABLE KEYS */;

-- Dumping structure for table mekozzy.q90rc_congtacvien_vendor_group
CREATE TABLE IF NOT EXISTS `q90rc_congtacvien_vendor_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` int(11) NOT NULL,
  `doituong_code` varchar(20) NOT NULL DEFAULT '',
  `created` date NOT NULL,
  `published_on` date NOT NULL,
  `expired_on` date NOT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table mekozzy.q90rc_congtacvien_vendor_group: 2 rows
DELETE FROM `q90rc_congtacvien_vendor_group`;
/*!40000 ALTER TABLE `q90rc_congtacvien_vendor_group` DISABLE KEYS */;
INSERT INTO `q90rc_congtacvien_vendor_group` (`id`, `virtuemart_vendor_id`, `doituong_code`, `created`, `published_on`, `expired_on`, `state`) VALUES
	(1, 2, 'CTVCT', '2020-05-22', '2020-05-22', '2020-05-22', 1),
	(2, 3, 'CTVTT', '2020-05-22', '2020-05-22', '2020-05-22', 1);
/*!40000 ALTER TABLE `q90rc_congtacvien_vendor_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
