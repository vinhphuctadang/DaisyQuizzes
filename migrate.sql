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
CREATE DATABASE IF NOT EXISTS `daisybeaver` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `daisybeaver`;

-- Dumping structure for table daisybeaver.daisy_admin
CREATE TABLE IF NOT EXISTS `daisy_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin: ~1 rows (approximately)
DELETE FROM `daisy_admin`;
/*!40000 ALTER TABLE `daisy_admin` DISABLE KEYS */;
INSERT INTO `daisy_admin` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'phuc', 'f899139df5e1059396431415e770c6dd', '2019-10-28 09:48:46');
/*!40000 ALTER TABLE `daisy_admin` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_collection
CREATE TABLE IF NOT EXISTS `daisy_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `name` varchar(50) DEFAULT 'Bộ câu hỏi không tên' COMMENT 'Tên của bộ câu hỏi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Bộ câu hỏi thuộc về ai ? (Người tạo)',
  `description` text COMMENT 'Mô tả về bộ câu hỏi',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

-- Dumping data for table daisybeaver.daisy_collection: ~1 rows (approximately)
DELETE FROM `daisy_collection`;
/*!40000 ALTER TABLE `daisy_collection` DISABLE KEYS */;
INSERT INTO `daisy_collection` (`id`, `created_at`, `name`, `admin_id`, `description`) VALUES
	(3, '2019-10-28 11:13:13', 'Câu hỏi ai là thánh troll', 1, NULL);
/*!40000 ALTER TABLE `daisy_collection` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_player_round
CREATE TABLE IF NOT EXISTS `daisy_player_round` (
  `name` varchar(32) NOT NULL,
  `created_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(32) DEFAULT NULL,
  `round` varchar(6) NOT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`,`round`),
  KEY `round` (`round`),
  CONSTRAINT `FK_daisy_player_round_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round` (`round`) ON DELETE CASCADE
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
  `collection_id` int(11) DEFAULT NULL COMMENT 'Thuộc về bộ câu hỏi nào',
  `choice_d` text COMMENT 'Phương án 3',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `explaination` text COMMENT 'Giải thích cho câu trả lời',
  KEY `collection_id` (`collection_id`),
  KEY `id` (`id`,`collection_id`),
  CONSTRAINT `FK_daisy_question_daisy_collection` FOREIGN KEY (`collection_id`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question: ~3 rows (approximately)
DELETE FROM `daisy_question`;
/*!40000 ALTER TABLE `daisy_question` DISABLE KEYS */;
INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `collection_id`, `choice_d`, `created_at`, `explaination`) VALUES
	(1, 'Tôi tên gì?', 'Anh là ai ', 'Tôi không biết', 'BIết không tôi', 3, 'Tôi biết không 1', '2019-10-28 11:15:59', NULL),
	(2, 'Chồng của góa phụ có thể cưới vợ được không?', 'Không', 'Có', 'Hên xui', 3, 'Không biết', '2019-10-28 11:34:37', NULL),
	(3, 'Con cua tám cẳng hai càng, bò qua bò lại hỏi bò mấy chân', '4 chân', '2 chân', '6 chân', 3, '8 chân', '2019-10-28 11:35:38', NULL);
/*!40000 ALTER TABLE `daisy_question` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_round
CREATE TABLE IF NOT EXISTS `daisy_round` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `description` varchar(255) DEFAULT 'Không có mô tả' COMMENT 'Mô tả của mỗi vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) NOT NULL COMMENT 'Mã vòng chơi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Admin đã tạo ra vòng chơi này',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  `access_token` varchar(32) DEFAULT 'has923$$uva_2931_2-2192' COMMENT 'Mã dành cho nhà phát triển',
  `next_timestamp` timestamp NULL DEFAULT NULL COMMENT 'Timestamp để cập nhật câu hỏi tiếp theo',
  PRIMARY KEY (`round`),
  UNIQUE KEY `round` (`round`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `collection` (`collection`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_round_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_daisy_round_collection_daisy_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_round: ~0 rows (approximately)
DELETE FROM `daisy_round`;
/*!40000 ALTER TABLE `daisy_round` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_round` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_shuffle_content
CREATE TABLE IF NOT EXISTS `daisy_shuffle_content` (
  `round` varchar(6) DEFAULT NULL COMMENT 'Vòng chơi đang diễn ra',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi',
  `question_id` int(11) DEFAULT NULL COMMENT 'ID của câu hỏi',
  KEY `round` (`round`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `FK_daisy_shuffle_content_daisy_question` FOREIGN KEY (`question_id`) REFERENCES `daisy_question` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_daisy_shuffle_content_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round` (`round`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_shuffle_content: ~0 rows (approximately)
DELETE FROM `daisy_shuffle_content`;
/*!40000 ALTER TABLE `daisy_shuffle_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `daisy_shuffle_content` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
