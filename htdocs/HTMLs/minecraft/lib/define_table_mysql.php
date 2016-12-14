<?php
	$define_master = <<<EOM
( `id` int(10) unsigned NOT NULL auto_increment,
  `ref` int(10) unsigned NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) default NULL,
  `email` varchar(50) default NULL,
  `title` varchar(100) default NULL,
  `comment` text,
  `color` smallint(2) NOT NULL default '0',
  `pass` varchar(32) default NULL,
  `host` varchar(100) default NULL,
  `hit` int(10) unsigned NOT NULL default '0',
  `top_flg` smallint(1) unsigned NOT NULL default '0',
  `thread_flg` smallint(1) unsigned NOT NULL default '0',
  `lastmodify` bigint(14) NOT NULL default '0',
  `thread` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ref` (`ref`),
  KEY `lastmodify` (`lastmodify`)
) ENGINE=InnoDB
EOM;
	$define_chk = <<<EOM
( `past_no` smallint(4) unsigned NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `lastmodify` int(20) NOT NULL default '0',
  `thread` int(8) unsigned NOT NULL default '0',
  `lastbak` int(8) NOT NULL default '0'
) ENGINE=InnoDB
EOM;
?>