-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************


-- --------------------------------------------------------
-- 
-- Table `tl_suggest`
-- 

CREATE TABLE `tl_suggest` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(128) NOT NULL default '',
  `comic` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '', 
  `published` int(1) NOT NULL default '0',
  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `suggest_avatar` varchar(32) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
