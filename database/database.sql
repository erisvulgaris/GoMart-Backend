-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 01:13 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u819666995_grocery1_9`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_charge_taxes`
--

CREATE TABLE `additional_charge_taxes` (
  `id` int(11) UNSIGNED NOT NULL,
  `tax_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `deliverable_area_id` int(11) NOT NULL DEFAULT 0,
  `flat` varchar(150) DEFAULT NULL,
  `floor` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `area` text DEFAULT NULL,
  `city` text NOT NULL,
  `state` text DEFAULT NULL,
  `pincode` text DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_mobile` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `map_address` text DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `address_type` enum('Home','Work','Other') NOT NULL DEFAULT 'Home',
  `landmark` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(8) NOT NULL,
  `role_id` int(11) NOT NULL,
  `fname` text DEFAULT NULL,
  `lname` text DEFAULT NULL,
  `username` text NOT NULL,
  `mobile` text NOT NULL,
  `password` text NOT NULL,
  `token` text DEFAULT NULL,
  `reset_link_token` text DEFAULT NULL,
  `reset_token_exp_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `role_id`, `fname`, `lname`, `username`, `mobile`, `password`, `token`, `reset_link_token`, `reset_token_exp_date`) VALUES
(5, 1, 'Chirag', 'Pashine', 'admin@gmail.com', '09766846429', '$2y$10$kqpq3JQXmSsmgXjti9y5a.0sYmcvAKQAXxUICzK4oHna8p7lDKZqy', 'dtJNRXnTgkwBsIjlbnePG0:APA91bHFLBQffg2WEJo9JmpXSrb9KR5z_ZnP2LRFJgkVmaowkn4XBMpO9rT9OOAGMMP3z1ex6ZxBO7foE--sAmMnaulodVlOHjIQBnVO-K8Uh4hyN6w5J0I', '9549891462154$2y$10$o4tOFU.6R8d0FggeaSuf1.WC3/poJlUu7.vjlrBJxtrlEWk84550i4401662330761', '2025-11-16 10:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `ai_report_data`
--

CREATE TABLE `ai_report_data` (
  `id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `ai_insight` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(8) NOT NULL,
  `banner_img` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 for header, 1 for deal of the day, 2 for home, 3 for footer'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `home_screen_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `banner_type` varchar(20) NOT NULL DEFAULT 'offer',
  `content_id` int(10) UNSIGNED DEFAULT NULL,
  `redirect_url` varchar(500) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `placement` int(11) NOT NULL DEFAULT 0 COMMENT '0 for header, 1 for deal of the day, 2 for home, 3 for footer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `image` text NOT NULL,
  `row_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(11) NOT NULL,
  `guest_id` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `save_for_later` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_group_id` int(11) NOT NULL,
  `row_order` int(11) NOT NULL,
  `category_name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `category_img` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `is_bestseller_category` tinyint(2) NOT NULL DEFAULT 0,
  `is_it_have_warning` int(11) NOT NULL DEFAULT 0,
  `warning_content` text DEFAULT NULL,
  `warning_link` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_group`
--

CREATE TABLE `category_group` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `country_code` text NOT NULL,
  `validation_no` int(11) NOT NULL DEFAULT 10,
  `icon` text NOT NULL,
  `country_short` varchar(10) DEFAULT NULL,
  `currency_shortcut` varchar(100) NOT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(50) DEFAULT NULL,
  `country_name` varchar(100) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `language` varchar(50) DEFAULT NULL,
  `language_shortcut` varchar(50) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country_code`, `validation_no`, `icon`, `country_short`, `currency_shortcut`, `currency`, `currency_symbol`, `country_name`, `is_active`, `language`, `language_shortcut`, `timezone`) VALUES
(1, '+93', 9, '', 'AF', 'AFN', 'Afghani', '؋', 'Afghanistan', 0, 'Pashto, Dari', 'ps, fa', 'Asia/Kabul'),
(2, '+355', 9, '', 'AL', 'ALL', 'Lek', 'L', 'Albania', 0, 'Albanian', 'sq', 'Europe/Tirane'),
(3, '+213', 9, '', 'DZ', 'DZD', 'Dinar', 'دج', 'Algeria', 0, 'Arabic', 'ar', 'Africa/Algiers'),
(4, '+1-684', 7, '', 'AS', 'USD', 'US Dollar', '$', 'American Samoa', 0, 'English, Samoan', 'en, sm', 'Pacific/Pago_Pago'),
(5, '+376', 6, '', 'AD', 'EUR', 'Euro', '€', 'Andorra', 0, 'Catalan', 'ca', 'Europe/Andorra'),
(6, '+244', 9, '', 'AO', 'AOA', 'Kwanza', 'Kz', 'Angola', 0, 'Portuguese', 'pt', 'Africa/Luanda'),
(7, '+54', 10, '', 'AR', 'ARS', 'Peso', '$', 'Argentina', 0, 'Spanish', 'es', 'America/Argentina/Buenos_Aires'),
(8, '+374', 8, '', 'AM', 'AMD', 'Dram', '֏', 'Armenia', 0, 'Armenian', 'hy', 'Asia/Yerevan'),
(9, '+61', 9, '', 'AU', 'AUD', 'Australian Dollar', '$', 'Australia', 0, 'English', 'en', 'Australia/Sydney'),
(10, '+43', 10, '', 'AT', 'EUR', 'Euro', '€', 'Austria', 0, 'German', 'de', 'Europe/Vienna'),
(11, '+994', 9, '', 'AZ', 'AZN', 'Manat', '₼', 'Azerbaijan', 0, 'Azerbaijani', 'az', 'Asia/Baku'),
(12, '+973', 8, '', 'BH', 'BHD', 'Dinar', '.د.ب', 'Bahrain', 0, 'Arabic', 'ar', 'Asia/Bahrain'),
(13, '+880', 10, '', 'BD', 'BDT', 'Taka', '৳', 'Bangladesh', 0, 'Bengali', 'bn', 'Asia/Dhaka'),
(14, '+375', 9, '', 'BY', 'BYN', 'Belarusian Ruble', 'Br', 'Belarus', 0, 'Belarusian, Russian', 'be, ru', 'Europe/Minsk'),
(15, '+32', 9, '', 'BE', 'EUR', 'Euro', '€', 'Belgium', 0, 'Dutch, French, German', 'nl, fr, de', 'Europe/Brussels'),
(16, '+501', 7, '', 'BZ', 'BZD', 'Belize Dollar', '$', 'Belize', 0, 'English', 'en', 'America/Belize'),
(17, '+229', 8, '', 'BJ', 'XOF', 'West African CFA franc', 'CFA', 'Benin', 0, 'French', 'fr', 'Africa/Porto-Novo'),
(18, '+975', 8, '', 'BT', 'BTN', 'Ngultrum', 'Nu.', 'Bhutan', 0, 'Dzongkha', 'dz', 'Asia/Thimphu'),
(19, '+591', 8, '', 'BO', 'BOB', 'Boliviano', 'Bs.', 'Bolivia', 0, 'Spanish, Quechua', 'es, qu', 'America/La_Paz'),
(20, '+387', 8, '', 'BA', 'BAM', 'Convertible Mark', 'KM', 'Bosnia and Herzegovina', 0, 'Bosnian, Croatian, Serbian', 'bs, hr, sr', 'Europe/Sarajevo'),
(21, '+267', 7, '', 'BW', 'BWP', 'Pula', 'P', 'Botswana', 0, 'English, Tswana', 'en, tn', 'Africa/Gaborone'),
(22, '+55', 11, '', 'BR', 'BRL', 'Real', 'R$', 'Brazil', 0, 'Portuguese', 'pt', 'America/Sao_Paulo'),
(23, '+673', 7, '', 'BN', 'BND', 'Brunei Dollar', '$', 'Brunei', 0, 'Malay', 'ms', 'Asia/Brunei'),
(24, '+359', 9, '', 'BG', 'BGN', 'Lev', 'лв', 'Bulgaria', 0, 'Bulgarian', 'bg', 'Europe/Sofia'),
(25, '+226', 8, '', 'BF', 'XOF', 'West African CFA franc', 'CFA', 'Burkina Faso', 0, 'French', 'fr', 'Africa/Ouagadougou'),
(26, '+257', 8, '', 'BI', 'BIF', 'Burundian Franc', 'FBu', 'Burundi', 0, 'Kirundi, French', 'rn, fr', 'Africa/Bujumbura'),
(27, '+855', 9, '', 'KH', 'KHR', 'Riel', '៛', 'Cambodia', 0, 'Khmer', 'km', 'Asia/Phnom_Penh'),
(28, '+237', 9, '', 'CM', 'XAF', 'Central African CFA franc', 'FCFA', 'Cameroon', 0, 'French, English', 'fr, en', 'Africa/Douala'),
(29, '+1', 10, '', 'CA', 'CAD', 'Canadian Dollar', '$', 'Canada', 0, 'English, French', 'en, fr', 'America/Toronto'),
(30, '+238', 7, '', 'CV', 'CVE', 'Escudo', '$', 'Cape Verde', 0, 'Portuguese', 'pt', 'Atlantic/Cape_Verde'),
(31, '+56', 9, '', 'CL', 'CLP', 'Peso', '$', 'Chile', 0, 'Spanish', 'es', 'America/Santiago'),
(32, '+86', 11, '', 'CN', 'CNY', 'Yuan', '¥', 'China', 0, 'Mandarin', 'zh', 'Asia/Shanghai'),
(33, '+57', 10, '', 'CO', 'COP', 'Peso', '$', 'Colombia', 0, 'Spanish', 'es', 'America/Bogota'),
(34, '+506', 8, '', 'CR', 'CRC', 'Colon', '₡', 'Costa Rica', 0, 'Spanish', 'es', 'America/Costa_Rica'),
(35, '+385', 9, '', 'HR', 'EUR', 'Euro', '€', 'Croatia', 0, 'Croatian', 'hr', 'Europe/Zagreb'),
(36, '+53', 8, '', 'CU', 'CUP', 'Peso', '$', 'Cuba', 0, 'Spanish', 'es', 'America/Havana'),
(37, '+357', 8, '', 'CY', 'EUR', 'Euro', '€', 'Cyprus', 0, 'Greek, Turkish', 'el, tr', 'Asia/Nicosia'),
(38, '+420', 9, '', 'CZ', 'CZK', 'Koruna', 'Kč', 'Czech Republic', 0, 'Czech', 'cs', 'Europe/Prague'),
(39, '+45', 8, '', 'DK', 'DKK', 'Krone', 'kr', 'Denmark', 0, 'Danish', 'da', 'Europe/Copenhagen'),
(40, '+253', 6, '', 'DJ', 'DJF', 'Djiboutian Franc', 'Fdj', 'Djibouti', 0, 'French, Arabic', 'fr, ar', 'Africa/Djibouti'),
(41, '+1-767', 7, '', 'DM', 'XCD', 'East Caribbean Dollar', '$', 'Dominica', 0, 'English', 'en', 'America/Dominica'),
(42, '+1-809', 10, '', 'DO', 'DOP', 'Peso', 'RD$', 'Dominican Republic', 0, 'Spanish', 'es', 'America/Santo_Domingo'),
(43, '+593', 9, '', 'EC', 'USD', 'US Dollar', '$', 'Ecuador', 0, 'Spanish', 'es', 'America/Guayaquil'),
(44, '+20', 10, '', 'EG', 'EGP', 'Pound', '£', 'Egypt', 0, 'Arabic', 'ar', 'Africa/Cairo'),
(45, '+503', 8, '', 'SV', 'USD', 'US Dollar', '$', 'El Salvador', 0, 'Spanish', 'es', 'America/El_Salvador'),
(46, '+240', 9, '', 'GQ', 'XAF', 'Central African CFA franc', 'FCFA', 'Equatorial Guinea', 0, 'Spanish, French', 'es, fr', 'Africa/Malabo'),
(47, '+291', 7, '', 'ER', 'ERN', 'Nakfa', 'Nfk', 'Eritrea', 0, 'Tigrinya, Arabic', 'ti, ar', 'Africa/Asmara'),
(48, '+372', 7, '', 'EE', 'EUR', 'Euro', '€', 'Estonia', 0, 'Estonian', 'et', 'Europe/Tallinn'),
(49, '+251', 9, '', 'ET', 'ETB', 'Birr', 'Br', 'Ethiopia', 0, 'Amharic', 'am', 'Africa/Addis_Ababa'),
(50, '+679', 7, '', 'FJ', 'FJD', 'Fijian Dollar', '$', 'Fiji', 0, 'Fijian, English', 'fj, en', 'Pacific/Fiji'),
(51, '+358', 10, '', 'FI', 'EUR', 'Euro', '€', 'Finland', 0, 'Finnish, Swedish', 'fi, sv', 'Europe/Helsinki'),
(52, '+679', 7, '', 'FJ', 'FJD', 'Fijian Dollar', '$', 'Fiji', 0, 'Fijian, English', 'fj, en', 'Pacific/Fiji'),
(53, '+358', 10, '', 'FI', 'EUR', 'Euro', '€', 'Finland', 0, 'Finnish, Swedish', 'fi, sv', 'Europe/Helsinki'),
(54, '+33', 10, '', 'FR', 'EUR', 'Euro', '€', 'France', 0, 'French', 'fr', 'Europe/Paris'),
(55, '+241', 8, '', 'GA', 'XAF', 'Central African CFA franc', 'FCFA', 'Gabon', 0, 'French', 'fr', 'Africa/Libreville'),
(56, '+220', 7, '', 'GM', 'GMD', 'Dalasi', 'D', 'Gambia', 0, 'English', 'en', 'Africa/Banjul'),
(57, '+995', 9, '', 'GE', 'GEL', 'Lari', '₾', 'Georgia', 0, 'Georgian', 'ka', 'Asia/Tbilisi'),
(58, '+49', 10, '', 'DE', 'EUR', 'Euro', '€', 'Germany', 0, 'German', 'de', 'Europe/Berlin'),
(59, '+233', 9, '', 'GH', 'GHS', 'Cedi', '₵', 'Ghana', 0, 'English', 'en', 'Africa/Accra'),
(60, '+350', 7, '', 'GI', 'GIP', 'Gibraltar Pound', '£', 'Gibraltar', 0, 'English', 'en', 'Europe/Gibraltar'),
(61, '+30', 10, '', 'GR', 'EUR', 'Euro', '€', 'Greece', 0, 'Greek', 'el', 'Europe/Athens'),
(62, '+299', 8, '', 'GL', 'DKK', 'Danish Krone', 'kr', 'Greenland', 0, 'Greenlandic, Danish', 'kl, da', 'America/Nuuk'),
(63, '+1-473', 7, '', 'GD', 'XCD', 'East Caribbean Dollar', '$', 'Grenada', 0, 'English', 'en', 'America/Grenada'),
(64, '+590', 9, '', 'GP', 'EUR', 'Euro', '€', 'Guadeloupe', 0, 'French', 'fr', 'America/Guadeloupe'),
(65, '+1-671', 7, '', 'GU', 'USD', 'US Dollar', '$', 'Guam', 0, 'English', 'en', 'Pacific/Guam'),
(66, '+502', 8, '', 'GT', 'GTQ', 'Quetzal', 'Q', 'Guatemala', 0, 'Spanish', 'es', 'America/Guatemala'),
(67, '+224', 8, '', 'GN', 'GNF', 'Franc', 'FG', 'Guinea', 0, 'French', 'fr', 'Africa/Conakry'),
(68, '+245', 8, '', 'GW', 'XOF', 'Franc', 'FG', 'Guinea-Bissau', 0, 'Portuguese', 'pt', 'Africa/Bissau'),
(69, '+592', 7, '', 'GY', 'GYD', 'Guyanese Dollar', '$', 'Guyana', 0, 'English', 'en', 'America/Guyana'),
(70, '+509', 8, '', 'HT', 'HTG', 'Gourde', 'G', 'Haiti', 0, 'French, Haitian Creole', 'fr, ht', 'America/Port-au-Prince'),
(71, '+504', 8, '', 'HN', 'HNL', 'Lempira', 'L', 'Honduras', 0, 'Spanish', 'es', 'America/Tegucigalpa'),
(72, '+852', 8, '', 'HK', 'HKD', 'Hong Kong Dollar', '$', 'Hong Kong', 0, 'Chinese', 'zh', 'Asia/Hong_Kong'),
(73, '+36', 9, '', 'HU', 'HUF', 'Forint', 'Ft', 'Hungary', 0, 'Hungarian', 'hu', 'Europe/Budapest'),
(74, '+354', 7, '', 'IS', 'ISK', 'Icelandic Króna', 'kr', 'Iceland', 0, 'Icelandic', 'is', 'Atlantic/Reykjavik'),
(75, '+91', 10, '', 'IN', 'INR', 'Indian Rupee', '₹', 'India', 1, 'Hindi, English', 'hi, en', 'Asia/Kolkata'),
(76, '+62', 10, '', 'ID', 'IDR', 'Rupiah', 'Rp', 'Indonesia', 0, 'Indonesian', 'id', 'Asia/Jakarta'),
(77, '+98', 10, '', 'IR', 'IRR', 'Iranian Rial', '﷼', 'Iran', 0, 'Persian', 'fa', 'Asia/Tehran'),
(78, '+964', 10, '', 'IQ', 'IQD', 'Iraqi Dinar', 'ع.د', 'Iraq', 0, 'Arabic', 'ar', 'Asia/Baghdad'),
(79, '+353', 8, '', 'IE', 'EUR', 'Euro', '€', 'Ireland', 0, 'English, Irish', 'en, ga', 'Europe/Dublin'),
(80, '+972', 9, '', 'IL', 'ILS', 'New Shekel', '₪', 'Israel', 0, 'Hebrew', 'he', 'Asia/Jerusalem'),
(81, '+39', 10, '', 'IT', 'EUR', 'Euro', '€', 'Italy', 0, 'Italian', 'it', 'Europe/Rome'),
(82, '+44-1534', 8, '', 'JE', 'GBP', 'Pound Sterling', '£', 'Jersey', 0, 'English', 'en', 'Europe/Jersey'),
(83, '+962', 9, '', 'JO', 'JOD', 'Jordanian Dinar', 'د.أ', 'Jordan', 0, 'Arabic', 'ar', 'Asia/Amman'),
(84, '+7', 10, '', 'KZ', 'KZT', 'Tenge', '₸', 'Kazakhstan', 0, 'Kazakh, Russian', 'kk, ru', 'Asia/Almaty'),
(85, '+254', 9, '', 'KE', 'KES', 'Kenyan Shilling', 'KSh', 'Kenya', 0, 'Swahili, English', 'sw, en', 'Africa/Nairobi'),
(86, '+686', 7, '', 'KI', 'AUD', 'Australian Dollar', '$', 'Kiribati', 0, 'English', 'en', 'Pacific/Kiribati'),
(87, '+850', 9, '', 'KP', 'KPW', 'North Korean Won', '₩', 'North Korea', 0, 'Korean', 'ko', 'Asia/Pyongyang'),
(88, '+82', 10, '', 'KR', 'KRW', 'South Korean Won', '₩', 'South Korea', 0, 'Korean', 'ko', 'Asia/Seoul'),
(89, '+965', 8, '', 'KW', 'KWD', 'Kuwaiti Dinar', 'د.ك', 'Kuwait', 0, 'Arabic', 'ar', 'Asia/Kuwait'),
(90, '+996', 9, '', 'KG', 'KGS', 'Som', 'с', 'Kyrgyzstan', 0, 'Kyrgyz', 'ky', 'Asia/Bishkek'),
(91, '+856', 8, '', 'LA', 'LAK', 'Kip', '₭', 'Laos', 0, 'Lao', 'lo', 'Asia/Vientiane'),
(92, '+371', 8, '', 'LV', 'EUR', 'Euro', '€', 'Latvia', 0, 'Latvian', 'lv', 'Europe/Riga'),
(93, '+961', 8, '', 'LB', 'LBP', 'Lebanese Pound', 'ل.ل', 'Lebanon', 0, 'Arabic', 'ar', 'Asia/Beirut'),
(94, '+266', 7, '', 'LS', 'LSL', 'Loti', 'L', 'Lesotho', 0, 'Sesotho', 'st', 'Africa/Maseru'),
(95, '+231', 7, '', 'LR', 'LRD', 'Liberian Dollar', '$', 'Liberia', 0, 'English', 'en', 'Africa/Monrovia'),
(96, '+218', 8, '', 'LY', 'LYD', 'Libyan Dinar', 'د.ل', 'Libya', 0, 'Arabic', 'ar', 'Africa/Tripoli'),
(97, '+423', 8, '', 'LI', 'CHF', 'Swiss Franc', 'CHF', 'Liechtenstein', 0, 'German', 'de', 'Europe/Vaduz'),
(98, '+370', 8, '', 'LT', 'EUR', 'Euro', '€', 'Lithuania', 0, 'Lithuanian', 'lt', 'Europe/Vilnius'),
(99, '+352', 8, '', 'LU', 'EUR', 'Euro', '€', 'Luxembourg', 0, 'Luxembourgish, French, German', 'lb, fr, de', 'Europe/Luxembourg'),
(100, '+853', 8, '', 'MO', 'MOP', 'Macanese Pataca', 'MOP$', 'Macau', 0, 'Chinese, Portuguese', 'zh, pt', 'Asia/Macau'),
(101, '+389', 8, '', 'MK', 'MKD', 'Denar', 'ден', 'North Macedonia', 0, 'Macedonian', 'mk', 'Europe/Skopje'),
(102, '+261', 9, '', 'MG', 'MGA', 'Ariary', 'Ar', 'Madagascar', 0, 'Malagasy, French', 'mg, fr', 'Indian/Antananarivo'),
(103, '+265', 9, '', 'MW', 'MWK', 'Malawian Kwacha', 'MK', 'Malawi', 0, 'Chichewa', 'ny', 'Africa/Blantyre'),
(104, '+60', 9, '', 'MY', 'MYR', 'Ringgit', 'RM', 'Malaysia', 0, 'Malay', 'ms', 'Asia/Kuala_Lumpur'),
(105, '+960', 7, '', 'MV', 'MVR', 'Rufiyaa', 'Rf', 'Maldives', 0, 'Dhivehi', 'dv', 'Indian/Maldives'),
(106, '+223', 8, '', 'ML', 'XOF', 'West African CFA franc', 'CFA', 'Mali', 0, 'French', 'fr', 'Africa/Bamako'),
(107, '+356', 8, '', 'MT', 'EUR', 'Euro', '€', 'Malta', 0, 'Maltese, English', 'mt, en', 'Europe/Malta'),
(108, '+692', 7, '', 'MH', 'USD', 'US Dollar', '$', 'Marshall Islands', 0, 'Marshallese, English', 'mh, en', 'Pacific/Majuro'),
(109, '+596', 9, '', 'MQ', 'EUR', 'Euro', '€', 'Martinique', 0, 'French', 'fr', 'America/Martinique'),
(110, '+222', 9, '', 'MR', 'MRU', 'Ouguiya', 'UM', 'Mauritania', 0, 'Arabic', 'ar', 'Africa/Nouakchott'),
(111, '+230', 10, '', 'MU', 'MUR', 'Mauritian Rupee', '₨', 'Mauritius', 0, 'English, French', 'en, fr', 'Indian/Mauritius'),
(112, '+262', 8, '', 'YT', 'EUR', 'Euro', '€', 'Mayotte', 0, 'French', 'fr', 'Indian/Mayotte'),
(113, '+52', 10, '', 'MX', 'MXN', 'Mexican Peso', '$', 'Mexico', 0, 'Spanish', 'es', 'America/Mexico_City'),
(114, '+691', 7, '', 'FM', 'USD', 'US Dollar', '$', 'Micronesia', 0, 'English', 'en', 'Pacific/Chuuk'),
(115, '+373', 8, '', 'MD', 'MDL', 'Moldovan Leu', 'L', 'Moldova', 0, 'Romanian', 'ro', 'Europe/Chisinau'),
(116, '+377', 8, '', 'MC', 'EUR', 'Euro', '€', 'Monaco', 0, 'French', 'fr', 'Europe/Monaco'),
(117, '+976', 8, '', 'MN', 'MNT', 'Tugrik', '₮', 'Mongolia', 0, 'Mongolian', 'mn', 'Asia/Ulaanbaatar'),
(118, '+382', 8, '', 'ME', 'EUR', 'Euro', '€', 'Montenegro', 0, 'Montenegrin', 'sr', 'Europe/Podgorica'),
(119, '+1-664', 7, '', 'MS', 'XCD', 'East Caribbean Dollar', '$', 'Montserrat', 0, 'English', 'en', 'America/Montserrat'),
(120, '+212', 10, '', 'MA', 'MAD', 'Dirham', 'د.م.', 'Morocco', 0, 'Arabic, Berber', 'ar, ber', 'Africa/Casablanca'),
(121, '+258', 9, '', 'MZ', 'MZN', 'Metical', 'MT', 'Mozambique', 0, 'Portuguese', 'pt', 'Africa/Maputo'),
(122, '+95', 9, '', 'MM', 'MMK', 'Kyat', 'Ks', 'Myanmar', 0, 'Burmese', 'my', 'Asia/Yangon'),
(123, '+264', 7, '', 'NA', 'NAD', 'Namibian Dollar', '$', 'Namibia', 0, 'English, Afrikaans', 'en, af', 'Africa/Windhoek'),
(124, '+674', 7, '', 'NR', 'AUD', 'Australian Dollar', '$', 'Nauru', 0, 'Nauruan', 'na', 'Pacific/Nauru'),
(125, '+977', 10, '', 'NP', 'NPR', 'Nepalese Rupee', '₨', 'Nepal', 0, 'Nepali', 'ne', 'Asia/Kathmandu'),
(126, '+31', 10, '', 'NL', 'EUR', 'Euro', '€', 'Netherlands', 0, 'Dutch', 'nl', 'Europe/Amsterdam'),
(127, '+687', 8, '', 'NC', 'XPF', 'Pacific Franc', '₣', 'New Caledonia', 0, 'French', 'fr', 'Pacific/Noumea'),
(128, '+64', 9, '', 'NZ', 'NZD', 'New Zealand Dollar', '$', 'New Zealand', 0, 'English', 'en', 'Pacific/Auckland'),
(129, '+505', 8, '', 'NI', 'NIO', 'Córdoba', 'C$', 'Nicaragua', 0, 'Spanish', 'es', 'America/Managua'),
(130, '+227', 8, '', 'NE', 'XOF', 'West African CFA franc', 'CFA', 'Niger', 0, 'French', 'fr', 'Africa/Niamey'),
(131, '+234', 10, '', 'NG', 'NGN', 'Naira', '₦', 'Nigeria', 0, 'English', 'en', 'Africa/Lagos'),
(132, '+683', 7, '', 'NU', 'NZD', 'New Zealand Dollar', '$', 'Niue', 0, 'Niuean', 'niu', 'Pacific/Niue'),
(133, '+672', 7, '', 'NF', 'AUD', 'Australian Dollar', '$', 'Norfolk Island', 0, 'English', 'en', 'Pacific/Norfolk'),
(134, '+1-670', 7, '', 'MP', 'USD', 'US Dollar', '$', 'Northern Mariana Islands', 0, 'English, Chamorro', 'en, ch', 'Pacific/Saipan'),
(135, '+47', 8, '', 'NO', 'NOK', 'Krone', 'kr', 'Norway', 0, 'Norwegian', 'no', 'Europe/Oslo'),
(136, '+968', 8, '', 'OM', 'OMR', 'Rial', 'ر.ع.', 'Oman', 0, 'Arabic', 'ar', 'Asia/Muscat'),
(137, '+92', 10, '', 'PK', 'PKR', 'Pakistani Rupee', '₨', 'Pakistan', 0, 'Urdu, English', 'ur, en', 'Asia/Karachi'),
(138, '+680', 7, '', 'PW', 'USD', 'US Dollar', '$', 'Palau', 0, 'Palauan, English', 'pau, en', 'Pacific/Palau'),
(139, '+507', 8, '', 'PA', 'PAB', 'Balboa', 'B/.', 'Panama', 0, 'Spanish', 'es', 'America/Panama'),
(140, '+675', 7, '', 'PG', 'PGK', 'Kina', 'K', 'Papua New Guinea', 0, 'English, Hiri Motu, Tok Pisin', 'en, ho, tp', 'Pacific/Port_Moresby'),
(141, '+595', 9, '', 'PY', 'PYG', 'Guarani', '₲', 'Paraguay', 0, 'Spanish, Guarani', 'es, gn', 'America/Asuncion'),
(142, '+51', 9, '', 'PE', 'PEN', 'Sol', 'S/', 'Peru', 0, 'Spanish, Quechua', 'es, qu', 'America/Lima'),
(143, '+63', 10, '', 'PH', 'PHP', 'Philippine Peso', '₱', 'Philippines', 0, 'Filipino, English', 'tl, en', 'Asia/Manila'),
(144, '+64', 7, '', 'PN', 'NZD', 'New Zealand Dollar', '$', 'Pitcairn Islands', 0, 'English', 'en', 'Pacific/Pitcairn'),
(145, '+48', 9, '', 'PL', 'PLN', 'Zloty', 'zł', 'Poland', 0, 'Polish', 'pl', 'Europe/Warsaw'),
(146, '+351', 9, '', 'PT', 'EUR', 'Euro', '€', 'Portugal', 0, 'Portuguese', 'pt', 'Europe/Lisbon'),
(147, '+1-787', 10, '', 'PR', 'USD', 'US Dollar', '$', 'Puerto Rico', 0, 'Spanish, English', 'es, en', 'America/Puerto_Rico'),
(148, '+974', 8, '', 'QA', 'QAR', 'Qatari Riyal', 'ر.ق', 'Qatar', 0, 'Arabic', 'ar', 'Asia/Qatar'),
(149, '+262', 9, '', 'RE', 'EUR', 'Euro', '€', 'Réunion', 0, 'French', 'fr', 'Indian/Reunion'),
(150, '+40', 10, '', 'RO', 'RON', 'Leu', 'lei', 'Romania', 0, 'Romanian', 'ro', 'Europe/Bucharest'),
(151, '+7', 10, '', 'RU', 'RUB', 'Ruble', '₽', 'Russia', 0, 'Russian', 'ru', 'Europe/Moscow'),
(152, '+250', 9, '', 'RW', 'RWF', 'Rwandan Franc', 'Fr', 'Rwanda', 0, 'Kinyarwanda, French, English', 'rw, fr, en', 'Africa/Kigali'),
(153, '+590', 7, '', 'BL', 'EUR', 'Euro', '€', 'Saint Barthélemy', 0, 'French', 'fr', 'America/St_Barthelemy'),
(154, '+290', 7, '', 'SH', 'SHP', 'Saint Helena Pound', '£', 'Saint Helena', 0, 'English', 'en', 'Atlantic/St_Helena'),
(155, '+1-869', 7, '', 'KN', 'XCD', 'East Caribbean Dollar', '$', 'Saint Kitts and Nevis', 0, 'English', 'en', 'America/St_Kitts'),
(156, '+1-758', 7, '', 'LC', 'XCD', 'East Caribbean Dollar', '$', 'Saint Lucia', 0, 'English', 'en', 'America/St_Lucia'),
(157, '+590', 7, '', 'MF', 'EUR', 'Euro', '€', 'Saint Martin', 0, 'French', 'fr', 'America/Marigot'),
(158, '+508', 7, '', 'PM', 'EUR', 'Euro', '€', 'Saint Pierre and Miquelon', 0, 'French', 'fr', 'America/St_Pierre'),
(159, '+1-784', 7, '', 'VC', 'XCD', 'East Caribbean Dollar', '$', 'Saint Vincent and the Grenadines', 0, 'English', 'en', 'America/St_Vincent'),
(160, '+685', 7, '', 'WS', 'WST', 'Tala', 'WS$', 'Samoa', 0, 'Samoan', 'sm', 'Pacific/Apia'),
(161, '+378', 8, '', 'SM', 'EUR', 'Euro', '€', 'San Marino', 0, 'Italian', 'it', 'Europe/San_Marino'),
(162, '+239', 9, '', 'ST', 'STN', 'Dobra', 'Db', 'São Tomé and Príncipe', 0, 'Portuguese', 'pt', 'Africa/Sao_Tome'),
(163, '+966', 9, '', 'SA', 'SAR', 'Saudi Riyal', 'ر.س', 'Saudi Arabia', 0, 'Arabic', 'ar', 'Asia/Riyadh'),
(164, '+221', 8, '', 'SN', 'XOF', 'West African CFA franc', 'CFA', 'Senegal', 0, 'French', 'fr', 'Africa/Dakar'),
(165, '+381', 9, '', 'RS', 'RSD', 'Serbian Dinar', 'дин.', 'Serbia', 0, 'Serbian', 'sr', 'Europe/Belgrade'),
(166, '+248', 4, '', 'SC', 'SCR', 'Seychellois Rupee', '₨', 'Seychelles', 0, 'Seychellois Creole, English, French', 'crs, en, fr', 'Indian/Ocean'),
(167, '+232', 8, '', 'SL', 'SLL', 'Leone', 'Le', 'Sierra Leone', 0, 'English', 'en', 'Africa/Freetown'),
(168, '+65', 8, '', 'SG', 'SGD', 'Singapore Dollar', '$', 'Singapore', 0, 'English, Malay, Mandarin, Tamil', 'en, ms, zh, ta', 'Asia/Singapore'),
(169, '+1-721', 7, '', 'SX', 'ANG', 'Netherlands Antillean Guilder', 'ƒ', 'Sint Maarten', 0, 'Dutch, English', 'nl, en', 'America/Curacao'),
(170, '+421', 8, '', 'SK', 'EUR', 'Euro', '€', 'Slovakia', 0, 'Slovak', 'sk', 'Europe/Bratislava'),
(171, '+386', 8, '', 'SI', 'EUR', 'Euro', '€', 'Slovenia', 0, 'Slovene', 'sl', 'Europe/Ljubljana'),
(172, '+677', 7, '', 'SB', 'SBD', 'Solomon Islands Dollar', '$', 'Solomon Islands', 0, 'English', 'en', 'Pacific/Guadalcanal'),
(173, '+252', 9, '', 'SO', 'SOS', 'Somali Shilling', 'Sh', 'Somalia', 0, 'Somali', 'so', 'Africa/Mogadishu'),
(174, '+27', 10, '', 'ZA', 'ZAR', 'Rand', 'R', 'South Africa', 0, 'Afrikaans, English', 'af, en', 'Africa/Johannesburg'),
(175, '+211', 9, '', 'SS', 'SSP', 'South Sudanese Pound', '£', 'South Sudan', 0, 'English', 'en', 'Africa/Juba'),
(176, '+34', 10, '', 'ES', 'EUR', 'Euro', '€', 'Spain', 0, 'Spanish', 'es', 'Europe/Madrid'),
(177, '+94', 9, '', 'LK', 'LKR', 'Sri Lankan Rupee', '₨', 'Sri Lanka', 0, 'Sinhala, Tamil', 'si, ta', 'Asia/Colombo'),
(178, '+249', 9, '', 'SD', 'SDG', 'Sudanese Pound', 'ج.س', 'Sudan', 0, 'Arabic', 'ar', 'Africa/Khartoum'),
(179, '+597', 9, '', 'SR', 'SRD', 'Surinamese Dollar', '$', 'Suriname', 0, 'Dutch', 'nl', 'America/Paramaribo'),
(180, '+268', 7, '', 'SZ', 'SZL', 'Lilangeni', 'E', 'Eswatini', 0, 'Swazi', 'ss', 'Africa/Mbabane'),
(181, '+46', 8, '', 'SE', 'SEK', 'Krona', 'kr', 'Sweden', 0, 'Swedish', 'sv', 'Europe/Stockholm'),
(182, '+41', 8, '', 'CH', 'CHF', 'Swiss Franc', 'CHF', 'Switzerland', 0, 'German, French, Italian', 'de, fr, it', 'Europe/Zurich'),
(183, '+963', 9, '', 'SY', 'SYP', 'Syrian Pound', 'ل.س', 'Syria', 0, 'Arabic', 'ar', 'Asia/Damascus'),
(184, '+886', 8, '', 'TW', 'TWD', 'New Taiwan Dollar', 'NT$', 'Taiwan', 0, 'Chinese', 'zh', 'Asia/Taipei'),
(185, '+992', 9, '', 'TJ', 'TJS', 'Somoni', 'SM', 'Tajikistan', 0, 'Tajik', 'tg', 'Asia/Dushanbe'),
(186, '+255', 10, '', 'TZ', 'TZS', 'Tanzanian Shilling', 'TSh', 'Tanzania', 0, 'Swahili, English', 'sw, en', 'Africa/Dar_es_Salaam'),
(187, '+66', 9, '', 'TH', 'THB', 'Baht', '฿', 'Thailand', 0, 'Thai', 'th', 'Asia/Bangkok'),
(188, '+670', 9, '', 'TL', 'USD', 'US Dollar', '$', 'Timor-Leste', 0, 'Tetum, Portuguese', 'tet, pt', 'Asia/Dili'),
(189, '+228', 8, '', 'TG', 'XOF', 'West African CFA franc', 'CFA', 'Togo', 0, 'French', 'fr', 'Africa/Lomé'),
(190, '+690', 7, '', 'TK', 'NZD', 'New Zealand Dollar', '$', 'Tokelau', 0, 'Tokelauan', 'tkl', 'Pacific/Fakaofo'),
(191, '+676', 7, '', 'TO', 'TOP', 'Tongan Paʻanga', 'T$', 'Tonga', 0, 'Tongan', 'to', 'Pacific/Tongatapu'),
(192, '+1-868', 7, '', 'TT', 'TTD', 'Trinidad and Tobago Dollar', '$', 'Trinidad and Tobago', 0, 'English', 'en', 'America/Port_of_Spain'),
(193, '+216', 9, '', 'TN', 'TND', 'Tunisian Dinar', 'د.ت', 'Tunisia', 0, 'Arabic', 'ar', 'Africa/Tunis'),
(194, '+90', 10, '', 'TR', 'TRY', 'Turkish Lira', '₺', 'Turkey', 0, 'Turkish', 'tr', 'Europe/Istanbul'),
(195, '+993', 10, '', 'TM', 'TMT', 'Manat', 'm', 'Turkmenistan', 0, 'Turkmen', 'tk', 'Asia/Ashgabat'),
(196, '+1-649', 7, '', 'TC', 'USD', 'US Dollar', '$', 'Turks and Caicos Islands', 0, 'English', 'en', 'America/Grand_Turk'),
(197, '+688', 7, '', 'TV', 'AUD', 'Australian Dollar', '$', 'Tuvalu', 0, 'Tuvaluan', 'tuv', 'Pacific/Funafuti'),
(198, '+256', 9, '', 'UG', 'UGX', 'Ugandan Shilling', 'USh', 'Uganda', 0, 'English, Swahili', 'en, sw', 'Africa/Kampala'),
(199, '+380', 10, '', 'UA', 'UAH', 'Hryvnia', '₴', 'Ukraine', 0, 'Ukrainian', 'uk', 'Europe/Kiev'),
(200, '+971', 9, '', 'AE', 'AED', 'Dirham', 'د.إ', 'United Arab Emirates', 0, 'Arabic', 'ar', 'Asia/Dubai'),
(201, '+44', 8, '', 'GB', 'GBP', 'Pound Sterling', '£', 'United Kingdom', 0, 'English', 'en', 'Europe/London'),
(202, '+1', 10, '', 'US', 'USD', 'US Dollar', '$', 'United States', 0, 'English', 'en', 'America/New_York'),
(203, '+1', 7, '', 'UM', 'USD', 'US Dollar', '$', 'United States Minor Outlying Islands', 0, 'English', 'en', 'Pacific/Wake'),
(204, '+598', 8, '', 'UY', 'UYU', 'Uruguayan Peso', '$', 'Uruguay', 0, 'Spanish', 'es', 'America/Montevideo'),
(205, '+998', 9, '', 'UZ', 'UZS', 'Som', 'лв', 'Uzbekistan', 0, 'Uzbek', 'uz', 'Asia/Tashkent'),
(206, '+678', 7, '', 'VU', 'VUV', 'Vatu', 'Vt', 'Vanuatu', 0, 'Bislama, English, French', 'bi, en, fr', 'Pacific/Efate'),
(207, '+379', 8, '', 'VA', 'EUR', 'Euro', '€', 'Vatican City', 0, 'Italian', 'it', 'Europe/Vatican'),
(208, '+58', 10, '', 'VE', 'VES', 'Bolívar', 'Bs', 'Venezuela', 0, 'Spanish', 'es', 'America/Caracas'),
(209, '+84', 9, '', 'VN', 'VND', 'Dong', '₫', 'Vietnam', 0, 'Vietnamese', 'vi', 'Asia/Ho_Chi_Minh'),
(210, '+1-284', 7, '', 'VG', 'USD', 'US Dollar', '$', 'Virgin Islands, British', 0, 'English', 'en', 'America/Tortola'),
(211, '+1-340', 7, '', 'VI', 'USD', 'US Dollar', '$', 'Virgin Islands, U.S.', 0, 'English', 'en', 'America/St_Thomas'),
(212, '+681', 7, '', 'WF', 'XPF', 'CFP Franc', '₣', 'Wallis and Futuna', 0, 'French', 'fr', 'Pacific/Wallis'),
(213, '+212', 9, '', 'EH', 'MAD', 'Moroccan Dirham', 'د.م.', 'Western Sahara', 0, 'Arabic', 'ar', 'Africa/El_Aaiun'),
(214, '+967', 9, '', 'YE', 'YER', 'Yemeni Rial', '﷼', 'Yemen', 0, 'Arabic', 'ar', 'Asia/Aden'),
(215, '+260', 10, '', 'ZM', 'ZMW', 'Zambian Kwacha', 'ZK', 'Zambia', 0, 'English', 'en', 'Africa/Lusaka'),
(216, '+263', 10, '', 'ZW', 'ZWL', 'Zimbabwean Dollar', '$', 'Zimbabwe', 0, 'English', 'en', 'Africa/Harare');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '0 for All user, other than 0 for specific user	',
  `coupon_img` text NOT NULL,
  `coupon_type` int(11) NOT NULL DEFAULT 2 COMMENT '1 = percentage, 2 = value	',
  `is_multitimes` int(11) NOT NULL COMMENT 'single = 0, multi = 1',
  `date` date NOT NULL,
  `description` text NOT NULL,
  `value` text NOT NULL,
  `coupon_code` text NOT NULL COMMENT 'coupon_code',
  `status` int(11) NOT NULL DEFAULT 1,
  `coupon_title` text NOT NULL,
  `min_order_amount` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliverable_area`
--

CREATE TABLE `deliverable_area` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `deliverable_area_title` text NOT NULL,
  `boundry_points` text DEFAULT NULL,
  `radius` text DEFAULT NULL,
  `geolocation_type` text DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `boundary_points_web` text DEFAULT NULL,
  `min_amount_for_free_delivery` int(11) NOT NULL DEFAULT 0,
  `delivery_charge_method` text NOT NULL,
  `fixed_charge` int(11) NOT NULL DEFAULT 0,
  `per_km_charge` int(11) NOT NULL DEFAULT 0,
  `range_wise_charges` text NOT NULL,
  `time_to_travel` int(11) NOT NULL DEFAULT 0,
  `max_deliverable_distance` int(11) NOT NULL DEFAULT 0,
  `base_delivery_time` int(11) NOT NULL DEFAULT 0 COMMENT 'it will helpful for quickcommerce time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy`
--

CREATE TABLE `delivery_boy` (
  `id` int(11) UNSIGNED NOT NULL,
  `admin_id` int(11) UNSIGNED DEFAULT NULL,
  `city_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `password` text NOT NULL,
  `balance` double(8,2) DEFAULT 0.00 COMMENT 'wallet-earning balance',
  `cash_collection_amount` double(8,2) NOT NULL COMMENT 'Customers order cash collection',
  `address` text NOT NULL,
  `bonus_type` int(11) DEFAULT 0 COMMENT '0 = fixed or Salaried, 1 = Commission',
  `bonus_percentage` double DEFAULT 0,
  `bonus_min_amount` double DEFAULT 0,
  `bonus_max_amount` double DEFAULT 0,
  `driving_license` text DEFAULT NULL,
  `national_identity_card` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `bank_account_number` text DEFAULT NULL,
  `bank_name` text DEFAULT NULL,
  `account_name` text DEFAULT NULL,
  `ifsc_code` text DEFAULT NULL,
  `other_payment_information` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'by Admin',
  `is_available` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'depricated',
  `app_key` text DEFAULT NULL,
  `pincode` int(11) DEFAULT NULL,
  `cash_received` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'by admin',
  `a_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'active, inactive by delivery boy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy_fund_transfer`
--

CREATE TABLE `delivery_boy_fund_transfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `type` text NOT NULL COMMENT 'credit | debit',
  `opening_balance` double(8,2) NOT NULL DEFAULT 0.00,
  `closing_balance` double(8,2) NOT NULL,
  `amount` double(8,2) NOT NULL COMMENT 'its may be order commission',
  `status` text NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy_notification`
--

CREATE TABLE `delivery_boy_notification` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy_transaction`
--

CREATE TABLE `delivery_boy_transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `delivery_boy_id` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL COMMENT 'credit | debit',
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `status` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='its cash collection';

-- --------------------------------------------------------

--
-- Table structure for table `delivery_charge_taxes`
--

CREATE TABLE `delivery_charge_taxes` (
  `id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_tracking`
--

CREATE TABLE `delivery_tracking` (
  `id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `heading` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_token`
--

CREATE TABLE `device_token` (
  `id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '1= admin, 2 = customer, 3 = delivery boy, 4 = seller',
  `user_id` int(11) NOT NULL,
  `app_key` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `row_order` int(11) NOT NULL DEFAULT 1,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(8) NOT NULL,
  `order_id` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `rate` text NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `header_category`
--

CREATE TABLE `header_category` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `icon_library` int(11) NOT NULL DEFAULT 4 COMMENT '1= MaterialDesignIcons, 2=FontAwesome,3=Ionicons, 4=MaterialIcons',
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `video` varchar(1024) DEFAULT NULL,
  `image` varchar(1024) DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE `home` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `deliverable_area_id` int(11) NOT NULL DEFAULT 0,
  `product_show_limit` int(11) NOT NULL DEFAULT 20,
  `is_active` int(11) NOT NULL,
  `sort_by` text DEFAULT NULL COMMENT 'best_selling, default, low_to_high, high_to_low, maximum_discount, best_rated, alphabetical '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_screens`
--

CREATE TABLE `home_screens` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `header_type` enum('gradient','gif') NOT NULL DEFAULT 'gradient',
  `gradient_start` varchar(20) DEFAULT NULL COMMENT 'Hex e.g. #56ab2f',
  `gradient_end` varchar(20) DEFAULT NULL COMMENT 'Hex e.g. #a8e063',
  `header_gif` varchar(255) DEFAULT NULL COMMENT 'Relative path to GIF/image',
  `overlay_text_color` varchar(20) DEFAULT NULL COMMENT 'Text color on gif/image header (e.g. #FFFFFF)',
  `tab_icon` varchar(255) DEFAULT NULL COMMENT 'Relative path to tab icon',
  `tab_active_color` varchar(20) DEFAULT NULL COMMENT 'Tab label color when active',
  `tab_inactive_color` varchar(20) DEFAULT NULL COMMENT 'Tab label color when inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

INSERT INTO home_screens (`id`, `name`, `slug`, `is_default`, `status`, `sort_order`, `header_type`, `gradient_start`, `gradient_end`, `header_gif`, `overlay_text_color`, `tab_icon`, `tab_active_color`, `tab_inactive_color`) 
VALUES (1, 'Home', 'home', '1', '1', '0', 'gradient', '#0fd5be', '#cbfcf1', NULL, '#ffffff', 'uploads/home_screens/1773229881_7c1903eed23b3e97cb18.png', '#000000', '#888888');

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `language` varchar(50) NOT NULL,
  `lang_short` varchar(5) NOT NULL,
  `is_rtl` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `is_default` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `language`, `lang_short`, `is_rtl`, `is_active`, `is_default`) VALUES
(1, 'English', 'en', 0, 1, 1),
(2, 'हिन्दी', 'hi', 0, 1, 0),
(3, 'عربي', 'ar', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `title` text NOT NULL,
  `img` text DEFAULT NULL,
  `msg` text NOT NULL,
  `date` datetime NOT NULL,
  `is_system_generated` int(11) NOT NULL DEFAULT 1 COMMENT '1 = yes, 0 = no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(8) NOT NULL,
  `order_id` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `order_delivery_otp` varchar(255) NOT NULL DEFAULT '1234',
  `delivery_date` datetime DEFAULT NULL,
  `timeslot` text DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `delivery_boy_id` int(11) NOT NULL DEFAULT 0,
  `transaction_id` text DEFAULT NULL,
  `delivery_sign` text DEFAULT NULL,
  `subtotal` double(8,2) NOT NULL DEFAULT 0.00,
  `tax` double(8,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` double(8,2) NOT NULL DEFAULT 0.00,
  `used_wallet_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `coupon_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `additional_charge` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `delivery_method` varchar(30) NOT NULL COMMENT 'scheduledDelivery, homeDelivery, selfPickup, ',
  `payment_json` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `order_delivery_otp_verification` tinyint(4) NOT NULL DEFAULT 0,
  `is_pos_order` tinyint(1) DEFAULT 0 COMMENT '1 = POS order, 0 = regular order',
  `pos_payment_method_id` int(11) NOT NULL DEFAULT 0,
  `pos_by` int(11) NOT NULL DEFAULT 0 COMMENT '1 = admin, 2 = seller, 0 for normal order',
  `pos_created_by` int(11) DEFAULT NULL COMMENT 'Admin/Seller user ID who created POS order',
  `additional_discount` double(8,2) DEFAULT 0.00,
  `additional_discount_type` varchar(20) DEFAULT NULL COMMENT 'percentage or flat',
  `customer_name` varchar(255) DEFAULT NULL COMMENT 'For quick POS customer entry',
  `customer_mobile` varchar(20) DEFAULT NULL COMMENT 'For quick POS customer entry',
  `delivery_tip_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `delivery_instruction` text DEFAULT NULL,
  `billing_gst` varchar(20) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_additional_charges`
--

CREATE TABLE `order_additional_charges` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `charge_name` varchar(255) NOT NULL,
  `charge_amount` double(8,2) NOT NULL,
  `tax_name` varchar(100) DEFAULT NULL,
  `tax_percentage` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_charge_taxes`
--

CREATE TABLE `order_charge_taxes` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `charge_type` enum('delivery','additional','tip') NOT NULL,
  `tax_name` varchar(100) NOT NULL,
  `tax_percentage` decimal(5,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  `product_name` text DEFAULT NULL,
  `product_variant_name` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `discounted_price` double(8,2) NOT NULL,
  `tax_amount` double(8,2) NOT NULL,
  `tax_percentage` double(8,2) NOT NULL,
  `discount` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_taxes`
--

CREATE TABLE `order_product_taxes` (
  `id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `tax_name` varchar(255) NOT NULL,
  `tax_percentage` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_request`
--

CREATE TABLE `order_return_request` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_products_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1= pending,  2= approved, 3= rejected, 4 = returned to deliveryboy, 5= returned to seller',
  `remark` text DEFAULT NULL,
  `delivery_boy_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `orders_id` varchar(191) NOT NULL,
  `order_products_id` int(11) DEFAULT NULL,
  `status` varchar(191) NOT NULL,
  `created_by` int(11) NOT NULL,
  `user_type` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_lists`
--

CREATE TABLE `order_status_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) NOT NULL,
  `color` text NOT NULL COMMENT 'user for Admin panel',
  `text_color` varchar(20) NOT NULL COMMENT 'use for Website',
  `bg_color` varchar(20) NOT NULL COMMENT 'use for Website',
  `app_text_color` varchar(20) NOT NULL COMMENT 'use for App',
  `app_bg_color` varchar(20) NOT NULL COMMENT 'use for App'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_lists`
--

INSERT INTO `order_status_lists` (`id`, `status`, `color`, `text_color`, `bg_color`, `app_text_color`, `app_bg_color`) VALUES
(1, 'Payment Pending', 'badge-warning', 'text-yellow-800', 'bg-yellow-200', '#D97706', '#FEF3C7'),
(2, 'Received', 'badge-primary	', 'text-blue-800', 'bg-blue-200', '#1D4ED8', '#DBEAFE'),
(3, 'Processed', 'badge-info	', 'text-cyan-800', 'bg-cyan-200', '#0891B2', '#CFFAFE'),
(4, 'Shipped', 'badge-secondary	', 'text-gray-800', 'bg-gray-300', '#374151', '#D1D5DB'),
(5, 'Out For Delivery', 'badge-light', 'text-white', 'bg-gray-700', '#FFFFFF', '#374151'),
(6, 'Delivered', 'badge-success	', 'text-green-800', 'bg-green-200', '#047857', '#D1FAE5'),
(7, 'Cancelled', 'badge-danger', 'text-red-800', 'bg-red-200', '#B91C1C', '#FECACA'),
(8, 'Returned', 'badge-dark', 'text-gray-800', 'bg-gray-400', '#374151', '#9CA3AF');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `verify_by` enum('email','mobile') NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `img` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `api_key` text DEFAULT NULL,
  `secret_key` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `screen_name` text NOT NULL,
  `environment` int(11) NOT NULL DEFAULT 1 COMMENT '1= test, 2= production'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `img`, `title`, `description`, `api_key`, `secret_key`, `status`, `screen_name`, `environment`) VALUES
(1, 'assets/dist/img/cash_on_delivery.webp', 'Cash on Delivery', 'Using COD Save Service Tax', NULL, NULL, 1, '', 1),
(2, 'assets/dist/img/razorpay.png', 'Razorpay', '100% Secure Payment', 'YOUR_RAZORPAY_API_KEY', 'YOUR_RAZORPAY_SECRET_KEY', 1, 'RazorpayPayment', 1),
(3, 'assets/dist/img/paypal.png', 'Paypal', 'Online Payment', 'YOUR_PAYPAL_CLIENT_ID', 'YOUR_PAYPAL_CLIENT_SECRET', 1, 'PaypalPayment', 1),
(4, 'assets/dist/img/paystack.png', 'Paystack', '100% Secure Payment', 'YOUR_PAYSTACK_API_KEY', 'YOUR_PAYSTACK_SECRET_KEY', 1, 'PaystackPayment', 1),
(5, 'assets/dist/img/cashfree.png', 'Cash Free', '100% Secure Payment', 'YOUR_CASHFREE_API_KEY', 'YOUR_CASHFREE_SECRET_KEY', 1, 'CashFreePayment', 1),
(6, 'assets/dist/img/stripe.png', 'Stripe', '100% Secure Payment', 'YOUR_STRIPE_PUBLISHABLE_KEY', 'YOUR_STRIPE_SECRET_KEY', 0, '', 1);
-- --------------------------------------------------------

--
-- Table structure for table `permission_category`
--

CREATE TABLE `permission_category` (
  `id` int(11) NOT NULL,
  `row_order_by` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) DEFAULT NULL,
  `enable_view` int(11) DEFAULT 0,
  `enable_add` int(11) DEFAULT 0,
  `enable_edit` int(11) DEFAULT 0,
  `enable_delete` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_category`
--

INSERT INTO `permission_category` (`id`, `row_order_by`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) VALUES
(1, 1, 'Category', 'category', 1, 1, 1, 1, '2024-10-22 13:20:33'),
(2, 2, 'Subcategory', 'subcategory', 1, 1, 1, 1, '2024-10-22 13:20:35'),
(3, 3, 'Product', 'product', 1, 1, 1, 1, '2024-10-22 13:20:37'),
(4, 4, 'Product Bulk Import', 'product-bulk-import', 1, 1, 0, 0, '2024-10-22 13:20:39'),
(5, 5, 'Product Bulk Update', 'product-bulk-update', 1, 1, 0, 0, '2024-10-22 13:21:13'),
(6, 6, 'Taxes', 'taxes', 1, 1, 1, 1, '2024-10-22 13:21:14'),
(7, 7, 'Product Order', 'product-order', 1, 0, 1, 0, '2024-10-22 13:21:17'),
(8, 8, 'Product Enquiry', 'product-enquiry', 1, 0, 0, 0, '2024-10-22 13:21:19'),
(9, 9, 'Category Order', 'category-order', 1, 0, 1, 0, '2024-10-22 13:21:21'),
(10, 10, 'Subcategory Order', 'subcategory-order', 1, 0, 1, 0, '2024-10-22 13:21:55'),
(11, 11, 'Seller', 'seller', 1, 1, 1, 1, '2024-10-22 13:22:15'),
(12, 12, 'Seller Transaction', 'seller-transaction', 1, 1, 0, 0, '2025-01-04 10:05:32'),
(13, 13, 'City', 'city', 1, 1, 1, 1, '2024-10-22 13:22:21'),
(14, 14, 'Deliverable Area', 'deliverable_area', 1, 1, 1, 1, '2024-10-22 13:22:24'),
(15, 15, 'Coupon', 'coupon', 1, 1, 0, 1, '2024-10-22 13:22:27'),
(16, 16, 'Banner', 'banner', 1, 1, 1, 1, '2024-10-22 13:22:31'),
(17, 17, 'Timeslot', 'timeslot', 1, 1, 0, 1, '2024-10-22 13:22:33'),
(18, 18, 'Delivery Boy', 'delivery-boy', 1, 1, 1, 1, '2024-10-22 13:22:44'),
(19, 19, 'Delivery Boy Cash Collection', 'delivery-boy-cash-collection', 1, 1, 0, 0, '2024-10-22 13:22:48'),
(20, 20, 'Home Section', 'home-section', 1, 1, 1, 1, '2024-10-22 13:22:51'),
(21, 21, 'Notification', 'notification', 1, 1, 0, 1, '2024-10-22 13:22:55'),
(22, 22, 'Manage User', 'manage-user', 1, 0, 1, 1, '2024-10-22 13:22:57'),
(23, 23, 'Manage User Wallet', 'manage-user-wallet', 1, 1, 0, 0, '2024-10-22 13:23:00'),
(24, 24, 'Manage Orders', 'manage-orders', 1, 0, 1, 0, '2024-10-22 13:23:04'),
(25, 25, 'Assign Delivery Boy', 'assign-delivery-boy', 0, 0, 1, 0, '2024-10-22 13:23:08'),
(26, 26, 'Product Rating', 'product-rating', 1, 0, 0, 0, '2024-10-22 13:23:13'),
(27, 28, 'Payment Setting', 'payment-setting', 1, 0, 1, 0, '2024-10-22 13:23:16'),
(31, 32, 'Change Password', 'change-password', 1, 0, 1, 0, '2024-10-22 13:23:30'),
(33, 27, 'Setting', 'setting', 1, 0, 1, 0, '2024-10-22 13:24:08'),
(34, 27, 'Manage Roles', 'manage-roles', 1, 1, 1, 1, '2024-10-22 13:24:08'),
(35, 7, 'Stock Mangement', 'stock-management', 1, 0, 1, 0, '2024-10-30 05:53:04'),
(36, 27, 'System User', 'system-user', 1, 1, 1, 1, '2024-11-06 10:57:21'),
(37, 3, 'Brand', 'brand', 1, 1, 1, 1, '2024-12-16 10:26:37'),
(38, 19, 'Delivery Boy Fund Transfer', 'delivery-boy-fund-transfer', 1, 1, 0, 0, '2024-10-22 13:22:48'),
(39, 11, 'Seller Request', 'seller-request', 1, 0, 1, 0, '2024-12-24 13:24:21'),
(40, 24, 'Return Rrequest', 'return-request', 1, 0, 1, 0, '2024-12-28 11:00:04'),
(41, 33, 'Customer App Policy', 'customer-app-policy', 1, 0, 1, 0, '2025-01-01 07:58:23'),
(42, 34, 'Delivery App Policy', 'delivery-app-policy', 1, 0, 1, 0, '2025-01-01 07:58:23'),
(43, 35, 'FAQ', 'faq', 1, 1, 1, 1, '2025-02-18 11:22:39'),
(44, 16, 'Highlights', 'highlights', 1, 1, 1, 1, '2025-02-18 12:15:36'),
(45, 25, 'Ai Insight Order Report', 'order-report-ai', 1, 1, 0, 0, '2025-03-24 14:00:07'),
(46, 27, 'Store Setting', 'store-setting', 1, 0, 1, 0, '2025-04-13 07:31:38'),
(47, 27, 'SMS Gateway', 'sms-gateway', 1, 0, 1, 0, '2025-04-13 07:31:38'),
(48, 24, 'Manage POS Orders', 'manage-pos-orders', 1, 1, 1, 0, '2025-10-11 13:23:04'),
(49, 25, 'POS Orders Report', 'pos-order-report', 1, 0, 0, 0, '2025-10-11 13:23:04'),
(50, 34, 'Seller App Policy', 'seller-app-policy', 1, 0, 1, 0, '2025-01-01 07:58:23');

-- --------------------------------------------------------

--
-- Table structure for table `pos_cart_sessions`
--

CREATE TABLE `pos_cart_sessions` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `created_by_admin` int(11) NOT NULL DEFAULT 0 COMMENT 'if yes then admin id',
  `seller_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_mobile` varchar(20) DEFAULT NULL,
  `cart_data` longtext NOT NULL COMMENT 'JSON data of cart items',
  `additional_discount` double(8,2) DEFAULT 0.00,
  `additional_discount_type` varchar(20) DEFAULT NULL,
  `additional_charges` text DEFAULT NULL COMMENT 'JSON data of additional charges',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_payment_method`
--

CREATE TABLE `pos_payment_method` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pos_payment_method`
--

INSERT INTO `pos_payment_method` (`id`, `name`) VALUES
(1, 'Cash'),
(2, 'Card'),
(3, 'UPI'),
(4, 'Net Banking');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `product_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) NOT NULL,
  `main_img` text NOT NULL,
  `description` text NOT NULL,
  `popular` int(11) NOT NULL,
  `deal_of_the_day` int(11) NOT NULL DEFAULT 0,
  `manufacturer` varchar(200) DEFAULT NULL,
  `made_in` varchar(200) DEFAULT NULL,
  `total_allowed_quantity` int(11) NOT NULL DEFAULT 0,
  `tax_included_in_price` int(11) NOT NULL DEFAULT 0 COMMENT '0 = no & 1 = yes',
  `fssai_lic_no` varchar(200) DEFAULT NULL,
  `return_days` int(11) NOT NULL DEFAULT 0,
  `is_returnable` int(11) NOT NULL DEFAULT 0 COMMENT '0 = no, 1 = yes',
  `row_order` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1= publish, other unpublish',
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `seo_title` varchar(256) DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `seo_alt_text` varchar(256) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `added_by_seller` int(11) NOT NULL DEFAULT 0 COMMENT '1 = yes, 0 = no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_enquiry`
--

CREATE TABLE `product_enquiry` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL DEFAULT 0,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rate` smallint(6) NOT NULL,
  `title` varchar(150) NOT NULL,
  `review` text NOT NULL,
  `created_at` datetime NOT NULL,
  `is_approved_to_show` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1= approve, 2= rejected',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sort_type`
--

CREATE TABLE `product_sort_type` (
  `id` int(11) NOT NULL,
  `sort` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sort_type`
--

INSERT INTO `product_sort_type` (`id`, `sort`) VALUES
(1, 'Relevance'),
(2, 'Price(Low to High)'),
(3, 'Price(High to Low)'),
(4, 'Discount(High to Low)'),
(5, 'Name(A to Z)'),
(6, 'Popular'),
(7, 'Deal of the day');

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategories`
--

CREATE TABLE `product_subcategories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_taxes`
--

CREATE TABLE `product_taxes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `price` double(11,2) NOT NULL,
  `discounted_price` double(11,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_unlimited_stock` int(11) NOT NULL COMMENT '1 =yes, 0 = no',
  `stock_unit_id` int(11) NOT NULL DEFAULT 0,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_order`
--

CREATE TABLE `rate_order` (
  `id` int(8) NOT NULL,
  `order_id` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `read_notification`
--

CREATE TABLE `read_notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `is_system` int(11) NOT NULL DEFAULT 0,
  `is_superadmin` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `is_system`, `is_superadmin`, `created_at`) VALUES
(1, 'Super Admin', 'super-admin', 1, 1, 1, '2025-03-24 13:59:24'),
(2, 'Admin', 'admin', 1, 1, 0, '2025-03-24 13:59:33'),
(3, 'Accountant', 'accountant', 1, 1, 0, '2025-03-24 13:59:35'),
(4, 'Manager', 'manger', 1, 1, 0, '2025-03-24 14:11:12'),
(5, 'Supervisor', 'supervisor', 1, 0, 0, '2025-03-24 19:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `perm_cat_id` int(11) DEFAULT NULL,
  `can_view` int(11) DEFAULT NULL,
  `can_add` int(11) DEFAULT NULL,
  `can_edit` int(11) DEFAULT NULL,
  `can_delete` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`id`, `role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(2, 1, 2, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(3, 1, 3, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(4, 1, 4, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(5, 1, 5, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(6, 1, 6, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(7, 1, 7, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(8, 1, 8, 1, 0, 0, 0, '2025-03-24 19:28:17'),
(9, 1, 9, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(10, 1, 10, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(11, 1, 11, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(12, 1, 12, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(13, 1, 13, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(14, 1, 14, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(15, 1, 15, 1, 1, 0, 1, '2025-03-24 19:28:17'),
(16, 1, 16, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(17, 1, 17, 1, 1, 0, 1, '2025-03-24 19:28:17'),
(18, 1, 18, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(19, 1, 19, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(20, 1, 20, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(21, 1, 21, 1, 1, 0, 1, '2025-03-24 19:28:17'),
(22, 1, 22, 1, 0, 1, 1, '2025-03-24 19:28:17'),
(23, 1, 23, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(24, 1, 24, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(25, 1, 25, 0, 0, 1, 0, '2025-03-24 19:28:17'),
(26, 1, 26, 1, 0, 0, 0, '2025-03-24 19:28:17'),
(27, 1, 27, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(28, 1, 28, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(29, 1, 29, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(30, 1, 30, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(31, 1, 31, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(32, 1, 33, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(33, 1, 34, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(34, 1, 35, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(35, 1, 36, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(36, 1, 37, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(37, 1, 38, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(38, 1, 39, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(39, 1, 40, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(40, 1, 41, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(41, 1, 42, 1, 0, 1, 0, '2025-03-24 19:28:17'),
(42, 1, 43, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(43, 1, 44, 1, 1, 1, 1, '2025-03-24 19:28:17'),
(44, 1, 45, 1, 1, 0, 0, '2025-03-24 19:28:17'),
(45, 2, 1, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(46, 2, 2, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(47, 2, 3, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(48, 2, 4, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(49, 2, 5, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(50, 2, 6, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(51, 2, 7, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(52, 2, 8, 1, 0, 0, 0, '2025-03-24 19:28:55'),
(53, 2, 9, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(54, 2, 10, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(55, 2, 11, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(56, 2, 12, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(57, 2, 13, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(58, 2, 14, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(59, 2, 15, 1, 1, 0, 1, '2025-03-24 19:28:55'),
(60, 2, 16, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(61, 2, 17, 1, 1, 0, 1, '2025-03-24 19:28:55'),
(62, 2, 18, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(63, 2, 19, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(64, 2, 20, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(65, 2, 21, 1, 1, 0, 1, '2025-03-24 19:28:55'),
(66, 2, 22, 1, 0, 1, 1, '2025-03-24 19:28:55'),
(67, 2, 23, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(68, 2, 24, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(69, 2, 25, 0, 0, 1, 0, '2025-03-24 19:28:55'),
(70, 2, 26, 1, 0, 0, 0, '2025-03-24 19:28:55'),
(71, 2, 27, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(72, 2, 28, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(73, 2, 29, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(74, 2, 30, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(75, 2, 31, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(76, 2, 33, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(77, 2, 34, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(78, 2, 35, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(79, 2, 36, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(80, 2, 37, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(81, 2, 38, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(82, 2, 39, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(83, 2, 40, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(84, 2, 41, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(85, 2, 42, 1, 0, 1, 0, '2025-03-24 19:28:55'),
(86, 2, 43, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(87, 2, 44, 1, 1, 1, 1, '2025-03-24 19:28:55'),
(88, 2, 45, 1, 1, 0, 0, '2025-03-24 19:28:55'),
(89, 3, 1, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(90, 3, 2, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(91, 3, 3, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(92, 3, 4, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(93, 3, 5, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(94, 3, 6, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(95, 3, 7, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(96, 3, 8, 1, 0, 0, 0, '2025-03-24 19:29:01'),
(97, 3, 9, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(98, 3, 10, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(99, 3, 11, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(100, 3, 12, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(101, 3, 13, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(102, 3, 14, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(103, 3, 15, 1, 1, 0, 1, '2025-03-24 19:29:01'),
(104, 3, 16, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(105, 3, 17, 1, 1, 0, 1, '2025-03-24 19:29:01'),
(106, 3, 18, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(107, 3, 19, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(108, 3, 20, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(109, 3, 21, 1, 1, 0, 1, '2025-03-24 19:29:01'),
(110, 3, 22, 1, 0, 1, 1, '2025-03-24 19:29:01'),
(111, 3, 23, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(112, 3, 24, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(113, 3, 25, 0, 0, 1, 0, '2025-03-24 19:29:01'),
(114, 3, 26, 1, 0, 0, 0, '2025-03-24 19:29:01'),
(115, 3, 27, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(116, 3, 28, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(117, 3, 29, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(118, 3, 30, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(119, 3, 31, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(120, 3, 33, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(121, 3, 34, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(122, 3, 35, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(123, 3, 36, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(124, 3, 37, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(125, 3, 38, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(126, 3, 39, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(127, 3, 40, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(128, 3, 41, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(129, 3, 42, 1, 0, 1, 0, '2025-03-24 19:29:01'),
(130, 3, 43, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(131, 3, 44, 1, 1, 1, 1, '2025-03-24 19:29:01'),
(132, 3, 45, 1, 1, 0, 0, '2025-03-24 19:29:01'),
(133, 4, 1, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(134, 4, 2, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(135, 4, 3, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(136, 4, 4, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(137, 4, 5, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(138, 4, 6, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(139, 4, 7, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(140, 4, 8, 1, 0, 0, 0, '2025-03-24 19:29:12'),
(141, 4, 9, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(142, 4, 10, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(143, 4, 11, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(144, 4, 12, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(145, 4, 13, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(146, 4, 14, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(147, 4, 15, 1, 1, 0, 1, '2025-03-24 19:29:12'),
(148, 4, 16, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(149, 4, 17, 1, 1, 0, 1, '2025-03-24 19:29:12'),
(150, 4, 18, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(151, 4, 19, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(152, 4, 20, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(153, 4, 21, 1, 1, 0, 1, '2025-03-24 19:29:12'),
(154, 4, 22, 1, 0, 1, 1, '2025-03-24 19:29:12'),
(155, 4, 23, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(156, 4, 24, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(157, 4, 25, 0, 0, 1, 0, '2025-03-24 19:29:12'),
(158, 4, 26, 1, 0, 0, 0, '2025-03-24 19:29:12'),
(159, 4, 27, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(160, 4, 28, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(161, 4, 29, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(162, 4, 30, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(163, 4, 31, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(164, 4, 33, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(165, 4, 34, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(166, 4, 35, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(167, 4, 36, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(168, 4, 37, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(169, 4, 38, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(170, 4, 39, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(171, 4, 40, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(172, 4, 41, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(173, 4, 42, 1, 0, 1, 0, '2025-03-24 19:29:12'),
(174, 4, 43, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(175, 4, 44, 1, 1, 1, 1, '2025-03-24 19:29:12'),
(176, 4, 45, 1, 1, 0, 0, '2025-03-24 19:29:12'),
(177, 5, 1, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(178, 5, 2, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(179, 5, 3, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(180, 5, 4, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(181, 5, 5, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(182, 5, 6, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(183, 5, 7, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(184, 5, 8, 1, 0, 0, 0, '2025-03-24 19:41:19'),
(185, 5, 9, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(186, 5, 10, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(187, 5, 11, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(188, 5, 12, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(189, 5, 13, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(190, 5, 14, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(191, 5, 15, 1, 1, 0, 1, '2025-03-24 19:41:19'),
(192, 5, 16, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(193, 5, 17, 1, 1, 0, 1, '2025-03-24 19:41:19'),
(194, 5, 18, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(195, 5, 19, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(196, 5, 20, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(197, 5, 21, 1, 1, 0, 1, '2025-03-24 19:41:19'),
(198, 5, 22, 1, 0, 1, 1, '2025-03-24 19:41:19'),
(199, 5, 23, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(200, 5, 24, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(201, 5, 25, 0, 0, 1, 0, '2025-03-24 19:41:19'),
(202, 5, 26, 1, 0, 0, 0, '2025-03-24 19:41:19'),
(203, 5, 27, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(204, 5, 28, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(205, 5, 29, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(206, 5, 30, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(207, 5, 31, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(208, 5, 33, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(209, 5, 34, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(210, 5, 35, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(211, 5, 36, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(212, 5, 37, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(213, 5, 38, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(214, 5, 39, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(215, 5, 40, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(216, 5, 41, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(217, 5, 42, 1, 0, 1, 0, '2025-03-24 19:41:19'),
(218, 5, 43, 1, 1, 1, 1, '2025-03-24 14:12:20'),
(219, 5, 44, 1, 1, 1, 1, '2025-03-24 19:41:19'),
(220, 5, 45, 1, 1, 0, 0, '2025-03-24 19:41:19'),
(221, 3, 46, 1, 0, 1, 0, '2025-04-13 11:44:18'),
(222, 1, 46, 1, 0, 1, 0, '2025-04-13 11:44:18'),
(223, 2, 46, 1, 0, 1, 0, '2025-05-02 03:36:33'),
(224, 2, 47, 1, 0, 1, 0, '2025-05-02 03:36:33'),
(225, 1, 47, 1, 0, 1, 0, '2025-05-02 03:36:33'),
(226, 1, 48, 1, 1, 1, 0, '2025-10-10 22:50:45'),
(227, 1, 49, 1, 0, 0, 0, '2025-10-10 22:50:45'),
(228, 1, 50, 1, 0, 1, 0, '2026-03-31 11:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `home_screen_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `title` varchar(150) NOT NULL,
  `short_title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `section_style` varchar(30) NOT NULL DEFAULT 'regular',
  `content_type` tinyint(1) NOT NULL DEFAULT 1,
  `product_content_type` tinyint(1) DEFAULT NULL,
  `section_type` tinyint(1) NOT NULL DEFAULT 0,
  `product_type` tinyint(1) DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `sub_category_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `seller_id` int(10) UNSIGNED DEFAULT NULL,
  `selling_type` tinyint(1) DEFAULT NULL,
  `price_min` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_max` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sort_by` varchar(50) DEFAULT NULL,
  `screen_layout` varchar(50) NOT NULL DEFAULT 'potrait_item',
  `no_of_content` int(11) NOT NULL DEFAULT 10,
  `no_of_row` int(11) NOT NULL DEFAULT 1,
  `view_all` tinyint(1) NOT NULL DEFAULT 0,
  `load_more` tinyint(1) NOT NULL DEFAULT 0,
  `order_by_upload` tinyint(1) NOT NULL DEFAULT 1,
  `order_by_like` tinyint(1) NOT NULL DEFAULT 1,
  `background_type` varchar(10) NOT NULL DEFAULT 'color',
  `bg_color` varchar(10) NOT NULL DEFAULT '#FFFFFF',
  `bg_image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_brands`
--

CREATE TABLE `section_brands` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_categories`
--

CREATE TABLE `section_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_highlights`
--

CREATE TABLE `section_highlights` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `highlight_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_products`
--

CREATE TABLE `section_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_sellers`
--

CREATE TABLE `section_sellers` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `id` int(11) UNSIGNED NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `deliverable_area_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `logo` varchar(255) DEFAULT NULL,
  `store_address` text DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `bank_ifsc_code` varchar(50) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `commission` decimal(5,2) DEFAULT 0.00,
  `status` tinyint(1) DEFAULT 0 COMMENT '0-registered, 1- approved, 2= rejected',
  `require_products_approval` tinyint(1) DEFAULT 0,
  `fcm_app_key` text DEFAULT NULL,
  `national_id_proof` varchar(255) DEFAULT NULL,
  `address_proof` varchar(255) DEFAULT NULL,
  `pan_number` varchar(50) DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `tax_name` varchar(255) DEFAULT NULL,
  `map_address` text DEFAULT NULL,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `view_customer_details` tinyint(1) DEFAULT 0,
  `registered_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_delete` tinyint(1) DEFAULT 0,
  `status_reason` text DEFAULT NULL,
  `order_status_permission` tinyint(1) DEFAULT 0,
  `reset_link_token` text DEFAULT NULL,
  `reset_token_exp_date` datetime DEFAULT NULL,
  `banner` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_categories`
--

CREATE TABLE `seller_categories` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_wallet_transaction`
--

CREATE TABLE `seller_wallet_transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_products_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `message` text DEFAULT NULL COMMENT 'send by seller',
  `remark` text NOT NULL COMMENT 'written by admin',
  `status` varchar(200) DEFAULT NULL COMMENT '1 = pending, 2 = payment_done, 3 = rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `this_is_request` int(11) NOT NULL DEFAULT 0 COMMENT '1= yes, 0 = no',
  `transaction_done_by` int(11) NOT NULL COMMENT 'admin_user_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `for_delivery_boy` tinyint(4) NOT NULL DEFAULT 0,
  `for_customer_app` tinyint(4) NOT NULL DEFAULT 0,
  `for_seller_app` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `for_delivery_boy`, `for_customer_app`, `for_seller_app`) VALUES
(1, 'business_name', 'GroceryHub - 10 Minute App', 1, 1, 1),
(2, 'logo', 'uploads/logo/1742534486_3f878cbcc488f43f569a.png', 1, 1, 1),
(3, 'mail_config', '{\"name\":\"GroceryHub - 10 Minute App\",\"host\":\"smtp.hostinger.com\",\"username\":\"YOUR_SMTP_USERNAME\",\"password\":\"YOUR_SMTP_PASSWORD\",\"port\":\"465\",\"encryption\":\"ssl\"}', 0, 1, 1),
(4, 'map_api_key', 'YOUR_GOOGLE_MAP_KEY', 0, 1, 1),
(5, 'phone', '8956656429', 1, 1, 1),
(6, 'email', 'info@apksoftwaresolution.com', 1, 1, 1),
(7, 'address', '{\"address\":\"Bhandara\",\"latitude\":\"21.177658\",\"longitude\":\"79.6570127\",\"logo_aspect_ratio\":\"1:3\"}', 1, 1, 1),
(8, 'frontend_category_section', '1', 0, 1, 0),
(9, 'frontend_brand_section', '1', 0, 1, 0),
(10, 'frontend_seller_section', '1', 0, 1, 0),
(11, 'direct_login', '1', 0, 1, 0),
(12, 'social_login', '[{\"status\":\"1\",\"client_id\":\"YOUR_GOOGLE_CLIENT_ID\",\"client_secret\":\"YOUR_GOOGLE_CLIENT_SECRET\",\"login_medium\":\"google\"},{\"status\":\"0\",\"login_medium\":\"apple\"}]', 0, 1, 0),
(13, 'fcm_credentials', '{\"apiKey\":\"YOUR_FCM_API_KEY\",\"authDomain\":\"YOUR_PROJECT.firebaseapp.com\",\"storageBucket\":\"YOUR_PROJECT.firebasestorage.app\",\"messagingSenderId\":\"YOUR_SENDER_ID\",\"projectId\":\"YOUR_PROJECT_ID\",\"appId\":\"YOUR_APP_ID\",\"measurementId\":\"YOUR_MEASUREMENT_ID\"}', 0, 1, 0),
(14, 'short_description', 'At Grocery, we offer fresh, quality groceries at\r\ngreat prices,ensuring convenience and value.', 0, 1, 0),
(15, 'firebase_admin_json_file_content', '{\"type\":\"service_account\",\"project_id\":\"YOUR_PROJECT_ID\",\"private_key_id\":\"YOUR_PRIVATE_KEY_ID\",\"private_key\":\"YOUR_PRIVATE_KEY\",\"client_email\":\"YOUR_CLIENT_EMAIL\",\"client_id\":\"YOUR_CLIENT_ID\",\"auth_uri\":\"https://accounts.google.com/o/oauth2/auth\",\"token_uri\":\"https://oauth2.googleapis.com/token\",\"auth_provider_x509_cert_url\":\"https://www.googleapis.com/oauth2/v1/certs\",\"client_x509_cert_url\":\"YOUR_CLIENT_CERT_URL\",\"universe_domain\":\"googleapis.com\"}', 0, 0, 0),
(16, 'footer_text', '© Developed by APK Software Solution, All Rights Reserved', 0, 0, 0),
(17, 'order_delivery_verification', '1', 1, 1, 0),
(18, 'currency_symbol_position', 'left', 1, 1, 0),
(19, 'social_link', '[\n    {\n        \"name\": \"Facebook\",\n        \"link\": \"https://facebook.com/asfad\",\n        \"icon\": \"fi fi-brands-facebook\",\n        \"appIcon\": \"logo-facebook\",\n        \"status\": \"1\"\n    },\n    {\n        \"name\": \"X\",\n        \"link\": \"https://x.com\",\n        \"icon\": \"fi fi-brands-twitter-alt\",\n        \"appIcon\": \"logo-twitter\",\n        \"status\": \"1\"\n    },\n    {\n        \"name\": \"Instagram\",\n        \"link\": \"https://instagram.com\",\n        \"icon\": \"fi fi-brands-instagram\",\n        \"appIcon\": \"logo-instagram\",\n        \"status\": \"1\"\n    },\n    {\n        \"name\": \"Youtube\",\n        \"link\": \"https://youtube.com\",\n        \"icon\": \"fi fi-brands-youtube\",\n        \"appIcon\": \"logo-youtube\",\n        \"status\": \"1\"\n    },\n    {\n        \"name\": \"Linkedin\",\n        \"link\": \"https://linkedin.com\",\n        \"icon\": \"fi fi-brands-linkedin\",\n        \"appIcon\": \"logo-linkedin\",\n        \"status\": \"1\"\n    }\n]', 1, 1, 0),
(21, 'twilio_sms', '{\"status\":\"0\",\"sid\":null,\"messaging_service_id\":null,\"token\":null,\"from\":null,\"otp_template\":\"Your otp is #OTP#.\"}', 0, 0, 0),
(22, 'nexmo_sms', '{\"status\":\"0\",\"api_key\":null,\"api_secret\":null,\"signature_secret\":\"\",\"private_key\":\"\",\"application_id\":\"\",\"from\":null,\"otp_template\":\"Your otp is #OTP#.\"}', 0, 0, 0),
(23, '2factor_sms', '{\"status\":\"0\",\"api_key\":null}', 0, 0, 0),
(24, 'msg91_sms', '{\"status\":\"0\",\"template_id\":null,\"authkey\":null}', 0, 0, 0),
(25, 'app_url_android_delivery_boy', 'https://play.google.com/store/apps/details?id=com.grocerydelivery.apk', 0, 0, 0),
(26, 'app_url_ios_delivery_boy', '#', 0, 0, 0),
(27, 'app_url_ios', '#', 0, 0, 0),
(28, 'app_url_android', 'https://play.google.com/store/apps/details?id=com.apksoftwaresolution.grocerycustomer', 0, 0, 0),
(29, 'refer_and_earn_status', '1', 0, 1, 0),
(30, 'referer_earning', '100', 0, 1, 0),
(31, 'refered_earning', '50', 0, 1, 0),
(32, 'frontend_deal_of_the_day', '1', 0, 1, 0),
(33, 'frontend_popular_product', '1', 0, 1, 0),
(34, 'app_minimum_version_android', '1.2', 0, 1, 0),
(35, 'app_minimum_version_ios', '1.0.0', 0, 1, 0),
(36, 'app_minimum_version_android_delivery_boy', '1.0', 1, 0, 0),
(37, 'app_minimum_version_ios_delivery_boy', '1.0.0', 1, 0, 0),
(38, 'home_delivery_status', '{\"id\":\"homeDelivery\",\"title\":\"Home Delivery\",\"description\":\"Get it delivered at your home.\",\"image\":\"assets/dist/img/dm-home.png\",\"status\":\"1\"}', 0, 1, 0),
(39, 'schedule_delivery_status', '{\"id\":\"scheduledDelivery\",\"title\":\"Pick a Time\",\"description\":\"Choose a time that works for you.\",\"image\":\"assets/dist/img/dm-watch.png\",\"status\":\"1\"}', 0, 1, 0),
(40, 'cookies_text', '**Rights and Legal Information for Your Company Name**\r\n\r\n**1. Ownership and Intellectual Property**\r\nAll content, logos, designs, text, graphics, software, and other materials on this website and app are the exclusive property of Sevenhills or its licensors. Unauthorized use, reproduction, or distribution of any material is prohibited and may result in legal action.\r\n\r\n**2. Terms of Service**\r\nBy using our platform, you agree to the following:\r\n\r\n- To provide accurate information during the order process.\r\n- To comply with all applicable laws and regulations.\r\n- To use our services solely for lawful purposes and personal or business transactions.\r\n\r\n**3. Delivery Policy**\r\n\r\n- Orders are processed and delivered according to the timelines mentioned during the checkout process.\r\n- Delivery charges, if applicable, will be communicated at the time of purchase.\r\n- Sevenhills is not liable for delays caused by unforeseen circumstances, including but not limited to natural disasters, strikes, or transportation issues.\r\n\r\n**4. Refund and Cancellation Policy**\r\n\r\n- Refunds and cancellations are governed by our [Refund and Cancellation Policy]. Please review this policy before placing an order.\r\n- Requests for cancellations must be made within [specific time frame] of placing the order.\r\n- Refunds will be processed within [specific time frame] after approval.\r\n\r\n**5. Liability Disclaimer**\r\nSevenhills is not liable for:\r\n\r\n- Damages or losses resulting from unauthorized access to our systems.\r\n- Any indirect, incidental, or consequential damages related to the use of our services.\r\n\r\n**6. Privacy Policy**\r\nYour personal data is collected, used, and stored as per our [Privacy Policy], which outlines how we handle your information to protect your privacy.\r\n\r\n**7. Compliance with Local Laws**\r\nAll transactions are subject to compliance with applicable laws and regulations in your jurisdiction. Any violations may result in termination of service and legal consequences.\r\n\r\n**8. Amendments to Rights and Legal Information**\r\nSevenhills reserves the right to update this document at any time. Changes will be effective upon posting on our website or app. Users are encouraged to review this document periodically.\r\n\r\n**9. Contact Information**\r\nFor questions or concerns regarding your rights or our policies, please contact us at:\r\n\r\n- Email: [info@thesevenhill.com](mailto\\:info@thesevenhill.com)\r\n- Address: Kaman Chowmuhani, Agartala, Tripura West, 799001\r\n\r\nBy using our services, you acknowledge that you have read and understood these terms and agree to abide by them.', 0, 0, 0),
(41, 'free_delivery_over_status', '1', 0, 1, 0),
(42, 'additional_charge_status', '1', 0, 1, 0),
(43, 'additional_charge_name', 'Platform Fee', 1, 1, 0),
(44, 'additional_charge', '2', 0, 1, 0),
(45, 'takeaway_status', '{\"id\":\"selfPickup\",\"title\":\"Self Pickup\",\"description\":\"Pick up your order from our store..\",\"image\":\"assets/dist/img/dm-user.png\",\"status\":\"1\"}', 0, 1, 0),
(46, 'push_notification_service_file_content', '{\"type\":\"service_account\",\"project_id\":\"YOUR_PROJECT_ID\",\"private_key_id\":\"YOUR_PRIVATE_KEY_ID\",\"private_key\":\"YOUR_PUSH_NOTIFICATION_PRIVATE_KEY\",\"client_email\":\"YOUR_PUSH_NOTIFICATION_CLIENT_EMAIL\",\"client_id\":\"YOUR_PUSH_NOTIFICATION_CLIENT_ID\",\"auth_uri\":\"https://accounts.google.com/o/oauth2/auth\",\"token_uri\":\"https://oauth2.googleapis.com/token\",\"auth_provider_x509_cert_url\":\"https://www.googleapis.com/oauth2/v1/certs\",\"client_x509_cert_url\":\"YOUR_PUSH_NOTIFICATION_CLIENT_CERT_URL\",\"universe_domain\":\"googleapis.com\"}', 0, 0, 0),
(47, 'thememode', 'Light', 0, 0, 0),
(48, 'primary_color', 'olive', 0, 0, 0),
(49, 'delivery_boy_show_earning_in_app', '1', 1, 0, 0),
(50, 'minimum_order_amount', '10', 0, 1, 0),
(51, 'delivery_boy_bonus_setting', '1', 1, 0, 0),
(52, 'delivery_boy_cash_in_hand', '1', 1, 0, 0),
(53, 'delivery_boy_maximum_cash_in_hand', '3000', 1, 0, 0),
(54, 'seller_can_cancel_order', '1', 0, 0, 0),
(55, 'seller_only_one_seller_cart', '1', 0, 1, 0),
(56, 'seller_approval_product', '1', 0, 0, 0),
(57, 'seller_approval_product_data', '{\"Update_product_price\":0,\"Add_new_product\":\"1\",\"Update_product_variation\":0,\"Update_anything_in_product_details\":0}', 0, 1, 0),
(58, 'frontend_popular_section', '1', 0, 1, 0),
(59, 'frontend_deal_of_the_day_section', '1', 0, 1, 0),
(60, 'google_recaptcha_site_key', 'YOUR_RECAPTCHA_SITE_KEY', 0, 0, 0),
(61, 'google_recaptcha_secret_key', 'YOUR_RECAPTCHA_SECRET_KEY', 0, 0, 0),
(62, 'website', 'https://grocery-ci.apksoftwaresolution.com', 1, 1, 0),
(63, 'order_cancelled_till', '2', 0, 1, 0),
(64, 'customer_app_about', '    <div><h1>About Us</h1>    <p>Welcome to <strong>GroceryHub - 10 Minutes Demo App</strong>, your go-to platform for seamless online shopping and quick commerce. We connect customers with multiple sellers, offering a wide range of products, instant deliveries, and flexible payment options.</p>    <h2>Our Mission</h2>    <p>We aim to simplify shopping with a user-friendly experience, AI-powered insights, and efficient order management. Whether it\'s groceries, essentials, or daily needs, we deliver convenience to your doorstep.</p>    <h2>Why Choose Us?</h2>    <ul>        <li>📍 Location-based shopping with nearby sellers</li>        <li>🛒 Smart multi-seller cart for flexible purchases</li>        <li>⚡ Instant, scheduled, or self-pickup delivery</li>        <li>💳 Secure payments via multiple gateways</li>        <li>🔔 Real-time order tracking &amp; notifications</li>    </ul>    <h2>Get in Touch</h2>    <p>Have questions? Reach out to us at:</p>    <p><strong>Email:</strong> info@apksoftwaresolution.com</p>    <p><strong>Phone:</strong> +91 9766846429</p>    <p>Thank you for choosing <b>GroceryHub - 10 Minute Demo App</b> 🚀</p></div>', 0, 1, 0),
(65, 'customer_app_privacy_policy', '<div><h1>Privacy Policy</h1><p>...</p></div>', 0, 1, 0),
(66, 'customer_app_terms_policy', '<h1>Terms and Conditions</h1><p>...</p>', 0, 1, 0),
(67, 'customer_app_refund_policy', '<div><h1>Refund &amp; Cancellation Policy</h1><p>...</p></div>', 0, 1, 0),
(68, 'delivery_app_about', '<h3><strong>About GroceryHub Delivery Demo App</strong></h3><p>...</p>', 1, 0, 0),
(69, 'delivery_app_privacy_policy', '<div><h1>Privacy Policy</h1><p>...</p></div>', 1, 0, 0),
(70, 'delivery_app_terms_policy', '<div><h1>Terms &amp; Conditions</h1><p>...</p></div>', 1, 0, 0),
(71, 'auto_review_approval', '0', 0, 0, 0),
(72, 'google_recaptcha_status', '0', 0, 0, 0),
(73, 'chatgpt_status', '1', 0, 0, 0),
(74, 'chatgpt_api_key', 'YOUR_CHATGPT_API_KEY', 0, 0, 0),
(75, 'chatgpt_model', '{\"gpt-4\":{\"status\":0},\"gpt-4o\":{\"status\":1},\"gpt-4-mini\":{\"status\":0}}', 0, 0, 0),
(76, 'google_speech_api', 'YOUR_GOOGLE_SPEECH_API_KEY', 0, 1, 0),
(77, 'seller_can_complete_order', '1', 0, 0, 0),
(78, 'demo_mode', '0', 0, 0, 0),
(79, 'twak_live_chat_status', '1', 0, 0, 0),
(80, 'twak_live_chat_widget_code', '', 0, 0, 0),
(81, 'notification_order_pending_status', '1', 0, 0, 0),
(82, 'notification_order_pending_message', '{userName}, Your  order {orderId} is successfully placed', 0, 0, 0),
(83, 'notification_order_received_status', '1', 0, 0, 0),
(84, 'notification_order_received_message', '{userName}, we\'ve received your order {orderId} and will start processing it soon.', 0, 0, 0),
(85, 'notification_order_processed_status', '1', 0, 0, 0),
(86, 'notification_order_processed_message', '{userName}, your order {orderId} has been processed and is getting ready for shipment.', 0, 0, 0),
(87, 'notification_order_shipped_status', '1', 0, 0, 0),
(88, 'notification_order_shipped_message', '{userName}, your order {orderId} has been shipped and is on its way.', 0, 0, 0),
(89, 'notification_order_out_for_delivery_status', '1', 0, 0, 0),
(90, 'notification_order_out_for_delivery_message', '{userName}, your order {orderId} is out for delivery. It will reach you soon!', 0, 0, 0),
(91, 'notification_order_delivered_status', '1', 0, 0, 0),
(92, 'notification_order_delivered_message', '{userName}, your order {orderId} has been delivered. Enjoy your purchase!', 0, 0, 0),
(93, 'notification_order_cancelled_status', '1', 0, 0, 0),
(94, 'notification_order_cancelled_message', '{userName}, your order {orderId} has been cancelled. If this wasn\'t you, please contact support.', 0, 0, 0),
(95, 'notification_order_delivery_boy_assign_status', '1', 0, 0, 0),
(96, 'notification_order_delivery_boy_assign_message', '{userName}, a delivery partner has been assigned for your order {orderId}.', 0, 0, 0),
(97, 'notification_order_item_return_request_pending_status', '1', 0, 0, 0),
(98, 'notification_order_item_return_request_pending_message', '{userName}, your return request for order {orderId} is pending review.', 0, 0, 0),
(99, 'notification_order_item_return_request_approved_status', '1', 0, 0, 0),
(100, 'notification_order_item_return_request_approved_message', '{userName}, your return request for order {orderId} has been approved.', 0, 0, 0),
(101, 'notification_order_item_return_request_reject_status', '1', 0, 0, 0),
(102, 'notification_order_item_return_request_reject_message', '{userName}, your return request for order {orderId} has been rejected. Please check details or contact support.', 0, 0, 0),
(103, 'notification_order_item_return_request_return_to_deliveryboy_status', '1', 0, 0, 0),
(104, 'notification_order_item_return_request_return_to_deliveryboy_message', '{userName}, the return item for order {orderId} has been picked up by our delivery partner.', 0, 0, 0),
(105, 'notification_order_item_return_request_return_to_seller_status', '1', 0, 0, 0),
(106, 'notification_order_item_return_request_return_to_seller_message', '{userName}, the returned item for order {orderId} has reached the seller.', 0, 0, 0),
(107, 'notification_order_update_delivery_date_status', '1', 0, 0, 0),
(108, 'notification_order_update_delivery_date_message', '{userName}, the delivery date for your order {orderId} has been updated. Please check the app for details.', 0, 0, 0),
(109, 'phone_login', '1', 0, 1, 0),
(110, 'live_tracking', '1', 1, 1, 0),
(111, 'qr_code_search_status', '1', 0, 1, 0),
(112, 'voice_search_status', '1', 0, 1, 0),
(113, 'user_can_select_language', '1', 1, 1, 0),
(114, 'website_loading_text', 'Loading Website', 0, 0, 0),
(115, 'main_header_banner', '0', 0, 1, 0),
(116, 'main_header_banner_img', 'uploads/main_header_banner_img/1760795603_a33802a390730131f925.gif', 0, 1, 0),
(117, 'main_header_banner_overlay_text_color', '#ffd700', 0, 1, 0),
(118, 'deal_of_the_day_product_show_limit', '20', 0, 1, 0),
(119, 'deal_of_the_day_product_show_sort_by', 'low_to_high', 0, 1, 0),
(120, 'popular_product_show_limit', '20', 0, 1, 0),
(121, 'popular_product_show_sort_by', 'default', 0, 1, 0),
(122, 'logo_aspect_ratio', '1:1', 1, 0, 0),
(123, 'app_search_bar_content', 'Mango, orange, Apple', 0, 1, 0),
(124, 'popular_search', 'Tea, Chocolate, Atta, Milk, Biscuit', 0, 1, 0),
(125, 'delivery_charge_tax_status', '1', 0, 0, 0),
(126, 'delivery_charge_tax_inclusive', '1', 0, 0, 0),
(127, 'company_gst', 'SSSS', 0, 0, 0),
(128, 'additional_charge_tax_status', '1', 0, 0, 0),
(129, 'seller_app_about', 'aaa', 0, 0, 1),
(130, 'seller_app_privacy_policy', 'ss', 0, 0, 1),
(131, 'seller_app_terms_policy', 'ddd', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sidebar`
--

CREATE TABLE `sidebar` (
  `id` int(11) NOT NULL,
  `row_order` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `url` varchar(256) NOT NULL,
  `for_account_type` varchar(256) NOT NULL,
  `icon` varchar(256) NOT NULL,
  `is_it_have_child` int(11) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL COMMENT '0 if none or parent_sidebar_id ',
  `is_it_header` int(11) NOT NULL DEFAULT 0,
  `permission_category_short_code` varchar(256) NOT NULL,
  `is_it_have_badge` int(11) NOT NULL DEFAULT 0,
  `badge_type` text DEFAULT NULL,
  `badge_function` text NOT NULL,
  `badge_function_parameter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sidebar`
--

INSERT INTO `sidebar` (`id`, `row_order`, `title`, `url`, `for_account_type`, `icon`, `is_it_have_child`, `parent_id`, `is_it_header`, `permission_category_short_code`, `is_it_have_badge`, `badge_type`, `badge_function`, `badge_function_parameter`) VALUES
(1, 1, 'Product Section', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(2, 2, 'Category', '/admin/category', 'Admin', 'fi fi-tr-rectangle-list', 0, 0, 0, 'category', 0, NULL, '', NULL),
(3, 3, 'SubCategory', '/admin/subcategory', 'Admin', 'fi fi-tr-rectangle-list', 0, 0, 0, 'subcategory', 0, NULL, '', NULL),
(4, 5, 'Product', '#', 'Admin', 'fi fi-tr-box-open', 1, 0, 0, 'product', 0, NULL, '', NULL),
(5, 6, 'Add new Product', '/admin/product', 'Admin', 'fi fi-tr-box-open-full', 0, 4, 0, 'product', 0, NULL, '', NULL),
(6, 7, 'Bulk Import', '/admin/product/bulk-import', 'Admin', 'fi fi-tr-file-import', 0, 4, 0, 'product-bulk-import', 0, NULL, '', NULL),
(7, 8, 'Bulk Update', '/admin/product/bulk-update', 'Admin', 'fi fi-tr-rotate-square', 0, 4, 0, 'product-bulk-update', 0, NULL, '', NULL),
(8, 9, 'Taxes', '/admin/taxes', 'Admin', 'fi fi-tr-tax-alt', 0, 4, 0, 'taxes', 0, NULL, '', NULL),
(9, 10, 'Product Order', '/admin/product_order', 'Admin', 'fi fi-tr-selling', 0, 4, 0, 'product-order', 0, NULL, '', NULL),
(10, 6, 'Product List', '/admin/product-list', 'Admin', 'fi fi-tr-completed', 0, 4, 0, 'product', 0, NULL, '', NULL),
(12, 13, 'Category Order', '/admin/category_order', 'Admin', 'fi fi-tr-restock', 0, 0, 0, 'category-order', 0, NULL, '', NULL),
(13, 14, 'Subcategory Order', '/admin/subcategory_order', 'Admin', 'fi fi-tr-restock', 0, 0, 0, 'subcategory-order', 0, NULL, '', NULL),
(14, 15, 'Manage Seller', '#', 'Admin', 'fi fi-tr-seller', 1, 0, 0, 'seller', 0, NULL, '', NULL),
(15, 16, 'Add Seller', '/admin/seller', 'Admin', 'fi fi-tr-seller-store', 0, 14, 0, 'seller', 0, NULL, '', NULL),
(16, 17, 'Manage Seller List', '/admin/seller/list', 'Admin', 'fi fi-tr-seller', 0, 14, 0, 'seller', 0, NULL, '', NULL),
(17, 18, 'Seller Transaction', '/admin/seller/payment_history', 'Admin', 'fi fi-tr-transaction-euro', 0, 14, 0, 'seller-transaction', 0, NULL, '', NULL),
(18, 19, 'Delivery Section', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(19, 20, 'Manage Location', '#', 'Admin', 'fi fi-tr-region-pin-alt', 1, 0, 0, 'city', 0, NULL, '', NULL),
(20, 21, 'Manage City', '/admin/manage-city', 'Admin', 'fi fi-tr-city', 0, 19, 0, 'city', 0, NULL, '', NULL),
(21, 22, 'Deliverable Area', '/admin/deliverable-area', 'Admin', 'fi fi-tr-land-location', 0, 19, 0, 'deliverable_area', 0, NULL, '', NULL),
(22, 23, 'Deliverable Area List', '/admin/deliverable-area/view', 'Admin', 'fi fi-tr-route', 0, 19, 0, 'deliverable_area', 0, NULL, '', NULL),
(23, 24, 'Coupon', '/admin/coupon', 'Admin', 'fi fi-tr-badge-percent', 0, 0, 0, 'coupon', 0, NULL, '', NULL),
(24, 30, 'Banner', '/admin/banner', 'Admin', 'fi fi-tr-images', 0, 0, 0, 'banner', 0, NULL, '', NULL),
(25, 26, 'Time Slot', '/admin/timeslot', 'Admin', 'fi fi-tr-clock-three', 0, 0, 0, 'timeslot', 0, NULL, '', NULL),
(26, 27, 'Delivery Boy', '#', 'Admin', 'fi fi-tr-person-carry-box', 1, 0, 0, 'delivery-boy', 0, NULL, '', NULL),
(27, 28, 'Miscellaneous', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(28, 30, 'Manage Home Section', '/admin/sections', 'Admin', 'fi fi-tr-ticket-alt', 0, 0, 0, 'home-section', 0, NULL, '', NULL),
(29, 29, 'Notification', '/admin/notification', 'Admin', 'fi fi-tr-bell-ring', 0, 0, 0, 'notification', 0, NULL, '', NULL),
(30, 28, 'Users', '/admin/users', 'Admin', 'fi fi-tr-member-list', 0, 0, 0, 'manage-user', 0, NULL, '', NULL),
(31, 32, 'Order Section', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(32, 33, 'Order List', '#', 'Admin', 'fi fi-tr-order-history', 1, 0, 0, 'manage-orders', 1, 'badge-danger', 'totalOrderCount', NULL),
(34, 34, 'Setting', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(35, 35, 'Payment List', '/admin/payment', 'Admin', 'fi fi-tr-money-check', 0, 0, 0, 'payment-setting', 0, NULL, '', NULL),
(37, 37, 'App Setting', '/admin/setting', 'Admin', 'fi fi-tr-customization-cogwheel', 0, 0, 0, 'setting', 0, NULL, '', NULL),
(38, 38, 'Manage Roles', '/admin/roles', 'Admin', 'fi fi-tr-customization-cogwheel', 0, 0, 0, 'manage-roles', 0, NULL, '', NULL),
(42, 4, 'Brand', '/admin/brand', 'Admin', 'fi fi-tr-rectangle-list', 0, 0, 0, 'subcategory', 0, NULL, '', NULL),
(44, 2, 'Category', '/seller/category', 'Seller', 'fi fi-tr-rectangle-list', 0, 0, 0, 'category', 0, NULL, '', NULL),
(45, 3, 'SubCategory', '/seller/subcategory', 'Seller', 'fi fi-tr-rectangle-list', 0, 0, 0, 'subcategory', 0, NULL, '', NULL),
(46, 5, 'Product', '#', 'Seller', 'fi fi-tr-box-open', 1, 0, 0, 'product', 0, NULL, '', NULL),
(47, 6, 'Add new Product', '/seller/product', 'Seller', 'fi fi-tr-box-open-full', 0, 46, 0, 'product', 0, NULL, '', NULL),
(48, 7, 'Bulk Import', '/seller/product/bulk-import', 'Seller', 'fi fi-tr-file-import', 0, 46, 0, 'product-bulk-import', 0, NULL, '', NULL),
(49, 8, 'Bulk Update', '/seller/product/bulk-update', 'Seller', 'fi fi-tr-rotate-square', 0, 46, 0, 'product-bulk-update', 0, NULL, '', NULL),
(50, 9, 'Taxes', '/seller/taxes', 'Seller', 'fi fi-tr-tax-alt', 0, 46, 0, 'taxes', 0, NULL, '', NULL),
(52, 11, 'Product List', '/seller/product-list', 'Seller', 'fi fi-tr-completed', 0, 46, 0, 'product', 0, NULL, '', NULL),
(53, 11, 'Stock Management', '/admin/stock-management', 'Admin', 'fi fi-tr-box-open', 0, 0, 0, 'stock-management', 0, NULL, '', NULL),
(54, 37, 'System User', '/admin/system-user', 'Admin', 'fi fi-tr-users-alt', 0, 0, 0, 'system-user', 0, NULL, '', NULL),
(55, 27, 'Add Delivery Boy', '/admin/delivery_boy', 'Admin', 'fi fi-tr-truck-medical', 0, 26, 0, 'delivery-boy', 0, NULL, '', NULL),
(56, 27, 'Manage Delivery Boy', '/admin/delivery_boy/view', 'Admin', 'fi fi-tr-shipping-fast', 0, 26, 0, 'delivery-boy', 0, NULL, '', NULL),
(57, 27, 'Fund Transfer', '/admin/delivery_boy/fund_transfer', 'Admin', 'fi fi-tr-selling', 0, 26, 0, 'delivery-boy-cash-collection', 0, NULL, '', NULL),
(58, 27, 'Cash Collection', '/admin/delivery_boy/cash_collection', 'Admin', 'fi fi-tr-refund-alt', 0, 26, 0, 'delivery-boy-fund-transfer', 0, NULL, '', NULL),
(59, 6, 'Product Request', '/admin/product/request', 'Admin', 'fi fi-tr-seller-store', 0, 4, 0, 'seller-request', 0, NULL, '', NULL),
(60, 1, 'Orders', '/seller/orders', 'Seller', 'fi fi-tr-order-history', 0, 0, 0, 'manage-orders', 0, NULL, '', NULL),
(61, 12, 'Stock Management', '/seller/stock-management', 'Seller', 'fi fi-tr-box-open', 0, 46, 0, 'stock-management', 0, NULL, '', NULL),
(62, 13, 'Wallet Transaction', '/seller/wallet-transaction', 'Seller', 'fi fi-tr-box-open', 0, 0, 0, 'seller-transaction', 0, NULL, '', NULL),
(63, 14, 'Reports', '#', 'Seller', 'fi fi-tr-box-open', 1, 0, 0, 'reports', 0, NULL, '', NULL),
(64, 14, 'Product Selling Report', '/seller/product-selling-report', 'Seller', 'fi fi-tr-box-open', 0, 63, 0, 'product-selling-report', 0, NULL, '', NULL),
(65, 16, 'Sales Report', '/seller/selling-report', 'Seller', 'fi fi-tr-box-open', 0, 63, 0, 'selling-report', 0, NULL, '', NULL),
(66, 13, 'Withdrawl request', '/seller/withdrawal-request', 'Seller', 'fi fi-tr-box-open', 0, 0, 0, 'withdrawl-request', 0, NULL, '', NULL),
(67, 17, 'Return', '/seller/return-request', 'Seller', 'fi fi-tr-box-open', 0, 0, 0, 'return-request', 0, NULL, '', NULL),
(68, 33, 'Return', '/admin/return-request', 'Admin', 'fi fi-tr-box-open', 0, 0, 0, 'return-request', 0, NULL, '', NULL),
(69, 39, 'Customer App Policy', '/admin/customer-app-policy', 'Admin', 'fi fi-tr-memo-circle-check', 0, 0, 0, 'customer-app-policy', 0, NULL, '', NULL),
(70, 40, 'Delivery App Policy', '/admin/delivery-app-policy', 'Admin', 'fi fi-tr-memo-circle-check', 0, 0, 0, 'delivery-app-policy', 0, NULL, '', NULL),
(71, 0, 'All Order', '/admin/orders', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-danger', 'totalOrderCount', NULL),
(72, 0, 'Pending Order', '/admin/orders?status=1', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-warning', 'orderCountStatusWise', 1),
(73, 0, 'Received Order', '/admin/orders?status=2', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-primary	', 'orderCountStatusWise', 2),
(74, 0, 'Processed Order', '/admin/orders?status=3', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-info	', 'orderCountStatusWise', 3),
(75, 0, 'Shipped Order', '/admin/orders?status=4', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-secondary', 'orderCountStatusWise', 4),
(76, 0, 'Out For Delivery', '/admin/orders?status=5', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-light', 'orderCountStatusWise', 5),
(77, 0, 'Delivered Order', '/admin/orders?status=6', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-success	', 'orderCountStatusWise', 6),
(78, 0, 'Cancelled Order', '/admin/orders?status=7', 'Admin', 'fi fi-ts-circle', 0, 32, 0, 'manage-orders', 1, 'badge-danger', 'orderCountStatusWise', 7),
(79, 29, 'FAQ', '/admin/faq', 'Admin', 'fi fi-tr-question-square', 0, 0, 0, 'faq', 0, NULL, '', NULL),
(80, 29, 'Promotion', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(81, 29, 'Highlights', '/admin/highlight', 'Admin', 'fi fi-tr-megaphone', 0, 0, 0, 'highlights', 0, NULL, '', NULL),
(82, 33, 'AI Insight Report', '/admin/order-report', 'Admin', 'fi fi-tr-artificial-intelligence', 0, 0, 0, 'order-report-ai', 0, NULL, '', NULL),
(83, 36, 'Store Setting', '/admin/store-setting', 'Admin', 'fi fi-tr-it', 0, 0, 0, 'store-setting', 0, NULL, '', NULL),
(84, 35, 'SMS Gateway ', '/admin/sms-gateway', 'Admin', 'fi fi-tr-message-sms', 0, 0, 0, 'sms-gateway', 0, NULL, '', NULL),
(85, 2, 'Category', '/admin/category', 'Admin', 'fi fi-tr-rectangle-list', 0, 2, 0, 'category', 0, NULL, '', NULL),
(86, 2, 'Group Category', '/admin/group-category', 'Admin', 'fi fi-tr-rectangle-list', 0, 2, 0, 'category', 0, NULL, '', NULL),
(87, 2, 'Header Category', '/admin/header-category', 'Admin', 'fi fi-tr-rectangle-list', 0, 2, 0, 'category', 0, NULL, '', NULL),
(88, 0, 'POS Section', '', 'Admin', '', 0, 0, 1, '', 0, NULL, '', NULL),
(89, 0, 'POS Orders', '/admin/pos', 'Admin', 'fi fi-tr-payment-pos', 0, 0, 0, 'manage-pos-orders', 1, 'badge-danger', 'New', 0),
(90, 0, 'POS Report', '/admin/pos-report', 'Admin', 'fi fi-tr-point-of-sale-bill', 0, 0, 0, 'pos-order-report', 1, 'badge-danger', 'New', 0),
(91, 17, 'POS Report', '/seller/pos-report', 'Seller', 'fi fi-tr-point-of-sale-bill', 0, 63, 0, 'pos-order-report', 0, NULL, '', NULL),
(92, 1, 'POS Orders', '/seller/pos', 'Seller', 'fi fi-tr-payment-pos', 0, 0, 0, 'manage-pos-orders', 0, NULL, '', NULL),
(95, 30, 'Manage Home Screens', '/admin/home-screens', 'Admin', 'fi fi-tr-smart-home', 0, 0, 0, 'home-section', 0, NULL, '', NULL),
(96, 41, 'Seller App Policy', '/admin/seller-app-policy', 'Admin', 'fi fi-tr-memo-circle-check', 0, 0, 0, 'seller-app-policy', 0, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_gateway`
--

CREATE TABLE `sms_gateway` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `img` text NOT NULL,
  `is_active` tinyint(4) NOT NULL COMMENT '1 yes, 0 no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_gateway`
--

INSERT INTO `sms_gateway` (`id`, `name`, `value`, `img`, `is_active`) VALUES
(1, 'Twilio SMS', '{\"accountSid\":\"YOUR_TWILIO_ACCOUNT_SID\",\"authToken\":\"YOUR_TWILIO_AUTH_TOKEN\",\"twilioNumber\":\"YOUR_TWILIO_NUMBER\",\"message\":\"Your otp is #OTP#.\"}', 'assets/dist/img/twilio.png', 0),
(2, 'Nexmo SMS', '{\"vonageApiKey\":\"YOUR_VONAGE_API_KEY\",\"vonageApiSecret\":\"YOUR_VONAGE_API_SECRET\",\"smsSenderId\":\"YOUR_SENDER_ID\",\"messageText\":\"Your otp is #OTP#.\"}', 'assets/dist/img/vonage.webp', 0),
(3, '2Factor SMS', '{\"apiKey\":\"\",\"otp_template_name\":\"\"}', 'assets/dist/img/2factor.webp', 0),
(4, 'MSG91 SMS', '{\"otpTemplateId\":\"\",\"authKey\":\"YOUR_MSG91_AUTH_KEY\"}', 'assets/dist/img/msg91.webp', 0),
(5, 'Fast2SMS', '{\"apiKey\":\"\",\"sender_id\":\"\",\"message_id\":\"\"}', 'assets/dist/img/fast2sms.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `row_order` int(11) NOT NULL,
  `name` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_config`
--

CREATE TABLE `sys_config` (
  `cfg_id` int(11) NOT NULL,
  `cfg_token` varchar(255) NOT NULL,
  `cfg_origin` varchar(255) NOT NULL,
  `cfg_sync` bigint(20) NOT NULL DEFAULT 0,
  `cfg_state` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_config`
--

INSERT INTO `sys_config` (`cfg_id`, `cfg_token`, `cfg_origin`, `cfg_sync`, `cfg_state`) VALUES
(1, '06ea268857262ddf958607f801889c1b568fa5e2ee9ea2a169e02089f171e3b9', 'groceryhub-1-9-vop8nh8d6knn97rb2avyai3c.crysttalskingdom.com', 1774870686, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

CREATE TABLE `tax` (
  `id` int(11) NOT NULL,
  `tax` text NOT NULL,
  `percentage` int(11) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

CREATE TABLE `timeslot` (
  `id` int(11) NOT NULL,
  `mintime` text NOT NULL,
  `maxtime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezone`
--

CREATE TABLE `timezone` (
  `id` int(11) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `gmt` varchar(6) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezone`
--

INSERT INTO `timezone` (`id`, `timezone`, `gmt`, `is_active`) VALUES
(1, 'Pacific/Midway', '-11:00', 0),
(2, 'Pacific/Niue', '-11:00', 0),
(3, 'Pacific/Pago_Pago', '-11:00', 0),
(4, 'America/Adak', '-10:00', 0),
(5, 'Pacific/Honolulu', '-10:00', 0),
(6, 'Pacific/Johnston', '-10:00', 0),
(7, 'Pacific/Rarotonga', '-10:00', 0),
(8, 'Pacific/Tahiti', '-10:00', 0),
(9, 'Pacific/Marquesas', '-09:30', 0),
(10, 'America/Anchorage', '-09:00', 0),
(11, 'Pacific/Gambier', '-09:00', 0),
(12, 'America/Los_Angeles', '-08:00', 0),
(13, 'America/Tijuana', '-08:00', 0),
(14, 'America/Vancouver', '-08:00', 0),
(15, 'Pacific/Pitcairn', '-08:00', 0),
(16, 'America/Denver', '-07:00', 0),
(17, 'America/Edmonton', '-07:00', 0),
(18, 'America/Hermosillo', '-07:00', 0),
(19, 'America/Mazatlan', '-07:00', 0),
(20, 'America/Phoenix', '-07:00', 0),
(21, 'America/Yellowknife', '-07:00', 0),
(22, 'America/Belize', '-06:00', 0),
(23, 'America/Chicago', '-06:00', 0),
(24, 'America/Costa_Rica', '-06:00', 0),
(25, 'America/El_Salvador', '-06:00', 0),
(26, 'America/Guatemala', '-06:00', 0),
(27, 'America/Managua', '-06:00', 0),
(28, 'America/Mexico_City', '-06:00', 0),
(29, 'America/Regina', '-06:00', 0),
(30, 'America/Tegucigalpa', '-06:00', 0),
(31, 'America/Winnipeg', '-06:00', 0),
(32, 'Pacific/Galapagos', '-06:00', 0),
(33, 'America/Bogota', '-05:00', 0),
(34, 'America/Cayman', '-05:00', 0),
(35, 'America/Guayaquil', '-05:00', 0),
(36, 'America/Havana', '-05:00', 0),
(37, 'America/Jamaica', '-05:00', 0),
(38, 'America/Lima', '-05:00', 0),
(39, 'America/New_York', '-05:00', 0),
(40, 'America/Panama', '-05:00', 0),
(41, 'America/Toronto', '-05:00', 0),
(42, 'Pacific/Easter', '-05:00', 0),
(43, 'America/Caracas', '-04:30', 0),
(44, 'America/Asuncion', '-04:00', 0),
(45, 'America/Barbados', '-04:00', 0),
(46, 'America/Boa_Vista', '-04:00', 0),
(47, 'America/Campo_Grande', '-04:00', 0),
(48, 'America/Cuiaba', '-04:00', 0),
(49, 'America/Curacao', '-04:00', 0),
(50, 'America/Guayaquil', '-05:00', 0),
(51, 'America/Halifax', '-04:00', 0),
(52, 'America/Guyana', '-04:00', 0),
(53, 'America/Manaus', '-04:00', 0),
(54, 'America/Martinique', '-04:00', 0),
(55, 'America/Port_of_Spain', '-04:00', 0),
(56, 'America/Puerto_Rico', '-04:00', 0),
(57, 'America/Santo_Domingo', '-04:00', 0),
(58, 'America/St_Kitts', '-04:00', 0),
(59, 'America/St_Lucia', '-04:00', 0),
(60, 'America/St_Vincent', '-04:00', 0),
(61, 'America/Thule', '-04:00', 0),
(62, 'America/Tortola', '-04:00', 0),
(63, 'America/Araguaina', '-03:00', 0),
(64, 'America/Belem', '-03:00', 0),
(65, 'America/Buenos_Aires', '-03:00', 0),
(66, 'America/Cayenne', '-03:00', 0),
(67, 'America/Fortaleza', '-03:00', 0),
(68, 'America/Godthab', '-03:00', 0),
(69, 'America/Montevideo', '-03:00', 0),
(70, 'America/Sao_Paulo', '-03:00', 0),
(71, 'Atlantic/Bermuda', '-04:00', 0),
(72, 'Atlantic/Stanley', '-03:00', 0),
(73, 'America/Santiago', '-03:00', 0),
(74, 'America/Noronha', '-02:00', 0),
(75, 'Atlantic/South_Georgia', '-02:00', 0),
(76, 'America/Scoresbysund', '-01:00', 0),
(77, 'Atlantic/Azores', '-01:00', 0),
(78, 'Atlantic/Cape_Verde', '-01:00', 0),
(79, 'Africa/Abidjan', '+00:00', 0),
(80, 'Africa/Accra', '+00:00', 0),
(81, 'Africa/Bamako', '+00:00', 0),
(82, 'Africa/Banjul', '+00:00', 0),
(83, 'Africa/Bissau', '+00:00', 0),
(84, 'Africa/Conakry', '+00:00', 0),
(85, 'Africa/Dakar', '+00:00', 0),
(86, 'Africa/Freetown', '+00:00', 0),
(87, 'Africa/Lome', '+00:00', 0),
(88, 'Africa/Monrovia', '+00:00', 0),
(89, 'Africa/Nouakchott', '+00:00', 0),
(90, 'Africa/Ouagadougou', '+00:00', 0),
(91, 'Africa/Sao_Tome', '+00:00', 0),
(92, 'Atlantic/Canary', '+00:00', 0),
(93, 'Atlantic/Faeroe', '+00:00', 0),
(94, 'Atlantic/Reykjavik', '+00:00', 0),
(95, 'Atlantic/St_Helena', '+00:00', 0),
(96, 'Europe/Dublin', '+00:00', 0),
(97, 'Europe/Lisbon', '+00:00', 0),
(98, 'Europe/London', '+00:00', 0),
(99, 'Africa/Algiers', '+01:00', 0),
(100, 'Africa/Bangui', '+01:00', 0),
(101, 'Africa/Brazzaville', '+01:00', 0),
(102, 'Africa/Casablanca', '+01:00', 0),
(103, 'Africa/Douala', '+01:00', 0),
(104, 'Africa/El_Aaiun', '+01:00', 0),
(105, 'Africa/Kinshasa', '+01:00', 0),
(106, 'Africa/Lagos', '+01:00', 0),
(107, 'Africa/Libreville', '+01:00', 0),
(108, 'Africa/Luanda', '+01:00', 0),
(109, 'Africa/Malabo', '+01:00', 0),
(110, 'Africa/Ndjamena', '+01:00', 0),
(111, 'Africa/Niamey', '+01:00', 0),
(112, 'Africa/Porto-Novo', '+01:00', 0),
(113, 'Africa/Tunis', '+01:00', 0),
(114, 'Africa/Windhoek', '+01:00', 0),
(115, 'Europe/Amsterdam', '+01:00', 0),
(116, 'Europe/Andorra', '+01:00', 0),
(117, 'Europe/Belgrade', '+01:00', 0),
(118, 'Europe/Berlin', '+01:00', 0),
(119, 'Europe/Brussels', '+01:00', 0),
(120, 'Europe/Budapest', '+01:00', 0),
(121, 'Europe/Copenhagen', '+01:00', 0),
(122, 'Europe/Gibraltar', '+01:00', 0),
(123, 'Europe/Luxembourg', '+01:00', 0),
(124, 'Europe/Madrid', '+01:00', 0),
(125, 'Europe/Malta', '+01:00', 0),
(126, 'Europe/Monaco', '+01:00', 0),
(127, 'Europe/Oslo', '+01:00', 0),
(128, 'Europe/Paris', '+01:00', 0),
(129, 'Europe/Prague', '+01:00', 0),
(130, 'Europe/Rome', '+01:00', 0),
(131, 'Europe/Stockholm', '+01:00', 0),
(132, 'Europe/Vienna', '+01:00', 0),
(133, 'Europe/Warsaw', '+01:00', 0),
(134, 'Europe/Zagreb', '+01:00', 0),
(135, 'Europe/Zurich', '+01:00', 0),
(136, 'Europe/Bratislava', '+01:00', 0),
(137, 'Europe/Sarajevo', '+01:00', 0),
(138, 'Europe/Skopje', '+01:00', 0),
(139, 'Europe/Vilnius', '+02:00', 0),
(140, 'Europe/Athens', '+02:00', 0),
(141, 'Europe/Bucharest', '+02:00', 0),
(142, 'Europe/Helsinki', '+02:00', 0),
(143, 'Europe/Istanbul', '+02:00', 0),
(144, 'Europe/Kaliningrad', '+02:00', 0),
(145, 'Europe/Kiev', '+02:00', 0),
(146, 'Europe/Minsk', '+03:00', 0),
(147, 'Europe/Riga', '+02:00', 0),
(148, 'Europe/Sofia', '+02:00', 0),
(149, 'Europe/Tallinn', '+02:00', 0),
(150, 'Europe/Zaporozhye', '+02:00', 0),
(151, 'Europe/Istanbul', '+03:00', 0),
(152, 'Europe/Moscow', '+03:00', 0),
(153, 'Europe/Samara', '+04:00', 0),
(154, 'Europe/Ulyanovsk', '+04:00', 0),
(155, 'Europe/Volgograd', '+03:00', 0),
(156, 'Asia/Aden', '+03:00', 0),
(157, 'Asia/Amman', '+02:00', 0),
(158, 'Asia/Baghdad', '+03:00', 0),
(159, 'Asia/Bahrain', '+03:00', 0),
(160, 'Asia/Beirut', '+02:00', 0),
(161, 'Asia/Damascus', '+02:00', 0),
(162, 'Asia/Dubai', '+04:00', 0),
(163, 'Asia/Gaza', '+02:00', 0),
(164, 'Asia/Hebron', '+02:00', 0),
(165, 'Asia/Jerusalem', '+02:00', 0),
(166, 'Asia/Kuwait', '+03:00', 0),
(167, 'Asia/Qatar', '+03:00', 0),
(168, 'Asia/Riyadh', '+03:00', 0),
(169, 'Europe/Astrakhan', '+04:00', 0),
(170, 'Europe/Istanbul', '+03:00', 0),
(171, 'Europe/Kirov', '+03:00', 0),
(172, 'Europe/Minsk', '+03:00', 0),
(173, 'Africa/Addis_Ababa', '+03:00', 0),
(174, 'Africa/Asmara', '+03:00', 0),
(175, 'Africa/Dar_es_Salaam', '+03:00', 0),
(176, 'Africa/Djibouti', '+03:00', 0),
(177, 'Africa/Kampala', '+03:00', 0),
(178, 'Africa/Khartoum', '+03:00', 0),
(179, 'Africa/Mogadishu', '+03:00', 0),
(180, 'Africa/Nairobi', '+03:00', 0),
(181, 'Antarctica/Syowa', '+03:00', 0),
(182, 'Asia/Tehran', '+03:30', 0),
(183, 'Asia/Aqtau', '+05:00', 0),
(184, 'Asia/Aqtobe', '+05:00', 0),
(185, 'Asia/Atyrau', '+05:00', 0),
(186, 'Asia/Dushanbe', '+05:00', 0),
(187, 'Asia/Karachi', '+05:00', 0),
(188, 'Asia/Oral', '+05:00', 0),
(189, 'Asia/Samarkand', '+05:00', 0),
(190, 'Asia/Tashkent', '+05:00', 0),
(191, 'Indian/Maldives', '+05:00', 1),
(192, 'Asia/Kolkata', '+05:30', 0),
(193, 'Asia/Kathmandu', '+05:45', 0),
(194, 'Antarctica/Mawson', '+05:00', 0),
(195, 'Asia/Almaty', '+06:00', 0),
(196, 'Asia/Bishkek', '+06:00', 0),
(197, 'Asia/Dhaka', '+06:00', 0),
(198, 'Asia/Omsk', '+06:00', 0),
(199, 'Asia/Qyzylorda', '+06:00', 0),
(200, 'Asia/Thimphu', '+06:00', 0),
(201, 'Indian/Chagos', '+06:00', 0),
(202, 'Asia/Yekaterinburg', '+05:00', 0),
(203, 'Indian/Cocos', '+06:30', 0),
(204, 'Asia/Yangon', '+06:30', 0),
(205, 'Antarctica/Vostok', '+06:00', 0),
(206, 'Asia/Bangkok', '+07:00', 0),
(207, 'Asia/Barnaul', '+07:00', 0),
(208, 'Asia/Ho_Chi_Minh', '+07:00', 0),
(209, 'Asia/Hovd', '+07:00', 0),
(210, 'Asia/Jakarta', '+07:00', 0),
(211, 'Asia/Krasnoyarsk', '+07:00', 0),
(212, 'Asia/Novokuznetsk', '+07:00', 0),
(213, 'Asia/Novosibirsk', '+07:00', 0),
(214, 'Asia/Phnom_Penh', '+07:00', 0),
(215, 'Asia/Pontianak', '+07:00', 0),
(216, 'Asia/Tomsk', '+07:00', 0),
(217, 'Asia/Vientiane', '+07:00', 0),
(218, 'Indian/Christmas', '+07:00', 0),
(219, 'Antarctica/Davis', '+07:00', 0),
(220, 'Asia/Brunei', '+08:00', 0),
(221, 'Asia/Chita', '+09:00', 0),
(222, 'Asia/Choibalsan', '+08:00', 0),
(223, 'Asia/Hong_Kong', '+08:00', 0),
(224, 'Asia/Irkutsk', '+08:00', 0),
(225, 'Asia/Kuala_Lumpur', '+08:00', 0),
(226, 'Asia/Kuching', '+08:00', 0),
(227, 'Asia/Macau', '+08:00', 0),
(228, 'Asia/Makassar', '+08:00', 0),
(229, 'Asia/Manila', '+08:00', 0),
(230, 'Asia/Shanghai', '+08:00', 0),
(231, 'Asia/Singapore', '+08:00', 0),
(232, 'Asia/Taipei', '+08:00', 0),
(233, 'Asia/Ulaanbaatar', '+08:00', 0),
(234, 'Australia/Perth', '+08:00', 0),
(235, 'Asia/Pyongyang', '+09:00', 0),
(236, 'Asia/Seoul', '+09:00', 0),
(237, 'Asia/Tokyo', '+09:00', 0),
(238, 'Asia/Yakutsk', '+09:00', 0),
(239, 'Pacific/Palau', '+09:00', 0),
(240, 'Australia/Darwin', '+09:30', 0),
(241, 'Antarctica/DumontDUrville', '+10:00', 0),
(242, 'Asia/Vladivostok', '+10:00', 0),
(243, 'Australia/Brisbane', '+10:00', 0),
(244, 'Australia/Hobart', '+11:00', 0),
(245, 'Australia/Sydney', '+11:00', 0),
(246, 'Pacific/Chuuk', '+10:00', 0),
(247, 'Pacific/Guam', '+10:00', 0),
(248, 'Pacific/Port_Moresby', '+10:00', 0),
(249, 'Pacific/Efate', '+11:00', 0),
(250, 'Pacific/Guadalcanal', '+11:00', 0),
(251, 'Pacific/Kosrae', '+11:00', 0),
(252, 'Pacific/Noumea', '+11:00', 0),
(253, 'Pacific/Pohnpei', '+11:00', 0),
(254, 'Pacific/Norfolk', '+11:00', 0),
(255, 'Asia/Magadan', '+11:00', 0),
(256, 'Asia/Sakhalin', '+11:00', 0),
(257, 'Asia/Srednekolymsk', '+11:00', 0),
(258, 'Pacific/Fiji', '+12:00', 0),
(259, 'Pacific/Funafuti', '+12:00', 0),
(260, 'Pacific/Kwajalein', '+12:00', 0),
(261, 'Pacific/Majuro', '+12:00', 0),
(262, 'Pacific/Nauru', '+12:00', 0),
(263, 'Pacific/Tarawa', '+12:00', 0),
(264, 'Pacific/Wake', '+12:00', 0),
(265, 'Pacific/Wallis', '+12:00', 0),
(266, 'Asia/Kamchatka', '+12:00', 0),
(267, 'Asia/Anadyr', '+12:00', 0),
(268, 'Pacific/Apia', '+13:00', 0),
(269, 'Pacific/Enderbury', '+13:00', 0),
(270, 'Pacific/Fakaofo', '+13:00', 0),
(271, 'Pacific/Tongatapu', '+13:00', 0),
(272, 'Pacific/Kiritimati', '+14:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `used_coupon`
--

CREATE TABLE `used_coupon` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `img` text DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `login_type` enum('normal','google','mobile','apple') NOT NULL,
  `ref_code` varchar(10) NOT NULL,
  `ref_by` int(11) NOT NULL,
  `wallet` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_mobile_verified` tinyint(1) NOT NULL DEFAULT 0,
  `password_reset_link` text DEFAULT NULL,
  `apple_user_id` text DEFAULT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ref_user_id` int(11) NOT NULL DEFAULT 0,
  `amount` double(8,2) NOT NULL,
  `closing_amount` double(8,2) NOT NULL,
  `flag` text NOT NULL,
  `remark` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` int(11) NOT NULL,
  `wishlist_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_charge_taxes`
--
ALTER TABLE `additional_charge_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_act_tax_id` (`tax_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_report_data`
--
ALTER TABLE `ai_report_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_home_screen` (`home_screen_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_group`
--
ALTER TABLE `category_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliverable_area`
--
ALTER TABLE `deliverable_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_boy`
--
ALTER TABLE `delivery_boy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_boy_fund_transfer`
--
ALTER TABLE `delivery_boy_fund_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_boy_notification`
--
ALTER TABLE `delivery_boy_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_boy_transaction`
--
ALTER TABLE `delivery_boy_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_charge_taxes`
--
ALTER TABLE `delivery_charge_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dct_tax` (`tax_id`);

--
-- Indexes for table `delivery_tracking`
--
ALTER TABLE `delivery_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_token`
--
ALTER TABLE `device_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `header_category`
--
ALTER TABLE `header_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_screens`
--
ALTER TABLE `home_screens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_slug` (`slug`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_orders` (`is_pos_order`,`pos_created_by`);

--
-- Indexes for table `order_additional_charges`
--
ALTER TABLE `order_additional_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_charge_taxes`
--
ALTER TABLE `order_charge_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_oct_order_id` (`order_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_product_taxes`
--
ALTER TABLE `order_product_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_id` (`order_product_id`);

--
-- Indexes for table `order_return_request`
--
ALTER TABLE `order_return_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_return_request_order_products_id_unique` (`order_products_id`);

--
-- Indexes for table `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order_status` (`orders_id`,`status`);

--
-- Indexes for table `order_status_lists`
--
ALTER TABLE `order_status_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_category`
--
ALTER TABLE `permission_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_cart_sessions`
--
ALTER TABLE `pos_cart_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `pos_payment_method`
--
ALTER TABLE `pos_payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_status` (`seller_id`,`status`,`is_delete`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_enquiry`
--
ALTER TABLE `product_enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sort_type`
--
ALTER TABLE `product_sort_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_tag_product_id_tag_id_unique` (`product_id`,`tag_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `product_taxes`
--
ALTER TABLE `product_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `tax_id` (`tax_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_status` (`product_id`,`status`);

--
-- Indexes for table `rate_order`
--
ALTER TABLE `rate_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `read_notification`
--
ALTER TABLE `read_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_home_screen` (`home_screen_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `section_brands`
--
ALTER TABLE `section_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_categories`
--
ALTER TABLE `section_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_section_cat` (`section_id`,`category_id`),
  ADD KEY `idx_section` (`section_id`);

--
-- Indexes for table `section_highlights`
--
ALTER TABLE `section_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_products`
--
ALTER TABLE `section_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_section_prod` (`section_id`,`product_id`),
  ADD KEY `idx_section` (`section_id`);

--
-- Indexes for table `section_sellers`
--
ALTER TABLE `section_sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `seller_categories`
--
ALTER TABLE `seller_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_wallet_transaction`
--
ALTER TABLE `seller_wallet_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sidebar`
--
ALTER TABLE `sidebar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_gateway`
--
ALTER TABLE `sms_gateway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_config`
--
ALTER TABLE `sys_config`
  ADD PRIMARY KEY (`cfg_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_name_unique` (`name`);

--
-- Indexes for table `tax`
--
ALTER TABLE `tax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezone`
--
ALTER TABLE `timezone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `used_coupon`
--
ALTER TABLE `used_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_charge_taxes`
--
ALTER TABLE `additional_charge_taxes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ai_report_data`
--
ALTER TABLE `ai_report_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_group`
--
ALTER TABLE `category_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliverable_area`
--
ALTER TABLE `deliverable_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boy`
--
ALTER TABLE `delivery_boy`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boy_fund_transfer`
--
ALTER TABLE `delivery_boy_fund_transfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boy_notification`
--
ALTER TABLE `delivery_boy_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boy_transaction`
--
ALTER TABLE `delivery_boy_transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_charge_taxes`
--
ALTER TABLE `delivery_charge_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_tracking`
--
ALTER TABLE `delivery_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_token`
--
ALTER TABLE `device_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `header_category`
--
ALTER TABLE `header_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home`
--
ALTER TABLE `home`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_screens`
--
ALTER TABLE `home_screens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_additional_charges`
--
ALTER TABLE `order_additional_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_charge_taxes`
--
ALTER TABLE `order_charge_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_taxes`
--
ALTER TABLE `order_product_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_request`
--
ALTER TABLE `order_return_request`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_lists`
--
ALTER TABLE `order_status_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permission_category`
--
ALTER TABLE `permission_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `pos_cart_sessions`
--
ALTER TABLE `pos_cart_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_payment_method`
--
ALTER TABLE `pos_payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_enquiry`
--
ALTER TABLE `product_enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sort_type`
--
ALTER TABLE `product_sort_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_taxes`
--
ALTER TABLE `product_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate_order`
--
ALTER TABLE `rate_order`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `read_notification`
--
ALTER TABLE `read_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_brands`
--
ALTER TABLE `section_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_categories`
--
ALTER TABLE `section_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_highlights`
--
ALTER TABLE `section_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_products`
--
ALTER TABLE `section_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_sellers`
--
ALTER TABLE `section_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_categories`
--
ALTER TABLE `seller_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_wallet_transaction`
--
ALTER TABLE `seller_wallet_transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `sidebar`
--
ALTER TABLE `sidebar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `sms_gateway`
--
ALTER TABLE `sms_gateway`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_config`
--
ALTER TABLE `sys_config`
  MODIFY `cfg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax`
--
ALTER TABLE `tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeslot`
--
ALTER TABLE `timeslot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezone`
--
ALTER TABLE `timezone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT for table `used_coupon`
--
ALTER TABLE `used_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery_charge_taxes`
--
ALTER TABLE `delivery_charge_taxes`
  ADD CONSTRAINT `fk_dct_tax` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_charge_taxes`
--
ALTER TABLE `order_charge_taxes`
  ADD CONSTRAINT `fk_oct_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- --------------------------------------------------------

--
-- Seeding default record for table `seller`
--

INSERT INTO `seller` (`id`, `name`, `store_name`, `slug`, `email`, `password`, `mobile`, `status`, `commission`, `balance`) VALUES
(1, 'Default Seller', 'GoMart Store', 'gomart-store', 'seller@gmail.com', '$2y$10$kqpq3JQXmSsmgXjti9y5a.0sYmcvAKQAXxUICzK4oHna8p7lDKZqy', '1234567890', 1, 0.00, 0.00)
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);
