-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 10, 2026 lúc 04:00 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ptgear`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Phụ kiện', '2025-08-17 14:41:35'),
(2, 'Linh kiện', '2025-08-17 14:41:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `order_code` varchar(20) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','canceled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `recipient_name`, `order_code`, `total_price`, `shipping_address`, `phone`, `status`, `created_at`) VALUES
(22, 1, 'admin', 'PTG202602095470', 18030000.00, 'qaz', '0123456789', 'completed', '2026-02-09 21:59:26'),
(23, 37, 'Nguyễn Văn B', 'PTG202602093598', 561816.89, 'q', '0123456788', 'canceled', '2026-02-09 22:09:50'),
(24, 37, 'Nguyễn Văn B', 'PTG202602092838', 8200916.76, 'a', '0123456788', 'completed', '2026-02-09 22:48:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(28, 22, 129, 1, 7200000.00, '2026-02-09 21:59:26'),
(29, 22, 130, 1, 10800000.00, '2026-02-09 21:59:26'),
(30, 23, 111, 1, 531816.89, '2026-02-09 22:09:50'),
(31, 24, 79, 1, 1870916.76, '2026-02-09 22:48:56'),
(32, 24, 133, 2, 3150000.00, '2026-02-09 22:48:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percent` int(3) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `description`, `price`, `discount_percent`, `stock`, `image`, `created_at`) VALUES
(65, 1, 27, 'Màn hình Gaming ASUS ROG Swift 27 inch', 'Màn hình gaming cao cấp với tần số quét 144Hz, độ phân giải 1440p, hỗ trợ G-Sync cho trải nghiệm chơi game mượt mà.', 5000000.00, 10, 20, 'img/sanpham/manhinh1.webp', '2025-08-18 07:38:56'),
(66, 1, 27, 'Màn hình UltraWide LG 34 inch Curved', 'Màn hình cong UltraWide với độ phân giải 3440x1440, lý tưởng cho công việc đa nhiệm và giải trí.', 9000000.00, 10, 48, 'img/sanpham/manhinh2.webp', '2025-08-18 07:38:56'),
(67, 1, 27, 'Màn hình Samsung Odyssey G9 49 inch', 'Màn hình siêu rộng 49 inch với tần số 240Hz, hỗ trợ HDR1000 cho hình ảnh sống động.', 7173044.25, 10, 47, 'img/sanpham/manhinh3.webp', '2025-08-18 07:38:56'),
(68, 1, 27, 'Màn hình Dell UltraSharp 32 inch 4K', 'Màn hình chuyên nghiệp 4K UHD với màu sắc chính xác, phù hợp cho thiết kế đồ họa.', 1096148.16, 10, 26, 'img/sanpham/manhinh4.webp', '2025-08-18 07:38:56'),
(69, 1, 27, 'Màn hình Acer Predator X27 27 inch', 'Màn hình IPS 4K với tần số 144Hz, hỗ trợ HDR cho gaming cao cấp.', 9957444.00, 10, 30, 'img/sanpham/manhinh5.webp', '2025-08-18 07:38:56'),
(70, 1, 27, 'Màn hình BenQ ZOWIE XL2546 24.5 inch', 'Màn hình esports 240Hz với công nghệ DyAc cho hình ảnh rõ nét.', 2376155.43, 10, 37, 'img/sanpham/manhinh6.webp', '2025-08-18 07:38:56'),
(73, 1, 27, 'Màn hình Gigabyte AORUS FI27Q 27 inch', 'Màn hình IPS 165Hz với thiết kế gaming mạnh mẽ và âm thanh tích hợp.', 2913027.85, 10, 17, 'img/sanpham/manhinh9.webp', '2025-08-18 07:38:56'),
(75, 1, 27, 'Màn hình Razer Raptor 27 inch', 'Màn hình gaming 27 inch với thiết kế hiện đại và tần số 144Hz.', 4581081.99, 10, 46, 'img/sanpham/manhinh11.webp', '2025-08-18 07:38:56'),
(76, 1, 27, 'Màn hình Alienware AW3420DW 34 inch', 'Màn hình cong UWQHD với tần số 120Hz và hỗ trợ FreeSync Premium Pro.', 1273951.70, 10, 50, 'img/sanpham/manhinh12.webp', '2025-08-18 07:38:56'),
(77, 1, 27, 'Màn hình Philips Momentum 436M6VBPAB 43 inch', 'Màn hình 4K HDR với công nghệ Ambiglow cho trải nghiệm xem phim tuyệt vời.', 8085393.40, 10, 16, 'img/sanpham/manhinh13.webp', '2025-08-18 07:38:56'),
(78, 1, 27, 'Màn hình Sony Inzone M9 27 inch', 'Màn hình 4K 144Hz dành cho gaming console và PC.', 6721850.05, 10, 29, 'img/sanpham/manhinh14.webp', '2025-08-18 07:38:56'),
(79, 1, 27, 'Màn hình Corsair Xeneon 32QHD165 32 inch', 'Màn hình QHD 165Hz với thiết kế cao cấp từ Corsair.', 2078796.40, 10, 17, 'img/sanpham/manhinh15.webp', '2025-08-18 07:38:56'),
(80, 1, 3, 'Chuột Gaming Logitech G502 Hero', 'Chuột gaming có dây với cảm biến Hero 25K DPI, 11 nút lập trình và trọng lượng điều chỉnh.', 3637887.78, 10, 11, 'img/sanpham/chuot1.webp', '2025-08-18 07:38:56'),
(81, 1, 3, 'Chuột Razer DeathAdder V2', 'Chuột ergonomic với cảm biến Focus+ 20K DPI và switch quang học cho độ bền cao.', 8032895.15, 10, 33, 'img/sanpham/chuot2.webp', '2025-08-18 07:38:56'),
(82, 1, 3, 'Chuột Corsair Nightsword RGB', 'Chuột tunable với hệ thống trọng lượng thông minh và cảm biến 18K DPI.', 9156124.61, 10, 10, 'img/sanpham/chuot3.webp', '2025-08-18 07:38:56'),
(83, 1, 3, 'Chuột SteelSeries Rival 600', 'Chuột dual sensor với hệ thống trọng lượng silicone và RGB lighting.', 2771267.33, 10, 9, 'img/sanpham/chuot4.webp', '2025-08-18 07:38:56'),
(84, 1, 3, 'Chuột Zowie EC2', 'Chuột esports ergonomic với cảm biến 3360 cho độ chính xác cao.', 9549716.18, 10, 12, 'img/sanpham/chuot5.webp', '2025-08-18 07:38:56'),
(85, 1, 3, 'Chuột HyperX Pulsefire Haste', 'Chuột siêu nhẹ 59g với honeycomb shell và cảm biến PixArt 3335.', 7271272.58, 10, 10, 'img/sanpham/chuot6.webp', '2025-08-18 07:38:56'),
(86, 1, 3, 'Chuột Glorious Model O', 'Chuột wireless siêu nhẹ 62g với switch TTC Golden và RGB.', 9931779.43, 10, 50, 'img/sanpham/chuot7.webp', '2025-08-18 07:38:56'),
(87, 1, 3, 'Chuột Roccat Kone Pro Air', 'Chuột wireless với Owl-Eye sensor 19K DPI và thiết kế ergonomic.', 7359613.36, 10, 26, 'img/sanpham/chuot8.webp', '2025-08-18 07:38:56'),
(88, 1, 3, 'Chuột Cooler Master MM720', 'Chuột honeycomb siêu nhẹ 55g với cable paracord.', 3721201.36, 10, 19, 'img/sanpham/chuot9.webp', '2025-08-18 07:38:56'),
(89, 1, 3, 'Chuột Endgame Gear XM1r', 'Chuột esports với cảm biến PMW3389 và switch Omron.', 9231429.01, 10, 20, 'img/sanpham/chuot10.webp', '2025-08-18 07:38:56'),
(90, 1, 3, 'Chuột Alienware AW720M', 'Chuột wireless với cảm biến Tri-Mode và pin lâu dài.', 4954319.28, 10, 34, 'img/sanpham/chuot11.webp', '2025-08-18 07:38:56'),
(91, 1, 3, 'Chuột ASUS ROG Keris Wireless', 'Chuột wireless với cảm biến ROG AimPoint 36K DPI.', 7702647.85, 10, 28, 'img/sanpham/chuot12.webp', '2025-08-18 07:38:56'),
(92, 1, 3, 'Chuột Microsoft Pro IntelliMouse', 'Chuột ergonomic với cảm biến PixArt 3389 cho productivity.', 9830404.89, 10, 45, 'img/sanpham/chuot13.webp', '2025-08-18 07:38:56'),
(93, 1, 3, 'Chuột EVGA X17', 'Chuột gaming với 17 nút lập trình và cảm biến 18K DPI.', 9523444.42, 10, 20, 'img/sanpham/chuot14.webp', '2025-08-18 07:38:56'),
(94, 1, 3, 'Chuột Bloody A60', 'Chuột optical switch với 4 mức DPI và hiệu ứng LED.', 4139792.30, 10, 9, 'img/sanpham/chuot15.webp', '2025-08-18 07:38:56'),
(95, 1, 2, 'Bàn phím Cơ Razer BlackWidow V3', 'Bàn phím cơ full-size với switch Razer Green, RGB Chroma và wrist rest.', 6869983.80, 10, 16, 'img/sanpham/banphim1.webp', '2025-08-18 07:38:56'),
(96, 1, 2, 'Bàn phím Corsair K70 RGB MK.2', 'Bàn phím cơ Cherry MX với frame aluminum và dynamic RGB.', 2321077.56, 10, 26, 'img/sanpham/banphim2.webp', '2025-08-18 07:38:56'),
(97, 1, 2, 'Bàn phím Logitech G Pro X', 'Bàn phím tenkeyless với switch swappable GX và RGB Lightsync.', 8746299.67, 10, 18, 'img/sanpham/banphim3.webp', '2025-08-18 07:38:56'),
(98, 1, 2, 'Bàn phím SteelSeries Apex Pro', 'Bàn phím OmniPoint adjustable switches với màn hình OLED.', 9289571.63, 10, 38, 'img/sanpham/banphim4.webp', '2025-08-18 07:38:56'),
(99, 1, 2, 'Bàn phím Ducky One 2 Mini', 'Bàn phím 60% RGB với switch Cherry MX và keycaps PBT.', 7235683.28, 10, 29, 'img/sanpham/banphim5.webp', '2025-08-18 07:38:56'),
(100, 1, 2, 'Bàn phím Anne Pro 2', 'Bàn phím 60% wireless với switch Gateron và RGB.', 1961933.79, 10, 37, 'img/sanpham/banphim6.webp', '2025-08-18 07:38:56'),
(101, 1, 2, 'Bàn phím Keychron K2', 'Bàn phím wireless Mac-compatible với switch Gateron.', 2625182.29, 10, 41, 'img/sanpham/banphim7.webp', '2025-08-18 07:38:56'),
(103, 1, 2, 'Bàn phím Glorious GMMK Pro', 'Bàn phím 75% hot-swappable với gasket mount và RGB.', 9626726.15, 10, 38, 'img/sanpham/banphim9.webp', '2025-08-18 07:38:56'),
(104, 1, 2, 'Bàn phím Drop CTRL', 'Bàn phím tenkeyless hot-swappable với Halo switches.', 2451668.42, 10, 40, 'img/sanpham/banphim10.webp', '2025-08-18 07:38:56'),
(105, 1, 2, 'Bàn phím Filco Majestouch 2', 'Bàn phím full-size Cherry MX với thiết kế classic.', 4566836.78, 10, 43, 'img/sanpham/banphim11.webp', '2025-08-18 07:38:56'),
(106, 1, 2, 'Bàn phím Leopold FC900R', 'Bàn phím full-size PBT keycaps với switch Cherry MX.', 1527527.04, 10, 49, 'img/sanpham/banphim12.webp', '2025-08-18 07:38:56'),
(107, 1, 2, 'Bàn phím Varmilo VA87M', 'Bàn phím tenkeyless với theme color và switch Cherry MX.', 8356475.09, 10, 13, 'img/sanpham/banphim13.webp', '2025-08-18 07:38:56'),
(108, 1, 2, 'Bàn phím IKBC CD108', 'Bàn phím full-size với switch Cherry MX và PBT keycaps.', 5983746.66, 10, 18, 'img/sanpham/banphim14.webp', '2025-08-18 07:38:56'),
(109, 1, 2, 'Bàn phím Royal Kludge RK61', 'Bàn phím 60% wireless với switch hot-swappable.', 5094247.40, 10, 13, 'img/sanpham/banphim15.webp', '2025-08-18 07:38:56'),
(110, 1, 1, 'Tai nghe Gaming HyperX Cloud II', 'Tai nghe gaming có dây với âm thanh vòm 7.1 và mic detachable.', 1694220.83, 10, 31, 'img/sanpham/tainghe1.webp', '2025-08-18 07:38:56'),
(111, 1, 1, 'Tai nghe Razer BlackShark V2', 'Tai nghe esports với THX Spatial Audio và mic cardioid.', 590907.65, 10, 21, 'img/sanpham/tainghe2.webp', '2025-08-18 07:38:56'),
(112, 1, 1, 'Tai nghe Logitech G Pro X', 'Tai nghe wireless với Blue VO!CE mic và DTS Headphone:X 2.0.', 2969288.53, 10, 48, 'img/sanpham/tainghe3.webp', '2025-08-18 07:38:56'),
(113, 1, 1, 'Tai nghe SteelSeries Arctis 7', 'Tai nghe wireless lossless 2.4GHz với pin 24 giờ.', 1526846.89, 10, 13, 'img/sanpham/tainghe4.webp', '2025-08-18 07:38:56'),
(114, 1, 1, 'Tai nghe Corsair HS70 Wireless', 'Tai nghe wireless với âm thanh vòm và mic unidirectional.', 8427845.85, 10, 27, 'img/sanpham/tainghe5.webp', '2025-08-18 07:38:56'),
(115, 1, 1, 'Tai nghe Sennheiser HD 660 S', 'Tai nghe open-back audiophile với dynamic driver.', 7228821.88, 10, 17, 'img/sanpham/tainghe6.webp', '2025-08-18 07:38:56'),
(116, 1, 1, 'Tai nghe Beyerdynamic DT 990 Pro', 'Tai nghe studio open-back với bass mạnh mẽ.', 5381925.09, 10, 28, 'img/sanpham/tainghe7.webp', '2025-08-18 07:38:56'),
(117, 1, 1, 'Tai nghe Audio-Technica ATH-M50x', 'Tai nghe monitor closed-back với âm thanh balanced.', 5831039.20, 10, 17, 'img/sanpham/tainghe8.webp', '2025-08-18 07:38:56'),
(118, 1, 1, 'Tai nghe Sony WH-1000XM4', 'Tai nghe wireless noise-cancelling với LDAC hi-res audio.', 4291740.95, 10, 14, 'img/sanpham/tainghe9.webp', '2025-08-18 07:38:56'),
(119, 1, 1, 'Tai nghe Bose QuietComfort 45', 'Tai nghe wireless ANC với pin 24 giờ và comfort cao.', 1275153.90, 10, 37, 'img/sanpham/tainghe10.webp', '2025-08-18 07:38:56'),
(120, 1, 1, 'Tai nghe Apple AirPods Max', 'Tai nghe wireless over-ear với Adaptive Noise Cancellation.', 7315400.95, 10, 22, 'img/sanpham/tainghe11.webp', '2025-08-18 07:38:56'),
(121, 1, 1, 'Tai nghe Jabra Elite 85h', 'Tai nghe wireless ANC với SmartSound và pin 36 giờ.', 9967274.04, 10, 22, 'img/sanpham/tainghe12.webp', '2025-08-18 07:38:56'),
(122, 1, 1, 'Tai nghe Bang & Olufsen Beoplay H95', 'Tai nghe luxury ANC với aluminum và leather.', 9689202.49, 10, 10, 'img/sanpham/tainghe13.webp', '2025-08-18 07:38:56'),
(123, 1, 1, 'Tai nghe Shure AONIC 50', 'Tai nghe wireless ANC với aptX Adaptive.', 9337439.07, 10, 22, 'img/sanpham/tainghe14.webp', '2025-08-18 07:38:56'),
(124, 1, 1, 'Tai nghe Master & Dynamic MW65', 'Tai nghe wireless ANC với leather và aluminum build.', 9882394.82, 10, 17, 'img/sanpham/tainghe15.webp', '2025-08-18 07:38:56'),
(127, 2, 7, 'Intel Core i9-13900K', 'Vi xử lý Intel Core i9 thế hệ 13, 24 nhân 32 luồng, tốc độ tối đa 5.8 GHz. Mạnh mẽ cho gaming và sáng tạo nội dung.', 15000000.00, 10, 50, 'img/sanpham/CPU1.webp', '2025-11-12 17:44:40'),
(128, 2, 7, 'AMD Ryzen 9 7950X', 'Vi xử lý AMD Ryzen 9, 16 nhân 32 luồng, kiến trúc Zen 4, socket AM5. Hiệu năng vượt trội cho đa tác vụ.', 16500000.00, 10, 50, 'img/sanpham/CPU2.webp', '2025-11-12 17:44:40'),
(129, 2, 7, 'Intel Core i5-13600K', 'Vi xử lý Intel Core i5 thế hệ 13, 14 nhân 20 luồng. Lựa chọn p/p (price/performance) tốt nhất cho gaming.', 8000000.00, 10, 49, 'img/sanpham/CPU3.webp', '2025-11-12 17:44:40'),
(130, 2, 11, 'ASUS ROG STRIX Z790-E GAMING', 'Mainboard cao cấp socket LGA 1700, hỗ trợ DDR5, PCIe 5.0 và WiFi 6E.', 12000000.00, 10, 49, 'img/sanpham/MB1.webp', '2025-11-12 17:44:40'),
(131, 2, 11, 'Gigabyte B760M AORUS ELITE AX', 'Mainboard Micro-ATX, socket LGA 1700, hỗ trợ DDR4, VRM mạnh mẽ và WiFi 6.', 5500000.00, 10, 50, 'img/sanpham/MB2.webp', '2025-11-12 17:44:40'),
(132, 2, 11, 'MSI MAG B650 TOMAHAWK WIFI', 'Mainboard socket AM5 cho CPU AMD Ryzen 7000 series, hỗ trợ DDR5 và WiFi 6E.', 7200000.00, 10, 50, 'img/sanpham/MB3.webp', '2025-11-12 17:44:40'),
(133, 2, 10, 'Samsung 990 PRO 1TB PCIe Gen4 NVMe', 'Ổ cứng SSD NVMe Gen 4 cao cấp, tốc độ đọc/ghi siêu nhanh, lý tưởng cho gaming và các tác vụ nặng.', 3500000.00, 10, 48, 'img/sanpham/SSD1.webp', '2025-11-12 17:44:40'),
(134, 2, 10, 'Kingston NV2 1TB PCIe Gen4 NVMe', 'Ổ cứng SSD NVMe Gen 4, p/p (price/performance) tốt, dung lượng 1TB, tăng tốc độ khởi động máy.', 1800000.00, 10, 50, 'img/sanpham/SSD2.webp', '2025-11-12 17:44:40'),
(135, 2, 10, 'Western Digital Blue SN580 1TB NVMe', 'Ổ cứng SSD NVMe Gen 4 từ Western Digital, tốc độ đọc 4150MB/s, bền bỉ và ổn định.', 2100000.00, 10, 50, 'img/sanpham/SSD3.webp', '2025-11-12 17:44:40'),
(136, 2, 8, 'Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 'RAM DDR5 tốc độ cao 6000MHz, dung lượng 32GB, tản nhiệt nhôm và LED RGB điều khiển qua iCUE.', 4500000.00, 10, 50, 'img/sanpham/RAM1.webp', '2025-11-12 17:44:40'),
(137, 2, 8, 'Kingston Fury Beast 16GB (2x8GB) DDR4 3200MHz', 'RAM DDR4 16GB, tốc độ 3200MHz, tản nhiệt mỏng, tương thích tốt với AMD Ryzen và Intel.', 2000000.00, 10, 50, 'img/sanpham/RAM2.webp', '2025-11-12 17:44:40'),
(138, 2, 8, 'G.Skill Trident Z5 Neo 32GB (2x16GB) DDR5 6000MHz', 'RAM DDR5 32GB, tối ưu cho AMD Ryzen 7000 series (AMD EXPO), tản nhiệt hầm hố và LED RGB.', 4800000.00, 10, 50, 'img/sanpham/RAM3.webp', '2025-11-12 17:44:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Tai nghe'),
(2, 1, 'Bàn phím'),
(3, 1, 'Chuột'),
(7, 2, 'CPU'),
(8, 2, 'RAM'),
(10, 2, 'SSD'),
(11, 2, 'Mainboard'),
(27, 1, 'Màn hình');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `phone`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin', 'pnthanh311@gmail.com', '0123456789', '$2y$10$5bSAZD7raSb6bHp/h705iuTKl2PjSjccgvEFXqqf6UyDDhDfzHw5a', 'admin', 'active', '2025-08-15 01:04:07'),
(37, 'user1', 'Nguyễn Văn B', 'pnh@gmail.com', '0123456788', '$2y$10$rWdaDlLBCOvNmV0VUWVQ8ugmVSh4x897ZiPnRd3VkcmqYzFPwwuQi', 'user', 'active', '2025-11-05 15:13:05'),
(38, 'user2', 'user2', 'test2@test.com', NULL, '$2y$10$kuhLSPHBoY.wgA6Lz/2mJOIFo5cFFGzCugNjNzPlFW572QKkrPpya', 'user', 'active', '2025-11-05 15:34:47'),
(39, 'user3', 'user3', 'test3@test.com', NULL, '$2y$10$8vnNfixvipYHxEg52UCoPOWMBnDLv2UafQ.NfbZ.XOtoqfgXxV2sa', 'user', 'active', '2025-11-05 15:35:14'),
(40, 'user4', 'user4', 'test4@test.com', NULL, '$2y$10$JRufLaRpmAilaU9LQ/u0wOHHyXwotrE3SPCro0694tLvrIzSbAY3K', 'user', 'active', '2025-11-05 15:35:25'),
(41, 'user5', 'user5', 'test5@test.com', NULL, '$2y$10$vGXoU4dvq.MW2ejiZKSmf.J2y/OYMkHriorFA.tTerBLrs3QV7YJK', 'user', 'active', '2025-11-05 15:35:36'),
(42, 'user6', 'user6', 'test6@test.com', NULL, '$2y$10$741CDxBQlBQFIBIDk8doj.Bbup2y2wQwIE6Kfg0Ot/869BKFN6wgq', 'user', 'active', '2025-11-05 15:35:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_cart`
--

CREATE TABLE `user_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `variant` varchar(100) DEFAULT 'Mặc định',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_cart`
--

INSERT INTO `user_cart` (`id`, `user_id`, `product_id`, `quantity`, `variant`, `created_at`) VALUES
(11, 38, 66, 1, 'Mặc định', '2025-11-12 14:43:57'),
(12, 38, 68, 1, 'Mặc định', '2025-11-12 14:43:59'),
(33, 37, 119, 1, 'Mặc định', '2025-11-16 17:09:50'),
(34, 37, 114, 1, 'Mặc định', '2025-11-16 17:09:51'),
(35, 37, 130, 1, 'Mặc định', '2025-11-16 17:09:54'),
(36, 37, 129, 1, 'Mặc định', '2025-11-16 17:09:56'),
(37, 37, 94, 1, 'Mặc định', '2025-11-16 17:09:58'),
(38, 37, 82, 1, 'Mặc định', '2025-11-16 17:09:59'),
(39, 37, 135, 1, 'Mặc định', '2025-11-16 17:10:00'),
(40, 37, 131, 2, 'Mặc định', '2025-11-16 17:10:01'),
(41, 37, 98, 1, 'Mặc định', '2025-11-16 17:10:11'),
(44, 1, 103, 1, 'Mặc định', '2026-02-09 22:06:39'),
(45, 37, 67, 1, 'Mặc định', '2026-02-09 22:48:39'),
(46, 37, 105, 1, 'Mặc định', '2026-02-09 22:48:39');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_products_sub` (`subcategory_id`);

--
-- Chỉ mục cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_sub` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
