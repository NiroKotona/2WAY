<?php
#----------------#
#  設定チェック  #
#----------------#
function check(){
	global $REG;

	// 認証キー用チェック
	if($REG['check'] == 1){
		$img = imagecreate(1, 1) or die("Could not Initialize of [ new GD image stream ]");
		imagedestroy($img);
		if($REG['crypt'] == 1){
			$hd = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
			mcrypt_module_close($hd);
		}elseif($REG['crypt'] == 2){
			$value = bcadd(1, 1);
			$value = bcsub(1, 1);
		}
	}
	// メール通知機能用チェック
	if(FLAG_MAIL){
		$version_org = phpversion();
		$version_arr = explode('.', $version_org);
		$version = $version_arr[0] * 10000 + $version_arr[1] * 100 + $version_arr[2];
		if($version < 40006){
			header("Content-Type: text/html;charset=utf-8");
			echo <<<EOM
mb_send_mailが使えるのはPHP4.0.6 以降からです。
現在お使いのPHPのバージョンは、<font color=\"#CC0000\">{$version_org}</font> です。
<p><a href="javascript:history.back();">&lt;&lt;戻る</a></p>
EOM;
exit;
		}
	}

	if(DBSTR == "mysql"){
		// MySQL用チェック
		$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
		$version = $db->check_version();
		$db->close();
		$ver_arr = split("\.", $version);
		if(intval($ver_arr[0]) >= 4 && intval($ver_arr[1]) >= 1){
		}else{
			header("Content-Type: text/html;charset=utf-8");
			echo <<<EOM
文字コード(UTF-8)が使えるのはMySQL4.1 以降からです。
現在お使いのMySQLのバージョンは、<font color=\"#CC0000\">{$version}</font> です。
<p><a href="javascript:history.back();">&lt;&lt;戻る</a></p>
EOM;
exit;
		}
	}else{
		// SQLite用チェック
		check_loaded_sqlite();
	}

	finish("設定チェック：OK");
}
##======================================================##
##  SQLiteが使えるか判定                                ##
##======================================================##
function check_loaded_sqlite(){

	$version_org = phpversion();
	$version_arr = explode('.', $version_org);
	$version = $version_arr[0] * 10000 + $version_arr[1] * 100 + $version_arr[2];
	if(DBSTR == "sqlite3"){
		if($version < 50300 || !extension_loaded('sqlite3')){
			header("Content-Type: text/html;charset=utf-8");
			echo <<<EOM
SQLite3 拡張モジュールがロードされてない為、データベースタイプ：3=SQLite3 は利用できません。<br>
SQLite3 拡張モジュールは、PHP 5.3.0 以降デフォルトで有効となります。<br>
現在お使いのPHPのバージョンは、<font color="#CC0000">{$version_org}</font> です。
<p><a href="javascript:history.back();">&lt;&lt;戻る</a></p>
EOM;
exit;
		}
	}else{
		if(!extension_loaded('sqlite')){
			if($version >= 50400){
				header("Content-Type: text/html;charset=utf-8");
				echo <<<EOM
SQLite 拡張モジュールがロードされてない為、データベースタイプ：2=SQLite2 は利用できません。<br>
SQLite 拡張モジュールは、PHP 5.4.0 以降デフォルトでは有効となっていません。<br>
別途インストールするか、データベースタイプ：3=SQLite3 を選択してご利用下さい。<br>
現在お使いのPHPのバージョンは、<font color="#CC0000">{$version_org}</font> です。
<p><a href="javascript:history.back();">&lt;&lt;戻る</a></p>
EOM;
exit;
			}else{
				header("Content-Type: text/html;charset=utf-8");
				echo <<<EOM
SQLite 拡張モジュールがロードされてない為、データベースタイプ：2=SQLite2 は利用できません。<br>
別途インストールするか、データベースタイプ：3=SQLite3 を選択してご利用下さい。<br>
現在お使いのPHPのバージョンは、<font color="#CC0000">{$version_org}</font> です。
<p><a href="javascript:history.back();">&lt;&lt;戻る</a></p>
EOM;
exit;
			}
		}
	}
}
#--------------------#
#  最後のメッセージ  #
#--------------------#
function finish($msg){
	global $homeurl,$title;

$msg4text = strip_tags($msg);
$path_css = PATH_CSS;
echo <<<EOM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>${msg4text}</title>
<link rel="stylesheet" href="${path_css}style.css" type="text/css">
</head>
<body>
<h4>${msg}</h4>
<a href="javascript:history.back();">&lt;&lt;戻る</a>　  <a href="${homeurl}">ホームへ戻る</a>
</body>
</html>
EOM;
exit;
}
?>