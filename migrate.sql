-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.38-MariaDB - mariadb.org binary distribution
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật vào csdl',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisyscore: ~7 rows (approximately)
DELETE FROM `daisyscore`;
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

-- Dumping structure for table daisybeaver.daisy_collection
CREATE TABLE IF NOT EXISTS `daisy_collection` (
  `id` int(11) DEFAULT NULL COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái của gói câu hỏi: 0-không sẵn sàng, 1 đang mở',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

-- Dumping data for table daisybeaver.daisy_collection: ~1 rows (approximately)
DELETE FROM `daisy_collection`;
/*!40000 ALTER TABLE `daisy_collection` DISABLE KEYS */;
INSERT INTO `daisy_collection` (`id`, `created_at`, `status`) VALUES
	(1, '2019-09-25 21:57:19', 0);
/*!40000 ALTER TABLE `daisy_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_player_round
CREATE TABLE IF NOT EXISTS `daisy_player_round` (
  `name` int(11) DEFAULT NULL,
  `created_time` timestamp NULL DEFAULT NULL,
  `round` varchar(6) DEFAULT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_player_round: ~0 rows (approximately)
DELETE FROM `daisy_player_round`;
/*!40000 ALTER TABLE `daisy_player_round` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_player_round` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_question
CREATE TABLE IF NOT EXISTS `daisy_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã câu hỏi',
  `body` text,
  `choice_a` text COMMENT 'Đáp án',
  `choice_b` text COMMENT 'Phương án 1',
  `choice_c` text COMMENT 'Phương án 2',
  `choice_d` text COMMENT 'Phương án 3',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian tạo câu hỏi',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question: ~4 rows (approximately)
DELETE FROM `daisy_question`;
/*!40000 ALTER TABLE `daisy_question` DISABLE KEYS */;
INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `choice_d`, `created_at`) VALUES
	(1, 'Tôi tên gì?', 'Tạ Đặng Vĩnh Phúc', 'Tạ Vĩnh Phúc', 'Đặng Vĩnh Phúc', 'Tạ Phúc', '2019-09-25 22:07:54'),
	(2, 'Mặt trời mọc ở hướng nào?', 'Đông', 'Tây', 'Nam', 'Bắc', '2019-09-26 10:38:24'),
	(4, 'Thủ đô Việt Nam là', 'Hà Nội', 'Cần Thơ', 'Đà Nẵng', 'TP Hồ Chí Minh', '2019-09-26 12:48:43'),
	(5, '1 + 2 x 0 + 3 = ?', '4', '5', '3', '1', '2019-09-26 12:49:44');
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

-- Dumping data for table daisybeaver.daisy_question_collection: ~4 rows (approximately)
DELETE FROM `daisy_question_collection`;
/*!40000 ALTER TABLE `daisy_question_collection` DISABLE KEYS */;
INSERT INTO `daisy_question_collection` (`id_question`, `id_collection`) VALUES
	(1, 1),
	(2, 1),
	(4, 1),
	(5, 1);
/*!40000 ALTER TABLE `daisy_question_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_round_collection
CREATE TABLE IF NOT EXISTS `daisy_round_collection` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) DEFAULT NULL COMMENT 'Mã vòng chơi',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  UNIQUE KEY `round` (`round`),
  KEY `collection` (`collection`),
  CONSTRAINT `FK_daisy_round_collection_daisy_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_collection` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_round_collection: ~1 rows (approximately)
DELETE FROM `daisy_round_collection`;
/*!40000 ALTER TABLE `daisy_round_collection` DISABLE KEYS */;
INSERT INTO `daisy_round_collection` (`collection`, `status`, `round`, `question_no`) VALUES
	(1, 1, 'love', 1);
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
DELETE FROM `daisy_shuffle_content`;
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
