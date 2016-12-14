<?php
$index_sql_arr = array();
	$define_master = <<<EOM
( id INTEGER PRIMARY KEY,
  ref INTEGER unsigned NOT NULL DEFAULT '0',
  date TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
  name TEXT NULL,
  email TEXT NULL,
  title TEXT NULL,
  comment TEXT,
  color TEXT NOT NULL DEFAULT '0',
  pass TEXT NULL,
  host TEXT NULL,
  hit TEXT NULL,
  top_flg INTEGER unsigned NOT NULL DEFAULT '0',
  thread_flg INTEGER unsigned NOT NULL DEFAULT '0',
  lastmodify INTEGER unsigned NOT NULL DEFAULT '0',
  thread INTEGER unsigned NOT NULL DEFAULT '0'
);
EOM;
$index_sql_arr[] = "CREATE INDEX ref ON DBPREFIXmaster (ref);";
$index_sql_arr[] = "CREATE INDEX lastmodify ON DBPREFIXmaster (lastmodify);";
	$define_chk = <<<EOM
( past_no INTEGER unsigned NOT NULL DEFAULT '0',
  ip TEXT NOT NULL DEFAULT '',
  lastmodify INTEGER unsigned NOT NULL DEFAULT '0',
  thread INTEGER unsigned NOT NULL DEFAULT '0',
  lastbak INTEGER unsigned NOT NULL DEFAULT '0'
);
EOM;
	$define_backup = <<<EOM
( lastbak INTEGER unsigned NOT NULL DEFAULT '0',
  count INTEGER unsigned NOT NULL DEFAULT '0'
);
EOM;
?>