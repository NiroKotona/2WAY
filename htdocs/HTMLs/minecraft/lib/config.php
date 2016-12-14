<?php
##======================================================##
##  365 Forum Config                                    ##
##  Copyright (C) php365.com All rights reserved.       ##
##  http://php365.com/                                  ##
##======================================================##
// ↓ライブラリパス
define("PATH_LIB", "lib/");
// ↓スタイルシート用パス
define("PATH_CSS", "css/");
// ↓javascript用パス
define("PATH_JS", "js/");
// ↓データベースパス（データベースタイプ：SQLite2 or SQLite3の場合のみ使用）
define("PATH_DB", "db/");
// ↓スタイルシート用パス
define("PATH_TEMPLATE", "tmpl/");

// データベースタイプ（1=MySQL、2=SQLite2(デフォルト)、3=SQLite3）
define("DBTYPE", 2);
// 接続するホスト名とポート番号
define("DBHOST", "localhost");
// データベース名、ユーザー名、パスワード（必ず修正）
define("DBNAME", "php365forum.db");	// データベース名
define("DBUSER", "");	// ユーザー名（MySQLの場合のみ設定必要）
define("DBPASS", "");	// パスワード（MySQLの場合のみ設定必要）
define("DBPREFIX", "php365forum_");	// テーブル名の最初に追加する共通の文字列

// ↓管理モード（0:ユーザ・管理者が登録可、1:管理者のみ登録可）
define("ADMIN_MODE", 0);

// ↓レス書き込み制限（0:ユーザ・管理者が返信可、1:管理者のみ返信可）
define("RES_MODE", 0);

// ↓管理用パスワード（必ず修正、半角英数8文字以内）
define("ADMIN_PASS", "123");

// ↓ホームに指定するURL（必ず修正）
$homeurl = 'http://www.zzz.zzz/';

// ↓タイトル
$title_name = '365 Forum';

// ↓バックアップは必要ですか？（0:No 1:Yes）
// Yesの場合、別テーブルにコピーしますが、バックアップはMySQLダンプを使って自己管理して下さい。
define("FLAG_BACK", 1);

// ↓バックアップは何日分必要ですか？（必ず1以上の数字で指定して下さい。）
// データベースタイプで（2=SQLite2(デフォルト)、3=SQLite3）を選択した場合のみ有効
// （バックアップファイルは、タイムスタンプで新旧を判断して下さい。）
define("BACKCNT", 3);

// ↓１ページに表示するスレッド件数（トップページのみ）
define("THREADVIEW", 20);
// ↓スレッドの表示方法（0:最初のスレを常に表示 1:最初のスレは最初のページのみ表示）
define("THREAD_DISP", 0);

// ↓１ページに表示する記事件数 (親記事)
define ("PAGEVIEW", 10);
// ↓改ページコントロール出力範囲（現在のページから出力する範囲を整数で指定）
define ("PAGE_CONTROL", 5);

// ↓最大記事件数
define ("LOG_MAX", 100);

// ↓返信された記事を先頭にソートしますか？（0:No 1:Yes）
define("SORT_FLG", 1);

// ↓リンクのtarget設定（例："_blank","_top"）
// コメント欄にURL記入があった場合に使用
define("TARGET", "new");

// ↓コメントに記入されたURLの自動リンク（0:No 1:Yes）
define("AUTO_LINK", 1);

// ↓名前が未記入の時
define("NONAME", "名無しさん");
// ↓タイトルが未記入の時
define("NOTITLE", "無題");

// ↓管理者メッセージの印（表示方法）
define("ADMIN_DISP", "Admin");
// ↓スレッド終了（ロック）の印（表示方法）
define("LOCK_DISP", "Lock");

// ↓投稿される文字数制限 (名前、タイトル、メッセージ　全角文字換算)
define("NAME_MAX", 20);
define("TITLE_MAX", 20);
define("COM_MAX", 500);

// ↓メッセージの文字色設定
$COLORS = array('#000000','#d2691e','#4169e1','#339933','#ff0000','#da70d6');

// ↓同一IPアドレスからの投稿間隔制限（0:制限無、1以上：秒数で指定）
define("REG_TIME", 0);

// ↓メール通知機能設定（投稿毎にメール通知） //
// ↓メール通知機能を使いますか？（0:No 1:Yes）
define("FLAG_MAIL", 0);
// ↓受信時に使うメールアドレス
define("MAILTO", "mail@mail.mail");
// ↓メールサブジェクト（件名）
define("SUBJECT", "365 Forum メール通知");
// ↑メール通知機能設定 ここまで //

