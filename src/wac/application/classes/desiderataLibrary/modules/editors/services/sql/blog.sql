-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2016 at 05:11 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sr_desideratalibrary_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries_tbl`
--

CREATE TABLE IF NOT EXISTS `countries_tbl` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `country_639_2` char(3) DEFAULT NULL,
  `country_639_1` char(2) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries_tbl`
--

INSERT INTO `countries_tbl` (`country_id`, `country_name`, `country_639_2`, `country_639_1`) VALUES
(1, 'Afrikaans', 'afr', 'af'),
(2, 'Albanian', 'alb', 'sq'),
(3, 'Amharic', 'amh', 'am'),
(4, 'Arabic', 'ara', 'ar'),
(5, 'Armenian', 'arm', 'hy'),
(6, 'Assamese', 'asm', 'as'),
(7, 'Avestan', 'ave', 'ae'),
(8, 'Aymara', 'aym', 'ay'),
(9, 'Azerbaijani', 'aze', 'az'),
(10, 'Bashkir', 'bak', 'ba'),
(11, 'Basque', 'baq', 'eu'),
(12, 'Belarusian', 'bel', 'be'),
(13, 'Bengali', 'ben', 'bn'),
(14, 'Bihari', 'bih', 'bh'),
(15, 'Bislama', 'bis', 'bi'),
(16, 'Bosnian', 'bos', 'bs'),
(17, 'Breton', 'bre', 'br'),
(18, 'Bulgarian', 'bul', 'bg'),
(19, 'Burmese', 'bur', 'my'),
(20, 'Catalan', 'cat', 'ca'),
(21, 'Chamorro', 'cha', 'ch'),
(22, 'Chechen', 'che', 'ce'),
(23, 'Chichewa', 'nya', 'ny'),
(24, 'Chinese', 'chi', 'zh'),
(25, 'Church Slavic', 'chu', 'cu'),
(26, 'Chuvash', 'chv', 'cv'),
(27, 'Cornish', 'cor', 'kw'),
(28, 'Corsican', 'cos', 'co'),
(29, 'Croatian', 'hrv', 'hr'),
(30, 'Czech', 'cze', 'cs'),
(31, 'Danish', 'dan', 'da'),
(32, 'Dutch', 'nld', 'nl'),
(33, 'Dzongkha', 'dzo', 'dz'),
(34, 'English', 'eng', 'en'),
(35, 'Esperanto', 'epo', 'eo'),
(36, 'Estonian', 'est', 'et'),
(37, 'Faroese', 'fao', 'fo'),
(38, 'Fijian', 'fij', 'fj'),
(39, 'Finnish', 'fin', 'fi'),
(40, 'French', 'fra', 'fr'),
(41, 'Frisian', 'fry', 'fy'),
(42, 'Gaelic', 'gla', 'gd'),
(43, 'Galician', 'glg', 'gl'),
(44, 'Georgian', 'geo', 'ka'),
(45, 'German', 'deu', 'de'),
(46, 'Greek (Modern)', 'ell', 'el'),
(47, 'Guarani', 'grn', 'gn'),
(48, 'Gujarati', 'guj', 'gu'),
(49, 'Hebrew', 'heb', 'he'),
(50, 'Herero', 'her', 'hz'),
(51, 'Hindi', 'hin', 'hi'),
(52, 'Hiri Motu', 'hmo', 'ho'),
(53, 'Hungarian', 'hun', 'hu'),
(54, 'Icelandic', 'isl', 'is'),
(55, 'Indonesian', 'ind', 'id'),
(56, 'Interlingua (International Auxiliary Language Association)', 'ina', 'ia'),
(57, 'Interlingue', 'ile', 'ie'),
(58, 'Inuktitut', 'iku', 'iu'),
(59, 'Inupiaq', 'ipk', 'ik'),
(60, 'Irish', 'gle', 'ga'),
(61, 'Italian', 'ita', 'it'),
(62, 'Japanese', 'jpn', 'ja'),
(63, 'Javanese', 'jav', 'jw'),
(64, 'Kalaallisut', 'kal', 'kl'),
(65, 'Kannada', 'kan', 'kn'),
(66, 'Kashmiri', 'kas', 'ks'),
(67, 'Kazakh', 'kaz', 'kk'),
(68, 'Khmer', 'khm', 'km'),
(69, 'Kikuyu', 'kik', 'ki'),
(70, 'Kinyarwanda', 'kin', 'rw'),
(71, 'Kirghiz', 'kir', 'ky'),
(72, 'Komi', 'kom', 'kv'),
(73, 'Korean', 'kor', 'ko'),
(74, 'Kuanyama', 'kua', 'kj'),
(75, 'Kurdish', 'kur', 'ku'),
(76, 'Lao', 'lao', 'lo'),
(77, 'Latin', 'lat', 'la'),
(78, 'Latvian', 'lav', 'lv'),
(79, 'Lingala', 'lin', 'ln'),
(80, 'Lithuanian', 'lit', 'lt'),
(81, 'Luxembourgish', 'ltz', 'lb'),
(82, 'Macedonian', 'mkd', 'mk'),
(83, 'Malagasy', 'mlg', 'mg'),
(84, 'Malay', 'msa', 'ms'),
(85, 'Malayalam', 'mal', 'ml'),
(86, 'Maltese', 'mlt', 'mt'),
(87, 'Manx', 'glv', 'gv'),
(88, 'Maori', 'mao', 'mi'),
(89, 'Marathi', 'mar', 'mr'),
(90, 'Marshallese', 'mah', 'mh'),
(91, 'Moldavian', 'mol', 'mo'),
(92, 'Mongolian', 'mon', 'mn'),
(93, 'Nauru', 'nau', 'na'),
(94, 'Navajo', 'nav', 'nv'),
(95, 'Ndebele, North', 'nde', 'nd'),
(96, 'Ndebele, South', 'nbl', 'nr'),
(97, 'Ndonga', 'ndo', 'ng'),
(98, 'Nepali', 'nep', 'ne'),
(99, 'Northern Sami', 'sme', 'se'),
(100, 'Norwegian', 'nor', 'no'),
(101, 'Norwegian Bokmål', 'nob', 'nb'),
(102, 'Norwegian Nynorsk', 'nno', 'nn'),
(103, 'Occitan (post 1500)', 'oci', 'oc'),
(104, 'Oriya', 'ori', 'or'),
(105, 'Oromo', 'orm', 'om'),
(106, 'Ossetian', 'oss', 'os'),
(107, 'Pali', 'pli', 'pi'),
(108, 'Panjabi', 'pan', 'pa'),
(109, 'Persian', 'fas', 'fa'),
(110, 'Polish', 'pol', 'pl'),
(111, 'Portuguese', 'por', 'pt'),
(112, 'Pushto', 'pus', 'ps'),
(113, 'Quechua', 'que', 'qu'),
(114, 'Raeto-Romance', 'roh', 'rm'),
(115, 'Romanian', 'ron', 'ro'),
(116, 'Rundi', 'run', 'rn'),
(117, 'Russian', 'rus', 'ru'),
(118, 'Samoan', 'smo', 'sm'),
(119, 'Sango', 'sag', 'sg'),
(120, 'Sanskrit', 'san', 'sa'),
(121, 'Sardinian', 'srd', 'sc'),
(122, 'Serbian', 'srp', 'sr'),
(123, 'Shona', 'sna', 'sn'),
(124, 'Sindhi', 'snd', 'sd'),
(125, 'Sinhalese', 'sin', 'si'),
(126, 'Slovak', 'slo', 'sk'),
(127, 'Slovenian', 'slv', 'sl'),
(128, 'Somali', 'som', 'so'),
(129, 'Sotho, Southern', 'sot', 'st'),
(130, 'Spanish', 'spa', 'es'),
(131, 'Sundanese', 'sun', 'su'),
(132, 'Swahili', 'swa', 'sw'),
(133, 'Swati', 'ssw', 'ss'),
(134, 'Swedish', 'swe', 'sv'),
(135, 'Tagalog', 'tgl', 'tl'),
(136, 'Tahitian', 'tah', 'ty'),
(137, 'Tajik', 'tgk', 'tg'),
(138, 'Tamil', 'tam', 'ta'),
(139, 'Tatar', 'tat', 'tt'),
(140, 'Telugu', 'tel', 'te'),
(141, 'Thai', 'tha', 'th'),
(142, 'Tibetan', 'bod', 'bo'),
(143, 'Tsonga', 'tso', 'ts'),
(144, 'Tswana', 'tsn', 'tn'),
(145, 'Turkish', 'tur', 'tr'),
(146, 'Turkmen', 'tuk', 'tk'),
(147, 'Twi', 'twi', 'tw'),
(148, 'Uighur', 'uig', 'ug'),
(149, 'Ukrainian', 'ukr', 'uk'),
(150, 'Urdu', 'urd', 'ur'),
(151, 'Uzbek', 'uzb', 'uz'),
(152, 'Vietnamese', 'vie', 'vi'),
(153, 'Volapük', 'vol', 'vo'),
(154, 'Welsh', 'wel', 'cy'),
(155, 'Welsh', 'cym', 'cy'),
(156, 'Wolof', 'wol', 'wo'),
(157, 'Xhosa', 'xho', 'xh'),
(158, 'Yiddish', 'yid', 'yi'),
(159, 'Zhuang', 'zha', 'za'),
(160, 'Zulu', 'zul', 'zu');

