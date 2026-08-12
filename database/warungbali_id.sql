-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2026 at 08:56 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warungbali_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorit`
--

CREATE TABLE `favorit` (
  `id_favorit` int NOT NULL,
  `id_user` int NOT NULL,
  `id_warung` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favorit`
--

INSERT INTO `favorit` (`id_favorit`, `id_user`, `id_warung`, `created_at`) VALUES
(58, 1, 45, '2026-07-31 01:32:52'),
(60, 13, 45, '2026-07-31 05:26:52'),
(61, 13, 48, '2026-07-31 07:23:58'),
(62, 13, 2, '2026-07-31 07:26:22'),
(63, 14, 45, '2026-08-10 02:35:23'),
(64, 15, 45, '2026-08-10 05:02:16'),
(65, 15, 1, '2026-08-10 05:02:20'),
(66, 15, 2, '2026-08-11 00:09:25'),
(67, 15, 48, '2026-08-11 00:12:46'),
(68, 15, 13, '2026-08-11 05:33:33'),
(69, 1, 1, '2026-08-11 11:15:05'),
(70, 16, 45, '2026-08-12 04:04:13'),
(71, 16, 1, '2026-08-12 04:04:16'),
(72, 16, 15, '2026-08-12 04:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `kabupaten`
--

CREATE TABLE `kabupaten` (
  `id_kabupaten` int NOT NULL,
  `nama_kabupaten` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kabupaten`
--

INSERT INTO `kabupaten` (`id_kabupaten`, `nama_kabupaten`, `created_at`) VALUES
(1, 'Denpasar', '2026-07-03 02:47:37'),
(2, 'Badung', '2026-07-03 02:47:37'),
(3, 'Gianyar', '2026-07-03 02:47:37'),
(4, 'Tabanan', '2026-07-03 02:47:37'),
(5, 'Bangli', '2026-07-03 02:47:37'),
(6, 'Karangasem', '2026-07-03 02:47:37'),
(7, 'Klungkung', '2026-07-03 02:47:37'),
(8, 'Buleleng', '2026-07-03 02:47:37'),
(9, 'Jembrana', '2026-07-03 02:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`) VALUES
(1, 'Warung Makan', '2026-07-03 02:47:14'),
(2, 'Warung Minuman', '2026-07-03 02:47:14'),
(3, 'Warung Sembako', '2026-07-03 02:47:14'),
(4, 'Warung Oleh-Oleh Bali', '2026-07-03 02:47:14'),
(5, 'Warung Buah & Sayur', '2026-07-03 02:47:14'),
(6, 'Warung Herbal', '2026-07-03 02:47:14'),
(7, 'Warung Pulsa & PPOB', '2026-07-16 02:10:12'),
(8, 'Warung ATK & Fotokopi', '2026-07-16 02:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `id_warung` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` int NOT NULL,
  `foto_menu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_warung`, `nama_menu`, `deskripsi`, `harga`, `foto_menu`, `created_at`) VALUES
(1, 1, 'Babi Guling Spesial', 'Porsi lengkap dengan lawar dan sate', 35000, 'babigulinggg.png', '2026-07-03 02:48:32'),
(2, 4, 'Satelilit ikan Tuna', 'Sate Lilit Ikan Tuna Khas bumbu Bali', 25000, 'satelilit.jpeg', '2026-07-03 02:48:11'),
(3, 1, 'Lawar Bali', 'Lawar khas Bali', 20000, 'lawar.jpeg', '2026-07-03 02:48:32'),
(4, 2, 'Nasi Campur Biasa', 'Porsi biasa ', 25000, 'nasicampur.png', '2026-07-03 02:48:32'),
(7, 45, 'CapCay', 'Capcay adalah hidangan yang terdiri dari aneka sayuran segar yang dimasak dengan bumbu gurih, cocok dinikmati sebagai menu sehat dan lezat.', 15000, '1785476268_capcay.jpeg', '2026-07-31 05:37:48'),
(8, 45, 'Nasi Goreng', 'Menu favorit dengan nasi yang ditumis bersama bumbu khas, disajikan hangat dengan pelengkap yang menggoda.', 15000, '1785476347_nasgor.jpg', '2026-07-31 05:39:07'),
(9, 45, 'FuyungHai', 'Telur dadar ala oriental yang disajikan dengan saus kental bercita rasa asam manis, cocok disantap bersama nasi hangat.', 18000, '1785476399_fuyunghai.jpg', '2026-07-31 05:39:59'),
(10, 45, 'Nasi Campurr', 'Nasi campur dengan aneka lauk pilihan yang dipadukan dalam satu porsi, menghadirkan cita rasa yang lengkap dan lezat.', 15000, '1785476578_purrr.jpg', '2026-07-31 05:42:58'),
(11, 45, 'Soda gembira', 'Minuman segar perpaduan soda, susu, dan sirup manis yang memberikan sensasi menyegarkan di setiap tegukan.', 7000, '1785476616_sodagembira.jpg', '2026-07-31 05:43:36'),
(12, 45, 'es campur', 'Es campur berisi aneka buah, cincau, agar-agar, dan sirup manis yang menyegarkan di setiap suapan.', 5000, '1785476667_esbuahcmpr.jpeg', '2026-07-31 05:44:27'),
(13, 45, 'Ayam Kolake', 'Hidangan ayam goreng tepung yang dipadukan dengan saus asam manis khas, cocok dinikmati bersama nasi hangat.', 20000, '1785476717_kolake.jpg', '2026-07-31 05:45:17'),
(14, 45, 'Sayur Hijau', 'Sayur hijau segar yang dimasak sederhana untuk menghasilkan cita rasa gurih, sehat, dan bergizi.', 15000, '1785476761_yurjo.jpeg', '2026-07-31 05:46:01'),
(15, 45, 'Mie Goreng', 'Mie goreng disajikan dengan aneka sayuran dan topping pilihan, cocok dinikmati sebagai menu makan siang maupun malam.', 15000, '1785476817_miegoreng.jpeg', '2026-07-31 05:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000010_create_kategori_table', 1),
(5, '2025_01_01_000011_create_kabupaten_table', 1),
(6, '2025_01_01_000012_create_warung_table', 1),
(7, '2025_01_01_000013_create_menu_table', 1),
(8, '2025_01_01_000014_create_review_table', 1),
(9, '2025_01_01_000015_create_favorit_table', 1),
(10, '2026_01_01_000001_add_pemilik_role_to_users_table', 2),
(11, '2026_01_01_000002_add_status_to_warung_table', 3),
(12, '2026_01_02_000001_add_menerima_catering_to_warung_table', 4),
(13, '2026_01_02_000002_add_unique_user_warung_to_review_table', 5),
(14, '2026_02_01_000001_create_page_visits_table', 5),
(15, '2026_02_02_000001_add_id_warung_induk_to_warung_table', 6),
(16, '2026_02_03_000001_fix_review_favorit_user_foreign_key', 7),
(17, '2026_02_03_000002_fix_warung_user_foreign_key', 8);

-- --------------------------------------------------------

--
-- Table structure for table `page_visits`
--

CREATE TABLE `page_visits` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visited_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_visits`
--

INSERT INTO `page_visits` (`id`, `session_id`, `visited_date`, `created_at`) VALUES
(1, 'I95geTmXympHvQq0o29XrnJVSCyCPkZ6USAMng9n', '2026-07-30', '2026-07-30 02:11:03'),
(2, 'OZMzeXV72W2EDhSJ4rQVWAODab1k5DcML42OxK4y', '2026-07-30', '2026-07-30 02:11:15'),
(3, '4aAP2iZveLOvO6vrHuro6CxP15PfUNgpkNU6xmdL', '2026-07-30', '2026-07-30 02:11:24'),
(4, 'LzECKPFenL90VO0vWkqQtjbdnSl587PUS3INCbcJ', '2026-07-30', '2026-07-30 02:11:37'),
(5, 'itCAhPHHRMZbaH790M4Pl9Bxl5ZJ3ogWHnRgsh19', '2026-07-30', '2026-07-30 02:22:59'),
(6, 'p1FvuYmU9ZW599NQs05jC2UGAo8WUvE857scuIex', '2026-07-30', '2026-07-30 02:23:12'),
(7, 'CDcabnQxC8GBEdvzJCW5aggBAvMXbGMrwq3u8Szj', '2026-07-30', '2026-07-30 02:55:10'),
(8, 'ju6hvSwloLwDyPszT1dENiOVaiTbtHKnB7KL8UxT', '2026-07-30', '2026-07-30 04:04:02'),
(9, 'yB5FLN34eH8uiRXHIwtKGBIzonRVGLXLbTSVuERo', '2026-07-30', '2026-07-30 04:04:39'),
(10, '1fuMHNRqsO0zcMckQpi7SQcADgbc2K3SI8TUB8iq', '2026-07-30', '2026-07-30 04:10:26'),
(11, 'sFi8DMhwmJZoUacS2dMGvb3e9XCGr6CIYI7NFUgJ', '2026-07-30', '2026-07-30 04:27:24'),
(12, 'Q1lWqeThCMCYr4JK76u1n195HeKNIZdQSymFKr9C', '2026-07-30', '2026-07-30 04:58:38'),
(13, 'gQyHewcAh8bczcuf3rSY0jF3s9f7CpmSja3gTEla', '2026-07-31', '2026-07-31 00:09:12'),
(14, 'TjpyHeMFvAqePV0VDZljQo19PY5ubse1G5GXCjCZ', '2026-07-31', '2026-07-31 00:19:30'),
(15, 'OHYS6r1hpZw28pcMnevdXnkVoIGLboYqQ0BurCS0', '2026-07-31', '2026-07-31 00:20:05'),
(16, 'dvavwy0RAmI8xvVp5efPdaSgM5OdiJF21enj4Mlm', '2026-07-31', '2026-07-31 00:20:57'),
(17, 'vvD2N7YG1t4Xyk9RoclQoWt03bGICHpG1pmeXsAY', '2026-07-31', '2026-07-31 00:22:49'),
(18, 'kKxgYaA3kvg8jJ0uUuIa2Ou6fVea7pJpmrSJm5iP', '2026-07-31', '2026-07-31 00:23:12'),
(19, 'e1cYuZH9Di3lODqqMsUfrcDig3skZ9skvixlEPFV', '2026-07-31', '2026-07-31 00:23:20'),
(20, 'vZciHGZMJZVzSB4bDG2mLe3Ipwfaag3spMnKR31Q', '2026-07-31', '2026-07-31 00:33:07'),
(21, 'dHioOGhEFyZyQw5thAnQLl7vfR0iwJE26uQYNHBN', '2026-07-31', '2026-07-31 00:58:00'),
(22, 'eabf7zYUjVQ6X0Q3ycanWRicph9X4XkYsefu3su1', '2026-07-31', '2026-07-31 00:59:45'),
(23, '0EucxsPSqlHyXIlci8XXBDjlyiY3xxFtEy7Vikv9', '2026-07-31', '2026-07-31 01:00:32'),
(24, 'EoSOmlLxLHKt7c7uxO08uLWGIBHBhUNiPkmvtTKS', '2026-07-31', '2026-07-31 01:00:42'),
(25, 'KBGssbZVG6dtylm0wxaoeltodTr0rzWTmCGKB0JS', '2026-07-31', '2026-07-31 01:03:09'),
(26, 'oewFBNW3DOm4WHlhHQgiTHc3q73JoIyN7rJm490z', '2026-07-31', '2026-07-31 01:33:47'),
(27, 'of3zh63eXfupNumlk79ZFN4w01kszegdgtRXafAr', '2026-07-31', '2026-07-31 01:34:11'),
(28, 'RozneLmCPc057wP8zl6dJeXXqKAvePz60nYg2K5a', '2026-07-31', '2026-07-31 04:19:16'),
(29, 'dPLugrimyhV9cRIAAYfdz5svJIW2wXxnibgrEX0Z', '2026-07-31', '2026-07-31 04:23:52'),
(30, 'srX7cQwGdXReKtqKCEDoIYy9IAG8RrHrVG972HCZ', '2026-07-31', '2026-07-31 04:24:13'),
(31, 'G47FZWfXIfnEAwpixsHPeNV2UhshY3usGYZZmAL7', '2026-07-31', '2026-07-31 04:24:43'),
(32, 'r3vLqVNAstuQj1VCLM7k7uhsnaMmROq4y9zwlgMW', '2026-07-31', '2026-07-31 04:24:58'),
(33, 'MT7tHUooNrDpg5Rne1b8w986niumI09NHaMcdR60', '2026-07-31', '2026-07-31 04:27:38'),
(34, 'EtntIWYkynTtAbhmAAy4ta9cgySXaM7KM4rwhA8v', '2026-07-31', '2026-07-31 04:27:49'),
(35, 'oHmw71Wblo68ChsepHx179j5PHuIhCZnhFptZFkC', '2026-07-31', '2026-07-31 04:55:35'),
(36, 'ClxZDDJTeswamZ7bTavLQ1XckkawigJrPRGAPLXf', '2026-07-31', '2026-07-31 05:22:07'),
(37, 'NUjFvNXkRqnsb6wNZAs5BMt0mLaY34Htnsjpaacg', '2026-07-31', '2026-07-31 05:49:35'),
(38, 'jVTF1TRK1C7DWEIRm8BSo3m6IuFHCYLNFqAIEgdG', '2026-07-31', '2026-07-31 07:49:35'),
(39, '2uLNzLwp53hp4z9I9xF0X0pENuxZ8RGG9VCdcKsV', '2026-08-10', '2026-08-10 00:11:36'),
(40, 'bSZszSf5pwoapNS3TpnFPHtN3G1rnIDpUu9mPRGh', '2026-08-10', '2026-08-10 00:13:37'),
(41, 'VK1bBCesqx0KQutp8uvKHpUq0yK92KUtUFP330Y7', '2026-08-10', '2026-08-10 00:17:56'),
(42, '8ZCxnpY06GfSI9CjmhK907Hv3eshk9Iqs34LBUmi', '2026-08-10', '2026-08-10 01:52:47'),
(43, 'B5EsT5B5WMPbEV4sxcZaZHhwsHh51PcUR42TPUGw', '2026-08-10', '2026-08-10 01:53:06'),
(44, 'jZptBpRafzYAqfCNrcWrMWGpg8MfHYFPv2LToHz6', '2026-08-10', '2026-08-10 01:55:08'),
(45, 'BvQh1e0nVwmNGr1JUoxvh4uMdxjU8zuhsTEc3RHs', '2026-08-10', '2026-08-10 01:59:53'),
(46, 'cgvNxN2o6nSTXlEzSpeJMJiWEHpKywL7ONfc5z4e', '2026-08-10', '2026-08-10 02:00:25'),
(47, '55VnRSCnfUFpLucfyqZgB6yvj06ZzwSfrv5ceRsR', '2026-08-10', '2026-08-10 03:26:01'),
(48, '2fOuTTyPAZt1O0w0Cg8QHL8vExWA2l1PIV5JdfIm', '2026-08-10', '2026-08-10 03:30:34'),
(49, 'Dmjc5j0fr4gnhHvgPHdpUqRNb7F7ECI0BKiMXan0', '2026-08-10', '2026-08-10 03:54:15'),
(50, 'FX9XazQnIC2S9ZJ300DA1rHqLEFeXePQfpaIc0xT', '2026-08-10', '2026-08-10 03:54:46'),
(51, 'KM6x62ZbHeCKgFVHLChig8840obRTuXiSsZm6TrP', '2026-08-10', '2026-08-10 07:04:16'),
(52, '0cHJqW2ArUxVxkPD39Llqkr7gshnTeqh4PPYdQxs', '2026-08-10', '2026-08-10 07:18:23'),
(53, 'DXXpqq8W0ZYuXuWwgwTSf7hiM2nzlLlzKpbSRuki', '2026-08-10', '2026-08-10 07:19:38'),
(54, 'MmcQ6bwemxZfYzVA93szBE2ASqpLi6PFljAM9SJE', '2026-08-10', '2026-08-10 07:22:38'),
(55, 'IKvEBgRePCTbeZMp8mhI6KT5SFBla7MHJALzvfw5', '2026-08-10', '2026-08-10 07:23:02'),
(56, '1VUdXCDHGLPcug6RMtmRqvUJqwnhSVZW3nuwLPX2', '2026-08-10', '2026-08-10 07:48:25'),
(57, 'fgcJKYxh9D6ZDMDGlNJGy6qvxPxGtLU50IX8JTVQ', '2026-08-10', '2026-08-10 07:51:19'),
(58, 'aovHeHp96mtW1qiQVBr6cJ76FNpnTaoPuievQ02z', '2026-08-10', '2026-08-10 07:55:00'),
(59, 'mmo1xwr0N9l3S85W6fWQ9eOucbtx46vcOfN2Pm8h', '2026-08-11', '2026-08-11 00:04:05'),
(60, 'rh2pBCP5Ha8AVR5Bpw1wDj80o8bdjmvGTtMf4P2T', '2026-08-11', '2026-08-11 00:04:18'),
(61, 'NaIzgkHZFGIER66izuBnrWSMZgqT3DupwUAT4iuT', '2026-08-11', '2026-08-11 00:05:42'),
(62, 'yUaZMqIIuAItFhh9IdEExD6ATnXeTRDo7BdYIlkx', '2026-08-11', '2026-08-11 00:06:25'),
(63, 'vnBfzAcPOZhId7xYkjZVMy4E038fLjb8hCpQAW5g', '2026-08-11', '2026-08-11 00:06:36'),
(64, 'c02ev5nZsH4B9UcsEXoZXGEF4PfEUNf7siqivZMV', '2026-08-11', '2026-08-11 00:06:57'),
(65, '4RIwUIMJB3YgtsPkyRvwhHgmh9iM6p1I9uo06QGR', '2026-08-11', '2026-08-11 00:08:52'),
(66, 'jZB3O501Sbd3BmeyQL9lGj6TG6aXyjsg63D9PXOS', '2026-08-11', '2026-08-11 00:13:04'),
(67, 'X75nWGdwavVoD4CcdoM6yLRsjzEgnlTxPdggV9M9', '2026-08-11', '2026-08-11 00:13:36'),
(68, 'xiBOwF41Bt5wu6KshbDDm5kBdnxOAYUSiR2Bgd0N', '2026-08-11', '2026-08-11 00:18:22'),
(69, 'B09cavVCmubYQvEEDZAQDCtAcJMnZVSzX7vE65QO', '2026-08-11', '2026-08-11 00:18:56'),
(70, '2O84pbvUJvnNsywyzxt6wyhZ0LGh2KiekZ3K8Aze', '2026-08-11', '2026-08-11 04:56:28'),
(71, 'Z7E9vuiR6bid5CoJR9edhC4zTFRB1Ed5cUHYrLxf', '2026-08-11', '2026-08-11 04:57:30'),
(72, 'u8FVTRD9dyhsw6sdjH0hkkLGO8BbOuPsREueZ0ZJ', '2026-08-11', '2026-08-11 05:22:39'),
(73, 'TtQmCNTi8sHo7yV4hnVtj2kgxZom3ws2UcGJuqq3', '2026-08-11', '2026-08-11 05:23:24'),
(74, 'MO5BtmoyiWLItkeGnvCqz5ShJOOVRvxlBCjYw6u4', '2026-08-11', '2026-08-11 05:26:54'),
(75, '7ZvUwcoWy8xHFF4LaU6RsinyBsc9x0cqGQQhEmn5', '2026-08-11', '2026-08-11 05:27:26'),
(76, 'divdrJKZ8K3XFbDj7TDmC4ADUMSspMLDnGFVn1wh', '2026-08-11', '2026-08-11 05:27:41'),
(77, 'T7wvgASMNfxJJLh7lkMAAL83IEAk7HVDJvbR0gjK', '2026-08-11', '2026-08-11 09:27:28'),
(78, 'uVSDrpPh4Uqi3vGmFWaAyfHrLH0nidswHrnTMzf5', '2026-08-11', '2026-08-11 09:28:41'),
(79, 'Q8BheWWQehFwYYcrhLQdyhwtUxNBKKCogDuLL4GB', '2026-08-12', '2026-08-12 00:02:17'),
(80, 'WXpUyirCzLbaK7BxJDW1lr1uGkB3YE3q26zu8eXT', '2026-08-12', '2026-08-12 00:02:57'),
(81, 'uYBrf6J9ZGZ8XzWyFujXIHkLzqXtZwOO4tDpm6Qv', '2026-08-12', '2026-08-12 00:47:25'),
(82, 'todfKvLqYON6Jh0BXfxlv88NdBMNvMRk86sq6Mdo', '2026-08-12', '2026-08-12 00:47:54'),
(83, 'aSNgS0AFTxezvG1E24WZcBcCLJkIdbekrC0KcJLy', '2026-08-12', '2026-08-12 03:21:39'),
(84, 'liTANRySPYMr2PFacCNtMkKY6AHwISvqLCLyHQev', '2026-08-12', '2026-08-12 07:39:42'),
(85, 'QnvQzrKCSXINJfvjwa6OEptJICl10TyYEjprTIEF', '2026-08-12', '2026-08-12 07:44:33'),
(86, 'r8uDAYDDO0QN4qMH4inu9gFZWjMiVHz5phUC5ZCt', '2026-08-12', '2026-08-12 07:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int NOT NULL,
  `id_user` int NOT NULL,
  `id_warung` int NOT NULL,
  `rating` int NOT NULL,
  `komentar` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id_review`, `id_user`, `id_warung`, `rating`, `komentar`, `created_at`) VALUES