// ↓トリップ機能設定 //
// ↓名前（HN）とトリップの区切り記号（入力する時に使用、名前欄の入力例：ハンドル#好きな文字列）
define("TRIP_PAUSE", "#");
// ↓名前（HN）とトリップの区切り記号（表示する時に使用）
define("TRIP_SIGN", "◆");
// ↓トリップ（個人識別キー）の長さ（半角換算）
define("TRIP_LENGTH", 8);
// ↓トリップ使用時の名前（HN）の文字色
define("TRIP_COLOR", "#339966");
// ↓管理人として投稿する時に使うトリップ（個人識別キー）（名前欄入力例：管理人#abc とする場合は、"abc"と指定）
// （""の場合は、変更・削除パスワードと管理用パスワードを照合します。）
define("TRIP_MASTER", "");

// ↓過去ログ作成するかどうか？（0:No 1:Yes）
define("PAST_FLG", 1);
// ↓過去ログ１ファイルの最大行数
define("PAST_MAX", 100);

// ↓検索する時にリファラチェックするかどうかか？
// （0=しない(他サイトからも検索可能)、1=する(自サイトからのみ検索可能)）
define("FLAG_REFERER", 1);

// ↓スパム投稿対策設定 ここから //
$SPAM = array();
// ↓Cookie必須（0=No、1=Yes）
$SPAM['cookie'] = 1;
// ↓JavaScript必須（0=No、1=Yes）
$SPAM['js'] = 1;
// ↓ワンタイムチケットを使用する（0=No、1=Yes）
$SPAM['ticket'] = 1;
// ↓投稿制限時間（単位：秒）
// フォーム（確認画面）を表示してから、送信ボタンを押すまで #Cookie、ワンタイムチケットを使う場合のみ有効
$SPAM['limit'] = 3;
// ↓投稿有効期限（単位：秒）
// フォーム（確認画面）を表示してから、送信ボタンを押すまで #Cookie、ワンタイムチケットを使う場合のみ有効
$SPAM['expire'] = 5*60;
// ↓正引き出来ないホストからのアクセス（0=許可しない、1=許可する）
$SPAM['hostbyname'] = 0;
// ↓逆引き出来ないホストからのアクセス（0=許可しない、1=許可する）
$SPAM['hostbyaddr'] = 0;
// ↓Referer（リファラー）チェック（0=No、1=Yes）#Cookieを使う場合のみ有効
$SPAM['referer'] = 1;
// ↓接続元IPが変わったら（0=許可しない、1=許可する）
// フォームを表示してから、送信ボタンを押すまで #Cookieを使う場合のみ有効
$SPAM['change_ip'] = 0;
// ↓ユーザーエージェントが変わったら（0=許可しない、1=許可する）
// フォームを表示してから、送信ボタンを押すまで #Cookieを使う場合のみ有効
$SPAM['change_ua'] = 0;
// ↓Secret Key（接続元IP・ユーザーエージェント照合用、必ず修正、適当に変更して下さい。）
// $SPAM['change_ip']、$SPAM['change_ua']のどちらかを1に設定した場合は必ず修正
$SPAM['secret_key'] = "Vn0ajd6aq2XALYmJ6aYlDSc47dU7ARuA";
// ↓半角文字のみの投稿（0=許可しない、1=許可する）
// 英文字のみの投稿を許可したくない時には、"0=許可しない"を選択して下さい。
$SPAM['single'] = 0;
// ↓URLを含む投稿（0=許可しない、1=許可する）
$SPAM['url'] = 0;
// ↓パスワードを設定していない投稿（0=修正・削除を許可しない、1=修正・削除を許可する）
// 管理用パスワードによる修正・削除は、どちらに設定しても常に出来ます。
$SPAM['nopass'] = 1;
// ↑スパム投稿対策設定 ここまで //


// ↓CAPTCHA（画像認証）用設定 ここから //
$REG = array();
// ↓画像認証（0=No、1=認証使用）＊認証キーは、GDライブラリが使える環境のみ
$REG['check'] = 1;

// ↓暗号方法（0=XOR、1=Mcrypt(推奨)、2=AddSub）
// 画像認証で1（認証キー使用）を選択した場合のみ有効
// Mcryptは使える環境のみ、AddSubはBCMath関数が使える環境のみ
$REG['crypt'] = 0;

// ↓暗号用パスワード（必ず修正、半角英数8文字以内）
// 画像認証で1（認証キー使用）を選択した場合のみ有効
$REG['pass'] = "password";

// ↓暗号用シード値（適当に書いてある文字の順番を入れ替えて下さい）
// 暗号方法で0（XOR）を選択した場合のみ有効
$REG['seed'] = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

