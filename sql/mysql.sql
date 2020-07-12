-- Tabulky ------------------------------------------------

CREATE TABLE `changelog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text,
  `datum` varchar(10),
  `uid` int(11) default '1' ,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;