(3, 1, 1, 4, 'mantappp', '2026-07-27 20:48:29'),
(6, 1, 45, 5, 'mantappp sekalii', '2026-07-30 17:33:18'),
(9, 13, 45, 5, 'sangat mantap dan enak hidangannya apa lagi nasigorengnyaaa', '2026-07-30 21:27:22'),
(10, 15, 45, 5, 'capcay nya mantapppp', '2026-08-09 21:03:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','pemilik') DEFAULT 'user',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `foto`, `created_at`) VALUES
(1, 'Admin', 'admin@warungbali.id', '$2y$12$nCEJSTWN4eimZrZexSE1JudT8cc5llgaojxBsYiqoFIpNSQyRQCmS', 'admin', NULL, '2026-07-03 02:46:39'),
(13, 'bayu yasa', 'bayuyasa@gmail.com', '$2y$12$oYOzGMjcm.dvRgP0HX9olO7Z.sgwwNhB3IAtJc2Av5FFKH.PMfG3u', 'user', NULL, '2026-07-31 04:55:34'),
(14, 'debayu', 'debayu@gmail.com', '$2y$12$eqQu2475s6OF3wqPWH8.i.QpuutlEeEbM0yQ2Frz04gaERTQQh3kq', 'user', NULL, '2026-08-10 02:00:24'),
(15, 'pakyan', 'pakyan@gmail.com', '$2y$12$vUyCMPazI8kMvTUwGXLcseKhpWMYvu1OO51K8H7yx58uslrPbK4zS', 'user', NULL, '2026-08-10 03:54:45'),
(16, 'Pemilik', 'pemilik@gmail.com', '$2y$12$hJEzVHEkisnlnatUVCq9befaBbJi28vA2gdDK0Ml25rCYd0WXf0Ey', 'pemilik', NULL, '2026-08-12 00:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `warung`
--

CREATE TABLE `warung` (
  `id_warung` int NOT NULL,
  `id_warung_induk` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `id_kategori` int NOT NULL,
  `id_kabupaten` int NOT NULL,
  `nama_warung` varchar(150) NOT NULL,
  `alamat` text NOT NULL,
  `deskripsi` text,
  `telepon` varchar(20) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `harga_min` int DEFAULT NULL,
  `harga_max` int DEFAULT NULL,
  `menerima_catering` tinyint(1) NOT NULL DEFAULT '0',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warung`
--

INSERT INTO `warung` (`id_warung`, `id_warung_induk`, `id_user`, `id_kategori`, `id_kabupaten`, `nama_warung`, `alamat`, `deskripsi`, `telepon`, `jam_buka`, `jam_tutup`, `harga_min`, `harga_max`, `menerima_catering`, `foto`, `status`, `created_at`) VALUES
(1, NULL, NULL, 1, 3, 'Warung Babi Guling Pan Deana', 'Jl. Tegal Sari No.2, Ubud, Gianyar', 'Warung Babi Guling Bali.', '081234567890', '08:00:00', '17:00:00', 35000, 75000, 0, '1785213667_pndea.jpg', 'approved', '2026-07-03 02:48:11'),
(2, NULL, NULL, 1, 5, 'Warung Nasi Campur Pan Ogi', 'Jl. Nusantara Kubu Bangli', 'Warung Nasi Campur Khas Bali', '081234567890', '08:00:00', '21:00:00', 25000, 30000, 1, '1785213805_nspr.jpeg', 'approved', '2026-07-13 03:32:04'),
(4, NULL, NULL, 1, 6, 'Warung Pan Debayu', 'jl. Nusantara Karangasem', 'menjual sate lilit khas bali', '0876543200', '10:00:00', '18:00:00', 25000, 30000, 0, '1785213973_wrngste.jpeg', 'approved', '2026-07-03 02:48:11'),
(6, NULL, NULL, 4, 2, 'Dek Gus Oleh-Oleh', 'Jl.Abiansemal', 'Menjual berbagai oleh oleh untuk keluarga,  sahabat, dan orang yang disayangi', '087825258736', '07:00:00', '22:00:00', 15000, 300000, 0, '1785120238_oleh4.jpg', 'approved', '2026-07-17 02:39:11'),
(7, NULL, 1, 4, 1, 'Krisna Oleh-Oleh Bali', 'Jl. Sunset Road, Kuta, Badung', 'Menyediakan berbagai oleh-oleh khas Bali seperti pie susu, kaos Bali, souvenir, dan kerajinan.', '081234567890', '08:00:00', '22:00:00', 10000, 250000, 0, '1785120218_oleh3.jpeg', 'approved', '2026-07-17 03:21:06'),
(8, NULL, 1, 4, 2, 'Erlangga Oleh-Oleh Bali', 'Jl. Nusa Kambangan, Denpasar', 'Toko oleh-oleh Bali dengan berbagai pilihan makanan khas, pakaian tradisional, dan cinderamata.', '082345678901', '08:00:00', '21:00:00', 15000, 200000, 0, '1785120110_oleh2.jpeg', 'approved', '2026-07-17 03:21:06'),
(9, NULL, 1, 4, 3, 'Joger Bali', 'Jl. Raya Kuta, Badung', 'Toko terkenal dengan produk kaos unik khas Bali dan souvenir kreatif.', '083456789012', '09:00:00', '21:00:00', 30000, 300000, 0, '1785120090_oleh1.jpeg', 'approved', '2026-07-17 03:21:06'),
(10, NULL, 1, 4, 4, 'Pie Susu Enaaak Bali', 'Jl. Nangka Selatan, Denpasar', 'Pusat oleh-oleh makanan khas Bali berupa pie susu dengan berbagai varian rasa.', '084567890123', '07:00:00', '20:00:00', 10000, 100000, 0, '1785120072_pie1.jpeg', 'approved', '2026-07-17 03:21:06'),
(11, NULL, 1, 5, 1, 'Warung Buah Segar Dewata', 'Jl. Raya Kuta No. 25, Badung', 'Menyediakan berbagai buah segar lokal, buah impor, serta sayuran segar berkualitas setiap hari.', '081234567801', '06:00:00', '21:00:00', 5000, 150000, 0, '1785119035_buah5.jpeg', 'approved', '2026-07-17 05:32:26'),
(12, NULL, 1, 5, 2, 'Warung Sayur Bali Asri', 'Jl. Teuku Umar No. 88, Denpasar', 'Menjual aneka sayuran segar, bumbu dapur, dan kebutuhan memasak harian dengan harga terjangkau.', '081234567802', '05:30:00', '20:00:00', 2000, 100000, 0, '1785119014_buah4.jpeg', 'approved', '2026-07-17 05:32:26'),
(13, NULL, 1, 5, 3, 'Buah & Sayur Tani Makmur', 'Jl. Raya Ubud No. 10, Gianyar', 'Menyediakan hasil panen langsung dari petani lokal berupa buah dan sayuran segar.', '081234567803', '06:00:00', '19:00:00', 3000, 120000, 0, '1785118993_buah2.jpg', 'approved', '2026-07-17 05:32:26'),
(14, NULL, 1, 5, 4, 'Warung Organik Hijau', 'Jl. Raya Bedugul, Tabanan', 'Menyediakan buah organik, sayuran organik, serta berbagai produk sehat pilihan.', '081234567804', '07:00:00', '20:00:00', 5000, 180000, 0, '1785118972_buah3.jpg', 'approved', '2026-07-17 05:32:26'),
(15, NULL, 1, 5, 5, 'Pasar Buah Nusantara', 'Jl. Diponegoro No. 15, Singaraja', 'Menjual berbagai macam buah segar, sayuran, dan rempah-rempah berkualitas.', '081234567805', '06:00:00', '21:00:00', 3000, 200000, 0, '1785118947_buah1.jpg', 'approved', '2026-07-17 05:32:26'),
(16, NULL, 1, 6, 1, 'Warung Herbal Bali Sehat', 'Jl. Raya Kuta No. 15, Badung', 'Menyediakan aneka jamu tradisional, teh herbal, madu, dan suplemen alami khas Indonesia.', '081234567901', '08:00:00', '20:00:00', 10000, 250000, 0, '1785118212_hrbl5.jpg', 'approved', '2026-07-17 05:34:39'),
(17, NULL, 1, 6, 2, 'Herbal Dewata', 'Jl. Teuku Umar No. 45, Denpasar', 'Menjual berbagai produk herbal, rempah-rempah, minyak atsiri, dan minuman kesehatan.', '081234567902', '07:30:00', '21:00:00', 5000, 200000, 0, '1785118189_hrbl4.jpg', 'approved', '2026-07-17 05:34:39'),
(18, NULL, 1, 6, 3, 'Warung Jamu Nusantara', 'Jl. Raya Ubud No. 22, Gianyar', 'Menyediakan jamu tradisional, kunyit asam, beras kencur, dan ramuan herbal alami.', '081234567903', '07:00:00', '19:00:00', 8000, 150000, 0, '1785118162_hrbl3.jpeg', 'approved', '2026-07-17 05:34:39'),
(19, NULL, 1, 6, 4, 'Rumah Herbal Organik', 'Jl. Raya Bedugul, Tabanan', 'Menjual produk herbal organik, teh kesehatan, madu murni, dan minyak herbal.', '081234567904', '08:00:00', '20:00:00', 15000, 300000, 0, '1785118133_hrbl2.jpg', 'approved', '2026-07-17 05:34:39'),
(20, NULL, 1, 6, 5, 'Warung Rempah Sejahtera 45', 'Jl. Diponegoro No. 18, Singaraja', 'Menyediakan rempah-rempah pilihan, herbal tradisional, dan produk kesehatan alami.', '081234567905', '07:00:00', '20:00:00', 10000, 180000, 0, '1785117957_hrbl1.jpeg', 'approved', '2026-07-17 05:34:39'),
(21, NULL, 1, 7, 1, 'Warung Pulsa Bali Cell', 'Jl. Raya Kuta No. 10, Badung', 'Melayani penjualan pulsa, paket data, token listrik, pembayaran PPOB, dan top up e-wallet.', '081234568001', '07:00:00', '22:00:00', 5000, 500000, 0, '1785118564_kntr5.jpeg', 'approved', '2026-07-17 05:37:02'),
(22, NULL, 1, 7, 2, 'Dewata Cellular', 'Jl. Teuku Umar No. 75, Denpasar', 'Menyediakan pulsa semua operator, paket internet, voucher game, dan layanan pembayaran tagihan.', '081234568002', '08:00:00', '21:00:00', 5000, 1000000, 0, '1785117849_kntr4.jpeg', 'approved', '2026-07-17 05:37:02'),
(23, NULL, 1, 7, 3, 'PPOB Nusantara', 'Jl. Raya Ubud No. 18, Gianyar', 'Melayani pembayaran listrik, PDAM, BPJS, internet, cicilan, serta pembelian pulsa dan token PLN.', '081234568003', '07:30:00', '20:30:00', 10000, 750000, 0, '1785117825_kntr3.jpg', 'approved', '2026-07-17 05:37:02'),
(24, NULL, 1, 7, 4, 'Warung Digital Bali', 'Jl. Raya Bedugul No. 12, Tabanan', 'Pusat layanan digital seperti pulsa, paket data, top up e-money, dan pembayaran PPOB.', '081234568004', '08:00:00', '21:00:00', 5000, 1000000, 0, '1785117793_kntr2.jpeg', 'approved', '2026-07-17 05:37:02'),
(25, NULL, 1, 7, 5, 'Cell Point Singaraja', 'Jl. Diponegoro No. 20, Buleleng', 'Menyediakan pulsa, paket internet, voucher game, token listrik, dan berbagai layanan PPOB.', '081234568005', '07:00:00', '22:00:00', 5000, 500000, 0, '1785117701_kntr1.jpeg', 'approved', '2026-07-17 05:37:02'),
(26, NULL, 1, 8, 1, 'Warung ATK Cerdas', 'Jl. Raya Kuta No. 35, Badung', 'Menyediakan alat tulis kantor, perlengkapan sekolah, serta layanan fotokopi, print, dan scan dokumen.', '081234568101', '07:00:00', '21:00:00', 1000, 500000, 0, '1785118786_atk4.jpg', 'approved', '2026-07-17 05:37:23'),
(27, NULL, 1, 8, 2, 'Fotokopi Dewata', 'Jl. Teuku Umar No. 102, Denpasar', 'Melayani fotokopi, print warna, jilid skripsi, laminating, serta penjualan alat tulis lengkap.', '081234568102', '07:30:00', '21:00:00', 500, 300000, 0, '1785118770_atk3.jpeg', 'approved', '2026-07-17 05:37:23'),
(28, NULL, 1, 8, 3, 'ATK & Print Ubud', 'Jl. Raya Ubud No. 18, Gianyar', 'Menyediakan berbagai kebutuhan ATK, fotokopi, print, scan, dan cetak dokumen untuk pelajar maupun perkantoran.', '081234568103', '08:00:00', '20:00:00', 1000, 400000, 0, '1785118753_atk2.jpeg', 'approved', '2026-07-17 05:37:23'),
(29, NULL, 1, 8, 4, 'Warung Pena Bali', 'Jl. Raya Bedugul No. 25, Tabanan', 'Menjual perlengkapan sekolah dan kantor serta melayani fotokopi, print, dan penjilidan dokumen.', '081234568104', '07:00:00', '20:30:00', 1000, 250000, 0, '1785118736_atk.jpeg', 'approved', '2026-07-17 05:37:23'),
(30, NULL, 1, 8, 5, 'Singaraja Copy Center', 'Jl. Diponegoro No. 30, Buleleng', 'Menyediakan layanan fotokopi, print, scan, laminating, dan penjualan alat tulis kantor dengan harga terjangkau.', '081234568105', '07:00:00', '21:00:00', 500, 500000, 0, '1785114942_atk1.jpg', 'approved', '2026-07-17 05:37:23'),
(31, NULL, 1, 2, 1, 'Es Teh Nusantara', 'Jl. Gajah Mada No. 10, Denpasar', 'Warung minuman dengan aneka es teh, lemon tea, dan thai tea.', '081234567801', '09:00:00', '22:00:00', 5000, 25000, 0, '1785117522_esth1.jpeg', 'approved', '2026-07-18 01:49:13'),
(32, NULL, 1, 2, 2, 'Segar Juice Bali', 'Jl. Raya Ubud No. 20, Gianyar', 'Menyediakan jus buah segar tanpa pengawet.', '081234567802', '08:00:00', '21:00:00', 10000, 35000, 0, '1785117466_jus.jpeg', 'approved', '2026-07-18 01:49:13'),
(33, NULL, 1, 2, 3, 'Kopi & Susu Kita', 'Jl. Diponegoro No. 15, Tabanan', 'Warung kopi susu kekinian dengan berbagai pilihan rasa.', '081234567803', '07:00:00', '23:00:00', 12000, 30000, 0, '1785117378_kopis1.jpeg', 'approved', '2026-07-18 01:49:13'),
(34, NULL, 1, 2, 4, 'Boba Corner', 'Jl. Ahmad Yani No. 5, Singaraja', 'Menyediakan minuman boba dengan topping premium.', '081234567804', '10:00:00', '22:00:00', 18000, 40000, 0, '1785117311_boba1.jpeg', 'approved', '2026-07-18 01:49:13'),
(35, NULL, 1, 2, 5, 'Fresh Coconut Bali', 'Jl. Pantai Kuta No. 8, Badung', 'Es kelapa muda segar dan minuman tropis khas Bali.', '081234567805', '09:00:00', '20:00:00', 10000, 30000, 0, '1785117206_klpa1.jpeg', 'approved', '2026-07-18 01:49:13'),
(36, NULL, 1, 2, 6, 'Tea Time', 'Jl. Ngurah Rai No. 18, Klungkung', 'Aneka teh hangat, teh tarik, dan milk tea.', '081234567806', '08:00:00', '21:00:00', 7000, 25000, 0, '1785117143_estea1.jpeg', 'approved', '2026-07-18 01:49:13'),
(37, NULL, 1, 2, 7, 'Es Campur Nusantara', 'Jl. Raya Negara No. 12, Jembrana', 'Spesialis es campur, es teler, dan minuman tradisional.', '081234567807', '10:00:00', '18:00:00', 8000, 20000, 0, '1785117076_escmpr.jpeg', 'approved', '2026-07-18 01:49:13'),
(38, NULL, 1, 2, 8, 'Smoothie House', 'Jl. Raya Bangli No. 30, Bangli', 'Healthy smoothie dengan buah segar pilihan.', '081234567808', '08:00:00', '20:00:00', 15000, 40000, 0, '1785116946_mnmn1.jpg', 'approved', '2026-07-18 01:49:13'),
(39, NULL, 1, 2, 9, 'Lemon Fresh', 'Jl. Raya Karangasem No. 22, Karangasem', 'Minuman lemon segar, mojito, dan infused water.', '081234567809', '09:00:00', '21:00:00', 10000, 28000, 0, '1785215923_mntp.jpg', 'approved', '2026-07-18 01:49:13'),
(40, NULL, 1, 2, 1, 'Dahaga Bali', 'Jl. Hayam Wuruk No. 45, Denpasar', 'Warung minuman modern dengan kopi, teh, dan jus.', '081234567810', '08:00:00', '22:00:00', 8000, 35000, 0, '1785215393_mnmn22.jpeg', 'approved', '2026-07-18 01:49:13'),
(41, NULL, 1, 1, 8, 'warung makan pak made', 'Jl. Nusantara', 'menjual makanan', '082146789679', '08:00:00', '17:00:00', 15000, 30000, 0, '1785113572_pkde.jpeg', 'approved', '2026-07-27 00:52:52'),
(42, NULL, NULL, 3, 5, 'pak selamat', 'Jl. Nusantara', 'menjual kebutuhan sehari hari', '098765', '07:00:00', '21:00:00', 500, 300000, 0, '1785116594_smbk1.jpeg', 'approved', '2026-07-27 01:41:33'),
(45, NULL, NULL, 1, 5, 'Warung Makan Permanasari', 'Jl. Nusantara, Kubu, Kec. Bangli, Kabupaten Bangli, Bali 80613', 'menjual nasi campur enakk', '082146789679', '08:00:00', '18:00:00', 10000, 50000, 1, '1785461540_YlnhwQ1cL1xy.png', 'approved', '2026-07-30 04:07:31'),
(48, NULL, NULL, 1, 5, 'Warung Bu Krisnha', 'Jl. Nusantara No.146, Kubu, Kec. Bangli, Kabupaten Bangli, Bali 80613', NULL, '082146789679', '09:00:00', '18:00:00', 1000, 100000, 0, '1785472416_wHzmj8wbwPVO.png', 'approved', '2026-07-31 04:33:36'),
(49, NULL, 13, 2, 1, 'Ratih Teh segar', 'kjhgdrtyu', 'Teh segar adalah minuman aromatik dari seduhan daun Camellia sinensis berkualitas baru yang menghadirkan aroma alami yang menenangkan, rasa khas yang ringan atau sedikit sepat, serta memberikan sensasi dahaga yang hilang seketika saat diminum hangat maupun dingin', '09876789', '09:00:00', '21:00:00', 5000, 25000, 0, '1786334530_ryjSrTWXoB0j.jpeg', 'approved', '2026-07-31 05:24:01'),
(50, 49, 13, 2, 5, 'Ratih teh segar 2', 'Jl. Nusantara No.146, Kubu, Kec. Bangli, Kabupaten Bangli, Bali 80613', 'hbgvggv', '987678987678', '09:00:00', '21:00:00', 5000, 25000, 0, '1786334343_nr7ZxF7xhNxu.jpg', 'approved', '2026-07-31 07:03:13'),
(51, 45, NULL, 1, 8, 'Warung Makan Permanasari 2', 'Jalan Nusantara, Kayubihi, Bangli, Bali 80613, Indonesia', 'lakshdgshjksdhfgdhsjksdhfdjskl', '082146789679', '08:00:00', '18:00:00', 10000, 50000, 1, '1785481671_KZY0PLQG105Z.png', 'approved', '2026-07-31 07:07:51'),
(52, NULL, 16, 3, 5, 'Pan Dur', 'Jl. Brigjen Ngurah Rai, Kawan, Kec. Bangli, Kabupaten Bangli, Bali 80614', 'usaha retail kecil di lingkungan sekitar yang menyediakan bahan pokok dan kebutuhan harian seperti beras, gula, minyak goreng, dan telur dengan harga terjangkau serta lokasi yang dekat dari rumah warga', '00000000000', '07:00:00', '21:00:00', 1000, 50000, 0, '1786495924_LAJLzHBuon5d.jpeg', 'approved', '2026-08-12 00:50:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id_favorit`),
  ADD KEY `fk_favorit_warung` (`id_warung`),
  ADD KEY `favorit_id_user_foreign` (`id_user`);

--
-- Indexes for table `kabupaten`
--
ALTER TABLE `kabupaten`
  ADD PRIMARY KEY (`id_kabupaten`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `fk_menu_warung` (`id_warung`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_visits`
--
ALTER TABLE `page_visits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_visits_session_id_visited_date_unique` (`session_id`,`visited_date`),
  ADD KEY `page_visits_visited_date_index` (`visited_date`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD UNIQUE KEY `review_user_warung_unique` (`id_user`,`id_warung`),
  ADD KEY `fk_review_warung` (`id_warung`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `warung`
--
ALTER TABLE `warung`
  ADD PRIMARY KEY (`id_warung`),
  ADD KEY `fk_warung_kategori` (`id_kategori`),
  ADD KEY `fk_warung_kabupaten` (`id_kabupaten`),
  ADD KEY `warung_id_warung_induk_foreign` (`id_warung_induk`),
  ADD KEY `warung_id_user_foreign` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id_favorit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `kabupaten`
--
ALTER TABLE `kabupaten`
  MODIFY `id_kabupaten` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `page_visits`
--
ALTER TABLE `page_visits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `warung`
--
ALTER TABLE `warung`
  MODIFY `id_warung` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorit_warung` FOREIGN KEY (`id_warung`) REFERENCES `warung` (`id_warung`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_warung` FOREIGN KEY (`id_warung`) REFERENCES `warung` (`id_warung`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_warung` FOREIGN KEY (`id_warung`) REFERENCES `warung` (`id_warung`),
  ADD CONSTRAINT `review_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `warung`
--
ALTER TABLE `warung`
  ADD CONSTRAINT `fk_warung_kabupaten` FOREIGN KEY (`id_kabupaten`) REFERENCES `kabupaten` (`id_kabupaten`),
  ADD CONSTRAINT `fk_warung_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `warung_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `warung_id_warung_induk_foreign` FOREIGN KEY (`id_warung_induk`) REFERENCES `warung` (`id_warung`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
