<?php
##======================================================##
##  365 Forum セットアップ                              ##
##  Copyright (C) php365.com All rights reserved.       ##
##  http://php365.com/                                  ##
##======================================================##
// デフォルト設定ライブラリ
require_once("./lib/config.php");
// 共通仕様サブルーチン
require_once(PATH_LIB."common.php");
// データベース操作ライブラリ
require_once(PATH_LIB.DBSTR.".php");
// テーブル定義
if(DBSTR == "mysql"){
	require_once(PATH_LIB."define_table_".DBSTR.".php");
}else{
	require_once(PATH_LIB."define_table_sqlite.php");
}

setup();
exit;
##======================================================##
##  セットアップ                                        ##
##======================================================##
function setup(){

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	if(DBSTR == "mysql"){
		$version = $db->check_version();
		$ver_arr = split("\.", $version);
		if(intval($ver_arr[0]) >= 5 && intval($ver_arr[1]) >= 5){
			$flag_storage_engine = true;
		}else{
			$flag_storage_engine = false;
		}
		if(intval($ver_arr[0]) >= 4 && intval($ver_arr[1]) >= 1){
			$flag_defaults_charset = true;
		}else{
			$flag_defaults_charset = false;
		}
	}else{
		$flag_storage_engine = true;
		$flag_defaults_charset = false;
	}
	$result = $db->transaction_begin();

	$tb_names = $db->list_tables(DBNAME);

	// テーブル作成（category,master,tags）
	setup_tables($db,$tb_names,$flag_storage_engine,$flag_defaults_charset);

	$result = $db->transaction_commit();
	$db->close();
	$msg_title = "セットアップ完了しました。";
	$msg_com = "セットアップ完了しました。<font color=\"red\">setup.php</font>は、すぐに削除して下さい。";
	message($msg_title,$msg_com);
}
##======================================================##
##  テーブル作成                                        ##
##======================================================##
function setup_tables(&$db,&$tb_names,&$flag_storage_engine,&$flag_defaults_charset){
	global $define_master,$define_chk,$define_backup,$index_sql_arr;

	foreach(array('master', 'chk', 'backup') as $key){
		if(in_array(DBPREFIX.$key, $tb_names)){
			error("テーブル：".DBPREFIX.$key."は作成済みです。 LINE=".__LINE__);
		}
		if($key == "backup" && DBSTR == "mysql"){	continue; }
		$define = "define_".$key;
		if(DBSTR == "mysql"){
			if(!$flag_storage_engine){	$$define = str_replace("ENGINE=", "TYPE=", $$define); }
			if($flag_defaults_charset){	$$define .= " DEFAULT CHARSET=utf8;"; }
		}
		$sql = "CREATE TABLE ".DBPREFIX.$key.$$define;
		$result = $db->query($sql);
		if($key == "chk"){
			$line = "1,'',0,0,0";
			$sql = "INSERT INTO ".DBPREFIX.$key." VALUES ($line)";
			$result = $db->query($sql);
		}elseif($key == "backup" && DBSTR != "mysql"){
			$line = "20000101,0";
			$sql = "INSERT INTO ".DBPREFIX.$key." VALUES ($line)";
			$result = $db->query($sql);
		}
	}
	if((intval(DBTYPE) == 2 || intval(DBTYPE) == 3) && is_array($index_sql_arr)){
		for($i = 0; $i < count($index_sql_arr); $i++){
			$sql = str_replace("DBPREFIX", DBPREFIX, $index_sql_arr[$i]);
			$result = $db->query($sql);
		}
	}
}
##======================================================##
##  メッセージ出力                                      ##
##======================================================##
function message($msg_title,$msg_com){
	global $homeurl,$title_name;

echo <<<EOM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>${msg_title}</title>
</head>

<body>
<center>
<h4>${msg_title}</h4>
<p>${msg_com}</p>
<br /><p><a href="${homeurl}">${title_name}へ戻る</a></p>
</center>
</body>
</html>
EOM;
exit;
}
?>