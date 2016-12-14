<?php
##======================================================##
##  365 Forum データコンバート                          ##
##  Copyright (C) php365.com All rights reserved.       ##
##  http://php365.com/                                  ##
##======================================================##
// デフォルト設定ライブラリ
require_once("./lib/config.php");
// データベース操作ライブラリ
require_once(PATH_LIB.DBSTR.".php");
// 共通仕様サブルーチン
require_once(PATH_LIB."common.php");
if(DBSTR == "mysql"){
	require_once(PATH_LIB."define_table_".DBSTR.".php");
}else{
	require_once(PATH_LIB."define_table_sqlite.php");
}
#--------------------------------------------------------#
$script_name4html = t2h($_SERVER['SCRIPT_NAME']);
switch($_SERVER['REQUEST_METHOD']):
	case 'POST':
		if(!isset($_POST['mode'])){	show_html_top();exit; }
		switch($_POST['mode']):
			case 'file':
				convert_from_file();
				break;
			case 'mysql':
				convert_from_mysql();
				break;
			default:
				show_html_top();
				break;
		endswitch;
		break;
	default:
		show_html_top();
		break;
endswitch;
exit;
#----------------#
#  初期画面表示  #
#----------------#
function show_html_top(){
	global $script_name4html;

	require(PATH_TEMPLATE."convert.html");
}
#-----------------------------------------#
#  365 bbs（ファイル版）からのコンバート  #
#-----------------------------------------#
function convert_from_file(){

	if(!file_exists($_POST['logfile'])){
		error("コンバートするファイル（".t2h($_POST['logfile'])."）が存在しません、LINE=".__LINE__);
	}
	if(!empty($_POST['past_dir']) && !is_dir($_POST['past_dir'])){
		error("過去ログのディレクトリ（".t2h($_POST['past_dir'])."）が存在しません、LINE=".__LINE__);
	}
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
	$flag_match = false;
	$flag_match2 = false;
	$tb_master = DBPREFIX."master";
	$tb_admin = DBPREFIX."chk";
	$tb_names = $db->list_tables(DBNAME);
	for($i = 0; $i < count($tb_names); $i++){
		if(DBTYPE == 3){
			if($tb_names[$i]['name'] == $tb_master){
				$flag_match = true;
			}elseif($tb_names[$i]['name'] == $tb_admin){
				$flag_match2 = true;
			}
		}else{
			if($tb_names[$i] == $tb_master){
				$flag_match = true;
			}elseif($tb_names[$i] == $tb_admin){
				$flag_match2 = true;
			}
		}
	}
	if(!$flag_match){
		$db->close();
		error("テーブル名（".$tb_master."）が存在しません。setup.phpでテーブルを作成してから処理して下さい、LINE=".__LINE__);
	}
	if(!empty($_POST['past_dir']) && !$flag_match2){
		$db->close();
		error("テーブル名（".$tb_admin."）が存在しません。setup.phpでテーブルを作成してから処理して下さい、LINE=".__LINE__);
	}
	$data = file($_POST['logfile']);
	array_shift($data);
	$result = $db->transaction_begin();
	$thread = 0;
	do_convert_from_file($db,$tb_master,$data);
	if(!empty($_POST['past_dir'])){
		convert_from_file_to_past($db,$tb_names,$_POST['past_dir'],$flag_storage_engine,$flag_defaults_charset);
	}
	$result = $db->transaction_commit();
	$db->close();
	message("データコンバート完了しました。","convert.phpは、すぐに削除して下さい。");
}
#---------------------------------------------#
#  365 bbs（ファイル版）からのコンバート実行  #
#---------------------------------------------#
function do_convert_from_file(&$db,$tb_name,&$data){
	global $thread;

	while(list($key, $value) = each($data)){
		$value = rtrim($value);
		if(empty($value)){	continue; }
		$value = mb_convert_encoding($value, "UTF-8", "EUC-JP,UTF-8");
		$value = $db->escape_string($value);
		list($id,$ref,$date,$name,$email,$title,$comment,$color,$pass,$host,$hit,$top_flg,$thread_flg) = explode(",", $value);
		if(empty($id)){	continue; }
		if($ref == 0){	$thread++;	}
		$date = str_replace("/", "-", $date);
		$del_word = substr($date, 10, 5);
		$date = str_replace($del_word, "", $date);
		$lastmodify = $date;
		$lastmodify = str_replace(":", "", $lastmodify);
		$lastmodify = str_replace("-", "", $lastmodify);
		$lastmodify = str_replace(" ", "", $lastmodify);
		if($hit == ""){		$hit = 0;	}
		if($top_flg == ""){	$top_flg = 0;	}
		if($thread_flg == ""){	$thread_flg = 0;	}
		$sql = <<<EOM
INSERT INTO $tb_name VALUES ($id,$ref,'$date','$name','$email','$title','$comment',$color,'$pass','$host',$hit,$top_flg,$thread_flg,$lastmodify,$thread);
EOM;
		$result = $db->query($sql);
		$rows = $db->affected_rows();
		if(!$rows){
			$db->close();
			error("テーブル：${tb_name}へデータ追加中にエラーが発生しました、LINE=".__LINE__);
		}
	}
}
#-------------------------------------------------------------#
#  365 bbs（ファイル版）からのコンバート（過去ログテーブル）  #
#-------------------------------------------------------------#
function convert_from_file_to_past(&$db,&$tb_names,$past_dir,&$flag_storage_engine,&$flag_defaults_charset){
	global $define_master,$thread;

	$handle = @opendir($past_dir) or error("opendir Error: ".$past_dir);
	while(false !== ($target = readdir($handle))){
		$targetfile = $past_dir.$target;
		if(strstr($target, "past") && file_exists($targetfile)){
			$filename[] = $targetfile;
		}
	}
	closedir($handle);
	$last_past_no = 0;
	if($filename[0] != ""){
		reset($filename);
		$rpos = strrpos($_POST['logfile'], ".");
		$rpos++;
		$extension = substr($_POST['logfile'], $rpos);
		$pattern = '/^past[0-9]\.' . $extension .'$/';
		while(list(,$value) = each($filename)){
			//if(!preg_match($pattern, $filename)){	continue; }
			$data = array();
			$data = file($value);
			$past_no = str_replace($past_dir, "", $value);
			$past_no = str_replace("past", "", $past_no);
			$past_no = str_replace(".".$extension, "", $past_no);
			$tb_past_name = DBPREFIX."past${past_no}";
			if(is_numeric($past_no)){
				if($past_no > $last_past_no){	$last_past_no = $past_no; }
				$match_flg = 0;
				while(list($key, $value) = each($tb_names)){
					if($value == $tb_past_name){
						$match_flg = 1;
						break;
					}
				}
				if($match_flg){
					$sql = "TRUNCATE ${tb_past_name}";
					$result = $db->query($sql);
				}else{
					// 過去ログ用テーブル作成
					$sql = "CREATE TABLE ${tb_past_name} ";
					if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
					$sql .= $define_master;
					if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
					$result = $db->query($sql);
				}
				do_convert_from_file($db,$tb_past_name,$data);
			}
		}
		// 投稿管理テーブル更新
		$sql = "UPDATE ".DBPREFIX."chk SET ";
		if($last_past_no != 0){	$sql .= "past_no=${last_past_no}, "; }
		$sql .= "thread=${thread}";
		$result = $db->query($sql);
	}
}
#----------------------------------------------#
#  365 bbs+（MySQL版）からMySQLへのコンバート  #
#----------------------------------------------#
function convert_from_mysql(){
	global $define_master;

	foreach(array('dbname_old', 'dbtbl_old', 'charset_old') as $key){	$$key = trim($_POST[$key]); }
	if(empty($_POST['dbname_old'])){
		error("旧データベース名を指定して下さい、LINE=".__LINE__);
	}elseif(empty($_POST['dbtbl_old'])){
		error("旧テーブル名を指定して下さい、LINE=".__LINE__);
	}elseif($_POST['charset_old'] != 1 && $_POST['charset_old'] != 0){
		error("旧文字コードの指定が不正です、LINE=".__LINE__);
	}

	$db = new DB(DBHOST,DBUSER,DBPASS,$dbname_old);
	$tb_names = $db->list_tables($dbname_old);
	$flag_master = false;
	$flag_admin = false;
	$tb_past_arr = array();
	for($i = 0; $i < count($tb_names); $i++){
		if(preg_match('/^'.$dbtbl_old.'_past[0-9]+$/', $tb_names[$i])){
			$tb_past_arr[] = $tb_names[$i];
		}
		if($tb_names[$i] == $dbtbl_old){	$flag_master = true; }
		if(preg_match('/^'.$dbtbl_old.'_chk$/', $tb_names[$i])){	$flag_admin = true; }
	}
	if(!$flag_master){	$db->close();error("テーブル（".t2h($dbtbl_old)."）が存在しません、LINE=".__LINE__); }
	if(!$flag_admin){	$db->close();error("テーブル（".t2h($dbtbl_old."_chk")."）が存在しません、LINE=".__LINE__); }
	$db->close();

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
	$flag_master = false;
	$flag_admin = false;
	$tb_master = DBPREFIX."master";
	$tb_admin = DBPREFIX."chk";
	$tb_names = $db->list_tables(DBNAME);
	for($i = 0; $i < count($tb_names); $i++){
		if($tb_names[$i] == $tb_master){
			$flag_master = true;
		}elseif($tb_names[$i] == $tb_admin){
			$flag_admin = true;
		}
	}
	if(!$flag_master){
		$db->close();
		error("テーブル名（".$tb_master."）が存在しません。setup.phpでテーブルを作成してから処理して下さい、LINE=".__LINE__);
	}
	if(!$flag_admin){
		$db->close();
		error("テーブル名（".$tb_admin."）が存在しません。setup.phpでテーブルを作成してから処理して下さい、LINE=".__LINE__);
	}
	$result = $db->transaction_begin();
if($_POST['charset_old'] == 1){
	$sql = "INSERT INTO ${tb_master} SELECT * FROM ".$dbname_old.".".$dbtbl_old;
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error(t2h($dbname_old.".".$dbtbl_old)."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
	for($i = 0; $i < count($tb_past_arr); $i++){
		if(empty($tb_past_arr[$i])){	continue; }
		$num = intval(substr($tb_past_arr[$i], -1));
		if($num < 1){	continue; }
		$tb_past_name = DBPREFIX."past".$num;
		if(!in_array($tb_past_name, $tb_names)){
			// 過去ログ用テーブル作成
			$sql = "CREATE TABLE ${tb_past_name} ";
			if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
			$sql .= $define_master;
			if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
			$result = $db->query($sql);
		}
		$sql = "INSERT INTO ${tb_past_name} SELECT * FROM ".$dbname_old.".".$tb_past_arr[$i];
		$result = $db->query($sql);
		$rows = $db->affected_rows();
		if(!$rows){	error(t2h($dbname_old.".".$tb_past_arr[$i])."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
	}
	$sql = "TRUNCATE TABLE ${tb_admin}";
	$result = $db->query($sql);
	$sql = "INSERT INTO ${tb_admin} SELECT * FROM ".$dbname_old.".".$dbtbl_old."_chk";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error(t2h($dbname_old.".".$dbtbl_old."_chk")."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
}else{
	$sql = "SELECT count(*) FROM ".$dbname_old.".".$dbtbl_old;
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows > 0){
		$sql = "SELECT * FROM ".$dbname_old.".".$dbtbl_old." ORDER BY id";
		$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		foreach($items as $item){
			$line = "";
			foreach(array('id','ref','date','name','email','title','comment','color','pass','host','hit','top_flg','thread_flg','lastmodify','thread') as $key){
				$item[$key] = mb_convert_encoding($item[$key], "UTF-8", "EUC-JP,UTF-8");
				if(!empty($line)){	$line .= ","; }
				$line .= "'".$db->escape_string($item[$key])."'";
			}
			if(!empty($sql)){
				$sql = "INSERT INTO ${tb_master} VALUES ($line)";
				$result = $db->query($sql);
				$rows = $db->affected_rows();
				if(!$rows){	error(t2h($dbname_old.".".$dbtbl_old)."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
			}
		}
		for($i = 0; $i < count($tb_past_arr); $i++){
			if(empty($tb_past_arr[$i])){	continue; }
			$sql = "SELECT count(*) FROM ".$dbname_old.".".$tb_past_arr[$i]." ORDER BY id";
			$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
			if($rows == 0){	continue; }
			$num = intval(substr($tb_past_arr[$i], -1));
			if($num < 1){	continue; }
			$tb_past_name = DBPREFIX."past".$num;
			if(!in_array($tb_past_name, $tb_names)){
				// 過去ログ用テーブル作成
				$sql = "CREATE TABLE ${tb_past_name} ";
				if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
				$sql .= $define_master;
				if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
				$result = $db->query($sql);
			}
			$sql = "SELECT * FROM ".$dbname_old.".".$tb_past_arr[$i]." ORDER BY id";
			$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
			foreach($items as $item){
				$line = "";
				foreach(array('id','ref','date','name','email','title','comment','color','pass','host','hit','top_flg','thread_flg','lastmodify','thread') as $key){
					$item[$key] = mb_convert_encoding($item[$key], "UTF-8", "EUC-JP,UTF-8");
					if(!empty($line)){	$line .= ","; }
					$line .= "'".$db->escape_string($item[$key])."'";
				}
				if(!empty($sql)){
					$sql = "INSERT INTO ${tb_past_name} VALUES ($line)";
					$result = $db->query($sql);
					$rows = $db->affected_rows();
					if(!$rows){	error(t2h($dbname_old.".".$tb_past_arr[$i])."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
				}
			}
		}
		$sql = "SELECT count(*) FROM ".$dbname_old.".".$dbtbl_old."_chk";
		$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		if($rows > 0){
			$sql = "SELECT * FROM ".$dbname_old.".".$dbtbl_old."_chk";
			$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
			foreach($items as $item){
				$line = "";
				foreach(array('past_no','ip','lastmodify','thread','lastbak') as $key){
					$item[$key] = mb_convert_encoding($item[$key], "UTF-8", "EUC-JP,UTF-8");
					if(!empty($line)){	$line .= ","; }
					$line .= "'".$db->escape_string($item[$key])."'";
				}
			}
			if(!empty($sql)){
				$sql = "TRUNCATE TABLE ${tb_admin}";
				$result = $db->query($sql);
				$sql = "INSERT INTO ${tb_admin} VALUES ($line)";
				$result = $db->query($sql);
				$rows = $db->affected_rows();
				if(!$rows){	error(t2h($dbname_old.".".$dbtbl_old."_chk")."のコンバート中にエラーが発生しました、LINE=".__LINE__);	}
			}
		}
	}
}
	$result = $db->transaction_commit();
	$db->close();
	message("データコンバート完了しました。","convert.phpは、すぐに削除して下さい。");
}
#------------------#
#  メッセージ表示  #
#------------------#
function message($msg_title,$msg_com){
	global $homeurl,$title_name;

	echo <<<EOM
<html>
<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{$msg_title}</title>
</head>

<body>
<center>
<h4>{$msg_title}</h4>
<p>${msg_com}</p>
<p><a href="${homeurl}">${title_name}へ戻る</a></p>
</center>
</body>
</html>
EOM;
exit;
}
?>