// ↓認証キー用画像背景サイズ（幅）
$REG['size_x'] = "65";
// ↓認証キー用画像背景サイズ（高さ）
$REG['size_y'] = "30";

// ↓認証キー用背景色（設定しない場合は""と指定して下さい。ページ背景色と同じになります。）
// 画像認証で1（認証キー使用）を選択した場合のみ有効
//（例：青色に設定する場合　$REG['regkey_bgc'] = "#0000FF";　又は、$REG['regkey_bgc'] = "0.0.255";
$REG['regkey_bgc'] = "";

// ↓認証キー用画像文字色（設定しない場合は""と指定して下さい。デフォルトで"#D2691Ed2691e"（chocolate）になります。）
// 画像認証で1（認証キー使用）を選択した場合のみ有効
//（例：青色に設定する場合　$REG['regkey_fc'] = "#0000FF";　又は、$REG['regkey_fc'] = "0.0.255";
$REG['regkey_fc'] = "";

// ↓認証キー用背景の線の色
//（例：青色に設定する場合　$REG['regkey_blc'] = "#0000FF";　又は、$REG['regkey_blc'] = "0.0.255";
$REG['regkey_blc'] = "#E0E0E0";

// ↓認証キー用背景の線の数（単位：本）
$REG['regkey_bln'] = 4;

// ↓認証キー用文字の回転角度（例：+-15度の場合　$REG['angle_rotation'] = "15";）
$REG['angle_rotation'] = "15";

// ↓認証キー有効期限
// 投稿フォームを表示してから、送信ボタンを押すまでの時間（単位：分）
$REG['expire'] = 5;
// ↑CAPTCHA（画像認証）用設定 ここまで //


// ↓NGワード（名前、タイトル、コメントに使われたくない言葉を指定。カンマ「,」区切、複数指定可）
// 例 define("NG_WORD", "バカ,バーカ,ばーか,ばか,死ね,氏ね,アダルト,出会い系");
define("NG_WORD", "");

// ↓NGURL（拒否したいサイトURLをIPアドレス又はホスト名で指定。カンマ「,」区切、複数指定可）
// 例 define("NG_URL", "127.0.0.1,hoge.com,127.0.0.*,*.hoge.com");
define("NG_URL", "");

// ↓アクセス拒否（荒らし対策、IPアドレス又はホスト名を指定。カンマ「,」区切、複数指定可）
// 例 define("DENY_HOST", "127.0.0.1,hoge.com,127.0.0.*,*.hoge.com");
define("DENY_HOST", "");

// ↓アクセス許可（ホワイトリスト用、IPアドレス又はホスト名を指定。カンマ「,」区切、複数指定可）
// 例 define("ALLOW_HOST", "127.0.0.1,hoge.com,127.0.0.*,*.hoge.com");
define("ALLOW_HOST", "");

$CNF = array(
	// ↓ページ背景色
	'bgcolor' => '#F6F6BD',
	// ↓レスの背景色（奇数行）
	'res_bgcolor' => "#F5F5F5",
);
// ↓地域時間設定（タイムゾーン：使用する地域のグリニッジ標準時間との時差。）
// ↓単位：時間(hour)単位、マイナス指定可
$timezone = 9;
// ↓noscriptタグ用メッセージ：タイトルの上に表示
// ↓スパム投稿対策設定 -> JavaScript必須（$SPAM['js'] = 1 の場合のみ有効、改行してEOM～EOM;までの間に指定して下さい。任意）
$msg_noscript = <<<EOM
<noscript>
<p align="center"><font color="red">JavaScriptを使用しています。JavaScriptを有効にしてからご利用下さい。</font></p>
</noscript>\n
EOM;
// ------------- 設定ここまで -------------
if(FLAG_MAIL && !preg_match('/[\w.-]+\@[\w.-]+\.[a-zA-Z]{2,6}/', MAILTO)){
	echo "メール通知に使う受信用メールアドレスに誤りがあります。";exit;
}
switch(DBTYPE):
	case '1':
		define("DBSTR", "mysql");
		break;
	case '2':
		define("DBSTR", "sqlite2");
		break;
	case '3':
		define("DBSTR", "sqlite3");
		break;
	default:
		header("Content-Type: text/html;charset=utf-8");
		echo "データベースタイプに誤りがあります。config.phpの設定を確認して下さい。";exit;
		break;
endswitch;
error_reporting(0);
// ↓著作権表示(改変/削除不可)
$copyright = "- <a href=\"http://php365.com/\" target=\"_top\">365 Forum Ver5.01</a> -\n";
?>