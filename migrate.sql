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

-- Dumping structure for table daisybeaver.daisy_admin_login
CREATE TABLE IF NOT EXISTS `daisy_admin_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin_login: ~3 rows (approximately)
/*!40000 ALTER TABLE `daisy_admin_login` DISABLE KEYS */;
INSERT INTO `daisy_admin_login` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'tadang', 'f899139df5e1059396431415e770c6dd', '2019-09-28 18:10:30'),
	(2, 'vinhphuctadang', 'f899139df5e1059396431415e770c6dd', '2019-09-28 21:08:53'),
	(3, 'vinhphuctadang1', 'f899139df5e1059396431415e770c6dd', '2019-09-29 06:31:32');
/*!40000 ALTER TABLE `daisy_admin_login` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_collection
CREATE TABLE IF NOT EXISTS `daisy_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái của gói câu hỏi: 0-không sẵn sàng, 1 đang mở để chia sẻ',
  `name` varchar(50) DEFAULT 'Bộ câu hỏi không tên' COMMENT 'Tên của bộ câu hỏi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Bộ câu hỏi thuộc về ai ? (Người tạo)',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin_login` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

-- Dumping data for table daisybeaver.daisy_collection: ~1 rows (approximately)
/*!40000 ALTER TABLE `daisy_collection` DISABLE KEYS */;
INSERT INTO `daisy_collection` (`id`, `created_at`, `status`, `name`, `admin_id`) VALUES
	(2, '2019-09-29 23:02:18', 0, 'Cho các bạn test', 2);
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
  CONSTRAINT `FK_daisy_player_round_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round_collection` (`round`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_player_round: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_player_round` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_player_round` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_question
CREATE TABLE IF NOT EXISTS `daisy_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã câu hỏi',
  `body` text DEFAULT NULL,
  `choice_a` text DEFAULT NULL COMMENT 'Đáp án',
  `choice_b` text DEFAULT NULL COMMENT 'Phương án 1',
  `choice_c` text DEFAULT NULL COMMENT 'Phương án 2',
  `collection_id` int(11) DEFAULT NULL COMMENT 'Thuộc về bộ câu hỏi nào',
  `choice_d` text DEFAULT NULL COMMENT 'Phương án 3',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo câu hỏi',
  KEY `id` (`id`),
  KEY `collection_id` (`collection_id`),
  CONSTRAINT `FK_daisy_question_daisy_collection` FOREIGN KEY (`collection_id`) REFERENCES `daisy_collection` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question: ~7 rows (approximately)
/*!40000 ALTER TABLE `daisy_question` DISABLE KEYS */;
INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `collection_id`, `choice_d`, `created_at`) VALUES
	(1, 'Tôi tên gì?', 'Tạ Đặng Vĩnh Phúc', 'Tạ Vĩnh Phúc', 'Đặng Vĩnh Phúc', NULL, 'Tạ Phúc', '2019-09-25 22:07:54'),
	(2, 'Mặt trời mọc ở hướng nào?', 'Đông', 'Tây', 'Nam', NULL, 'Bắc', '2019-09-26 10:38:24'),
	(4, 'Thủ đô Việt Nam là', 'Hà Nội', 'Cần Thơ', 'Đà Nẵng', NULL, 'TP Hồ Chí Minh', '2019-09-26 12:48:43'),
	(5, '1 + 2 x 0 + 3 = ?', '4', '5', '3', NULL, '1', '2019-09-26 12:49:44'),
	(6, 'Một ngày bao nhiêu giờ?', '24', '48', '8', NULL, '16', '2019-09-28 14:34:12'),
	(7, 'I love you có nghĩa là gì', 'Tôi yêu bạn', 'Bạn yêu tôi', 'Chẳng ai yêu ai', NULL, 'Kệ nó', '2019-09-28 14:34:53'),
	(8, 'EQ là gì', 'Emotional Quality', 'E-commerce Quality', 'Encode Quality', NULL, 'Emergency Quirk', '2019-09-28 14:36:09');
/*!40000 ALTER TABLE `daisy_question` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_round_collection
CREATE TABLE IF NOT EXISTS `daisy_round_collection` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) DEFAULT NULL COMMENT 'Mã vòng chơi',
  `admin_id` int(11) DEFAULT NULL,
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  `access_token` varchar(32) DEFAULT 'has923$$uva_2931_2-2192',
  UNIQUE KEY `round` (`round`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `collection` (`collection`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_round_collection_daisy_admin_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_admin_collection` (`collection_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_daisy_round_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin_login` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_round_collection: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_round_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_round_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_shuffle_content
CREATE TABLE IF NOT EXISTS `daisy_shuffle_content` (
  `round` varchar(6) DEFAULT NULL COMMENT 'Vòng chơi đang diễn ra',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi',
  `question_id` int(11) DEFAULT NULL COMMENT 'ID của câu hỏi',
  KEY `round` (`round`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `FK_daisy_shuffle_content_daisy_question` FOREIGN KEY (`question_id`) REFERENCES `daisy_question` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_daisy_shuffle_content_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round_collection` (`round`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_shuffle_content: ~0 rows (approximately)
/*!40000 ALTER TABLE `daisy_shuffle_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_shuffle_content` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
