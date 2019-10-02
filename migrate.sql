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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_admin_login: ~1 rows (approximately)
/*!40000 ALTER TABLE `daisy_admin_login` DISABLE KEYS */;
INSERT INTO `daisy_admin_login` (`id`, `username`, `password`, `created_at`) VALUES
	(8, 'phuc', 'f899139df5e1059396431415e770c6dd', '2019-09-30 21:57:42'),
	(9, 'maymay2610', 'f899139df5e1059396431415e770c6dd', '2019-10-01 16:27:07');
/*!40000 ALTER TABLE `daisy_admin_login` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_collection
CREATE TABLE IF NOT EXISTS `daisy_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái của gói câu hỏi: 0-không sẵn sàng, 1 đang mở để chia sẻ',
  `name` varchar(50) DEFAULT 'Bộ câu hỏi không tên' COMMENT 'Tên của bộ câu hỏi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Bộ câu hỏi thuộc về ai ? (Người tạo)',
  `description` text DEFAULT NULL COMMENT 'Mô tả về bộ câu hỏi',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

-- Dumping data for table daisybeaver.daisy_collection: ~4 rows (approximately)
/*!40000 ALTER TABLE `daisy_collection` DISABLE KEYS */;
INSERT INTO `daisy_collection` (`id`, `created_at`, `status`, `name`, `admin_id`, `description`) VALUES
	(3, '2019-09-30 21:59:42', 0, 'Bộ để các bạn test', 8, NULL),
	(6, '2019-09-30 22:20:07', 0, 'Bộ ai là thánh troll', 8, NULL),
	(7, '2019-09-30 22:20:19', 0, 'Các câu hỏi về hệ thống DaisyQuizzes', 8, NULL),
	(8, '2019-09-30 22:20:29', 0, 'Câu hỏi về cá nhân tôi', 8, NULL);
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
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo câu hỏi',
  KEY `id` (`id`),
  KEY `collection_id` (`collection_id`),
  CONSTRAINT `FK_daisy_question_daisy_collection` FOREIGN KEY (`collection_id`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_question: ~16 rows (approximately)
/*!40000 ALTER TABLE `daisy_question` DISABLE KEYS */;
INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `collection_id`, `choice_d`, `created_at`) VALUES
	(11, 'Tôi tên gì', 'Tạ Đặng Vĩnh Phúc', 'Phúc Vĩnh ', 'Tạ Vĩnh Phúc', 3, 'Phúc Tạ Vĩnh ', NULL),
	(12, 'Câu hỏi rất ít người trả lời đúng: 1+0x2+3 = ?', '4', '3', '2', 3, '1', NULL),
	(13, 'Tôi yêu thích gì', 'Mây', 'Lúa', 'Chim', 3, 'Cá', NULL),
	(14, 'Thủ đô nước Việt Nam là ?', 'Hà Nội', 'Đà Nẵng', 'Kon Tum', 3, 'Huế', NULL),
	(15, 'Mô hình thực thể quan hệ là:', 'E-R', 'R-R', 'R-F', 3, 'R-A', NULL),
	(16, 'Nếu như ngày đó em không lừa dối ...', 'thì tình đôi ta đã không lạc lối', 'thì ngày mai em đâu còn buồn', 'thì có lẽ ta đã xa nhau', 3, 'thì chắc có lẽ, đớn đau như ngày xưa', '2019-09-30 22:13:00'),
	(17, 'Tình/TP nào sau đây ở miền Trung Việt Nam', 'Đà Nẵng', 'Cao Bằng', 'Cà Mau', 3, 'Quảng Ninh', '2019-09-30 22:13:59'),
	(18, '3+2>4?', 'Đúng', 'Sai ', 'Vừa đúng vừa sai', 3, 'Cũng sai nhưng chữ dài hơn', '2019-09-30 22:14:38'),
	(19, 'Một cây lê có 3 nhánh, mỗi nhánh lớn có 2 nhánh con, mỗi nhánh con có 5 nhánh nhỏ và mỗi nhánh nhỏ có 3 hoa. Biết mỗi hoa có thể đơm được 1 trái thì cây có bao nhiêu trái táo?', '0', '125', '96', 3, '85', '2019-09-30 22:15:37'),
	(20, 'Bác Hồ ra đi tìm đường cứu nước vào ngày tháng năm nào?', '5-6-1911', '6-5-1911', '30-4-1911', 3, '4-6-1911', '2019-09-30 22:17:04'),
	(23, 'Cái gì đánh cha, đánh mẹ, đánh luôn cả chị em họ hàng', 'Bàn chải ', 'Kem trộn', 'Lưới cá', 6, 'Cái đánh trứng', '2019-10-02 10:34:04'),
	(24, 'Bên trái có một căn nhà xanh, bên phải có một căn nhà đỏ. Hỏi nhà Trắng ở đâu?', 'Ở Mỹ', 'Ở kế bên căn nhà xanh', 'Ở giữa hai ngôi nhà', 6, 'Ở bên hiên nhà em', '2019-10-02 10:35:01'),
	(25, 'Bệnh gì bác sĩ bó tay?', 'Gãy tay', 'Nhức đầu', 'Răng khôn mọc ngu', 6, 'Tiểu ra nước', '2019-10-02 10:35:46'),
	(26, 'Con cua tám cẳng hai càng, bò qua bò lại hỏi bò mấy chân?', '4 chân', '6 chân', '8 chân (4x2=8)', 6, '10 chân', '2019-10-02 10:36:38'),
	(27, 'Bệnh nào dưới đây không di truyền', 'Vô sinh', 'Máu khó đông', 'Thần kinh', 6, 'Bạch tạng', '2019-10-02 10:38:32'),
	(28, 'Quần gì dưới đây rộng nhất?', 'Quần đảo', 'Quần đùi', 'Quần jean', 6, 'Qq', '2019-10-02 10:39:45');
/*!40000 ALTER TABLE `daisy_question` ENABLE KEYS */;

-- Dumping structure for table daisybeaver.daisy_round_collection
CREATE TABLE IF NOT EXISTS `daisy_round_collection` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `description` text DEFAULT 'Không có mô tả' COMMENT 'Mô tả của mỗi vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) DEFAULT NULL COMMENT 'Mã vòng chơi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Admin đã tạo ra vòng chơi này',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  `access_token` varchar(32) DEFAULT 'has923$$uva_2931_2-2192' COMMENT 'Mã dành cho nhà phát triển',
  UNIQUE KEY `round` (`round`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `collection` (`collection`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_daisy_round_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin_login` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_daisy_round_collection_daisy_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- Dumping data for table daisybeaver.daisy_round_collection: ~1 rows (approximately)
/*!40000 ALTER TABLE `daisy_round_collection` DISABLE KEYS */;
INSERT INTO `daisy_round_collection` (`collection`, `description`, `status`, `round`, `admin_id`, `question_no`, `access_token`) VALUES
	(3, NULL, 0, 'love', 8, 0, 'bebda1483b8f2324d8ecd9d35b4c0e47');
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

-- Dumping data for table daisybeaver.daisy_shuffle_content: ~10 rows (approximately)
/*!40000 ALTER TABLE `daisy_shuffle_content` DISABLE KEYS */;
INSERT INTO `daisy_shuffle_content` (`round`, `question_no`, `question_id`) VALUES
	('love', 1, 15),
	('love', 2, 12),
	('love', 3, 17),
	('love', 4, 13),
	('love', 5, 18),
	('love', 6, 11),
	('love', 7, 19),
	('love', 8, 16),
	('love', 9, 20),
	('love', 10, 14);
/*!40000 ALTER TABLE `daisy_shuffle_content` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
