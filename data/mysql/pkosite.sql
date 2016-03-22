DROP TABLE IF EXISTS `authors`;
CREATE TABLE IF NOT EXISTS `authors` (
  `author_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `authors` (`author_id`, `author`) VALUES
(1, 'Administrator'),
(2, 'SnowCrash');

DROP TABLE IF EXISTS `dlcategories`;
CREATE TABLE IF NOT EXISTS `dlcategories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `dlcategories` (`category_id`, `category`) VALUES
(1, 'Client'),
(2, 'Patches');

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE IF NOT EXISTS `downloads` (
  `download_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `url` varchar(120) DEFAULT NULL,
  `description` text,
  `size` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`download_id`),
  KEY `category_id` (`category_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `downloads` (`download_id`, `category_id`, `name`, `url`, `description`, `size`) VALUES
(1, 1, 'Tales of Pirates v1.37', 'http://top.igg.com/download_edit.php?id=256', 'Latest Tales of Pirates Client.', 382730240),
(2, 1, 'Pirate King Online', 'http://download.cibmall.net/Pirate_King_Online.exe', 'This is the latest Pirate King Online client.', 377487360);

DROP TABLE IF EXISTS `ncategories`;
CREATE TABLE IF NOT EXISTS `ncategories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `ncategories` (`category_id`, `category`) VALUES
(1, 'News'),
(2, 'Events'),
(3, 'Patches');

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) unsigned DEFAULT NULL,
  `headline` varchar(80) DEFAULT NULL,
  `body` text,
  `date` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`news_id`),
  KEY `category_id` (`category_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `news` (`news_id`, `category_id`, `author_id`, `headline`, `body`, `date`) VALUES
(3, 1, 2, 'Congratulations', 'You successfully installed <b><i>pkoSite</i></b>.\r\n\r\nTo enter the Admin-Area you have to login using a char with GM-Level 99.\r\n\r\nThe current version of the site allows players to register new accounts, edit their password/email-address and a lot of other things.\r\n\r\nCurrently there are <b>no</b> admin functions build into the admin-area that allow admins to modify the characters or accounts.\r\n\r\nIt is <b>not</b> planned to add those functions at any time! the main reason for this is that I don''t want to open a potential security hole. If I ever add such functions I will add them to a totally idependant application that can be installed seperatly.\r\n\r\n-SnowCrash', 1233226800);
