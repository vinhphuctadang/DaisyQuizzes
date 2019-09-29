-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.3.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for daisybeaver
CREATE DATABASE IF NOT EXISTS `daisybeaver` /*!40100 DEFAULT CHARACTER SET utf16 */;
USE `daisybeaver`;

-- Dumping structure for table daisybeaver.daisyscore
CREATE TABLE IF NOT EXISTS `daisyscore` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'STT của record',
  `fbid` varchar(255) DEFAULT NULL COMMENT 'ID của người dùng facebook',
  `score` int(10) unsigned DEFAULT NULL COMMENT 'Điểm của người dùng facebook',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời điểm cập nhật vào csdl',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisyscore: ~7 rows (approximately)
/*!40000 ALTER TABLE `daisyscore` DISABLE KEYS */;
INSERT INTO `daisyscore` (`id`, `fbid`, `score`, `created_at`) VALUES
	(1, 'Phuc', 1000, '2019-09-22 14:56:23'),
	(2, 'Phuc', 1000, '2019-09-22 14:57:32'),
	(3, 'Phuc', 1000, '2019-09-22 15:00:21'),
	(4, 'Phuc', 2000, '2019-09-22 15:18:33'),
	(5, 'Phuc', 30000, '2019-09-23 10:45:59'),
	(6, 'Phuc', 300000, '2019-09-23 12:08:13'),
	(7, 'Phuc', 300000, '2019-09-23 12:23:24');
/*!40000 ALTER TABLE `daisyscore` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_admin_collection
CREATE TABLE IF NOT EXISTS `daisy_admin_collection` (
  `admin_id` int(11) NOT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  KEY `collection_id` (`collection_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_admin_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin_login` (`id`),
  CONSTRAINT `FK_daisy_admin_collection_daisy_collection` FOREIGN KEY (`collection_id`) REFERENCES `daisy_collection` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin_collection: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_admin_collection` DISABLE KEYS */;
INSERT INTO `daisy_admin_collection` (`admin_id`, `collection_id`, `created_at`) VALUES
	(2, 1, '2019-09-29 06:55:33');
/*!40000 ALTER TABLE `daisy_admin_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_admin_login
CREATE TABLE IF NOT EXISTS `daisy_admin_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin_login: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_admin_login` DISABLE KEYS */;
INSERT INTO `daisy_admin_login` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'tadang', 'f899139df5e1059396431415e770c6dd', '2019-09-28 18:10:30'),
	(2, 'vinhphuctadang', 'f899139df5e1059396431415e770c6dd', '2019-09-28 21:08:53'),
	(3, 'vinhphuctadang1', 'f899139df5e1059396431415e770c6dd', '2019-09-29 06:31:32');
/*!40000 ALTER TABLE `daisy_admin_login` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_admin_round
CREATE TABLE IF NOT EXISTS `daisy_admin_round` (
  `admin_id` int(11) DEFAULT NULL,
  `round_id` varchar(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin_round: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_admin_round` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_admin_round` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_collection
CREATE TABLE IF NOT EXISTS `daisy_collection` (
  `id` int(11) DEFAULT NULL COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái của gói câu hỏi: 0-không sẵn sàng, 1 đang mở',
  `name` varchar(50) DEFAULT 'Bộ câu hỏi không tên',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

-- Dumping data for table daisybeaver.daisy_collection: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_collection` DISABLE KEYS */;
INSERT INTO `daisy_collection` (`id`, `created_at`, `status`, `name`) VALUES
	(1, '2019-09-25 21:57:19', 0, 'Bộ câu hỏi 1');
/*!40000 ALTER TABLE `daisy_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_player_round
CREATE TABLE IF NOT EXISTS `daisy_player_round` (
  `name` varchar(32) DEFAULT NULL,
  `created_time` timestamp NULL DEFAULT current_timestamp(),
  `token` varchar(32) DEFAULT NULL,
  `round` varchar(6) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `token` (`token`),
  KEY `round` (`round`),
  CONSTRAINT `FK_daisy_player_round_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round_collection` (`round`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_player_round: ~5 rows (approximately)
/*!40000 ALTER TABLE `daisy_player_round` DISABLE KEYS */;
INSERT INTO `daisy_player_round` (`name`, `created_time`, `token`, `round`, `score`) VALUES
	('phucphuc', '2019-09-28 15:25:21', '08c4c16c7fcb27981fa75245d30c3e56', 'love', 0),
	('phucphuc2', '2019-09-28 15:27:23', '527fbb98fbe082683dcb53d1d4f217fa', 'love', 0),
	('phuc2', '2019-09-28 15:37:36', 'a02a9e873130348c90ed96f2953ea97a', 'love', 0),
	('phuc23', '2019-09-28 15:40:56', '072459b3a56f706cffae4abad34241fa', 'love', 0),
	('Cua', '2019-09-28 17:14:04', 'd5ca5d2b89ed008f44a9917346fcacf2', 'love', 0);
