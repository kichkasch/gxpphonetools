-- --------------------------------------------------------

--
-- Table structure for table `gxp_phonebook`
--

CREATE TABLE IF NOT EXISTS `gxp_phonebook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` tinytext NOT NULL,
  `last_name` tinytext NOT NULL,
  `phone_number` tinytext NOT NULL,
  `account_index` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66;
