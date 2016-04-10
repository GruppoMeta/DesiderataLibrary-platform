-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2016 at 06:34 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sr_desideratalibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `annotations_tbl`
--

CREATE TABLE `annotations_tbl` (
  `annotation_id` int(10) NOT NULL,
  `annotation_type` varchar(255) NOT NULL,
  `annotation_title` text,
  `annotation_data` longtext,
  `annotation_parent_id` int(10) DEFAULT '0',
  `annotation_user_id` int(10) NOT NULL,
  `annotation_volume_id` varchar(100) NOT NULL,
  `annotation_content_id` varchar(100) NOT NULL,
  `annotation_created_at` datetime NOT NULL,
  `annotation_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cds_tbl`
--

CREATE TABLE `cds_tbl` (
  `cds_id` int(10) NOT NULL,
  `cds_FK_user_id` int(10) NOT NULL,
  `cds_FK_pub_id` int(10) NOT NULL,
  `cds_deviceKey` text NOT NULL,
  `cds_appKey` text NOT NULL,
  `cds_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `codesgroup_tbl`
--

CREATE TABLE `codesgroup_tbl` (
  `codegroup_id` int(10) NOT NULL,
  `codegroup_name` varchar(255) NOT NULL DEFAULT '',
  `codegroup_startDate` date DEFAULT NULL,
  `codegroup_endDate` date DEFAULT NULL,
  `codegroup_num` int(10) NOT NULL DEFAULT '0',
  `codegroup_FK_user_id` int(10) NOT NULL DEFAULT '0',
  `codegroup_licenses` text NOT NULL,
  `codegroup_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `codes_tbl`
--

CREATE TABLE `codes_tbl` (
  `code_id` int(10) NOT NULL,
  `code_status` tinyint(1) NOT NULL DEFAULT '0',
  `code_FK_codegroup_id` int(10) NOT NULL DEFAULT '0',
  `code_FK_user_id` int(10) NOT NULL DEFAULT '0',
  `code_creationDate` date DEFAULT NULL,
  `code_burnDate` date DEFAULT NULL,
  `code_pos` int(10) NOT NULL DEFAULT '0',
  `code_value` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `desiderata_tbl`
--

CREATE TABLE `desiderata_tbl` (
  `desiderata_id` int(10) NOT NULL,
  `desiderata_FK_user_id` int(10) NOT NULL,
  `desiderata_title` text NOT NULL,
  `desiderata_tags` text NOT NULL,
  `desiderata_elements` longtext NOT NULL,
  `desiderata_created_at` datetime NOT NULL,
  `desiderata_coverName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_detail_tbl`
--

CREATE TABLE `documents_detail_tbl` (
  `document_detail_id` int(10) unsigned NOT NULL,
  `document_detail_FK_document_id` int(10) unsigned NOT NULL,
  `document_detail_FK_language_id` int(10) unsigned NOT NULL,
  `document_detail_FK_user_id` int(10) unsigned NOT NULL,
  `document_detail_modificationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `document_detail_status` varchar(9) NOT NULL DEFAULT 'DRAFT',
  `document_detail_translated` tinyint(1) NOT NULL,
  `document_detail_object` longtext NOT NULL,
  `document_detail_isVisible` tinyint(1) NOT NULL DEFAULT '1',
  `document_detail_note` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents_detail_tbl`
--

INSERT INTO `documents_detail_tbl` (`document_detail_id`, `document_detail_FK_document_id`, `document_detail_FK_language_id`, `document_detail_FK_user_id`, `document_detail_modificationDate`, `document_detail_status`, `document_detail_translated`, `document_detail_object`, `document_detail_isVisible`, `document_detail_note`) VALUES
(1, 1, 1, 1, '2016-04-10 18:32:37', 'PUBLISHED', 1, '{"id":1,"title":"Nuova pubblicazione","content":{"__indexFields":[]}}', 1, ''),
(2, 2, 1, 1, '2016-04-10 18:32:46', 'OLD', 1, '{"id":2,"title":"Nuova pubblicazione","content":{"__indexFields":[]}}', 1, ''),
(3, 2, 1, 1, '2016-04-10 18:33:27', 'PUBLISHED', 1, '{"id":"2","title":"Nuova pubblicazione PDF","content":{"__indexFields":{"refId":"text","author":"text","category":"text","isbn":"text","publisher@id":"int","keywords":"text","subject":"text"},"refId":"0945620001460305970_2_21460305973487","subtitle":"","cover":"","state":"0","author":["Luigi Pirandello"],"serie":"","category":["Narrativa"],"isbn":"","price":"","isFree":0,"blogUrl":"","profile_age":"","profile_interests":null,"profile_qualification":null,"profile_profession":null,"publisher":null,"keywords":[],"subject":[],"abstract":"Descrizione breve","dc_creator":"","dc_contributor":"","dc_type":"","dc_identifier":"","dc_source":"","dc_relation":"","dc_coverage":""}}', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_datetime_tbl`
--

CREATE TABLE `documents_index_datetime_tbl` (
  `document_index_datetime_id` int(10) unsigned NOT NULL,
  `document_index_datetime_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_datetime_name` varchar(100) NOT NULL,
  `document_index_datetime_value` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_date_tbl`
--

CREATE TABLE `documents_index_date_tbl` (
  `document_index_date_id` int(10) unsigned NOT NULL,
  `document_index_date_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_date_name` varchar(100) NOT NULL,
  `document_index_date_value` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_fulltext_tbl`
--

CREATE TABLE `documents_index_fulltext_tbl` (
  `document_index_fulltext_id` int(10) unsigned NOT NULL,
  `document_index_fulltext_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_fulltext_name` varchar(100) NOT NULL,
  `document_index_fulltext_value` longtext NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents_index_fulltext_tbl`
--

INSERT INTO `documents_index_fulltext_tbl` (`document_index_fulltext_id`, `document_index_fulltext_FK_document_detail_id`, `document_index_fulltext_name`, `document_index_fulltext_value`) VALUES
(1, 1, 'fulltext', 'Nuova pubblicazione ##'),
(2, 2, 'fulltext', 'Nuova pubblicazione ##'),
(3, 3, 'fulltext', 'Nuova pubblicazione PDF ## text ## text ## text ## text ## int ## text ## text ## 0945620001460305970_2_21460305973487 ## Luigi Pirandello ## Narrativa ## Descrizione breve ##');

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_int_tbl`
--

CREATE TABLE `documents_index_int_tbl` (
  `document_index_int_id` int(10) unsigned NOT NULL,
  `document_index_int_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_int_name` varchar(100) NOT NULL,
  `document_index_int_value` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents_index_int_tbl`
--

INSERT INTO `documents_index_int_tbl` (`document_index_int_id`, `document_index_int_FK_document_detail_id`, `document_index_int_name`, `document_index_int_value`) VALUES
(1, 1, 'id', 1),
(2, 2, 'id', 2),
(3, 3, 'id', 2);

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_text_tbl`
--

CREATE TABLE `documents_index_text_tbl` (
  `document_index_text_id` int(10) unsigned NOT NULL,
  `document_index_text_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_text_name` varchar(100) NOT NULL,
  `document_index_text_value` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents_index_text_tbl`
--

INSERT INTO `documents_index_text_tbl` (`document_index_text_id`, `document_index_text_FK_document_detail_id`, `document_index_text_name`, `document_index_text_value`) VALUES
(1, 3, 'refId', '0945620001460305970_2_21460305973487'),
(2, 3, 'author', 'Luigi Pirandello'),
(3, 3, 'category', 'Narrativa'),
(4, 3, 'isbn', '');

-- --------------------------------------------------------

--
-- Table structure for table `documents_index_time_tbl`
--

CREATE TABLE `documents_index_time_tbl` (
  `document_index_time_id` int(10) unsigned NOT NULL,
  `document_index_time_FK_document_detail_id` int(10) unsigned NOT NULL,
  `document_index_time_name` varchar(100) NOT NULL,
  `document_index_time_value` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents_tbl`
--

CREATE TABLE `documents_tbl` (
  `document_id` int(10) unsigned NOT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `document_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `document_FK_site_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents_tbl`
--

INSERT INTO `documents_tbl` (`document_id`, `document_type`, `document_creationDate`, `document_FK_site_id`) VALUES
(1, 'glizycms.content', '2016-04-10 18:32:37', 1),
(2, 'glizycms.content', '2016-04-10 18:32:46', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ecommordersitems_tbl`
--

CREATE TABLE `ecommordersitems_tbl` (
  `orderitem_id` int(10) unsigned NOT NULL,
  `orderitem_FK_order_id` int(10) unsigned NOT NULL,
  `orderitem_price` decimal(10,2) NOT NULL,
  `orderitem_FK_publication_id` int(10) NOT NULL DEFAULT '0',
  `orderitem_publicationTitle` varchar(255) NOT NULL,
  `orderitem_FK_license_id` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ecommorders_tbl`
--

CREATE TABLE `ecommorders_tbl` (
  `order_id` int(10) unsigned NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_state` enum('open','completed') NOT NULL,
  `order_FK_user_id` int(10) unsigned NOT NULL,
  `order_transactionCode` varchar(50) DEFAULT NULL,
  `order_bankAnswer` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `editors_tbl`
--

CREATE TABLE `editors_tbl` (
  `editor_id` int(11) NOT NULL,
  `editor_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `editor_address` text NOT NULL,
  `editor_city` varchar(255) NOT NULL DEFAULT '',
  `editor_zip` varchar(100) NOT NULL DEFAULT '',
  `editor_reference_name` varchar(255) NOT NULL DEFAULT '',
  `editor_reference_email` varchar(255) NOT NULL DEFAULT '',
  `editor_blogPath` varchar(255) NOT NULL DEFAULT '',
  `editor_hasBlog` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `editors_tbl`
--

INSERT INTO `editors_tbl` (`editor_id`, `editor_name`, `editor_address`, `editor_city`, `editor_zip`, `editor_reference_name`, `editor_reference_email`, `editor_blogPath`, `editor_hasBlog`) VALUES
(1, 'Editore', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `entity_properties_tbl`
--

CREATE TABLE `entity_properties_tbl` (
  `entity_properties_id` int(10) unsigned NOT NULL,
  `entity_properties_FK_entity_id` int(10) unsigned NOT NULL,
  `entity_properties_type` varchar(100) NOT NULL,
  `entity_properties_target_FK_entity_id` int(10) unsigned DEFAULT NULL,
  `entity_properties_label_key` varchar(255) NOT NULL,
  `entity_properties_required` tinyint(1) NOT NULL,
  `entity_properties_show_label_in_frontend` tinyint(1) NOT NULL DEFAULT '1',
  `entity_properties_relation_show` int(11) DEFAULT NULL COMMENT '0 = show images, 1 = show links, 2 = show images and links, 3 = hide',
  `entity_properties_reference_relation_show` int(11) NOT NULL DEFAULT '0' COMMENT '0 = Show, 1 = Hide',
  `entity_properties_dublic_core` varchar(100) DEFAULT NULL,
  `entity_properties_row_index` int(10) NOT NULL,
  `entity_properties_params` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `entity_tbl`
--

CREATE TABLE `entity_tbl` (
  `entity_id` int(10) unsigned NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `entity_show_relations_graph` tinyint(1) NOT NULL DEFAULT '1',
  `entity_FK_site_id` int(10) NOT NULL DEFAULT '0',
  `entity_skin_attributes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eventStats_tbl`
--

CREATE TABLE `eventStats_tbl` (
  `eventStats_id` int(11) NOT NULL,
  `eventStats_nomeEvento` enum('burnCode','login','registration','read','code') NOT NULL,
  `eventStats_datetime` datetime NOT NULL,
  `eventStats_parametro` varchar(255) NOT NULL,
  `eventStats_idPubblicazione` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobs_tbl`
--

CREATE TABLE `jobs_tbl` (
  `job_id` int(10) unsigned NOT NULL,
  `job_type` enum('INTERACTIVE','BACKGROUND') NOT NULL DEFAULT 'INTERACTIVE',
  `job_name` varchar(255) NOT NULL,
  `job_params` text NOT NULL,
  `job_description` text,
  `job_message` text,
  `job_status` enum('NOT_STARTED','RUNNING','COMPLETED','ERROR') NOT NULL DEFAULT 'NOT_STARTED',
  `job_progress` int(3) NOT NULL DEFAULT '0',
  `job_modificationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `job_creationDate` datetime NOT NULL,
  `job_FK_user_id` int(10) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `joins_tbl`
--

CREATE TABLE `joins_tbl` (
  `join_id` int(1) unsigned NOT NULL,
  `join_FK_source_id` int(10) unsigned NOT NULL,
  `join_FK_dest_id` int(10) unsigned NOT NULL,
  `join_objectName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keywords_tbl`
--

CREATE TABLE `keywords_tbl` (
  `keyword_id` int(10) NOT NULL,
  `keyword_text` varchar(255) DEFAULT NULL,
  `keyword_FK_user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages_tbl`
--

CREATE TABLE `languages_tbl` (
  `language_id` int(10) unsigned NOT NULL,
  `language_name` varchar(100) NOT NULL DEFAULT '',
  `language_code` varchar(10) NOT NULL DEFAULT '',
  `language_FK_country_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_isDefault` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `language_order` int(4) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages_tbl`
--

INSERT INTO `languages_tbl` (`language_id`, `language_name`, `language_code`, `language_FK_country_id`, `language_isDefault`, `language_order`) VALUES
(1, 'Italiano', 'it', 61, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `libraries_tbl`
--

CREATE TABLE `libraries_tbl` (
  `library_id` int(10) NOT NULL,
  `library_name` varchar(255) NOT NULL DEFAULT '',
  `library_address` text NOT NULL,
  `library_city` varchar(255) NOT NULL DEFAULT '',
  `library_zip` varchar(100) NOT NULL DEFAULT '',
  `library_reference_name` varchar(255) NOT NULL DEFAULT '',
  `library_reference_email` varchar(255) NOT NULL DEFAULT '',
  `library_ip` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `licenses_tbl`
--

CREATE TABLE `licenses_tbl` (
  `license_id` int(10) NOT NULL,
  `license_startDate` date DEFAULT NULL,
  `license_FK_menu_id` int(10) NOT NULL DEFAULT '0',
  `license_FK_user_id` int(10) NOT NULL DEFAULT '0',
  `license_FK_library_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs_tbl`
--

CREATE TABLE `logs_tbl` (
  `log_id` int(11) unsigned NOT NULL,
  `log_level` varchar(100) NOT NULL DEFAULT '',
  `log_date` datetime NOT NULL,
  `log_ip` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `log_session` varchar(50) NOT NULL DEFAULT '',
  `log_group` varchar(50) NOT NULL DEFAULT '',
  `log_message` text NOT NULL,
  `log_FK_user_id` int(10) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs_tbl`
--

INSERT INTO `logs_tbl` (`log_id`, `log_level`, `log_date`, `log_ip`, `log_session`, `log_group`, `log_message`, `log_FK_user_id`) VALUES
(1, '4', '2016-04-10 18:32:37', '::1', 'cb4d50b97edfc63e95b491eb24d03c2c', 'easybook', 'gruppometa_easybook_controllers_publication_new Pubblicazione aggiunta correttamente #id:1', 1),
(2, '4', '2016-04-10 18:32:46', '::1', 'cb4d50b97edfc63e95b491eb24d03c2c', 'easybook', 'gruppometa_easybook_controllers_publication_new Pubblicazione aggiunta correttamente #id:2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mediadetails_tbl`
--

CREATE TABLE `mediadetails_tbl` (
  `mediadetail_id` int(10) unsigned NOT NULL,
  `mediadetail_FK_media_id` int(10) unsigned NOT NULL,
  `media_FK_language_id` int(10) unsigned NOT NULL,
  `media_FK_user_id` int(10) unsigned NOT NULL,
  `media_modificationDate` datetime DEFAULT '0000-00-00 00:00:00',
  `media_title` varchar(255) NOT NULL DEFAULT '',
  `media_category` varchar(255) DEFAULT NULL,
  `media_date` varchar(100) DEFAULT NULL,
  `media_copyright` varchar(255) DEFAULT NULL,
  `media_description` text,
  `media_tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_tbl`
--

CREATE TABLE `media_tbl` (
  `media_id` int(10) unsigned NOT NULL,
  `media_FK_site_id` int(10) unsigned NOT NULL,
  `media_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `media_fileName` varchar(255) NOT NULL DEFAULT '',
  `media_size` int(4) unsigned NOT NULL DEFAULT '0',
  `media_type` enum('IMAGE','OFFICE','PDF','ARCHIVE','FLASH','AUDIO','VIDEO','OTHER') NOT NULL DEFAULT 'IMAGE',
  `media_author` varchar(255) DEFAULT '',
  `media_originalFileName` varchar(255) NOT NULL DEFAULT '',
  `media_zoom` tinyint(1) DEFAULT '0',
  `media_download` int(10) NOT NULL DEFAULT '0',
  `media_md5` varchar(32) NOT NULL,
  `media_watermark` tinyint(1) NOT NULL DEFAULT '0',
  `media_allowDownload` tinyint(1) NOT NULL DEFAULT '1',
  `media_thumbFileName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menudetails_tbl`
--

CREATE TABLE `menudetails_tbl` (
  `menudetail_id` int(10) unsigned NOT NULL,
  `menudetail_FK_menu_id` int(10) unsigned NOT NULL DEFAULT '0',
  `menudetail_FK_language_id` int(10) unsigned NOT NULL DEFAULT '0',
  `menudetail_title` text,
  `menudetail_keywords` text,
  `menudetail_description` text,
  `menudetail_subject` text,
  `menudetail_creator` text,
  `menudetail_publisher` text,
  `menudetail_contributor` text,
  `menudetail_type` text,
  `menudetail_identifier` text,
  `menudetail_source` text,
  `menudetail_relation` text,
  `menudetail_coverage` text,
  `menudetail_isVisible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `menudetail_titleLink` varchar(255) NOT NULL DEFAULT '',
  `menudetail_linkDescription` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menudetails_tbl`
--

INSERT INTO `menudetails_tbl` (`menudetail_id`, `menudetail_FK_menu_id`, `menudetail_FK_language_id`, `menudetail_title`, `menudetail_keywords`, `menudetail_description`, `menudetail_subject`, `menudetail_creator`, `menudetail_publisher`, `menudetail_contributor`, `menudetail_type`, `menudetail_identifier`, `menudetail_source`, `menudetail_relation`, `menudetail_coverage`, `menudetail_isVisible`, `menudetail_titleLink`, `menudetail_linkDescription`) VALUES
(1, 1, 1, 'Nuova pubblicazione', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', ''),
(2, 2, 1, 'Nuova pubblicazione PDF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `menus_tbl`
--

CREATE TABLE `menus_tbl` (
  `menu_id` int(10) unsigned NOT NULL,
  `menu_parentId` int(10) unsigned DEFAULT '0',
  `menu_FK_site_id` int(10) unsigned NOT NULL DEFAULT '0',
  `menu_pageType` varchar(100) NOT NULL DEFAULT '',
  `menu_order` int(4) unsigned DEFAULT '0',
  `menu_hasPreview` tinyint(1) unsigned DEFAULT '1',
  `menu_creationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `menu_modificationDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `menu_type` enum('HOMEPAGE','PAGE','SYSTEM') NOT NULL DEFAULT 'PAGE',
  `menu_url` varchar(255) DEFAULT '',
  `menu_isLocked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `menu_hasComment` tinyint(1) NOT NULL DEFAULT '0',
  `menu_printPdf` tinyint(1) NOT NULL DEFAULT '0',
  `menu_extendsPermissions` tinyint(1) NOT NULL DEFAULT '0',
  `menu_cssClass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus_tbl`
--

INSERT INTO `menus_tbl` (`menu_id`, `menu_parentId`, `menu_FK_site_id`, `menu_pageType`, `menu_order`, `menu_hasPreview`, `menu_creationDate`, `menu_modificationDate`, `menu_type`, `menu_url`, `menu_isLocked`, `menu_hasComment`, `menu_printPdf`, `menu_extendsPermissions`, `menu_cssClass`) VALUES
(1, 0, 1, 'Publication', 0, 1, '2016-04-10 18:32:37', '2016-04-10 18:32:37', 'PAGE', '', 0, 0, 0, 0, NULL),
(2, 0, 2, 'PublicationPdf', 2, 1, '2016-04-10 18:32:46', '2016-04-10 18:33:27', 'PAGE', '', 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `offline_tbl`
--

CREATE TABLE `offline_tbl` (
  `offline_id` int(10) NOT NULL,
  `offline_path` varchar(150) NOT NULL DEFAULT '',
  `offline_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `registry_tbl`
--

CREATE TABLE `registry_tbl` (
  `registry_id` int(11) NOT NULL,
  `registry_path` varchar(255) NOT NULL DEFAULT '',
  `registry_value` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registry_tbl`
--

INSERT INTO `registry_tbl` (`registry_id`, `registry_path`, `registry_value`) VALUES
(1, 'easybook/siteProp/it', '');

-- --------------------------------------------------------

--
-- Table structure for table `roles_tbl`
--

CREATE TABLE `roles_tbl` (
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(100) NOT NULL DEFAULT '',
  `role_permissions` text NOT NULL,
  `role_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_tbl`
--

INSERT INTO `roles_tbl` (`role_id`, `role_name`, `role_permissions`, `role_active`) VALUES
(1, 'Amministratori', 'a:14:{s:20:"easybookcontentsedit";a:1:{s:3:"all";s:1:"1";}s:12:"mediaarchive";a:1:{s:3:"all";s:1:"1";}s:26:"isle_ontologybuilder_alias";a:1:{s:3:"all";s:1:"1";}s:17:"usermanager_alias";a:1:{s:3:"all";s:1:"1";}s:6:"dummy1";a:1:{s:7:"visible";s:1:"1";}s:6:"dummy2";a:1:{s:7:"visible";s:1:"1";}s:6:"dummy3";a:1:{s:7:"visible";s:1:"1";}s:20:"isle_ontologybuilder";a:1:{s:3:"all";s:1:"1";}s:20:"isle_relationseditor";a:1:{s:3:"all";s:1:"1";}s:10:"isle_graph";a:1:{s:3:"all";s:1:"1";}s:19:"isle_contentseditor";a:1:{s:3:"all";s:1:"1";}s:11:"usermanager";a:1:{s:3:"all";s:1:"1";}s:12:"groupmanager";a:1:{s:3:"all";s:1:"1";}s:11:"rolemanager";a:1:{s:3:"all";s:1:"1";}}', 1),
(2, 'Contenuti plus', 'a:4:{s:6:"dummy2";a:1:{s:7:"visible";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', 1),
(3, 'Contenuti protetti', 'a:4:{s:6:"dummy1";a:1:{s:7:"visible";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', 1),
(4, 'Contenuti per docenti', 'a:4:{s:6:"dummy3";a:1:{s:7:"visible";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', NULL),
(5, 'Autore', 'a:13:{s:20:"easybookcontentsedit";a:1:{s:3:"all";s:1:"1";}s:12:"mediaarchive";a:1:{s:3:"all";s:1:"1";}s:26:"isle_ontologybuilder_alias";a:1:{s:3:"all";s:1:"1";}s:6:"dummy1";a:1:{s:7:"visible";s:1:"1";}s:6:"dummy2";a:1:{s:7:"visible";s:1:"1";}s:6:"dummy3";a:1:{s:7:"visible";s:1:"1";}s:20:"isle_ontologybuilder";a:1:{s:3:"all";s:1:"1";}s:20:"isle_relationseditor";a:1:{s:3:"all";s:1:"1";}s:10:"isle_graph";a:1:{s:3:"all";s:1:"1";}s:19:"isle_contentseditor";a:1:{s:3:"all";s:1:"1";}s:11:"usermanager";N;s:12:"groupmanager";N;s:11:"rolemanager";N;}', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_datetime_tbl`
--

CREATE TABLE `simple_documents_index_datetime_tbl` (
  `simple_document_index_datetime_id` int(10) unsigned NOT NULL,
  `simple_document_index_datetime_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_datetime_name` varchar(255) NOT NULL,
  `simple_document_index_datetime_value` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_date_tbl`
--

CREATE TABLE `simple_documents_index_date_tbl` (
  `simple_document_index_date_id` int(10) unsigned NOT NULL,
  `simple_document_index_date_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_date_name` varchar(255) NOT NULL,
  `simple_document_index_date_value` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_fulltext_tbl`
--

CREATE TABLE `simple_documents_index_fulltext_tbl` (
  `simple_document_index_fulltext_id` int(10) unsigned NOT NULL,
  `simple_document_index_fulltext_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_fulltext_name` varchar(70) NOT NULL,
  `simple_document_index_fulltext_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_int_tbl`
--

CREATE TABLE `simple_documents_index_int_tbl` (
  `simple_document_index_int_id` int(10) unsigned NOT NULL,
  `simple_document_index_int_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_int_name` varchar(255) NOT NULL,
  `simple_document_index_int_value` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_text_tbl`
--

CREATE TABLE `simple_documents_index_text_tbl` (
  `simple_document_index_text_id` int(10) unsigned NOT NULL,
  `simple_document_index_text_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_text_name` varchar(255) NOT NULL,
  `simple_document_index_text_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_index_time_tbl`
--

CREATE TABLE `simple_documents_index_time_tbl` (
  `simple_document_index_time_id` int(10) unsigned NOT NULL,
  `simple_document_index_time_FK_simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_index_time_name` varchar(255) NOT NULL,
  `simple_document_index_time_value` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simple_documents_tbl`
--

CREATE TABLE `simple_documents_tbl` (
  `simple_document_id` int(10) unsigned NOT NULL,
  `simple_document_type` varchar(255) NOT NULL,
  `simple_document_FK_site_id` int(10) unsigned NOT NULL,
  `simple_document_object` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tagdetails_tbl`
--

CREATE TABLE `tagdetails_tbl` (
  `tagdetail_id` int(10) NOT NULL,
  `tagdetail_FK_tag_id` int(10) NOT NULL,
  `tagdetail_keyword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags_tbl`
--

CREATE TABLE `tags_tbl` (
  `tag_id` int(10) NOT NULL,
  `tag_FK_user_id` int(10) NOT NULL,
  `tag_volume_id` varchar(100) NOT NULL,
  `tag_content_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usergroups_tbl`
--

CREATE TABLE `usergroups_tbl` (
  `usergroup_id` int(11) NOT NULL,
  `usergroup_name` varchar(50) NOT NULL DEFAULT '',
  `usergroup_backEndAccess` tinyint(1) NOT NULL DEFAULT '0',
  `usergroup_FK_site_id` int(10) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroups_tbl`
--

INSERT INTO `usergroups_tbl` (`usergroup_id`, `usergroup_name`, `usergroup_backEndAccess`, `usergroup_FK_site_id`) VALUES
(1, 'Amministratori', 1, 1),
(2, 'Editori', 1, 1),
(4, 'Utenti', 0, 1),
(6, 'Bibliotecari', 1, 1),
(7, 'Content Editor', 1, 1),
(8, 'Autori blog', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userlogs_tbl`
--

CREATE TABLE `userlogs_tbl` (
  `userlog_id` int(10) unsigned NOT NULL,
  `userlog_FK_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `userlog_session` varchar(50) NOT NULL DEFAULT '',
  `userlog_ip` varchar(50) NOT NULL DEFAULT '',
  `userlog_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userlog_lastAction` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `user_id` int(10) unsigned NOT NULL,
  `user_FK_usergroup_id` int(10) unsigned NOT NULL DEFAULT '2',
  `user_FK_site_id` int(10) unsigned NOT NULL,
  `user_dateCreation` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_isActive` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_loginId` varchar(100) NOT NULL DEFAULT '',
  `user_password` varchar(100) NOT NULL DEFAULT '',
  `user_firstName` varchar(100) NOT NULL DEFAULT '',
  `user_lastName` varchar(100) NOT NULL DEFAULT '',
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_FK_editor_id` int(11) NOT NULL,
  `user_age` int(11) DEFAULT NULL,
  `user_city` varchar(255) DEFAULT NULL,
  `user_interests` varchar(255) DEFAULT NULL,
  `user_qualification` varchar(255) DEFAULT NULL,
  `user_profession` varchar(255) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `user_fiscalCode` char(16) DEFAULT NULL,
  `user_province` varchar(255) DEFAULT NULL,
  `user_cap` char(5) DEFAULT NULL,
  `user_country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`user_id`, `user_FK_usergroup_id`, `user_FK_site_id`, `user_dateCreation`, `user_isActive`, `user_loginId`, `user_password`, `user_firstName`, `user_lastName`, `user_email`, `user_FK_editor_id`, `user_age`, `user_city`, `user_interests`, `user_qualification`, `user_profession`, `user_address`, `user_fiscalCode`, `user_province`, `user_cap`, `user_country`) VALUES
(1, 1, 0, '2016-04-10 00:00:00', 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Mario', 'Rossi', 'admin@admin.com', 0, 18, '', '', '', '', '', '', '', '', 'Italia'),
(115, 2, 0, '2016-04-10 18:31:42', 1, 'admin.editore', 'd49b474472fddbe7bca43d6ccd108b1d', 'Luigi', 'Bianchi', 'admin.editore@admin.com', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_requests_tbl`
--

CREATE TABLE `user_requests_tbl` (
  `user_request_id` int(10) unsigned NOT NULL,
  `user_request_firstName` varchar(100) NOT NULL DEFAULT '',
  `user_request_lastName` varchar(100) NOT NULL DEFAULT '',
  `user_request_email` varchar(255) NOT NULL DEFAULT '',
  `user_request_bookTitle` varchar(255) NOT NULL DEFAULT '',
  `user_request_description` text NOT NULL,
  `user_request_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annotations_tbl`
--
ALTER TABLE `annotations_tbl`
  ADD PRIMARY KEY (`annotation_id`);

--
-- Indexes for table `cds_tbl`
--
ALTER TABLE `cds_tbl`
  ADD PRIMARY KEY (`cds_id`),
  ADD KEY `cds_FK_user_id` (`cds_FK_user_id`),
  ADD KEY `cds_FK_pub_id` (`cds_FK_pub_id`);

--
-- Indexes for table `codesgroup_tbl`
--
ALTER TABLE `codesgroup_tbl`
  ADD PRIMARY KEY (`codegroup_id`),
  ADD KEY `codegroup_FK_user_id` (`codegroup_FK_user_id`);

--
-- Indexes for table `codes_tbl`
--
ALTER TABLE `codes_tbl`
  ADD PRIMARY KEY (`code_id`),
  ADD KEY `code_FK_user_id` (`code_FK_user_id`),
  ADD KEY `code_value` (`code_pos`),
  ADD KEY `code_FK_codegroup_id` (`code_FK_codegroup_id`);

--
-- Indexes for table `desiderata_tbl`
--
ALTER TABLE `desiderata_tbl`
  ADD PRIMARY KEY (`desiderata_id`),
  ADD KEY `desiderata_FK_user_id` (`desiderata_FK_user_id`);

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
  ADD KEY `document_index_datetime_name` (`document_index_datetime_name`,`document_index_datetime_value`);

--
-- Indexes for table `documents_index_date_tbl`
--
ALTER TABLE `documents_index_date_tbl`
  ADD PRIMARY KEY (`document_index_date_id`),
  ADD KEY `document_index_date_fk` (`document_index_date_FK_document_detail_id`),
  ADD KEY `document_index_date_name` (`document_index_date_name`,`document_index_date_value`);

--
-- Indexes for table `documents_index_fulltext_tbl`
--
ALTER TABLE `documents_index_fulltext_tbl`
  ADD PRIMARY KEY (`document_index_fulltext_id`),
  ADD KEY `document_index_fulltext_name` (`document_index_fulltext_name`),
  ADD KEY `document_index_fulltext_FK_document_detail_id` (`document_index_fulltext_FK_document_detail_id`),
  ADD FULLTEXT KEY `document_index_fulltext_value` (`document_index_fulltext_value`);

--
-- Indexes for table `documents_index_int_tbl`
--
ALTER TABLE `documents_index_int_tbl`
  ADD PRIMARY KEY (`document_index_int_id`),
  ADD KEY `document_index_int_fk` (`document_index_int_FK_document_detail_id`),
  ADD KEY `document_index_int_name` (`document_index_int_name`,`document_index_int_value`);

--
-- Indexes for table `documents_index_text_tbl`
--
ALTER TABLE `documents_index_text_tbl`
  ADD PRIMARY KEY (`document_index_text_id`),
  ADD KEY `document_index_text_fk` (`document_index_text_FK_document_detail_id`),
  ADD KEY `document_index_text_name` (`document_index_text_name`(70),`document_index_text_value`);

--
-- Indexes for table `documents_index_time_tbl`
--
ALTER TABLE `documents_index_time_tbl`
  ADD PRIMARY KEY (`document_index_time_id`),
  ADD KEY `document_index_time_fk` (`document_index_time_FK_document_detail_id`),
  ADD KEY `document_index_time_name` (`document_index_time_name`,`document_index_time_value`);

--
-- Indexes for table `documents_tbl`
--
ALTER TABLE `documents_tbl`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `document_type` (`document_type`),
  ADD KEY `document_FK_site_id` (`document_FK_site_id`);

--
-- Indexes for table `ecommordersitems_tbl`
--
ALTER TABLE `ecommordersitems_tbl`
  ADD PRIMARY KEY (`orderitem_id`),
  ADD KEY `orderitem_FK_order_id` (`orderitem_FK_order_id`);

--
-- Indexes for table `ecommorders_tbl`
--
ALTER TABLE `ecommorders_tbl`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_FK_user_id` (`order_FK_user_id`);

--
-- Indexes for table `editors_tbl`
--
ALTER TABLE `editors_tbl`
  ADD PRIMARY KEY (`editor_id`);

--
-- Indexes for table `entity_properties_tbl`
--
ALTER TABLE `entity_properties_tbl`
  ADD PRIMARY KEY (`entity_properties_id`),
  ADD KEY `entity_properties_fk_entity_id` (`entity_properties_FK_entity_id`),
  ADD KEY `entity_properties_target_fk_entity_id` (`entity_properties_target_FK_entity_id`);

--
-- Indexes for table `entity_tbl`
--
ALTER TABLE `entity_tbl`
  ADD PRIMARY KEY (`entity_id`),
  ADD KEY `entity_FK_site_id` (`entity_FK_site_id`);

--
-- Indexes for table `eventStats_tbl`
--
ALTER TABLE `eventStats_tbl`
  ADD PRIMARY KEY (`eventStats_id`);

--
-- Indexes for table `jobs_tbl`
--
ALTER TABLE `jobs_tbl`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `job_FK_user_id` (`job_FK_user_id`);

--
-- Indexes for table `joins_tbl`
--
ALTER TABLE `joins_tbl`
  ADD PRIMARY KEY (`join_id`),
  ADD KEY `join_FK_source_id` (`join_FK_source_id`),
  ADD KEY `join_FK_dest_id` (`join_FK_dest_id`),
  ADD KEY `join_objectName` (`join_objectName`);

--
-- Indexes for table `keywords_tbl`
--
ALTER TABLE `keywords_tbl`
  ADD PRIMARY KEY (`keyword_id`),
  ADD KEY `keyword_FK_user_id` (`keyword_FK_user_id`);

--
-- Indexes for table `languages_tbl`
--
ALTER TABLE `languages_tbl`
  ADD PRIMARY KEY (`language_id`),
  ADD KEY `language_FK_country_id` (`language_FK_country_id`);

--
-- Indexes for table `libraries_tbl`
--
ALTER TABLE `libraries_tbl`
  ADD PRIMARY KEY (`library_id`);

--
-- Indexes for table `licenses_tbl`
--
ALTER TABLE `licenses_tbl`
  ADD PRIMARY KEY (`license_id`),
  ADD KEY `license_FK_menu_id` (`license_FK_menu_id`),
  ADD KEY `license_FK_user_id` (`license_FK_user_id`),
  ADD KEY `license_FK_library_id` (`license_FK_library_id`);

--
-- Indexes for table `logs_tbl`
--
ALTER TABLE `logs_tbl`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_level` (`log_level`),
  ADD KEY `log_group` (`log_group`),
  ADD KEY `log_FK_user_id` (`log_FK_user_id`);

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
  ADD KEY `menu_pageType` (`menu_pageType`),
  ADD KEY `menu_FK_site_id` (`menu_FK_site_id`),
  ADD KEY `menu_parentId` (`menu_parentId`);

--
-- Indexes for table `offline_tbl`
--
ALTER TABLE `offline_tbl`
  ADD PRIMARY KEY (`offline_id`);

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
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `simple_documents_index_datetime_tbl`
--
ALTER TABLE `simple_documents_index_datetime_tbl`
  ADD PRIMARY KEY (`simple_document_index_datetime_id`),
  ADD KEY `simple_document_index_datetime_fk` (`simple_document_index_datetime_FK_simple_document_id`),
  ADD KEY `simple_document_index_datetime_name` (`simple_document_index_datetime_name`(250));

--
-- Indexes for table `simple_documents_index_date_tbl`
--
ALTER TABLE `simple_documents_index_date_tbl`
  ADD PRIMARY KEY (`simple_document_index_date_id`),
  ADD KEY `simple_document_index_date_fk` (`simple_document_index_date_FK_simple_document_id`),
  ADD KEY `simple_document_index_date_name` (`simple_document_index_date_name`);

--
-- Indexes for table `simple_documents_index_fulltext_tbl`
--
ALTER TABLE `simple_documents_index_fulltext_tbl`
  ADD PRIMARY KEY (`simple_document_index_fulltext_id`),
  ADD KEY `simple_document_index_fulltext_FK_simple_document_detail_id` (`simple_document_index_fulltext_FK_simple_document_id`),
  ADD KEY `simple_document_index_fulltext_name` (`simple_document_index_fulltext_name`),
  ADD FULLTEXT KEY `simple_document_index_fulltext_value` (`simple_document_index_fulltext_value`);

--
-- Indexes for table `simple_documents_index_int_tbl`
--
ALTER TABLE `simple_documents_index_int_tbl`
  ADD PRIMARY KEY (`simple_document_index_int_id`),
  ADD KEY `simple_document_index_int_fk` (`simple_document_index_int_FK_simple_document_id`),
  ADD KEY `simple_document_index_int_name` (`simple_document_index_int_name`);

--
-- Indexes for table `simple_documents_index_text_tbl`
--
ALTER TABLE `simple_documents_index_text_tbl`
  ADD PRIMARY KEY (`simple_document_index_text_id`),
  ADD KEY `simple_document_index_text_fk` (`simple_document_index_text_FK_simple_document_id`),
  ADD KEY `simple_document_index_text_name` (`simple_document_index_text_name`);

--
-- Indexes for table `simple_documents_index_time_tbl`
--
ALTER TABLE `simple_documents_index_time_tbl`
  ADD PRIMARY KEY (`simple_document_index_time_id`),
  ADD KEY `simple_document_index_time_fk` (`simple_document_index_time_FK_simple_document_id`),
  ADD KEY `simple_document_index_time_name` (`simple_document_index_time_name`);

--
-- Indexes for table `simple_documents_tbl`
--
ALTER TABLE `simple_documents_tbl`
  ADD PRIMARY KEY (`simple_document_id`),
  ADD KEY `simple_document_type` (`simple_document_type`),
  ADD KEY `simple_document_FK_site_id` (`simple_document_FK_site_id`);

--
-- Indexes for table `tagdetails_tbl`
--
ALTER TABLE `tagdetails_tbl`
  ADD PRIMARY KEY (`tagdetail_id`);

--
-- Indexes for table `tags_tbl`
--
ALTER TABLE `tags_tbl`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `usergroups_tbl`
--
ALTER TABLE `usergroups_tbl`
  ADD PRIMARY KEY (`usergroup_id`),
  ADD KEY `usergroup_FK_site_id` (`usergroup_FK_site_id`);

--
-- Indexes for table `userlogs_tbl`
--
ALTER TABLE `userlogs_tbl`
  ADD PRIMARY KEY (`userlog_id`),
  ADD KEY `userlog_FK_user_id` (`userlog_FK_user_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_FK_usergroup_id` (`user_FK_usergroup_id`),
  ADD KEY `user_FK_site_id` (`user_FK_site_id`);

--
-- Indexes for table `user_requests_tbl`
--
ALTER TABLE `user_requests_tbl`
  ADD PRIMARY KEY (`user_request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annotations_tbl`
--
ALTER TABLE `annotations_tbl`
  MODIFY `annotation_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cds_tbl`
--
ALTER TABLE `cds_tbl`
  MODIFY `cds_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `codesgroup_tbl`
--
ALTER TABLE `codesgroup_tbl`
  MODIFY `codegroup_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `codes_tbl`
--
ALTER TABLE `codes_tbl`
  MODIFY `code_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `desiderata_tbl`
--
ALTER TABLE `desiderata_tbl`
  MODIFY `desiderata_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_detail_tbl`
--
ALTER TABLE `documents_detail_tbl`
  MODIFY `document_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
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
  MODIFY `document_index_fulltext_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `documents_index_int_tbl`
--
ALTER TABLE `documents_index_int_tbl`
  MODIFY `document_index_int_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `documents_index_text_tbl`
--
ALTER TABLE `documents_index_text_tbl`
  MODIFY `document_index_text_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `documents_index_time_tbl`
--
ALTER TABLE `documents_index_time_tbl`
  MODIFY `document_index_time_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents_tbl`
--
ALTER TABLE `documents_tbl`
  MODIFY `document_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ecommordersitems_tbl`
--
ALTER TABLE `ecommordersitems_tbl`
  MODIFY `orderitem_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ecommorders_tbl`
--
ALTER TABLE `ecommorders_tbl`
  MODIFY `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `editors_tbl`
--
ALTER TABLE `editors_tbl`
  MODIFY `editor_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `entity_properties_tbl`
--
ALTER TABLE `entity_properties_tbl`
  MODIFY `entity_properties_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `entity_tbl`
--
ALTER TABLE `entity_tbl`
  MODIFY `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `eventStats_tbl`
--
ALTER TABLE `eventStats_tbl`
  MODIFY `eventStats_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jobs_tbl`
--
ALTER TABLE `jobs_tbl`
  MODIFY `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joins_tbl`
--
ALTER TABLE `joins_tbl`
  MODIFY `join_id` int(1) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keywords_tbl`
--
ALTER TABLE `keywords_tbl`
  MODIFY `keyword_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `languages_tbl`
--
ALTER TABLE `languages_tbl`
  MODIFY `language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `libraries_tbl`
--
ALTER TABLE `libraries_tbl`
  MODIFY `library_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `licenses_tbl`
--
ALTER TABLE `licenses_tbl`
  MODIFY `license_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs_tbl`
--
ALTER TABLE `logs_tbl`
  MODIFY `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
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
  MODIFY `menudetail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `menus_tbl`
--
ALTER TABLE `menus_tbl`
  MODIFY `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `offline_tbl`
--
ALTER TABLE `offline_tbl`
  MODIFY `offline_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `registry_tbl`
--
ALTER TABLE `registry_tbl`
  MODIFY `registry_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `roles_tbl`
--
ALTER TABLE `roles_tbl`
  MODIFY `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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
-- AUTO_INCREMENT for table `tagdetails_tbl`
--
ALTER TABLE `tagdetails_tbl`
  MODIFY `tagdetail_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tags_tbl`
--
ALTER TABLE `tags_tbl`
  MODIFY `tag_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usergroups_tbl`
--
ALTER TABLE `usergroups_tbl`
  MODIFY `usergroup_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `userlogs_tbl`
--
ALTER TABLE `userlogs_tbl`
  MODIFY `userlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=116;
--
-- AUTO_INCREMENT for table `user_requests_tbl`
--
ALTER TABLE `user_requests_tbl`
  MODIFY `user_request_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