/*!40000 ALTER TABLE `daisy_player_round` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_question
CREATE TABLE IF NOT EXISTS `daisy_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã câu hỏi',
  `body` text DEFAULT NULL,
  `choice_a` text DEFAULT NULL COMMENT 'Đáp án',
  `choice_b` text DEFAULT NULL COMMENT 'Phương án 1',
  `choice_c` text DEFAULT NULL COMMENT 'Phương án 2',
  `choice_d` text DEFAULT NULL COMMENT 'Phương án 3',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo câu hỏi',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question: ~7 rows (approximately)
/*!40000 ALTER TABLE `daisy_question` DISABLE KEYS */;
INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `choice_d`, `created_at`) VALUES
	(1, 'Tôi tên gì?', 'Tạ Đặng Vĩnh Phúc', 'Tạ Vĩnh Phúc', 'Đặng Vĩnh Phúc', 'Tạ Phúc', '2019-09-25 22:07:54'),
	(2, 'Mặt trời mọc ở hướng nào?', 'Đông', 'Tây', 'Nam', 'Bắc', '2019-09-26 10:38:24'),
	(4, 'Thủ đô Việt Nam là', 'Hà Nội', 'Cần Thơ', 'Đà Nẵng', 'TP Hồ Chí Minh', '2019-09-26 12:48:43'),
	(5, '1 + 2 x 0 + 3 = ?', '4', '5', '3', '1', '2019-09-26 12:49:44'),
	(6, 'Một ngày bao nhiêu giờ?', '24', '48', '8', '16', '2019-09-28 14:34:12'),
	(7, 'I love you có nghĩa là gì', 'Tôi yêu bạn', 'Bạn yêu tôi', 'Chẳng ai yêu ai', 'Kệ nó', '2019-09-28 14:34:53'),
	(8, 'EQ là gì', 'Emotional Quality', 'E-commerce Quality', 'Encode Quality', 'Emergency Quirk', '2019-09-28 14:36:09');
/*!40000 ALTER TABLE `daisy_question` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_question_collection
CREATE TABLE IF NOT EXISTS `daisy_question_collection` (
  `id_question` int(11) DEFAULT NULL,
  `id_collection` int(11) DEFAULT NULL,
  KEY `id_question` (`id_question`),
  KEY `id_collection` (`id_collection`),
  CONSTRAINT `FK_daisy_question_collection_daisy_collection` FOREIGN KEY (`id_collection`) REFERENCES `daisy_collection` (`id`),
  CONSTRAINT `FK_daisy_question_collection_daisy_question` FOREIGN KEY (`id_question`) REFERENCES `daisy_question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question_collection: ~6 rows (approximately)
/*!40000 ALTER TABLE `daisy_question_collection` DISABLE KEYS */;
INSERT INTO `daisy_question_collection` (`id_question`, `id_collection`) VALUES
	(1, 1),
	(2, 1),
	(4, 1),
	(5, 1),
	(8, 1),
	(7, 1);
/*!40000 ALTER TABLE `daisy_question_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_round_collection
CREATE TABLE IF NOT EXISTS `daisy_round_collection` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) DEFAULT NULL COMMENT 'Mã vòng chơi',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  `access_token` varchar(32) DEFAULT 'has923$$uva_2931_2-2192',
  UNIQUE KEY `round` (`round`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `collection` (`collection`),
  CONSTRAINT `FK_daisy_round_collection_daisy_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_collection` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_round_collection: ~1 rows (approximately)
/*!40000 ALTER TABLE `daisy_round_collection` DISABLE KEYS */;
INSERT INTO `daisy_round_collection` (`collection`, `status`, `round`, `question_no`, `access_token`) VALUES
	(1, 1, 'love', 1, 'abcdef');
/*!40000 ALTER TABLE `daisy_round_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_shuffle_content
CREATE TABLE IF NOT EXISTS `daisy_shuffle_content` (
  `round` varchar(6) DEFAULT NULL COMMENT 'Vòng chơi đang diễn ra',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi',
  `question_id` int(11) DEFAULT NULL COMMENT 'ID của câu hỏi',
  KEY `round` (`round`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `FK_daisy_shuffle_content_daisy_question` FOREIGN KEY (`question_id`) REFERENCES `daisy_question` (`id`),
  CONSTRAINT `FK_daisy_shuffle_content_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round_collection` (`round`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_shuffle_content: ~4 rows (approximately)
/*!40000 ALTER TABLE `daisy_shuffle_content` DISABLE KEYS */;
INSERT INTO `daisy_shuffle_content` (`round`, `question_no`, `question_id`) VALUES
	('love', 1, 2),
	('love', 2, 1),
	('love', 3, 4),
	('love', 4, 5);
/*!40000 ALTER TABLE `daisy_shuffle_content` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