-- --------------------------------------------------------

--
-- Table structure for table `documents_detail_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_detail_tbl` (
  `document_detail_id` int(10) unsigned NOT NULL,
  `document_detail_FK_document_id` int(10) unsigned NOT NULL,
  `document_detail_FK_language_id` int(10) unsigned NOT NULL,
  `document_detail_FK_user_id` int(10) unsigned NOT NULL,
  `document_detail_modificationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `document_detail_status` varchar(9) NOT NULL DEFAULT 'DRAFT',
  `document_detail_translated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `document_detail_object` longtext NOT NULL,
  `document_detail_isVisible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `document_detail_note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_datetime_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_datetime_tbl` (
  `document_index_datetime_id` int(10) unsigned NOT NULL,
  `document_index_datetime_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_datetime_name` varchar(100) NOT NULL,
  `document_index_datetime_value` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_date_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_date_tbl` (
  `document_index_date_id` int(10) unsigned NOT NULL,
  `document_index_date_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_date_name` varchar(100) NOT NULL,
  `document_index_date_value` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_fulltext_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_fulltext_tbl` (
  `document_index_fulltext_id` int(10) unsigned NOT NULL,
  `document_index_fulltext_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_fulltext_name` varchar(100) NOT NULL,
  `document_index_fulltext_value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_int_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_int_tbl` (
  `document_index_int_id` int(10) unsigned NOT NULL,
  `document_index_int_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_int_name` varchar(100) NOT NULL,
  `document_index_int_value` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_text_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_text_tbl` (
  `document_index_text_id` int(10) unsigned NOT NULL,
  `document_index_text_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_text_name` varchar(100) NOT NULL,
  `document_index_text_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_time_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_index_time_tbl` (
  `document_index_time_id` int(10) unsigned NOT NULL,
  `document_index_time_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_time_name` varchar(100) NOT NULL,
  `document_index_time_value` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_tbl`
--

CREATE TABLE IF NOT EXISTS `documents_tbl` (
  `document_id` int(10) unsigned NOT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `document_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `document_FK_site_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `joins_tbl`
--

CREATE TABLE IF NOT EXISTS `joins_tbl` (
  `join_id` int(1) unsigned NOT NULL,
  `join_FK_source_id` int(10) unsigned NOT NULL,
  `join_FK_dest_id` int(10) unsigned NOT NULL,
  `join_objectName` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `joins_tbl`
--

INSERT INTO `joins_tbl` (`join_id`, `join_FK_source_id`, `join_FK_dest_id`, `join_objectName`) VALUES
(9, 1, 1, 'roles2usergroups'),
(12, 2, 7, 'roles2usergroups'),
(13, 3, 8, 'roles2usergroups');

-- --------------------------------------------------------

--
-- Table structure for table `languages_tbl`
--

CREATE TABLE IF NOT EXISTS `languages_tbl` (
  `language_id` int(10) unsigned NOT NULL,
  `language_FK_site_id` int(10) unsigned DEFAULT NULL,
  `language_name` varchar(100) NOT NULL DEFAULT '',
  `language_code` varchar(10) NOT NULL DEFAULT '',
  `language_FK_country_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_isDefault` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `language_order` int(4) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages_tbl`
--

INSERT INTO `languages_tbl` (`language_id`, `language_FK_site_id`, `language_name`, `language_code`, `language_FK_country_id`, `language_isDefault`, `language_order`) VALUES
(1, NULL, 'Italiano', 'it', 61, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs_tbl`
--

CREATE TABLE IF NOT EXISTS `logs_tbl` (
  `log_id` int(10) unsigned NOT NULL,
  `log_level` varchar(100) NOT NULL DEFAULT '',
  `log_date` datetime NOT NULL,
  `log_ip` varchar(20) DEFAULT NULL,
  `log_session` varchar(50) NOT NULL DEFAULT '',
  `log_group` varchar(50) NOT NULL DEFAULT '',
  `log_message` text NOT NULL,
  `log_FK_user_id` int(10) DEFAULT '0',
  `log_FK_site_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mediadetails_tbl`
--

CREATE TABLE IF NOT EXISTS `mediadetails_tbl` (
  `mediadetail_id` int(10) unsigned NOT NULL,
  `mediadetail_FK_media_id` int(10) unsigned NOT NULL,
  `media_FK_language_id` int(10) unsigned NOT NULL,
  `media_FK_user_id` int(10) unsigned NOT NULL,
  `media_modificationDate` datetime DEFAULT '0000-00-00 00:00:00',
  `media_title` varchar(255) NOT NULL DEFAULT '',
  `media_category` varchar(255) DEFAULT NULL,
  `media_date` varchar(100) DEFAULT NULL,
  `media_copyright` varchar(255) DEFAULT NULL,
  `media_description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_tbl`
--

CREATE TABLE IF NOT EXISTS `media_tbl` (
  `media_id` int(10) unsigned NOT NULL,
  `media_FK_site_id` int(10) unsigned DEFAULT NULL,
  `media_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `media_fileName` varchar(255) NOT NULL DEFAULT '',
  `media_size` int(4) unsigned NOT NULL DEFAULT '0',
  `media_type` enum('IMAGE','OFFICE','PDF','ARCHIVE','FLASH','AUDIO','VIDEO','OTHER') NOT NULL DEFAULT 'IMAGE',
  `media_author` varchar(255) DEFAULT '',
  `media_originalFileName` varchar(255) NOT NULL DEFAULT '',
  `media_zoom` tinyint(1) DEFAULT '0',
  `media_download` int(10) NOT NULL DEFAULT '0',
  `media_watermark` tinyint(1) NOT NULL DEFAULT '0',
  `media_allowDownload` tinyint(1) NOT NULL DEFAULT '1',
  `media_thumbFileName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menudetails_tbl`
--

CREATE TABLE IF NOT EXISTS `menudetails_tbl` (
  `menudetail_id` int(10) unsigned NOT NULL,
  `menudetail_FK_menu_id` int(10) unsigned NOT NULL DEFAULT '0',
  `menudetail_FK_language_id` int(10) unsigned NOT NULL DEFAULT '0',
  `menudetail_title` text NOT NULL,
  `menudetail_keywords` text NOT NULL,
  `menudetail_description` text NOT NULL,
  `menudetail_subject` text NOT NULL,
  `menudetail_creator` text NOT NULL,
  `menudetail_publisher` text NOT NULL,
  `menudetail_contributor` text NOT NULL,
  `menudetail_type` text NOT NULL,
  `menudetail_identifier` text NOT NULL,
  `menudetail_source` text NOT NULL,
  `menudetail_relation` text NOT NULL,
  `menudetail_coverage` text NOT NULL,
  `menudetail_isVisible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `menudetail_titleLink` varchar(255) NOT NULL DEFAULT '',
  `menudetail_linkDescription` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menudetails_tbl`
--

INSERT INTO `menudetails_tbl` (`menudetail_id`, `menudetail_FK_menu_id`, `menudetail_FK_language_id`, `menudetail_title`, `menudetail_keywords`, `menudetail_description`, `menudetail_subject`, `menudetail_creator`, `menudetail_publisher`, `menudetail_contributor`, `menudetail_type`, `menudetail_identifier`, `menudetail_source`, `menudetail_relation`, `menudetail_coverage`, `menudetail_isVisible`, `menudetail_titleLink`, `menudetail_linkDescription`) VALUES
(1, 1, 1, 'Home', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(2, 2, 1, 'Metanavigazione', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(3, 3, 1, 'Footer', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(4, 4, 1, 'Utilità', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(5, 5, 1, 'Strumenti', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(6, 6, 1, 'Crediti', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(7, 7, 1, 'Disclaimer', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(8, 8, 1, 'Login', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(14, 14, 1, 'Contatti', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(15, 15, 1, 'Home', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(16, 16, 1, 'Disclaimer', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(18, 18, 1, 'Home', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(20, 20, 1, 'Pubblicazione 1', '', '', '', '', '', '', '', '', '', '', '', 1, '', ''),
(21, 21, 1, 'Pubblicazione ABC', '', '', '', '', '', '', '', '', '', '', '', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `menus_tbl`
--

CREATE TABLE IF NOT EXISTS `menus_tbl` (
  `menu_id` int(10) unsigned NOT NULL,
  `menu_FK_site_id` int(10) unsigned DEFAULT NULL,
  `menu_parentId` int(10) unsigned DEFAULT '0',
  `menu_pageType` varchar(100) NOT NULL DEFAULT '',
  `menu_order` int(4) unsigned DEFAULT '0',
  `menu_hasPreview` tinyint(1) unsigned DEFAULT '1',
  `menu_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `menu_modificationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `menu_type` enum('HOMEPAGE','PAGE','SYSTEM') NOT NULL DEFAULT 'PAGE',
  `menu_url` varchar(255) NOT NULL DEFAULT '',
  `menu_isLocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu_hasComment` tinyint(1) NOT NULL DEFAULT '0',
  `menu_printPdf` tinyint(1) NOT NULL DEFAULT '0',
  `menu_extendsPermissions` tinyint(1) NOT NULL DEFAULT '0',
  `menu_cssClass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus_tbl`
--

INSERT INTO `menus_tbl` (`menu_id`, `menu_FK_site_id`, `menu_parentId`, `menu_pageType`, `menu_order`, `menu_hasPreview`, `menu_creationDate`, `menu_modificationDate`, `menu_type`, `menu_url`, `menu_isLocked`, `menu_hasComment`, `menu_printPdf`, `menu_extendsPermissions`, `menu_cssClass`) VALUES
(1, NULL, 0, 'Home', 1, 1, '2015-01-01 12:00:00', '2015-01-01 12:00:00', 'HOMEPAGE', '', 0, 0, 0, 0, NULL),
(2, NULL, 1, 'Empty', 0, 1, '2015-01-01 12:00:00', '2015-01-01 12:00:00', 'SYSTEM', '', 0, 0, 0, 0, NULL),
(3, NULL, 1, 'Empty', 1, 1, '2015-01-01 12:00:00', '2015-01-01 12:00:00', 'SYSTEM', '', 0, 0, 0, 0, NULL),
(4, NULL, 1, 'Empty', 2, 1, '2015-01-01 12:00:00', '2015-01-01 12:00:00', 'SYSTEM', '', 0, 0, 0, 0, NULL),
(5, NULL, 1, 'Empty', 3, 1, '2015-01-01 12:00:00', '2015-01-01 12:00:00', 'SYSTEM', '', 0, 0, 0, 0, NULL),
(6, NULL, 5, 'Page', 1, 1, '2015-01-01 12:00:00', '2015-12-13 23:28:43', 'PAGE', '', 0, 0, 0, 0, NULL),
(7, NULL, 5, 'Page', 2, 1, '2015-01-01 12:00:00', '2015-12-13 23:28:57', 'PAGE', '', 0, 0, 0, 0, NULL),
(8, NULL, 2, 'Login', 1, 1, '2015-12-13 00:00:00', '2015-12-13 23:34:13', 'PAGE', '', 0, 0, 0, 0, NULL),
(14, NULL, 2, 'Page', 4, 1, '2015-01-01 12:00:00', '2015-12-13 23:35:10', 'PAGE', '', 0, 0, 0, 0, NULL),
(15, NULL, 3, 'Alias', 1, 1, '2015-01-01 12:00:00', '2015-12-13 23:28:27', 'PAGE', 'alias:internal:1', 0, 0, 0, 0, NULL),
(16, NULL, 3, 'Alias', 2, 1, '2015-01-01 12:00:00', '2015-12-13 23:29:10', 'PAGE', 'alias:internal:7', 0, 0, 0, 0, NULL),
(18, NULL, 2, 'Alias', 0, 1, '2015-01-01 12:00:00', '2016-03-04 14:32:21', 'PAGE', 'alias:internal:21', 0, 0, 0, 0, NULL),
(20, NULL, 1, 'desiderataLibrary.modules.blog.views.FrontEnd', 4, 1, '2015-12-13 00:00:00', '2015-12-15 19:35:43', 'PAGE', '', 0, 0, 0, 0, NULL),
(21, NULL, 1, 'desiderataLibrary.modules.blog.views.FrontEnd', 5, 1, '2015-12-13 23:36:01', '2015-12-13 23:36:10', 'PAGE', '', 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `registry_tbl`
--

CREATE TABLE IF NOT EXISTS `registry_tbl` (
  `registry_id` int(11) NOT NULL,
  `registry_FK_site_id` int(10) unsigned DEFAULT NULL,
  `registry_path` varchar(255) NOT NULL DEFAULT '',
  `registry_value` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registry_tbl`
--

INSERT INTO `registry_tbl` (`registry_id`, `registry_FK_site_id`, `registry_path`, `registry_value`) VALUES
(4, NULL, 'metacms/siteProp/it', 'a:5:{s:5:"title";s:12:"Blog Editore";s:7:"address";s:0:"";s:9:"copyright";s:16:"© 2015 Meta srl";s:9:"slideShow";s:0:"";s:9:"analytics";s:0:"";}'),
(5, NULL, 'metacms/templateName', 'Fourteen'),
(6, NULL, 'metacms/templateValues/Fourteen', '{"0":{"headerLogo":"","footerLogo":"","footerLogoLink":"","footerLogoTitle":"","font1":"default","font2":"default","templateEdit-c139c5f8abc91d2f6e91948625c086c78":"","theme-color":"#AE3433","bg-body":"#FFFFFF","bg-outer":"#FFFFFF","bg-header":"#FFFFFF","bg-module-main-search":"#F8F6F0","text":"#000000","text-heading":"#000000","color-link":"#AE3433","box-image-border":"#CCCCCC","bg-top-bar":"#FFFFFF","metanavigation-link":"#AE3433","metanavigation-link-hover":"#AE3433","bg-menu":"#AE3433","color-menu-link":"#FFFFFF","color-menu-link-hover":"#FFFFFF","slider-text":"#FFFFFF","slider-bullet-background":"#FFFFFF","slider-bullet-background-selected":"#AE3433","icon-in-box":"#FFFFFF","icon-in-box-background":"#CCCCCC","box-border":"#CCCCCC","box-header-background":"#FFFFFF","box-header-link":"#AE3433","box-text":"#000000","box-item-even-background":"#FFFFFF","box-item-odd-background":"#FFFFFF","color-arrow-button-slider":"#AE3433","bg-link-attach":"#CCCCCC","color-link-attach":"#FFFFFF","bg-link-attach-hover":"#AE3433","color-link-attach-hover":"#FFFFFF","form-input-text":"#000000","form-input-background":"#FFFFFF","form-border":"#CCCCCC","form-required":"#CCCCCC","form-button":"#666666","form-button-text":"#FFFFFF","form-button-primary":"#AE3433","form-button-primary-text":"#FFFFFF","footer-border":"#CCCCCC","footer-text":"#000000","footer-background":"#E5E5E5","customCss":""}}'),
(7, NULL, 'metacms/shareButtons', 'a:3:{s:6:"enable";i:1;s:3:"dim";s:2:"md";s:10:"buttonList";s:49:"Twitter,Facebook,Google-plus,E-mail,Facebook-like";}');

-- --------------------------------------------------------

--
-- Table structure for table `roles_tbl`
--

CREATE TABLE IF NOT EXISTS `roles_tbl` (
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(100) NOT NULL DEFAULT '',
  `role_permissions` text NOT NULL,
  `role_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_tbl`
--

INSERT INTO `roles_tbl` (`role_id`, `role_name`, `role_permissions`, `role_active`) VALUES
(1, 'Amministratori', 'a:11:{s:4:"home";a:1:{s:3:"all";s:1:"1";}s:12:"mediaarchive";a:1:{s:3:"all";s:1:"1";}s:30:"desideratalibrary.modules.blog";a:1:{s:3:"all";s:1:"1";}s:21:"glizycms_contentsedit";a:1:{s:3:"all";s:1:"1";}s:21:"glizycms_siteproperty";a:1:{s:3:"all";s:1:"1";}s:22:"glizycms_sharingbutton";a:1:{s:3:"all";s:1:"1";}s:23:"glizycms_templateselect";a:1:{s:3:"all";s:1:"1";}s:17:"usermanager_alias";a:1:{s:3:"all";s:1:"1";}s:11:"usermanager";a:1:{s:3:"all";s:1:"1";}s:12:"groupmanager";a:1:{s:3:"all";s:1:"1";}s:11:"rolemanager";a:1:{s:3:"all";s:1:"1";}}', 1),
(2, 'Content Editor', 'a:10:{s:4:"home";a:1:{s:3:"all";s:1:"1";}s:12:"mediaarchive";a:1:{s:3:"all";s:1:"1";}s:30:"desideratalibrary.modules.blog";a:1:{s:3:"all";s:1:"1";}s:21:"glizycms_contentsedit";a:1:{s:3:"all";s:1:"1";}s:21:"glizycms_siteproperty";a:1:{s:3:"all";s:1:"1";}s:22:"glizycms_sharingbutton";a:1:{s:3:"all";s:1:"1";}s:23:"glizycms_templateselect";a:1:{s:3:"all";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', 1),
(3, 'Autori blog', 'a:6:{s:4:"home";a:1:{s:3:"all";s:1:"1";}s:12:"mediaarchive";a:1:{s:3:"all";s:1:"1";}s:30:"desideratalibrary.modules.blog";a:1:{s:3:"all";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_datetime_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_datetime_tbl` (
  `simple_document_index_datetime_id` int(10) unsigned NOT NULL,
  `simple_document_index_datetime_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_datetime_name` varchar(100) NOT NULL,
  `simple_document_index_datetime_value` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_date_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_date_tbl` (
  `simple_document_index_date_id` int(10) unsigned NOT NULL,
  `simple_document_index_date_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_date_name` varchar(100) NOT NULL,
  `simple_document_index_date_value` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_fulltext_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_fulltext_tbl` (
  `simple_document_index_fulltext_id` int(10) unsigned NOT NULL,
  `simple_document_index_fulltext_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_fulltext_name` varchar(100) NOT NULL,
  `simple_document_index_fulltext_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_int_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_int_tbl` (
  `simple_document_index_int_id` int(10) unsigned NOT NULL,
  `simple_document_index_int_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_int_name` varchar(100) NOT NULL,
  `simple_document_index_int_value` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_text_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_text_tbl` (
  `simple_document_index_text_id` int(10) unsigned NOT NULL,
  `simple_document_index_text_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_text_name` varchar(100) NOT NULL,
  `simple_document_index_text_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_time_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_index_time_tbl` (
  `simple_document_index_time_id` int(10) unsigned NOT NULL,
  `simple_document_index_time_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_time_name` varchar(100) NOT NULL,
  `simple_document_index_time_value` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_tbl`
--

CREATE TABLE IF NOT EXISTS `simple_documents_tbl` (
  `simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_FK_site_id` int(10) unsigned DEFAULT NULL,
  `simple_document_type` varchar(255) NOT NULL,
  `simple_document_object` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `speakingurls_tbl`
--

CREATE TABLE IF NOT EXISTS `speakingurls_tbl` (
  `speakingurl_id` int(10) unsigned NOT NULL,
  `speakingurl_FK_language_id` int(10) unsigned NOT NULL,
  `speakingurl_FK_site_id` int(10) unsigned DEFAULT NULL,
  `speakingurl_FK` int(10) unsigned NOT NULL,
  `speakingurl_type` varchar(255) NOT NULL,
  `speakingurl_value` varchar(255) NOT NULL,
  `speakingurl_option` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `usergroups_tbl`
--
CREATE TABLE IF NOT EXISTS `usergroups_tbl` (
`usergroup_id` int(11)
,`usergroup_name` varchar(50)
,`usergroup_backEndAccess` tinyint(1)
,`usergroup_FK_site_id` int(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `userlogs_tbl`
--

CREATE TABLE IF NOT EXISTS `userlogs_tbl` (
  `userlog_id` int(10) unsigned NOT NULL,
  `userlog_FK_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `userlog_FK_site_id` int(10) unsigned DEFAULT NULL,
  `userlog_session` varchar(50) NOT NULL DEFAULT '',
  `userlog_ip` varchar(50) NOT NULL DEFAULT '',
  `userlog_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userlog_lastAction` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `users_tbl`
--
CREATE TABLE IF NOT EXISTS `users_tbl` (
`user_id` int(10) unsigned
,`user_FK_usergroup_id` int(10) unsigned
,`user_FK_site_id` int(10) unsigned
,`user_dateCreation` datetime
,`user_isActive` tinyint(1) unsigned
,`user_loginId` varchar(100)
,`user_password` varchar(100)
,`user_firstName` varchar(100)
,`user_lastName` varchar(100)
,`user_email` varchar(255)
,`user_FK_editor_id` int(11)
,`user_age` int(11)
,`user_city` varchar(255)
,`user_interests` varchar(255)
,`user_qualification` varchar(255)
,`user_profession` varchar(255)
,`user_address` varchar(255)
,`user_fiscalCode` char(16)
,`user_province` varchar(255)
,`user_cap` char(5)
,`user_country` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `usergroups_tbl`
--
DROP TABLE IF EXISTS `usergroups_tbl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usergroups_tbl` AS select `sr_desideratalibrary`.`usergroups_tbl`.`usergroup_id` AS `usergroup_id`,`sr_desideratalibrary`.`usergroups_tbl`.`usergroup_name` AS `usergroup_name`,`sr_desideratalibrary`.`usergroups_tbl`.`usergroup_backEndAccess` AS `usergroup_backEndAccess`,`sr_desideratalibrary`.`usergroups_tbl`.`usergroup_FK_site_id` AS `usergroup_FK_site_id` from `sr_desideratalibrary`.`usergroups_tbl`;

-- --------------------------------------------------------

--
-- Structure for view `users_tbl`
--
DROP TABLE IF EXISTS `users_tbl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users_tbl` AS select `sr_desideratalibrary`.`users_tbl`.`user_id` AS `user_id`,`sr_desideratalibrary`.`users_tbl`.`user_FK_usergroup_id` AS `user_FK_usergroup_id`,`sr_desideratalibrary`.`users_tbl`.`user_FK_site_id` AS `user_FK_site_id`,`sr_desideratalibrary`.`users_tbl`.`user_dateCreation` AS `user_dateCreation`,`sr_desideratalibrary`.`users_tbl`.`user_isActive` AS `user_isActive`,`sr_desideratalibrary`.`users_tbl`.`user_loginId` AS `user_loginId`,`sr_desideratalibrary`.`users_tbl`.`user_password` AS `user_password`,`sr_desideratalibrary`.`users_tbl`.`user_firstName` AS `user_firstName`,`sr_desideratalibrary`.`users_tbl`.`user_lastName` AS `user_lastName`,`sr_desideratalibrary`.`users_tbl`.`user_email` AS `user_email`,`sr_desideratalibrary`.`users_tbl`.`user_FK_editor_id` AS `user_FK_editor_id`,`sr_desideratalibrary`.`users_tbl`.`user_age` AS `user_age`,`sr_desideratalibrary`.`users_tbl`.`user_city` AS `user_city`,`sr_desideratalibrary`.`users_tbl`.`user_interests` AS `user_interests`,`sr_desideratalibrary`.`users_tbl`.`user_qualification` AS `user_qualification`,`sr_desideratalibrary`.`users_tbl`.`user_profession` AS `user_profession`,`sr_desideratalibrary`.`users_tbl`.`user_address` AS `user_address`,`sr_desideratalibrary`.`users_tbl`.`user_fiscalCode` AS `user_fiscalCode`,`sr_desideratalibrary`.`users_tbl`.`user_province` AS `user_province`,`sr_desideratalibrary`.`users_tbl`.`user_cap` AS `user_cap`,`sr_desideratalibrary`.`users_tbl`.`user_country` AS `user_country` from `sr_desideratalibrary`.`users_tbl`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries_tbl`
--
ALTER TABLE `countries_tbl`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `documents_detail_tbl`
--
ALTER TABLE `documents_detail_tbl`
  ADD PRIMARY KEY (`document_detail_id`),
  ADD KEY `document_detail_fk_document_id` (`document_detail_FK_document_id`),
  ADD KEY `document_detail_fk_language_id` (`document_detail_FK_language_id`),
  ADD KEY `document_detail_fk_user_id` (`document_detail_FK_user_id`),
  ADD KEY `document_detail_status` (`document_detail_status`);

--
-- Indexes for table `documents_index_datetime_tbl`
--
ALTER TABLE `documents_index_datetime_tbl`
  ADD PRIMARY KEY (`document_index_datetime_id`),
  ADD KEY `document_index_datetime_fk` (`document_index_datetime_FK_document_detail_id`),
  ADD KEY `document_index_datetime_name` (`document_index_datetime_name`),
  ADD KEY `document_index_datetime_value` (`document_index_datetime_value`);

--
-- Indexes for table `documents_index_date_tbl`
--
ALTER TABLE `documents_index_date_tbl`
  ADD PRIMARY KEY (`document_index_date_id`),
  ADD KEY `document_index_date_name` (`document_index_date_name`),
  ADD KEY `document_index_date_value` (`document_index_date_value`),
  ADD KEY `document_index_date_fk` (`document_index_date_FK_document_detail_id`) USING BTREE;

--
-- Indexes for table `documents_index_fulltext_tbl`
--
ALTER TABLE `documents_index_fulltext_tbl`
  ADD PRIMARY KEY (`document_index_fulltext_id`),
  ADD KEY `document_index_fulltext_name` (`document_index_fulltext_name`),
  ADD KEY `document_index_fulltext_fk` (`document_index_fulltext_FK_document_detail_id`) USING BTREE,
  ADD FULLTEXT KEY `document_index_fulltext_value` (`document_index_fulltext_value`);

--
-- Indexes for table `documents_index_int_tbl`
--
ALTER TABLE `documents_index_int_tbl`
  ADD PRIMARY KEY (`document_index_int_id`),
  ADD KEY `document_index_int_fk` (`document_index_int_FK_document_detail_id`),
  ADD KEY `document_index_int_name` (`document_index_int_name`),
  ADD KEY `document_index_int_value` (`document_index_int_value`);

--
-- Indexes for table `documents_index_text_tbl`
--
ALTER TABLE `documents_index_text_tbl`
  ADD PRIMARY KEY (`document_index_text_id`),
  ADD KEY `document_index_text_fk` (`document_index_text_FK_document_detail_id`),
  ADD KEY `document_index_text_name` (`document_index_text_name`),
  ADD KEY `document_index_text_value` (`document_index_text_value`);

--
-- Indexes for table `documents_index_time_tbl`
--
ALTER TABLE `documents_index_time_tbl`
  ADD PRIMARY KEY (`document_index_time_id`),
  ADD KEY `document_index_time_fk` (`document_index_time_FK_document_detail_id`),
  ADD KEY `document_index_time_name` (`document_index_time_name`),
  ADD KEY `document_index_time_value` (`document_index_time_value`);

--
-- Indexes for table `documents_tbl`
--
ALTER TABLE `documents_tbl`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `document_type` (`document_type`),
  ADD KEY `document_FK_site_id` (`document_FK_site_id`);

--
-- Indexes for table `joins_tbl`
--
ALTER TABLE `joins_tbl`
  ADD PRIMARY KEY (`join_id`),
  ADD KEY `join_FK_source_id` (`join_FK_source_id`),
  ADD KEY `join_FK_dest_id` (`join_FK_dest_id`),
  ADD KEY `join_objectName` (`join_objectName`);

--
-- Indexes for table `languages_tbl`
--
ALTER TABLE `languages_tbl`
  ADD PRIMARY KEY (`language_id`),
  ADD KEY `language_FK_country_id` (`language_FK_country_id`),
  ADD KEY `language_isDefault` (`language_isDefault`),
  ADD KEY `language_order` (`language_order`);

--
-- Indexes for table `logs_tbl`
--
ALTER TABLE `logs_tbl`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_level` (`log_level`),
  ADD KEY `log_group` (`log_group`),
  ADD KEY `log_FK_user_id` (`log_FK_user_id`),
  ADD KEY `log_FK_site_id` (`log_FK_site_id`);

--
-- Indexes for table `mediadetails_tbl`
--
ALTER TABLE `mediadetails_tbl`
  ADD PRIMARY KEY (`mediadetail_id`),
  ADD KEY `mediadetail_FK_media_id` (`mediadetail_FK_media_id`),
  ADD KEY `media_FK_language_id` (`media_FK_language_id`),
  ADD KEY `media_FK_user_id` (`media_FK_user_id`);

--
-- Indexes for table `media_tbl`
--
ALTER TABLE `media_tbl`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `media_FK_site_id` (`media_FK_site_id`),
  ADD KEY `media_type` (`media_type`);

--
-- Indexes for table `menudetails_tbl`
--
ALTER TABLE `menudetails_tbl`
  ADD PRIMARY KEY (`menudetail_id`),
  ADD KEY `menudetail_FK_menu_id` (`menudetail_FK_menu_id`),
  ADD KEY `menudetail_FK_language_id` (`menudetail_FK_language_id`);

--
-- Indexes for table `menus_tbl`
--
ALTER TABLE `menus_tbl`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `menu_FK_site_id` (`menu_FK_site_id`),
  ADD KEY `menu_parentId` (`menu_parentId`),
  ADD KEY `menu_pageType` (`menu_pageType`);

--
-- Indexes for table `registry_tbl`
--
ALTER TABLE `registry_tbl`
  ADD PRIMARY KEY (`registry_id`),
  ADD KEY `registry_path` (`registry_path`);

--
-- Indexes for table `roles_tbl`
--
ALTER TABLE `roles_tbl`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `role_name` (`role_name`);

--
-- Indexes for table `simple_documents_index_datetime_tbl`
--
ALTER TABLE `simple_documents_index_datetime_tbl`
  ADD PRIMARY KEY (`simple_document_index_datetime_id`),
  ADD KEY `simple_document_index_datetime_name` (`simple_document_index_datetime_name`) USING BTREE,
  ADD KEY `simple_document_index_datetime_value` (`simple_document_index_datetime_value`) USING BTREE,
  ADD KEY `simple_document_index_datetime_fk` (`simple_document_index_datetime_FK_simple_document_id`) USING BTREE;

--
-- Indexes for table `simple_documents_index_date_tbl`
--
ALTER TABLE `simple_documents_index_date_tbl`
  ADD PRIMARY KEY (`simple_document_index_date_id`),
  ADD KEY `simple_document_index_date_fk` (`simple_document_index_date_FK_simple_document_id`),
  ADD KEY `simple_document_index_date_value` (`simple_document_index_date_value`),
  ADD KEY `simple_document_index_date_name` (`simple_document_index_date_name`) USING BTREE;

--
-- Indexes for table `simple_documents_index_fulltext_tbl`
--
ALTER TABLE `simple_documents_index_fulltext_tbl`
  ADD PRIMARY KEY (`simple_document_index_fulltext_id`),
  ADD KEY `simple_document_index_fulltext_name` (`simple_document_index_fulltext_name`),
  ADD KEY `simple_document_index_fulltext_fk` (`simple_document_index_fulltext_FK_simple_document_id`) USING BTREE,
  ADD FULLTEXT KEY `simple_document_index_fulltext_value` (`simple_document_index_fulltext_value`);

--
-- Indexes for table `simple_documents_index_int_tbl`
--
ALTER TABLE `simple_documents_index_int_tbl`
  ADD PRIMARY KEY (`simple_document_index_int_id`),
  ADD KEY `simple_document_index_int_fk` (`simple_document_index_int_FK_simple_document_id`),
  ADD KEY `simple_document_index_int_value` (`simple_document_index_int_value`),
  ADD KEY `simple_document_index_int_name` (`simple_document_index_int_name`) USING BTREE;

--
-- Indexes for table `simple_documents_index_text_tbl`
--
ALTER TABLE `simple_documents_index_text_tbl`
  ADD PRIMARY KEY (`simple_document_index_text_id`),
  ADD KEY `simple_document_index_text_fk` (`simple_document_index_text_FK_simple_document_id`),
  ADD KEY `simple_document_index_text_value` (`simple_document_index_text_value`),
  ADD KEY `simple_document_index_text_name` (`simple_document_index_text_name`) USING BTREE;

--
-- Indexes for table `simple_documents_index_time_tbl`
--
ALTER TABLE `simple_documents_index_time_tbl`
  ADD PRIMARY KEY (`simple_document_index_time_id`),
  ADD KEY `simple_document_index_time_fk` (`simple_document_index_time_FK_simple_document_id`),
  ADD KEY `simple_document_index_time_name` (`simple_document_index_time_name`) USING BTREE,
  ADD KEY `simple_document_index_time_value` (`simple_document_index_time_value`);

--
-- Indexes for table `simple_documents_tbl`
--
ALTER TABLE `simple_documents_tbl`
  ADD PRIMARY KEY (`simple_document_id`),
  ADD KEY `simple_document_type` (`simple_document_type`),
  ADD KEY `simple_document_FK_site_id` (`simple_document_FK_site_id`);

--
-- Indexes for table `speakingurls_tbl`
--
ALTER TABLE `speakingurls_tbl`
  ADD PRIMARY KEY (`speakingurl_id`),
  ADD KEY `speakingurl_FK_language_id` (`speakingurl_FK_language_id`),
  ADD KEY `speakingurl_FK` (`speakingurl_FK`),
  ADD KEY `speakingurl_type` (`speakingurl_type`),
  ADD KEY `speakingurl_FK_site_id` (`speakingurl_FK_site_id`);

--
-- Indexes for table `userlogs_tbl`
--
ALTER TABLE `userlogs_tbl`
  ADD PRIMARY KEY (`userlog_id`),
  ADD UNIQUE KEY `userlog_FK_user_id` (`userlog_FK_user_id`),
  ADD KEY `userlog_FK_site_id` (`userlog_FK_site_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries_tbl`
--
ALTER TABLE `countries_tbl`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `documents_detail_tbl`
--
ALTER TABLE `documents_detail_tbl`
  MODIFY `document_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_datetime_tbl`
--
ALTER TABLE `documents_index_datetime_tbl`
  MODIFY `document_index_datetime_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_date_tbl`
--
ALTER TABLE `documents_index_date_tbl`
  MODIFY `document_index_date_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_fulltext_tbl`
--
ALTER TABLE `documents_index_fulltext_tbl`
  MODIFY `document_index_fulltext_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_int_tbl`
--
ALTER TABLE `documents_index_int_tbl`
  MODIFY `document_index_int_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_text_tbl`
--
ALTER TABLE `documents_index_text_tbl`
  MODIFY `document_index_text_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_index_time_tbl`
--
ALTER TABLE `documents_index_time_tbl`
  MODIFY `document_index_time_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_tbl`
--
ALTER TABLE `documents_tbl`
  MODIFY `document_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joins_tbl`
--
ALTER TABLE `joins_tbl`
  MODIFY `join_id` int(1) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `languages_tbl`
--
ALTER TABLE `languages_tbl`
  MODIFY `language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mediadetails_tbl`
--
ALTER TABLE `mediadetails_tbl`
  MODIFY `mediadetail_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media_tbl`
--
ALTER TABLE `media_tbl`
  MODIFY `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menudetails_tbl`
--
ALTER TABLE `menudetails_tbl`
  MODIFY `menudetail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `menus_tbl`
--
ALTER TABLE `menus_tbl`
  MODIFY `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `registry_tbl`
--
ALTER TABLE `registry_tbl`
  MODIFY `registry_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `roles_tbl`
--
ALTER TABLE `roles_tbl`
  MODIFY `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `simple_documents_index_datetime_tbl`
--
ALTER TABLE `simple_documents_index_datetime_tbl`
  MODIFY `simple_document_index_datetime_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_index_date_tbl`
--
ALTER TABLE `simple_documents_index_date_tbl`
  MODIFY `simple_document_index_date_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_index_fulltext_tbl`
--
ALTER TABLE `simple_documents_index_fulltext_tbl`
  MODIFY `simple_document_index_fulltext_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_index_int_tbl`
--
ALTER TABLE `simple_documents_index_int_tbl`
  MODIFY `simple_document_index_int_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_index_text_tbl`
--
ALTER TABLE `simple_documents_index_text_tbl`
  MODIFY `simple_document_index_text_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_index_time_tbl`
--
ALTER TABLE `simple_documents_index_time_tbl`
  MODIFY `simple_document_index_time_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simple_documents_tbl`
--
ALTER TABLE `simple_documents_tbl`
  MODIFY `simple_document_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `userlogs_tbl`
--
ALTER TABLE `userlogs_tbl`
  MODIFY `userlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
