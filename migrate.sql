-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 09, 2019 lúc 11:25 AM
-- Phiên bản máy phục vụ: 8.0.11
-- Phiên bản PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `daisybeaver`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_admin`
--

CREATE TABLE `daisy_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(24) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Đang đổ dữ liệu cho bảng `daisy_admin`
--

INSERT INTO `daisy_admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'phuc', 'f899139df5e1059396431415e770c6dd', '2019-10-28 02:48:46'),
(2, 'theminh330', 'de1cce56fbaf75e5500c7ceaab15a853', '2019-11-08 07:49:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_collection`
--

CREATE TABLE `daisy_collection` (
  `id` int(11) NOT NULL COMMENT 'ID của bộ câu hỏi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo của bộ câu hỏi này',
  `name` varchar(255) CHARACTER SET utf16 COLLATE utf16_general_ci DEFAULT 'Bộ câu hỏi không tên' COMMENT 'Tên của bộ câu hỏi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Bộ câu hỏi thuộc về ai ? (Người tạo)',
  `description` text COMMENT 'Mô tả về bộ câu hỏi'
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COMMENT='Bao gồm các bộ các questions';

--
-- Đang đổ dữ liệu cho bảng `daisy_collection`
--

INSERT INTO `daisy_collection` (`id`, `created_at`, `name`, `admin_id`, `description`) VALUES
(3, '2019-10-28 04:13:13', 'Câu hỏi ai là thánh troll hihihi', 1, NULL),
(4, '2019-11-07 03:48:11', 'a', 1, NULL),
(6, '2019-11-07 03:48:19', 'a', 1, NULL),
(7, '2019-11-08 07:55:08', 'Câu hỏi mẹo 1', 2, NULL),
(8, '2019-11-08 08:25:59', 'Đố vui lịch sử 1', 2, NULL),
(9, '2019-11-08 09:28:59', 'Phong trào giải phóng dân tộc của các nước Châu Á, Châu Phi & Mĩ Latinh 1', 2, NULL),
(10, '2019-11-08 09:48:51', 'Phong trào giải phóng dân tộc của các nước Châu Á, Châu Phi & Mĩ Latinh 2', 2, NULL),
(12, '2019-11-08 10:07:45', 'Liệu bạn có phải học sinh giỏi môn văn?', 2, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_player_round`
--

CREATE TABLE `daisy_player_round` (
  `name` varchar(32) NOT NULL,
  `created_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(32) DEFAULT NULL,
  `round` varchar(6) NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_question`
--

CREATE TABLE `daisy_question` (
  `id` int(11) NOT NULL COMMENT 'Mã câu hỏi',
  `body` text,
  `choice_a` text COMMENT 'Đáp án',
  `choice_b` text COMMENT 'Phương án 1',
  `choice_c` text COMMENT 'Phương án 2',
  `collection_id` int(11) DEFAULT NULL COMMENT 'Thuộc về bộ câu hỏi nào',
  `choice_d` text COMMENT 'Phương án 3',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `explaination` text COMMENT 'Giải thích cho câu trả lời'
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Đang đổ dữ liệu cho bảng `daisy_question`
--

INSERT INTO `daisy_question` (`id`, `body`, `choice_a`, `choice_b`, `choice_c`, `collection_id`, `choice_d`, `created_at`, `explaination`) VALUES
(3, 'Con cua tám cẳng hai càng, bò qua bò lại hỏi bò mấy chân?', '4 chân ', '2 chân', '6 chân', 3, '8 chân', '2019-10-28 04:35:38', NULL),
(17, 'Ny cua tdvphuc?', 'a', 'b', 'c', 3, 'd', '2019-11-07 12:46:31', 'aaaaaaaaa'),
(19, 'hi', 'Aa', 'Bb', 'Cc', 4, 'Dd', '2019-11-07 15:24:30', 'Des'),
(21, 'aaa', 'aa', 'aaa', 'aaaa', 3, 'aassdad', '2019-11-08 00:35:58', ''),
(23, 'Anh trai của cháu gái gọi bạn bằng cô là gì của bạn?', 'Cháu', 'Cha', 'Chú', 7, 'Bác', '2019-11-08 07:59:44', ''),
(24, 'Chia 30 với 1/2 rồi cộng thêm 10, đáp án là bao nhiêu?', '70', '25', '15', 7, '60', '2019-11-08 08:01:07', '30 / (1/2) + 10 = 70 nha'),
(25, 'Một số tháng trong năm có ngày 31. Vậy bao nhiêu tháng có ngày 28?', '12 tháng', '1 tháng', '5 tháng', 7, 'không có tháng nào', '2019-11-08 08:02:25', 'Tháng nào cũng có ngày 28 cả ^^'),
(26, 'Nếu có 3 quả táo, bạn lấy đi hai quả, vậy bạn có bao nhiêu quả táo?', '2 quả', '1 quả', '3 quả', 7, 'không có quả nào', '2019-11-08 08:03:08', 'có 3 quả, BẠN lấy 2 quả thì BẠN sẽ có 2 quả nhé ^^~'),
(27, 'Cha của Ly có 5 người con tên thứ Nhất, thứ Hai, thứ Ba, thứ Năm, hỏi tên của người con thứ 5 là gì?', 'Ly', 'Út', 'Thứ 6', 7, 'Cả', '2019-11-08 08:13:05', 'Cha của Ly có 5 người con, đã biết tên 4 người kia thì tất nhiên người còn lại sẽ tên là Ly'),
(28, 'Con cua đỏ dài 10 cm chạy đua với con cua xanh dài 15cm. Con nào về đích trước?', 'Con cua xanh', 'Con cua đỏ', 'Cả hai cùng về đích', 7, 'Không có con nào về đích', '2019-11-08 08:14:42', 'Con cua đỏ là cua đã bị luộc chín nên không thể về đích'),
(29, 'Ai là phụ nữ đầu tiên ở nước ta xưng vương?', 'Trưng Trắc', 'Trưng Nhị', 'Triệu Thị Trinh', 8, 'Lý Chiêu Hoàng', '2019-11-08 09:04:12', ''),
(30, 'Bà Triệu còn có tên gọi khác là gì?', 'Cả 3 đáp án đều đúng', 'Triệu Quốc Trinh', 'Triệu Trinh Nương', 8, 'Triệu Ẩu', '2019-11-08 09:04:36', ''),
(31, '“Tôi chỉ muốn cưỡi cơn gió mạnh, đạp luồng sóng dữ, chém cá trường kình ở biển Đông, lấy lại giang sơn, dựng nền độc lập, cởi ách nô lệ…” là câu nói của?', 'Triệu Thị Trinh', 'Trưng Nhị', 'Trưng Trắc', 8, 'Bùi Thị Xuân', '2019-11-08 09:15:02', ''),
(32, 'Nữ tướng duy nhất trong lịch sử nước ta từng được phong đô đốc?', 'Bùi Thị Xuân', 'Phạm Thị Uyển', 'Lê Hoa', 8, 'Lê Chân', '2019-11-08 09:15:27', ''),
(33, 'Hoàng hậu nào từng cầm quân đánh giặc?', 'A. Phạm Thị Uyển\r\n', 'Lê Ngọc Hân', 'Lê Thị Phất Ngân', 8, 'Cả 3 đáp án đều đúng', '2019-11-08 09:16:46', ''),
(34, '“Múa giáo chém hổ dễ / Đối mặt Vua Bà khó”, Vua Bà là ai?', 'Triệu Thị Trinh', 'Trưng Trắc', 'Trưng Nhị', 8, 'Lý Chiêu Hoàng', '2019-11-08 09:21:26', ''),
(35, 'Ai dù không làm vua nhưng lại nắm quyền điều hành đất nước trong thời gian dài?', 'Nguyên Phi Ỷ Lan\r\n', 'Nguyên Từ Quốc Mẫu', 'Lê Ngọc Hân', 8, 'Linh Từ Quốc Mẫu', '2019-11-08 09:21:52', ''),
(36, 'Lý Chiêu Hoàng - nữ hoàng duy nhất trong lịch sử Việt Nam đã…?', 'Cả 3 đáp án đều đúng', 'Làm Công chúa của 2 triều đại khác nhau', 'Xuống tóc đi tu', 8, 'Nhường ngôi cho chồng', '2019-11-08 09:22:27', ''),
(37, 'Nhà yêu nước Nguyễn Thị Minh Khai quê ở đâu?', 'Nghệ An\r\n', 'Bến Tre', 'TP. Hồ Chí Minh', 8, 'Hà Tĩnh', '2019-11-08 09:22:49', ''),
(38, 'Bà Nguyễn Thị Định được phong tướng vào năm nào?', '1974', '1975', '1965', 8, '1954', '2019-11-08 09:23:10', ''),
(39, 'Thành tựu của Ấn Độ trong công cuộc xây dựng đất nước ở thập niên 70 là:', 'Đã giải quyết được vấn đề lương thực cho gần 1 tỷ người và đã bắt đầu xuất khẩu.', 'Trở thành nước xuất khẩu lúa gạo đứng thứ ba trên thế giới.', 'Đứng hàng thứ 10 trong những nước sản xuất công nghiệp lớn nhất thế giới.', 9, 'Trở thành một cường quốc về công nghiệp vũ trụ.', '2019-11-08 09:34:17', ''),
(40, 'Biến đổi lớn nhất của các nước châu Á sau Chiến tranh thế giới thứ hai là gì?', 'Các nước châu Á đã giành độc lập\r\n', 'Các nước châu Á đã trở thành trung tâm kinh tế - tài chính thế giới.', 'Các nước châu Á đã gia nhập ASEAN.', 9, 'Tất cả các vấn đề trên.', '2019-11-08 09:35:05', ''),
(41, 'Trước Chiến tranh thế giới thứ hai, các nước Đông Nam Á đều là thuộc địa của các nước Âu – Mĩ, ngoại trừ', 'Thái Lan', 'Nhật Bản.', 'Philippin', 9, 'Xingapo', '2019-11-08 09:39:57', ''),
(42, 'Điểm khác biệt có ý nghĩa quan trọng nhất của các nước Đông Nam Á trước và sau Chiến tranh thế giới thứ hai là', 'Từ các nước thuộc địa trở thành các quốc gia độc lập.', 'Từ chưa có địa vị quốc tế trở thành khu vực được quốc tế coi trọng.\r\n', 'Từ quan hệ biệt lập đã đẩy mạnh hợp tác trong khuôn khổ ASEAN.', 9, 'Từ những nước nghèo nàn trở thành những nước có nền kinh tế phát triển.', '2019-11-08 09:43:14', ''),
(43, 'Tổ chức nào lãnh đạo nhân dân Ấn Độ đấu tranh giành độc lập sau Chiến tranh thế giới thứ hai?', 'Đảng Quốc đại', 'Đảng Dân tộc', 'Đảng Dân chủ', 9, 'Đảng Quốc dân', '2019-11-08 09:43:56', ''),
(44, 'Ngày 26/1/1950 diễn ra sự kiện gì trong lịch sử Ấn Độ?', 'Ấn Độ tuyên bố độc lập và thành lập nước cộng hòa.\r\n', 'Thực dân Anh thực hiện “Phương án Maobattơn”', 'Hai nhà nước tự trị Ấn Độ và Pakixtan được thành lập.', 9, 'Cuộc khởi nghĩa của 2 vạn thủy binh ở Bom-bay', '2019-11-08 09:45:06', ''),
(45, 'Năm 1945, những quốc gia ở Đông Nam Á tuyên bố độc lập là', 'Việt Nam, Lào, Inđônêxia.\r\n\r\n\r\n', 'Việt Nam, Lào, Campuchia.', 'Thái Lan, Philippin, Xingapo.', 9, 'Việt Nam, Campuchia, Thái Lan.', '2019-11-08 09:45:55', ''),
(46, 'Trong số các nước sau, nước nào không thuộc khu vực Đông Bắc Á?', 'Ápganixtan, Nêpan.', 'Trung Quốc, Nhật Bản', 'Hàn Quốc, Đài Loan', 9, 'Cộng hòa dân chủ nhân dân Triều Tiên, Nhật Bản', '2019-11-08 09:46:41', ''),
(47, 'Trước Chiến tranh thế Giới thứ II, Inđônêxia là thuộc địa của nước nào?', 'Hà Lan.\r\n', 'Pháp', 'Mĩ', 9, 'Anh', '2019-11-08 09:47:10', ''),
(48, 'Nhân dân Lào tiến hành cuộc kháng chiến chống Mĩ, cứu nước (1955 - 1975) dưới sự lãnh đạo của:', 'Đảng Nhân dân cách mạng Lào.\r\n\r\nD. ', 'Đảng FUNCIPEC.', 'Đảng Cộng sản Lào.', 9, 'Đảng Cộng sản Đông Dương.', '2019-11-08 09:48:19', ''),
(49, 'Từ những năm 60 đến những năm 80 của thế kỉ XX, phong trào đấu tranh của nhân dân các nước Mĩ Latinh diễn ra dưới hình thức nào?', 'Cả ba hình thức trên.', 'Đấu tranh vũ trang.\r\n', 'Đấu tranh chính trị.', 10, 'Bãi công của công nhân.', '2019-11-08 10:00:32', ''),
(50, 'Những khó khăn của châu Phi sau khi giành được độc lập:', 'Tất cả các vấn đề trên', 'Sự xâm nhập của chủ nghĩa thực dân mới và sự vơ vét bóc lột về kinh tế của các cường quốc phương Tây.', 'Sự bùng nổ về dân số.', 10, 'Nợ nước ngoài, đói rét, bệnh tật, mù chữ.', '2019-11-08 10:01:28', ''),
(51, 'Khó khăn của châu Phi sau khi giành được độc lập là:', 'Kinh tế nghèo nàn, lạc hậu.', 'Tất cả các ý trên.', 'Tỉ lệ tăng dân số cao.', 10, 'Dịch bệnh lan tràn.', '2019-11-08 10:02:03', ''),
(52, 'Cuộc nội chiến năm 1994 được xem là bi thảm nhất của xung đột sắc tộc ở châu Phi diễn ra tại:', 'Ruanda', 'Xômali', 'Môdămbích', 10, 'Nam Phi', '2019-11-08 10:02:39', ''),
(53, 'Cuộc đấu tranh chống chế độ phân biệt chủng tộc ở châu Phi được xếp vào cuộc đấu tranh giải phóng dân tộc vì chế độ phân biệt chủng tộc', 'Là một hình thái của chủ nghĩa thực dân.', 'Là con đẻ của chủ nghĩa thực dân.', 'Do thực dân xây dựng và nuôi dưỡng.', 10, 'Có quan hệ mật thiết với chủ nghĩa thực dân.', '2019-11-08 10:05:13', ''),
(54, 'Sự kiện nào dưới đây gắn với tên tuổi của Nenxơn Manđêla:', 'Lãnh tụ của phong trào đấu tranh chống chế độ phân biệt chủng tộc ở Nam Phi.', 'Chiến sĩ nỗi tiếng chống ách thống trị của bọn thực dân.', 'Lãnh tụ của phong trào giải phóng dân tộc ở An–giê–ri.', 10, 'Lãnh tụ của phong trào giải phóng dân tộc ở Ăng–gô–la.', '2019-11-08 10:05:36', ''),
(55, 'Phong trào cách mạng châu Phi từ sau Chiến tranh thế giới thứ II được bắt đầu từ khu vực nào?', 'Khu vực Nam Phi.', 'Khu vực Tây Phi.', 'Khu vực Trung Phi.', 10, 'Khu vực Bắc Phi.', '2019-11-08 10:05:56', ''),
(56, 'Sự kiện được xem là mốc mở đầu của phong trào đấu tranh giành độc lập ở châu Phi sau Chiến tranh thế giới thứ II?', 'Cuộc binh biến của sĩ quan, binh lính yêu nước ở Ai Cập (1952).', 'Cách mạng Libi bùng nổ (1952).', 'Thắng lợi của phong trào cách mạng Angiêri (1962).', 10, 'Thắng lợi của phong trào cách mạng ở Tuynidi (1956).', '2019-11-08 10:06:18', ''),
(57, 'Vì sao năm 1960 được lịch sử ghi nhận là “Năm châu Phi”?', 'Đây là năm có 17 nước ở châu Phi giành được độc lập.', 'Đây là năm có 17 nước ở Bắc Phi giành được độc lập.', 'Đây là năm có 27 nước Tây và Nam Phi giành được độc lập.', 10, 'Đây là năm có 27 nước ở châu Phi giành được độc lập.', '2019-11-08 10:06:40', ''),
(58, 'Tình hình Mĩ Latinh trong thập niên 80 của thế kỉ XX?', 'Kinh tế nhiều nước lâm vào tình trạng suy thoái, lạm phát tăng cao.', 'Phong trào giải phóng dân tộc đã giành được những thắng lợi to lớn, 13 quốc gia giành được độc lập.', 'Phong trào giải phóng dân tộc ở Mĩ Latinh lâm vào tình trạng khó khăn.', 10, 'Tình hình kinh tế, chính trị ổn định, đời sống nhân dân lao động ở các nước được cải thiện đáng kể.', '2019-11-08 10:07:07', ''),
(59, 'Trong truyện Kiều của Nguyễn Du, bạn biết giữa Thúy Kiều và Thúy Vân, ai là chị, ai là em chứ?', 'Thúy Kiều là chị, Thúy Vân là em.', 'Thúy Kiều là em, Thúy Vân là chị.', 'Thúy Vân là chị, Thúy Kiều là em.', 12, 'Thúy Vân và Thúy Kiều sinh đôi.', '2019-11-08 10:08:07', ''),
(60, 'Con chó của lão Hạc tên gì nhỉ?', 'Vàng.', 'Robert Chen', 'Phèn.', 12, 'Cờ hó', '2019-11-08 10:08:26', ''),
(61, '\"Thuyền về có nhớ bến chăng - Bến thì một dạ ... ... đợi thuyền\" - Dấu \"... ...\" là gì?', 'Khăng khăng.', 'Băn khoăn.', 'Lăn tăn.', 12, 'Bâng khuâng.', '2019-11-08 10:09:01', ''),
(62, 'Lang Liêu là người con trai thứ mấy của vua Hùng?', '18', '16', '9', 12, '10', '2019-11-08 10:09:41', ''),
(63, 'Bài thơ nào sau đây không phải do Hồ Xuân Hương sáng tác?', 'Dạ tình bi ai khúc.', 'Bà Lang Khóc Chồng.', 'Bánh trôi nước.', 12, 'Cái quạt.', '2019-11-08 10:09:59', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_round`
--

CREATE TABLE `daisy_round` (
  `collection` int(11) DEFAULT NULL COMMENT 'Bộ sưu tập vòng chơi',
  `description` varchar(255) DEFAULT 'Không có mô tả' COMMENT 'Mô tả của mỗi vòng chơi',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Trạng thái vòng chơi 0: Chưa sẵn sàng, 1: Sẵn sàng nhận thành viên; 2 đang chơi',
  `round` varchar(6) NOT NULL COMMENT 'Mã vòng chơi',
  `admin_id` int(11) DEFAULT NULL COMMENT 'Admin đã tạo ra vòng chơi này',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi hiện tại',
  `access_token` varchar(32) DEFAULT 'has923$$uva_2931_2-2192' COMMENT 'Mã dành cho nhà phát triển',
  `next_timestamp` timestamp NULL DEFAULT NULL COMMENT 'Timestamp để cập nhật câu hỏi tiếp theo'
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Đang đổ dữ liệu cho bảng `daisy_round`
--

INSERT INTO `daisy_round` (`collection`, `description`, `status`, `round`, `admin_id`, `question_no`, `access_token`, `next_timestamp`) VALUES
(6, 'Không có mô tả', 0, '199', 1, 0, '1cfb25da24081801819e7c1ec636235f', NULL),
(3, 'Không có mô tả', 0, '1999', 1, 0, '22f9537002f86b9df26c097b8576fb58', NULL),
(3, 'Không có mô tả', 0, '2908', 1, 1, 'bc6a08b48531cdae57993c894566fbf6', NULL),
(8, 'Không có mô tả', 2, 'ls1', 2, 1, '89feddea370fe7e2d8a6d99eae23095c', NULL),
(9, 'Không có mô tả', 1, 'ls2', 2, 1, 'fe7d416361609efde17f276c4ed9178b', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `daisy_shuffle_content`
--

CREATE TABLE `daisy_shuffle_content` (
  `round` varchar(6) DEFAULT NULL COMMENT 'Vòng chơi đang diễn ra',
  `question_no` int(11) DEFAULT NULL COMMENT 'Số thứ tự câu hỏi',
  `question_id` int(11) DEFAULT NULL COMMENT 'ID của câu hỏi'
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Đang đổ dữ liệu cho bảng `daisy_shuffle_content`
--

INSERT INTO `daisy_shuffle_content` (`round`, `question_no`, `question_id`) VALUES
('1999', 1, 3),
('2908', 1, 17),
('2908', 2, 21),
('2908', 3, 3),
('ls1', 1, 36),
('ls1', 2, 35),
('ls1', 3, 32),
('ls1', 4, 29),
('ls1', 5, 30),
('ls1', 6, 34),
('ls1', 7, 31),
('ls1', 8, 37),
('ls1', 9, 33),
('ls1', 10, 38),
('ls2', 1, 42),
('ls2', 2, 46),
('ls2', 3, 41),
('ls2', 4, 48),
('ls2', 5, 43),
('ls2', 6, 45),
('ls2', 7, 39),
('ls2', 8, 44),
('ls2', 9, 47),
('ls2', 10, 40);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `daisy_admin`
--
ALTER TABLE `daisy_admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `daisy_collection`
--
ALTER TABLE `daisy_collection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `daisy_player_round`
--
ALTER TABLE `daisy_player_round`
  ADD PRIMARY KEY (`name`,`round`),
  ADD KEY `round` (`round`);

--
-- Chỉ mục cho bảng `daisy_question`
--
ALTER TABLE `daisy_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_id` (`collection_id`),
  ADD KEY `id` (`id`,`collection_id`);

--
-- Chỉ mục cho bảng `daisy_round`
--
ALTER TABLE `daisy_round`
  ADD PRIMARY KEY (`round`),
  ADD UNIQUE KEY `round` (`round`),
  ADD UNIQUE KEY `access_token` (`access_token`),
  ADD KEY `collection` (`collection`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Chỉ mục cho bảng `daisy_shuffle_content`
--
ALTER TABLE `daisy_shuffle_content`
  ADD KEY `round` (`round`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `daisy_admin`
--
ALTER TABLE `daisy_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `daisy_collection`
--
ALTER TABLE `daisy_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID của bộ câu hỏi', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `daisy_question`
--
ALTER TABLE `daisy_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã câu hỏi', AUTO_INCREMENT=64;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `daisy_collection`
--
ALTER TABLE `daisy_collection`
  ADD CONSTRAINT `FK_daisy_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `daisy_player_round`
--
ALTER TABLE `daisy_player_round`
  ADD CONSTRAINT `FK_daisy_player_round_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round` (`round`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `daisy_question`
--
ALTER TABLE `daisy_question`
  ADD CONSTRAINT `FK_daisy_question_daisy_collection` FOREIGN KEY (`collection_id`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `daisy_round`
--
ALTER TABLE `daisy_round`
  ADD CONSTRAINT `FK_daisy_round_collection_daisy_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `daisy_admin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_daisy_round_collection_daisy_collection` FOREIGN KEY (`collection`) REFERENCES `daisy_collection` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `daisy_shuffle_content`
--
ALTER TABLE `daisy_shuffle_content`
  ADD CONSTRAINT `FK_daisy_shuffle_content_daisy_question` FOREIGN KEY (`question_id`) REFERENCES `daisy_question` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_daisy_shuffle_content_daisy_round_collection` FOREIGN KEY (`round`) REFERENCES `daisy_round` (`round`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
