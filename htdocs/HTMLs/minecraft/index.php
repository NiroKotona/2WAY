<?php
##======================================================##
##  365 Forum                                           ##
##  Copyright (C) php365.com All rights reserved.       ##
##  http://php365.com/                                  ##
##======================================================##
// デフォルト設定ライブラリ
require_once("./lib/config.php");
// データベース操作ライブラリ
require_once(PATH_LIB.DBSTR.".php");
// 共通仕様サブルーチン
require_once(PATH_LIB."common.php");
// 管理用スクリプト
require_once("./admin.php");
#--------------------------------------------------------#

check_host();

$script_name4html = t2h($_SERVER['SCRIPT_NAME']);
switch($_SERVER['REQUEST_METHOD']):
	case 'GET':
		if(!isset($_GET['mode'])){	show_html_top();exit; }
		switch($_GET['mode']):
			case 'view':		// メッセージ表示
				view();
				break;
			case 'regist':		// 登録フォーム表示
				regist();
				break;
			case 'res':		// 返信フォーム表示
				res();
				break;
			case 'past':		// 過去ログ表示
				past();
				break;
			case 'past_view':	// メッセージ表示（過去ログ）
				past_view();
				break;
			case 'search':		// 検索フォーム表示
				search();
				break;
			case 'do_search':	// キーワード検索
				decode_search();
				do_search();
				break;
			case 'login':		// 管理室ログイン画面表示
				login();
				break;
			case 'check':
				require_once(PATH_LIB."check_env.php");
				check();
				break;
			default:
				show_html_top();
				break;
		endswitch;
		break;
	case 'POST':
		if(!isset($_POST['mode'])){	show_html_top();exit; }
		switch($_POST['mode']):
			case 'regist':		// 登録フォーム表示
				regist();
				break;
			case 'do_regist':	// 新規登録処理
				decode();
				do_regist();
				break;
			case 'res':		// 返信フォーム表示
				res();
				break;
			case 'do_res':		// 返信処理
				decode();
				do_res();
				break;
			case 'modify':		// 修正フォーム表示
				modify();
				break;
			case 'do_modify':	// データ修正処理
				decode();
				do_modify();
				break;
			case 'delete':		// データ削除処理
				delete();
				break;
			case 'do_login':	// 管理室初期画面表示
				do_login();
				break;
			case 'top_disp':	// TOP表示（管理者用）
				top_disp();
				break;
			case 'notop_disp':	// TOP解除（管理者用）
				notop_disp();
				break;
			case 'lockon':		// スレッド終了（ロックオン：閲覧、書き込み不可）
				lockon();
				break;
			case 'lockoff':		// スレッド再開（ロックオフ：閲覧、書き込み再開）
				lockoff();
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
	global $CNF,$COLORS,$SPAM,$title_name,$homeurl,$script_name4html,$msg_noscript;

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE ref = 0";
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	// バックアップ処理
	if(FLAG_BACK && $rows_all > 0){	backup($db); }

	$pagenow = (isset($_GET['p']) && $_GET['p'] > 0) ? $_GET['p'] : 1;

	$list_detail = "";
	$num_start = ($pagenow - 1) * THREADVIEW + 1;
	$offset = $num_start - 1;

if($rows_all > 0){
	$list_detail .= <<<EOM
<table border="0" cellspacing="1" cellpadding="4" width="100%" class="solid_t">
<tr><th class="bg" nowrap align="center"><font color="#FFFFFF">No.</font></th>
    <th class="bg" align="center"><font color="#FFFFFF">スレッド名</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">レス</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">ヒット</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">投稿者</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">最終更新日</font></th></tr>
EOM;
	$sql = "SELECT * FROM ".DBPREFIX."master WHERE ref = 0";
	if(SORT_FLG){
		$sql .= " ORDER BY top_flg DESC, lastmodify DESC ";
	}else{
		$sql .= " ORDER BY top_flg DESC, id DESC ";
	}
	$sql .= " limit ${offset}, ".THREADVIEW;
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	foreach($items as $item){
		$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${item['id']} OR ref = ${item['id']} ";
		$sql .= "ORDER BY date DESC ";
		$res_cnt = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		$res_cnt--;
		$res_cnt_format = number_format($res_cnt);
		$hit_format = number_format($item['hit']);
		$sql = str_replace("count(*)", "date", $sql);
		$item2 = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		if($item['title'] == ""){	$item['title'] = NOTITLE;	}
		if($item['name'] == ""){	$item['name'] = NONAME;	}
		$list_detail .= "<tr><td bgcolor=\"#FFFFFF\" align=\"center\">${item['id']}</td>\n";
		if($item['top_flg']){
			if($item['thread_flg'] == 0){
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=view&amp;id=${item['id']}&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".ADMIN_DISP."</font></td>\n";
			}else{
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=view&amp;id=${item['id']}&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".ADMIN_DISP."　".LOCK_DISP."</font></td>\n";
			}
		}else{
			if($item['thread_flg'] == 0){
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=view&amp;id=${item['id']}&amp;res_cnt=$res_cnt\">${item['title']}</a></td>\n";
			}else{
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=view&amp;id=${item['id']}&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".LOCK_DISP."</font></td>\n";
			}
		}
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"right\">${res_cnt_format}</td>\n";
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"right\">${hit_format}</td>\n";
		if(strstr($item['name'], TRIP_SIGN)){
			$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\"><font color=\"".TRIP_COLOR."\">${item['name']}</font></td>\n";
		}else{
			$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\">${item['name']}</td>\n";
		}
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\">${item2['date']}</td></tr>\n";
	}
	$list_detail .= "</table><br><center>\n";
}
	$db->close();

	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"",$argv,$pageing,THREADVIEW,PAGE_CONTROL);

	if($SPAM['js']){
		$notice = $msg_noscript;
		$link_regist = "<a href=\"javascript:void(0);\" onclick=\"window.open('${script_name4html}?mode=regist','_top').focus();\">新規投稿</a>";
	}else{
		$notice = "";
		$link_regist = "<a href=\"${script_name4html}?mode=regist\">新規投稿</a>";
	}
	require(PATH_TEMPLATE."top.html");
}
#------------------#
#  メッセージ表示  #
#------------------#
function view(){
	global $COLORS,$CNF,$SPAM,$title_name,$REG,$SPAM,$script_name4html,$msg_noscript;

	set_tracking();
	set_session();
	$ticket4html = t2h($_SESSION['ticket']);

	foreach(array('id', 'p', 'res_cnt') as $key){
		$_GET[$key] = isset($_GET[$key]) ? intval($_GET[$key]) : 0;
	}
	$pagenow = (isset($_GET['p']) && $_GET['p'] > 0) ? $_GET['p'] : 1;

	$num_start = ($pagenow - 1) * PAGEVIEW + 1;
	$offset = $num_start - 1;

	$navi_detail = "";
	$thread_detail = "";
	$str_form = "";

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_GET['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("該当記事が存在しません、LINE=".__LINE__);	}
	$sql = "SELECT * FROM ".DBPREFIX."master WHERE id = ${_GET['id']}";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$title = $item['title'] == "" ? NOTITLE : $item['title'];
	$thread = $item['thread'];
	if($item['thread_flg']){	error("このスレッドはロックされています、LINE=".__LINE__);	}
	if($item['title'] == ""){	$item['title'] = NOTITLE;	}
	if(RES_MODE == 0){
		$navi_detail .= <<<EOM
[ <a href="${script_name4html}">&lt;&lt; ${title_name}に戻る</a> ]　[ <a href="${script_name4html}?mode=res&amp;id=${_GET['id']}">このスレッドに返信する</a> ]<br><br>
EOM;
	}else{
		$navi_detail .= <<<EOM
[ <a href="${script_name4html}">&lt;&lt; ${title_name}に戻る</a> ]<br><br>
EOM;
	}
	if($item['name'] == ""){	$item['name'] = NONAME;	}
	if($item['title'] == ""){	$item['title'] = NOTITLE;	}
	if($item['email']){
		$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
	}else{
		$name_tag = $item['name'];
	}
	if(strstr($name_tag, TRIP_SIGN)){
		$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
	}
	if(THREAD_DISP){
		$thread_detail .= "<center><b>スレッド：${item['title']}</b><br><br></center>\n";
	}else{
		$thread_detail .= <<<EOM
<table border="0" cellpadding="3" cellspacing="0" width="100%" class="solid">
<tr><th class="bg" nowrap align="left">
<font color="#FFFFFF"><b>【${item['id']}:${_GET['res_cnt']}】　スレッド：${item['title']}</b></font></th></tr>
<tr><td bgcolor="#FFFFFF"><b>${name_tag}</b>　${item['date']}　No.<b>${item['id']}</b><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote></td></tr>
</table><br>\n
EOM;
	}
	if(THREAD_DISP){
		$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${item['id']} OR ref = ${item['id']} ";
	}else{
		$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE ref = ${item['id']} ";
	}
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
if($rows_all > 0){
	$view_cnt = 0;
	$sql = str_replace("count(*)", "*", $sql);
	$sql .= "ORDER BY id limit ${offset}, ".PAGEVIEW;
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	foreach($items as $item){
		$view_cnt++;
		$bgcolor = ($view_cnt % 2) == 1 ? "#FFFFFF" : $CNF[res_bgcolor];
		if($item['name'] == ""){	$item['name'] = NONAME;	}
		if($item['title'] == ""){	$item['title'] = NOTITLE;	}
		if($item['email']){
			$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
		}else{
			$name_tag = $item['name'];
		}
		if(strstr($name_tag, TRIP_SIGN)){
			$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
		}
		if($view_cnt == 1){
			$thread_detail .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"solid\">\n";
			$thread_detail .= "<tr><th bgcolor=\"#FFFFFF\" class=\"bg_article_top\" nowrap align=\"left\"><b>${item['title']}&nbsp;&nbsp;${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br>\n";
			$thread_detail .= "<blockquote><font color=\"${COLORS[$item['color']]}\">${item['comment']}</font></blockquote></th></tr>\n";
		}else{
			$thread_detail .= "<tr><td bgcolor=\"${bgcolor}\"><b>${item['title']}&nbsp;&nbsp;${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br>\n";
			$thread_detail .= "<blockquote><font color=\"${COLORS[$item['color']]}\">${item['comment']}</font></blockquote></td></tr>\n";
		}
	}
	$thread_detail .= "</table><center>\n";

	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?mode=view&id=${_GET['id']}&res_cnt=${_GET['res_cnt']}&";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"",$argv,$pageing,PAGEVIEW,PAGE_CONTROL);
}else{
	$pages_str = "";
}
	$thread_detail .= $pages_str . "<br>\n<center><b>返信フォーム</b></center>\n";

	if($SPAM['js']){
		$notice = $msg_noscript;
		$path_parts = pathinfo($_SERVER["SCRIPT_NAME"]);
		$dirname = $path_parts['dirname'] . "/";
		$str_form .= "<form action=\"".t2h($dirname)."dummy.php\" method=\"post\" name=\"form1\" id=\"form1\" onSubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
		$str_form .= "<input type=\"hidden\" name=\"script\" value=\"${script_name4html}\">\n";
	}else{
		$notice = "";
		$str_form .= "<form action=\"${script_name4html}\" method=\"post\" name=\"form1\" id=\"form1\" onsubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
	}
	$str_form .= <<<EOM
<input type="hidden" name="mode" value="do_res">
<input type="hidden" name="ref" value="${_GET['id']}">
<input type="hidden" name="thread" value="${thread}">
<input type="hidden" name="ticket" value="${ticket4html}">
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%">
<table border=0 width="100%" cellspacing="1" cellpadding="2">
<tr><td nowrap align="right"><b>名前：</b></td>
<td align="left"><input type="text" name="name" size="40"></td></tr>
<tr><td nowrap align="right"><b>Email：</b></td>
<td align="left"><input type="text" name="email" size="40"></td></tr>
<tr><td nowrap align="right"><b>タイトル：</b></td>
<td align="left"><input type="text" name="title" size="40" value="Re: $title"></td></tr>
<tr><td nowrap align="right"><font color="#CC0000">*</font><b>メッセージ：</b></td>
<td align="left"><textarea rows="7" name="comment" cols="54"></textarea></td></tr>
<tr><td nowrap align="right"><b>フォントカラー：</b></td><td align="left">
EOM;
	for($i = 0; $i < count($COLORS); $i++){
		if($i == 0){
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\" checked><font color=\"${COLORS[$i]}\">■</font>\n";
		}else{
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\"><font color=\"${COLORS[$i]}\">■</font>\n";
		}
	}
	$str_form .= <<<EOM
</td></tr>
<tr><td nowrap align="right"><b>パスワード：</b></td>
<td align="left"><input type="password" name="pass" size="10" maxlength="8"><font color="#cc0000">（修正・削除に使用、半角英数8文字以内）</font></td></tr>
EOM;

	$str_form .= show_regkey();
	$str_form .= <<<EOM
<tr><td colspan="2" align="center" height="50"><input type="submit" name="submit" value="返信する"><input type="reset" value="リセット"></td></tr>
</table>
</td></tr>
</table>
</form></center>
EOM;
	if(RES_MODE == 0){
		$str_form .= "<div align=\"right\"><a href=\"${script_name4html}?mode=res&amp;id=${_GET['id']}\">このスレッドに返信する</a></div><br><br>\n";
	}

	$result = $db->transaction_begin();

	// ログを最新状態に更新
	$sql = "UPDATE ".DBPREFIX."master SET ";
	$sql .= "hit=hit+1";
	$sql .= " WHERE id=${_GET['id']}";
	$result = $db->query($sql);

	$result = $db->transaction_commit();

	$db->close();

	require(PATH_TEMPLATE."view.html");
}
#--------------------------------#
#  新規スレッド作成フォーム表示  #
#--------------------------------#
function regist(){
	global $COLORS,$title_name,$REG,$SPAM,$script_name4html,$msg_noscript;

	set_tracking();
	set_session();
	$ticket4html = t2h($_SESSION['ticket']);

	// パスワードチェック
	if(ADMIN_MODE == 1 && $_POST['pass'] != ADMIN_PASS){
		error("管理用パスワードが違います、LINE=".__LINE__);
	}

	$str_form = "";
	if($SPAM['js']){
		$notice = $msg_noscript;
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$dirname = $path_parts['dirname'] . "/";
		$str_form .= "<form action=\"".t2h($dirname)."dummy.php\" method=\"post\" name=\"form1\" id=\"form1\" onSubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
		$str_form .= "<input type=\"hidden\" name=\"script\" value=\"${script_name4html}\">\n";
	}else{
		$notice = "";
		$str_form .= "<form action=\"${script_name4html}\" method=\"post\" name=\"form1\" id=\"form1\" onsubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
	}
	$str_form .= <<<EOM
<input type="hidden" name="mode" value="do_regist">
<input type="hidden" name="ticket" value="${ticket4html}">
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%">
<table border="0" width="100%" cellspacing="1" cellpadding="2">
<tr><td nowrap align="right"><b>名前：</b></td>
<td align="left"><input type="text" name="name" size="40"></td></tr>
<tr><td nowrap align="right"><b>Email：</b></td>
<td align="left"><input type="text" name="email" size="40"></td></tr>
<tr><td nowrap align="right"><b>タイトル：</b></td>
<td align="left"><input type="text" name="title" size="40"></td></tr>
<tr><td nowrap align="right"><font color="#CC0000">*</font><b>メッセージ：</b></td>
<td align="left"><textarea rows="7" name="comment" cols="54"></textarea></td></tr>
<tr><td nowrap align="right"><b>フォントカラー：</b></td><td align="left">
EOM;
	for($i = 0; $i < count($COLORS); $i++){
		if($i == 0){
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\" checked><font color=\"${COLORS[$i]}\">■</font>\n";
		}else{
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\"><font color=\"${COLORS[$i]}\">■</font>\n";
		}
	}
	$str_form .= <<<EOM
</td></tr>
<tr><td nowrap align="right"><b>パスワード：</b></td>
<td align="left"><input type="password" name="pass" size="10" maxlength="8"><font color="#cc0000">（修正・削除に使用、半角英数8文字以内）</font></td></tr>\n
EOM;

	$str_form .= show_regkey();
	$str_form .= <<<EOM
<tr><td colspan="2" align="center" height="50"><input type="submit" name="submit" value="投稿する">&nbsp;<input type="reset" value="リセット"></td></tr>
</table>
</form>\n
EOM;
	require(PATH_TEMPLATE."regist.html");
}
#----------------#
#  新規投稿処理  #
#----------------#
function do_regist(){
	global $homeurl,$title_name,$REG,$SPAM,$script_name4html,$timezone;

	check_referer();
	check_tracking();
	check_session();
	verify_regkey();

	if(strstr($_POST['name'], TRIP_PAUSE)){
		list($_POST['name'],$trip_input) = explode(TRIP_PAUSE, $_POST['name']);
		if($trip_input == ""){	error("トリップ（個人識別キー）が入力されていません。\"".TRIP_PAUSE."\"の後に好きな文字列を入力するか、\"".TRIP_PAUSE."\"を消して下さい、LINE=".__LINE__); }
	}else{
		$trip_input = "";
	}
	if(strlen(mb_convert_encoding($_POST['name'], "EUC-JP", "UTF-8")) > (NAME_MAX*2)){
		error("名前が全角".NAME_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strstr($_POST['name'], TRIP_SIGN)){
		error("名前（ハンドルネーム）に\"".TRIP_SIGN."\"は使えません。トリップ機能で使われます、LINE=".__LINE__);
	}elseif(strlen(mb_convert_encoding($_POST['title'], "EUC-JP", "UTF-8")) > (TITLE_MAX*2)){
		error("タイトルが全角".TITLE_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strlen(mb_convert_encoding($_POST['comment'], "EUC-JP", "UTF-8")) > (COM_MAX*2)){
		error("メッセージが全角".COM_MAX."文字を超えています、LINE=".__LINE__);
	}elseif($_POST['comment'] == ""){
		error("メッセージが入力されていません、LINE=".__LINE__);
	}elseif($_POST['email'] != "" && !preg_match('/[\w.-]+\@[\w.-]+\.[a-zA-Z]{2,6}/', $_POST['email'])){
		error("Ｅメールの入力内容が正しくありません、LINE=".__LINE__);
	}elseif(!$SPAM['single'] && !preg_match('/[\xc0-\xf7][\x80-\xbf]/', $_POST['comment'])){
		error("半角文字のみの投稿は禁止しています、LINE=".__LINE__);
	}elseif(!$SPAM['url'] && preg_match('/http:\/\//', $_POST['comment'])){
		error("URLを含む投稿は禁止しています。<br>URLを投稿した場合は、頭の「h」を除いた「ttp://」から始めるようにして再度投稿して下さい、LINE=".__LINE__);
	}

	// NGワードチェック
	if(NG_WORD != ""){	check_ng_word(array('name','title','comment')); }
	// NGURLチェック
	if(NG_URL != ""){	check_ng_url(array('name','title','comment')); }

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);

	foreach(array('name', 'title', 'comment') as $key){	$_POST[$key] = $db->escape_string($_POST[$key]); }
	// トリップ処理
	if($trip_input != ""){
		$trip_flg = 1;
		$trip_enc = substr(md5($trip_input), 0, TRIP_LENGTH);
		$_POST['name'] .= TRIP_SIGN.$trip_enc;
	}

	// 時間を取得
	$date = gmdate("Y-m-d H:i:s",time()+$timezone*60*60);
	$lastmodify = gmdate("YmdHis",time()+$timezone*60*60);
	// パスワードの暗号化
	$pass = "";
	if($_POST['pass'] != ""){	$pass = md5($_POST['pass']);	}

	// 現在時刻
	$now = time();
	$host = getenv("REMOTE_HOST");
	$addr = getenv("REMOTE_ADDR");
	if($host == "" || $host == $addr){	$host = @gethostbyaddr($addr);	}

	$sql = "SELECT count(*) FROM ".DBPREFIX."chk";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows){
		$sql = str_replace("count(*)", "past_no, ip, lastmodify, thread", $sql);
		$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		// 投稿間隔チェック
		if($addr == $item['ip'] && ($now - $item['lastmodify']) < REG_TIME){
			error("投稿間隔が制限されています。しばらくしてから投稿して下さい、LINE=".__LINE__);
		}
		$thread = $item['thread'];
		$past_no = $item['past_no'];
	}else{
		error("投稿管理テーブルが参照できませんでした、LINE=".__LINE__);
	}

	// 重複投稿チェック
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE name = '${_POST['name']}' AND comment = '${_POST['comment']}'";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows > 0){	error("重複投稿は禁止されています、LINE=".__LINE__);	}
	// 管理人を名乗る偽者チェック
	if(strstr($_POST['name'], "管理人")){
		if($trip_flg){
			if(TRIP_MASTER == ""){
				if($_POST['pass'] != ADMIN_PASS){
					error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
				}
			}elseif($trip_input != TRIP_MASTER){
				error("管理人が使う、トリップ（個人識別キー）が間違っています、LINE=".__LINE__);
			}
		}else{
			if($_POST['pass'] != ADMIN_PASS){
				error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
			}
		}
	}

	// リンク自動変換
	if(AUTO_LINK){	link_make($_POST['comment']);	}

	$result = $db->transaction_begin();

	$sql = "SELECT count(*) FROM ".DBPREFIX."master";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	// ログオーバー、過去ログ作成
 	if($rows >= LOG_MAX){
		$sql = "SELECT id FROM ".DBPREFIX."master WHERE ref = 0 ";
		if(SORT_FLG){
			$sql .= " ORDER BY top_flg, lastmodify";
		}else{
			$sql .= " ORDER BY top_flg, id";
		}
		$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		if(PAST_FLG){
			$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${item['id']} OR ref = ${item['id']} ORDER BY id, ref";
			$rows2 = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
			if($rows2 <= ($rows - LOG_MAX + 1)){	$past_no = make_past($db,$item['id']); }
		}else{
			$sql = "SELECT id FROM ".DBPREFIX."master WHERE id = ${item['id']} OR ref = ${item['id']} ORDER BY id DESC";
			$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
			$sql = "DELETE FROM ".DBPREFIX."master WHERE id = ${item['id']}";
			$result = $db->query($sql);
			$rows = $db->affected_rows();
			if(!$rows){	error("データ削除中にエラーが発生しました、LINE=".__LINE__);	}
		}
	}
	// 投稿データ追加
	$thread++;
	$line = "NULL,0,'$date','${_POST['name']}','${_POST['email']}','${_POST['title']}','${_POST['comment']}',${_POST['color']},'$pass','$host',0,0,0,$lastmodify,$thread";
	$sql = "INSERT INTO ".DBPREFIX."master VALUES ($line)";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ追加中にエラーが発生しました、LINE=".__LINE__);	}
	// 投稿管理テーブル更新
	$sql = "UPDATE ".DBPREFIX."chk SET ";
	$sql .= "past_no=${past_no}, ip='${addr}', lastmodify=${now}, thread=${thread}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("投稿管理データ更新中にエラーが発生しました、LINE=".__LINE__);	}

	$result = $db->transaction_commit();
	$db->close();

	foreach(array('name', 'title', 'comment') as $key){	$_POST[$key] = stripslashes($_POST[$key]); }

	// メール通知
	sendmail();

	// 投稿完了メッセージ出力
	$msg_title = "投稿完了";
	$msg_detail = <<<EOM
<center><p><b>投稿完了</b></p>
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%">
<table border="0" width="100%" cellspacing="1" cellpadding="3" bgcolor="#c0c0c0">
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>名前</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['name']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>Email</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['email']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>タイトル</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['title']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>メッセージ</b><font color="#CC0000">*</font></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['comment']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>パスワード</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['pass']}</td></tr>
</table>
</td></tr>
</table>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
</center>
EOM;

	require(PATH_TEMPLATE."finish.html");
}
#--------------------#
#  返信フォーム表示  #
#--------------------#
function res(){
	global $COLORS,$title_name,$REG,$SPAM,$script_name4html,$msg_noscript;

	set_tracking();
	set_session();
	$ticket4html = t2h($_SESSION['ticket']);

	// レス書き込み制限チェック
	if(RES_MODE == 1){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if($_POST['id'] == ""){
				error("No.が入力されていません、LINE=".__LINE__);
			}elseif(!is_numeric($_POST['id'])){
				error("No.に数字を入力してください、LINE=".__LINE__);
			}elseif($_POST['pass'] != ADMIN_PASS){
				error("管理用パスワードが違います、LINE=".__LINE__);
			}
			$_GET['id'] = $_POST['id'];
		}else{
			if($_GET['pass'] != md5(ADMIN_PASS)){	error("管理用パスワードが違います、LINE=".__LINE__); }
		}
	}
	$_GET['id'] = intval($_GET['id']);
	$pagenow = (isset($_GET['p']) && intval($_GET['p']) > 0) ? intval($_GET['p']) : 1;
	$num_start = ($pagenow - 1) * PAGEVIEW + 1;
	$offset = $num_start - 1;
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_GET['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("該当スレッドが存在しません。レスの番号を指定していないか確認して下さい、LINE=".__LINE__); }
	$sql = "SELECT ref, title, thread  FROM ".DBPREFIX."master WHERE id = ${_GET['id']}";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($item['ref'] != 0){	error("スレッド番号が不正です。レスの番号を指定していないか確認して下さい、LINE=".__LINE__); }
	$title = $item['title'] == "" ? NOTITLE : $item['title'];
	$thread = $item['thread'];

	$thread_detail = "";
	$str_form = "";
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_GET['id']} OR ref = ${_GET['id']} ";
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$sql = "SELECT * FROM ".DBPREFIX."master WHERE id = ${_GET['id']} OR ref = ${_GET['id']} ";
	$sql .= "ORDER BY id, ref";
	$sql .= " limit ${offset}, ".PAGEVIEW;
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	foreach($items as $item){
		if($item['name'] == ""){	$item['name'] = NONAME;	}
		if($item['title'] == ""){	$item['title'] = NOTITLE;	}
		if($item['email']){
			$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
		}else{
			$name_tag = $item['name'];
		}
		if(strstr($name_tag, TRIP_SIGN)){
			$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
		}
		$thread_detail .= <<<EOM
<b>${item['title']}&nbsp;&nbsp;${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote>
<hr class="horizon">\n
EOM;
	}
	$db->close();

	if($SPAM['js']){
		$notice = $msg_noscript;
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$dirname = $path_parts['dirname'] . "/";
		$str_form .= "<form action=\"".t2h($dirname)."dummy.php\" method=\"post\" name=\"form1\" id=\"form1\" onSubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
		$str_form .= "<input type=\"hidden\" name=\"script\" value=\"${script_name4html}\">\n";
	}else{
		$notice = "";
		$str_form .= "<form action=\"${script_name4html}\" method=\"post\" name=\"form1\" id=\"form1\" onsubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
	}
	$str_form .= <<<EOM
<input type="hidden" name="mode" value="do_res">
<input type="hidden" name="ref" value="${_GET['id']}">
<input type="hidden" name="thread" value="${thread}">
<input type="hidden" name="ticket" value="${ticket4html}">
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%">
<table border="0" width="100%" cellspacing="1" cellpadding="2">
<tr><td nowrap align="right"><b>名前：</b></td>
<td align="left"><input type="text" name="name" size="40"></td></tr>
<tr><td nowrap align="right"><b>Email：</b></td>
<td align="left"><input type="text" name="email" size="40"></td></tr>
<tr><td nowrap align="right"><b>タイトル：</b></td>
<td align="left"><input type="text" name="title" size="40" value="Re: $title"></td></tr>
<tr><td nowrap align="right"><font color="#CC0000">*</font><b>メッセージ：</b></td>
<td align="left"><textarea rows="7" name="comment" cols="54"></textarea></td></tr>
<tr><td nowrap align="right"><b>フォントカラー：</b></td><td align="left">
EOM;
	for($i = 0; $i < count($COLORS); $i++){
		if($i == 0){
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\" checked><font color=\"${COLORS[$i]}\">■</font>\n";
		}else{
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\"><font color=\"${COLORS[$i]}\">■</font>\n";
		}
	}
	$str_form .= <<<EOM
</td></tr>
<tr><td nowrap align="right"><b>パスワード：</b></td>
<td align="left"><input type="password" name="pass" size="10" maxlength="8"><font color="#cc0000">（修正・削除に使用、半角英数8文字以内）</font></td></tr>
EOM;

	$str_form .= show_regkey();
	$str_form .= <<<EOM
<tr><td colspan="2" align="center" height="50"><input type="submit" name="submit" value="返信する">&nbsp;<input type="reset" value="リセット"></td></tr>
</table>
</form>\n
EOM;

	$thread_detail .= "</table><center>\n";

	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?mode=view&id=${_GET['id']}&res_cnt=${_GET['res_cnt']}&";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"",$argv,$pageing,PAGEVIEW,PAGE_CONTROL);

	require(PATH_TEMPLATE."res.html");
/*
	// 返信フォームへ
	$user_agent_flg = 0;
	foreach(array('MSIE', 'Netscape') as $key){
		if(stristr($_SERVER["HTTP_USER_AGENT"], $key)){	$user_agent_flg = 1; }
	}
	if($user_agent_flg){	echo "<meta http-equiv=\"refresh\" content=\"0;URL=#under\">"; }
*/
}
#------------#
#  返信処理  #
#------------#
function do_res(){
	global $homeurl,$title_name,$REG,$SPAM,$script_name4html,$timezone;

	check_referer();
	check_tracking();
	check_session();
	verify_regkey();

	$_POST['ref'] = intval($_POST['ref']);

	if(strstr($_POST['name'], TRIP_PAUSE)){
		list($_POST['name'],$trip_input) = explode(TRIP_PAUSE, $_POST['name']);
		if($trip_input == ""){	error("トリップ（個人識別キー）が入力されていません。\"".TRIP_PAUSE."\"の後に好きな文字列を入力するか、\"".TRIP_PAUSE."\"を消して下さい。"); }
	}else{
		$trip_input = "";
	}
	if(strlen(mb_convert_encoding($_POST['name'], "EUC-JP", "UTF-8")) > (NAME_MAX*2)){
		error("名前が全角".NAME_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strstr($_POST['name'], TRIP_SIGN)){
		error("名前（ハンドルネーム）に\"".TRIP_SIGN."\"は使えません。トリップ機能で使われます、LINE=".__LINE__);
	}elseif(strlen(mb_convert_encoding($_POST['title'], "EUC-JP", "UTF-8")) > (TITLE_MAX*2)){
		error("タイトルが全角".TITLE_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strlen(mb_convert_encoding($_POST['comment'], "EUC-JP", "UTF-8")) > (COM_MAX*2)){
		error("メッセージが全角".COM_MAX."文字を超えています、LINE=".__LINE__);
	}elseif($_POST['comment'] == ""){
		error("メッセージが入力されていません、LINE=".__LINE__);
	}elseif($_POST['email'] != "" && !preg_match('/[\w.-]+\@[\w.-]+\.[a-zA-Z]{2,6}/', $_POST['email'])){
		error("Ｅメールの入力内容が正しくありません、LINE=".__LINE__);
	}elseif(!$SPAM['single'] && !preg_match('/[\xc0-\xf7][\x80-\xbf]/', $_POST['comment'])){
		error("半角文字のみの投稿は禁止しています、LINE=".__LINE__);
	}elseif(!$SPAM['url'] && preg_match('/http:\/\//', $_POST['comment'])){
		error("URLを含む投稿は禁止しています。<br>URLを投稿した場合は、頭の「h」を除いた「ttp://」から始めるようにして再度投稿して下さい、LINE=".__LINE__);
	}

	// NGワードチェック
	if(NG_WORD != ""){	check_ng_word(array('name','title','comment')); }
	// NGURLチェック
	if(NG_URL != ""){	check_ng_url(array('name','title','comment')); }

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);

	foreach(array('name', 'title', 'comment') as $key){	$_POST[$key] = $db->escape_string($_POST[$key]); }

	// トリップ処理
	if($trip_input != ""){
		$trip_flg = 1;
		$trip_enc = substr(md5($trip_input), 0, TRIP_LENGTH);
		$_POST['name'] .= TRIP_SIGN.$trip_enc;
	}

	// 時間を取得
	$date = gmdate("Y-m-d H:i:s",time()+$timezone*60*60);
	$lastmodify = gmdate("YmdHis",time()+$timezone*60*60);
	// パスワードの暗号化
	$pass = "";
	if($_POST['pass'] != ""){	$pass = md5($_POST['pass']);	}

	// 現在時刻
	$now = time();
	$host = getenv("REMOTE_HOST");
	$addr = getenv("REMOTE_ADDR");
	if($host == "" || $host == $addr){	$host = @gethostbyaddr($addr);	}

	$sql = "SELECT count(*) FROM ".DBPREFIX."chk";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows){
		$sql = str_replace("count(*)", "ip, lastmodify", $sql);
		$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		// 投稿間隔チェック
		if($addr == $item['ip'] && ($now - $item['lastmodify']) < REG_TIME){
			error("投稿間隔が制限されています。しばらくしてから投稿して下さい、LINE=".__LINE__);
		}
	}else{
		error("投稿管理テーブルが参照できませんでした、LINE=".__LINE__);
	}
	// 重複投稿チェック
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE name = '${_POST['name']}' AND comment = '${_POST['comment']}'";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows){	error("重複投稿は禁止されています、LINE=".__LINE__);	}
	// 管理人を名乗る偽者チェック
	if(strstr($_POST['name'], "管理人")){
		if($trip_flg){
			if(TRIP_MASTER == ""){
				if($_POST['pass'] != ADMIN_PASS){
					error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
				}
			}elseif($trip_input != TRIP_MASTER){
				error("管理人が使う、トリップ（個人識別キー）が間違っています、LINE=".__LINE__);
			}
		}else{
			if($_POST['pass'] != ADMIN_PASS){
				error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
			}
		}
	}

	// リンク自動変換
	if(AUTO_LINK){	link_make($_POST['comment']);	}

	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['ref']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("親記事が削除されている為、処理を中断しました、LINE=".__LINE__);	}
	$sql = "SELECT top_flg FROM ".DBPREFIX."master WHERE id = ${_POST['ref']}";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);

	$result = $db->transaction_begin();

	// 返信データ追加
	$line = "NULL,${_POST['ref']},'$date','${_POST['name']}','${_POST['email']}','${_POST['title']}','${_POST['comment']}',${_POST['color']},'$pass','$host',0,${item['top_flg']},0,$lastmodify,${_POST['thread']}";
	$sql = "INSERT INTO ".DBPREFIX."master VALUES ($line)";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("返信データ追加中にエラーが発生しました、LINE=".__LINE__); }
if(SORT_FLG){
	// 親記事の最終時刻更新
	$sql = "UPDATE ".DBPREFIX."master SET ";
	$sql .= "lastmodify=${lastmodify} ";
	$sql .= " WHERE id=${_POST['ref']}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ更新中にエラーが発生しました、LINE=".__LINE__);	}
}
	// 投稿管理テーブル更新
	$sql = "UPDATE ".DBPREFIX."chk SET ";
	$sql .= "ip='${addr}', lastmodify=${now}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("投稿管理データ更新中にエラーが発生しました、LINE=".__LINE__);	}

	$result = $db->transaction_commit();
	$db->close();

	foreach(array('name', 'title', 'comment') as $key){	$_POST[$key] = stripslashes($_POST[$key]); }

	// メール通知
	sendmail();

	// 返信完了メッセージ出力
	$msg_title = "返信完了";
	$msg_detail = <<<EOM
<p><b>返信完了</b></p>
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%" align="left">
<table border="0" width="100%" cellspacing="1" cellpadding="3" bgcolor="#c0c0c0">
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>名前</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['name']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>Email</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['email']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>タイトル</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['title']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>メッセージ</b><font color="#CC0000">*</font></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['comment']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>パスワード</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['pass']}</td></tr>
</table>
</td></tr>
</table>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
EOM;

	require(PATH_TEMPLATE."finish.html");
}
#--------------------#
#  修正フォーム表示  #
#--------------------#
function modify(){
	global $COLORS,$homeurl,$title_name,$REG,$SPAM,$script_name4html,$msg_noscript;

	set_tracking();
	set_session();
	$ticket4html = t2h($_SESSION['ticket']);

	$_POST['id'] = intval($_POST['id']);
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("該当No.が存在しません、LINE=".__LINE__);	}
	$sql = str_replace("count(*)", "*", $sql);
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$db->close();

	// パスワードチェック
	if(!$SPAM['nopass'] && empty($item['pass']) && $_POST['pass'] != ADMIN_PASS){
		error("パスワードを設定していない投稿は、修正・削除を許可していません、LINE=".__LINE__);
	}
	$post_pass_md5 = $_POST['pass'] != "" ? md5($_POST['pass']) : "";
	if($post_pass_md5 != $item['pass'] && $item['pass'] != "" && $_POST['pass'] != ADMIN_PASS){
		error("パスワードが違います、LINE=".__LINE__);
	}
	$item['comment'] = h2t($item['comment']);
	$url_style = '(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)';
	$item['comment'] = ereg_replace("<a href=\"${url_style}\" target=\"".TARGET."\">${url_style}</a>","\\1\\2",$item['comment']);

	$str_form = "";

	if($SPAM['js']){
		$notice = $msg_noscript;
		$path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
		$dirname = $path_parts['dirname'] . "/";
		$str_form .= "<form action=\"".t2h($dirname)."dummy.php\" method=\"post\" name=\"form1\" id=\"form1\" onSubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
		$str_form .= "<input type=\"hidden\" name=\"script\" value=\"${script_name4html}\">\n";
	}else{
		$notice = "";
		$str_form .= "<form action=\"${script_name4html}\" method=\"post\" name=\"form1\" id=\"form1\" onsubmit=\"return checkForm(${REG['check']},${SPAM['js']});\">\n";
	}
	$str_form .= <<<EOM
<input type="hidden" name="mode" value="do_modify">
<input type="hidden" name="id" value="${_POST['id']}">
<input type="hidden" name="ref" value="${item['ref']}">
<input type="hidden" name="post_pass" value="${post_pass_md5}">
<input type="hidden" name="old_pass" value="${item['pass']}">
<input type="hidden" name="old_name" value="${item['name']}">
<input type="hidden" name="ticket" value="${ticket4html}">
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%"><table border=0 width="100%" cellspacing="1" cellpadding="2">
<tr><td nowrap align="right"><b>名前：</b></td>
<td align="left"><input type="text" name="name" size="30"><font color="#cc0000">（修正する場合のみ記入）</font></td></tr>
<tr><td nowrap align="right"><b>Email：</b></td>
<td align="left"><input type="text" name="email" size="30" value="${item['email']}"></td></tr>
<tr><td nowrap align="right"><b>タイトル：</b></td>
<td align="left"><input type="text" name="title" size="40" value="${item['title']}"></td></tr>
<tr><td nowrap align="right"><font color="#CC0000">*</font><b>メッセージ：</b></td>
<td align="left"><textarea rows="7" name="comment" cols="54">${item['comment']}</textarea></td></tr>
<tr><td nowrap align="right"><b>フォントカラー：</b></td><td align="left">\n
EOM;
	for($i = 0; $i < count($COLORS); $i++){
		if($i == $item['color']){
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\" checked><font color=\"${COLORS[$i]}\">■</font>\n";
		}else{
			$str_form .= "<input type=\"radio\" name=\"color\" value=\"${i}\"><font color=\"${COLORS[$i]}\">■</font>\n";
		}
	}
	$str_form .= <<<EOM
</td></tr>
<tr><td nowrap align="right"><b>パスワード：</b></td>
<td align="left"><input type="password" name="pass" size="10" maxlength="8"><font color="#cc0000">（修正する場合のみ記入）</font></td></tr>
EOM;

	$str_form .= show_regkey();

	$str_form .= <<<EOM
<tr><td colspan="2" align="center" height="50"><input type="submit" name="submit" value="修正する">&nbsp;<input type="reset" value="リセット"></td></tr>
</table>
</form>\n
EOM;
	require(PATH_TEMPLATE."modify.html");
}
#------------------#
#  データ修正処理  #
#------------------#
function do_modify(){
	global $homeurl,$title_name,$REG,$SPAM,$script_name4html,$timezone;

	check_referer();
	check_tracking();
	check_session();
	verify_regkey();

	$_POST['id'] = intval($_POST['id']);
	$trip_input = "";

if($_POST['name'] != ""){
	if(strstr($_POST['name'], TRIP_PAUSE)){
		list($_POST['name'],$trip_input) = explode(TRIP_PAUSE, $_POST['name']);
		if($trip_input == ""){	error("トリップ（個人識別キー）が入力されていません。\"".TRIP_PAUSE."\"の後に好きな文字列を入力するか、\"".TRIP_PAUSE."\"を消して下さい、LINE=".__LINE__); }
	}
	if(strlen(mb_convert_encoding($_POST['name'], "EUC-JP", "UTF-8")) > (NAME_MAX*2)){
		error("名前が全角".NAME_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strstr($_POST['name'], TRIP_SIGN)){
		error("名前（ハンドルネーム）に\"".TRIP_SIGN."\"は使えません。トリップ機能で使われます、LINE=".__LINE__);
	}
}
	if(strlen(mb_convert_encoding($_POST['title'], "EUC-JP", "UTF-8")) > (TITLE_MAX*2)){
		error("タイトルが全角".TITLE_MAX."文字を超えています、LINE=".__LINE__);
	}elseif(strlen(mb_convert_encoding($_POST['comment'], "EUC-JP", "UTF-8")) > (COM_MAX*2)){
		error("メッセージが全角".COM_MAX."文字を超えています、LINE=".__LINE__);
	}elseif($_POST['comment'] == ""){
		error("メッセージが入力されていません、LINE=".__LINE__);
	}elseif($_POST['email'] != "" && !preg_match('/[\w.-]+\@[\w.-]+\.[a-zA-Z]{2,6}/', $_POST['email'])){
		error("Ｅメールの入力内容が正しくありません、LINE=".__LINE__);
	}elseif(!$SPAM['single'] && !preg_match('/[\xc0-\xf7][\x80-\xbf]/', $_POST['comment'])){
		error("半角文字のみの投稿は禁止しています、LINE=".__LINE__);
	}elseif(!$SPAM['url'] && preg_match('/http:\/\//', $_POST['comment'])){
		error("URLを含む投稿は禁止しています。<br>URLを投稿した場合は、頭の「h」を除いた「ttp://」から始めるようにして再度投稿して下さい、LINE=".__LINE__);
	}

	// NGワードチェック
	if(NG_WORD != ""){	check_ng_word(array('name','title','comment')); }
	// NGURLチェック
	if(NG_URL != ""){	check_ng_url(array('name','title','comment')); }

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);

	foreach(array('name', 'old_name', 'title', 'comment') as $key){	$_POST[$key] = $db->escape_string($_POST[$key]); }
	// トリップ処理
	if($trip_input != ""){
		$trip_flg = 1;
		$trip_enc = substr(md5($trip_input), 0, TRIP_LENGTH);
		$_POST['name'] .= TRIP_SIGN.$trip_enc;
	}

	// 時間を取得
	$date = gmdate("Y-m-d H:i:s",time()+$timezone*60*60);
	$lastmodify = gmdate("YmdHis",time()+$timezone*60*60);
	// パスワードの暗号化
	if($_POST['pass'] == ""){
		$pass = $_POST['old_pass'];
		$_POST['pass'] = "変更無";
	}else{
		$pass = md5($_POST['pass']);
	}
	// 名前のセット
	if($_POST['name'] == ""){
		$_POST['name'] = $_POST['old_name'];
		$name_disp = "変更無";
	}else{
		$name_disp = $_POST['name'];
	}

	// 現在時刻
	$now = time();
	$host = getenv("REMOTE_HOST");
	$addr = getenv("REMOTE_ADDR");
	if($host == "" || $host == $addr){	$host = @gethostbyaddr($addr);	}
	
	// 管理人を名乗る偽者チェック
	if(strstr($_POST['name'], "管理人")){
		if($trip_flg){
			if(TRIP_MASTER == ""){
				if($_POST['pass'] != ADMIN_PASS){
					error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
				}
			}elseif($trip_input != TRIP_MASTER){
				error("管理人が使う、トリップ（個人識別キー）が間違っています、LINE=".__LINE__);
			}
		}else{
			if($_POST['pass'] != ADMIN_PASS){
				error("名前に管理人と使う場合、修正・削除用パスワードに管理用パスワードを入力して下さい、LINE=".__LINE__);
			}
		}
	}

	// リンク自動変換
	if(AUTO_LINK){	link_make($_POST['comment']);	}

	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("該当No.が存在しません、LINE=".__LINE__);	}
	$sql = str_replace("count(*)", "pass", $sql);
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$SPAM['nopass'] && empty($item['pass']) && $_POST['post_pass'] != md5(ADMIN_PASS)){
		error("パスワードを設定していない投稿は、修正・削除を許可していません、LINE=".__LINE__);
	}
	if($_POST['post_pass'] != $item['pass'] && $item['pass'] != "" && $_POST['post_pass'] != md5(ADMIN_PASS)){
		error("パスワードが違います、LINE=".__LINE__);
	}

	$result = $db->transaction_begin();

	$sql = "UPDATE ".DBPREFIX."master SET ";
	$sql .= "date='${date}', ";
	$sql .= "name='${_POST['name']}', ";
	$sql .= "email='${_POST['email']}', ";
	$sql .= "title='${_POST['title']}', ";
	$sql .= "comment='${_POST['comment']}', ";
	$sql .= "color=${_POST['color']}, ";
	$sql .= "pass='${pass}', ";
	$sql .= "host='${host}', ";
	$sql .= "lastmodify=${lastmodify} ";
	$sql .= " WHERE id=${_POST['id']}";
	$result = $db->query($sql);

	$result = $db->transaction_commit();
	$db->close();

	$name_disp = stripslashes($name_disp);
	foreach(array('title', 'comment') as $key){	$_POST[$key] = stripslashes($_POST[$key]); }

	// メール通知
	sendmail();

	// 修正完了メッセージ出力
	$msg_title = "修正完了";
	$msg_detail = <<<EOM
<p><b>修正完了</b></p>
<table border="0" cellpadding="0" cellspacing="0" width="450">
<tr><td width="100%" align="left">
<table border="0" width="100%" cellspacing="1" cellpadding="3" bgcolor="#c0c0c0">
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>名前</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${name_disp}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF"><b>Email</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['email']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>タイトル</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['title']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>メッセージ</b><font color="#CC0000">*</font></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['comment']}</td></tr>
<tr><td align="left" width="25%" bgcolor="#FFFFFF" nowrap><b>パスワード</b></td>
<td align="left" width="75%" bgcolor="#FFFFFF">${_POST['pass']}</td></tr>
</table></td></tr></table>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
EOM;

	require(PATH_TEMPLATE."finish.html");
}
#------------------#
#  データ削除処理  #
#------------------#
function delete(){
	global $title_name,$REG,$SPAM,$script_name4html;

	$_POST['id'] = intval($_POST['id']);
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("該当No.が存在しません、LINE=".__LINE__);	}
	$sql = str_replace("count(*)", "pass", $sql);
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);

	// パスワードチェック
	if(!$SPAM['nopass'] && empty($item['pass']) && $_POST['pass'] != ADMIN_PASS){
		error("パスワードを設定していない投稿は、修正・削除を許可していません、LINE=".__LINE__);
	}
	if(md5($_POST['pass']) != $item['pass'] && $item['pass'] != "" && $_POST['pass'] != ADMIN_PASS){
		error("パスワードが違います、LINE=".__LINE__);
	}

	$result = $db->transaction_begin();

	$sql = "DELETE FROM ".DBPREFIX."master WHERE id = ${_POST['id']} OR ref = ${_POST['id']}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ削除中にエラーが発生しました、LINE=".__LINE__);	}

	$result = $db->transaction_commit();
	$db->close();

	// 削除完了メッセージ出力
	$msg_title = "削除完了";
	$msg_detail = <<<EOM
<p><b>削除完了</b></p>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
EOM;

	require(PATH_TEMPLATE."finish.html");
}
#----------------#
#  過去ログ表示  #
#----------------#
function past(){
	global $COLORS,$title_name,$CNF,$script_name4html;

	$tb_past = array();
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$tb_names = $db->list_tables(DBNAME);
	for($i = 0; $i < count($tb_names); $i++){
		if(DBTYPE == 3){
			if(preg_match('/^'.DBPREFIX.'past[0-9]+$/', $tb_names[$i]['name'])){
				$tb_past[] = $tb_names[$i]['name'];
			}
		}else{
			if(preg_match('/^'.DBPREFIX.'past[0-9]+$/', $tb_names[$i])){
				$tb_past[] = $tb_names[$i];
			}
		}
	}
	$tb_past_rev = array_reverse($tb_past);
	unset($tb_past);
	if($tb_past_rev[0] == ""){	error("過去ログはありません、LINE=".__LINE__);	}
	if(isset($_GET['no']) && intval($_GET['no']) > 0){
		$no = intval($_GET['no']);
	}else{
		$first_no = str_replace(DBPREFIX."past", "", $tb_past_rev[0]);
		$no = $first_no;
	}
	$pagenow = (isset($_GET['p']) && intval($_GET['p']) > 0) ? intval($_GET['p']) : 1;
	$num_start = ($pagenow - 1) * PAGEVIEW + 1;
	$offset = $num_start - 1;
	$list_tables = "";
	while(list(,$value) = each($tb_past_rev)){
		$target_no = str_replace(DBPREFIX."past", "", $value);
		if($target_no != $no){
			$list_tables .= "[&nbsp;<a href=\"${script_name4html}?mode=past&no=${target_no}\">${target_no}</a>&nbsp;]&nbsp;";
		}else{
			$list_tables .= "[&nbsp;${target_no}&nbsp;]&nbsp;";
		}
	}
	$list_detail = "";

	$past_name = DBPREFIX."past".$no;
	$sql = "SELECT count(*) FROM ${past_name} WHERE ref = 0";
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
if($rows_all > 0 ){
	$list_detail .= <<<EOM
<table border="0" cellspacing="1" cellpadding="4" width="100%" class="solid_t">
<tr><th class="bg" nowrap align="center"><font color="#FFFFFF">No.</font></th>
    <th class="bg" align="center"><font color="#FFFFFF">スレッド名</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">レス</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">ヒット</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">投稿者</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">最終更新日</font></th></tr>
EOM;
	$sql = "SELECT * FROM ${past_name} WHERE ref = 0";
	if(SORT_FLG){
		$sql .= " ORDER BY top_flg DESC, lastmodify DESC ";
	}else{
		$sql .= " ORDER BY top_flg DESC, id DESC ";
	}
	$sql .= " limit ${offset}, ".THREADVIEW;
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	foreach($items as $item){
		$sql = "SELECT count(*) FROM ${past_name} WHERE id = ${item['id']} OR ref = ${item['id']} ";
		$sql .= "ORDER BY date DESC ";
		$res_cnt = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		$res_cnt--;
		$res_cnt_format = number_format($res_cnt);
		$hit_format = number_format($item['hit']);
		$sql = str_replace("count(*)", "date", $sql);
		$item2 = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		if($item['title'] == ""){	$item['title'] = NOTITLE;	}
		if($item['name'] == ""){	$item['name'] = NONAME;	}
		$list_detail .= "<tr><td bgcolor=\"#FFFFFF\" align=\"center\">${item['id']}</td>\n";
		if($item['top_flg']){
			if($item['thread_flg'] == 0){
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=past_view&amp;id=${item['id']}&amp;past_no=$no&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".ADMIN_DISP."</font></td>\n";
			}else{
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=past_view&amp;id=${item['id']}&amp;past_no=$no&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".ADMIN_DISP."　".LOCK_DISP."</font></td>\n";
			}
		}else{
			if($item['thread_flg'] == 0){
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=past_view&amp;id=${item['id']}&amp;past_no=$no&amp;res_cnt=$res_cnt\">${item['title']}</a></td>\n";
			}else{
				$list_detail .= "<td bgcolor=\"#FFFFFF\"><a href=\"${script_name4html}?mode=past_view&amp;id=${item['id']}&amp;past_no=$no&amp;res_cnt=$res_cnt\">${item['title']}</a>　<font color=\"#FF0000\" size=\"1\">".LOCK_DISP."</font></td>\n";
			}
		}
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"right\">${res_cnt_format}</td>\n";
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"right\">${hit_format}</td>\n";
		if(strstr($item['name'], TRIP_SIGN)){
			$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\"><font color=\"".TRIP_COLOR."\">${item['name']}</font></td>\n";
		}else{
			$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\">${item['name']}</td>\n";
		}
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\">${item2['date']}</td></tr>\n";
	}
$list_detail .= "</table><br><center>\n";
}
	$db->close();

	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?mode=past&no=${no}";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"search",$argv,$pageing,THREADVIEW,PAGE_CONTROL);

	require(PATH_TEMPLATE."past.html");
}
#------------------------------#
#  メッセージ表示（過去ログ）  #
#------------------------------#
function past_view(){
	global $COLORS,$CNF,$title_name,$script_name4html;

	$navi_detail = "";
	$thread_detail = "";
	foreach(array('id', 'p', 'res_cnt') as $key){
		$_GET[$key] = isset($_GET[$key]) ? intval($_GET[$key]) : 0;
	}
	$pagenow = (isset($_GET['p']) && intval($_GET['p']) > 0) ? intval($_GET['p']) : 1;
	$num_start = ($pagenow - 1) * PAGEVIEW + 1;
	$offset = $num_start - 1;
	$past_name = DBPREFIX."past".$_GET['past_no'];

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ${past_name} WHERE id = ${_GET['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$sql = "SELECT * FROM ${past_name} WHERE id = ${_GET['id']}";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($item['thread_flg']){	error("このスレッドはロックされています、LINE=".__LINE__); }
	if($item['title'] == ""){	$item['title'] = NOTITLE;	}
	if($item['name'] == ""){	$item['name'] = NONAME;	}
	if($item['title'] == ""){	$item['title'] = NOTITLE;	}
	if($item['email']){
		$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
	}else{
		$name_tag = $item['name'];
	}
	if(strstr($name_tag, TRIP_SIGN)){
		$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
	}
	if(THREAD_DISP){
		$thread_detail .= "<center><b>スレッド：${item['title']}</b><br><br></center>\n";
	}else{
		$thread_detail .= <<<EOM
<table border="0" cellpadding="3" cellspacing="0" width="100%" class="solid">
<tr><th class="bg" nowrap align="left"><font color="#FFFFFF"><b>【${item['id']}:${_GET['res_cnt']}】&nbsp;&nbsp;スレッド：${item['title']}</b></font></th></tr>
<tr><td bgcolor="#FFFFFF"><b>${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote></td></tr>
</table><br>
EOM;
	}
	if(THREAD_DISP){
		$sql = "SELECT count(*) FROM ${past_name} WHERE id = ${item['id']} OR ref = ${item['id']} ";
	}else{
		$sql = "SELECT count(*) FROM ${past_name} WHERE ref = ${item['id']} ";
	}
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
if($rows_all > 0){
	$sql = str_replace("count(*)", "*", $sql);
	$sql .= "ORDER BY id";
	$sql .= " limit ${offset}, ".PAGEVIEW;
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$view_cnt = 0;
	foreach($items as $item){
		$view_cnt++;
		$bgcolor = ($view_cnt % 2) == 1 ? "#FFFFFF" : $CNF['res_bgcolor'];
		if($item['name'] == ""){	$item['name'] = NONAME;	}
		if($item['title'] == ""){	$item['title'] = NOTITLE;	}
		if($item['email']){
			$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
		}else{
			$name_tag = $item['name'];
		}
		if(strstr($name_tag, TRIP_SIGN)){
			$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
		}
		if($view_cnt == 1){
			$thread_detail .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" class=\"solid\">\n";
			$thread_detail .= <<<EOM
<tr><th bgcolor="#FFFFFF" class="bg_article_top" nowrap align="left"><b>${item['title']}${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote></td></tr>\n
EOM;
		}else{
			$thread_detail .= <<<EOM
<tr><td bgcolor="${bgcolor}"><b>${item['title']}&nbsp;&nbsp;${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote></td></tr>\n
EOM;
		}
	}
	$thread_detail .= "</table><br>\n";
}

	$result = $db->transaction_begin();

	// ログを最新状態に更新
	$sql = "UPDATE ${past_name} SET ";
	$sql .= "hit=hit+1";
	$sql .= " WHERE id=${_GET['id']}";
	$result = $db->query($sql);

	$result = $db->transaction_commit();

	$db->close();

	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?mode=past_view&id=${_GET['id']}&past_no=${_GET['past_no']}&res_cnt=${_GET['res_cnt']}";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"search",$argv,$pageing,PAGEVIEW,PAGE_CONTROL);

	require(PATH_TEMPLATE."past_view.html");
}
#--------------------#
#  検索フォーム表示  #
#--------------------#
function search(){
	global $script_name4html;

	require(PATH_TEMPLATE."search.html");
}
#------------------#
#  キーワード検索  #
#------------------#
function do_search(){
	global $title_name,$script_name4html;

	// Referer（リファラー）チェック
	check_referer_search(FLAG_REFERER);

	$keyword_input = "";
	if(empty($_GET['kw'])){	error("キーワードが入力されていません、LINE=".__LINE__); }
	$pagenow = (isset($_GET['p']) && intval($_GET['p']) > 0) ? intval($_GET['p']) : 1;
	$num_start = ($pagenow - 1) * PAGEVIEW + 1;

	$time_start = get_microtime();

	$_GET['kw'] = h2t($_GET['kw']);
	$_GET['kw'] = mb_ereg_replace("(\s|　)+", " ", $_GET['kw']);
	$keyword_input = $_GET['kw'];
	$keyword_encoded = rawurlencode($_GET['kw']);
	$keywords = array();
	$keywords = split(" ", $_GET['kw']);
	for($i = 0; $i < count($keywords); $i++){	$keywords[$i] = t2h($keywords[$i]); }
	if(count($keywords) == 0){	error("キーワードが入力されていません、LINE=".__LINE__); }
	if(count($keywords) > 3){	error("指定できるキーワードは3つまでです、LINE=".__LINE__); }
	$tb_past = array();
	if($keywords[0] == ""){	error("キーワードが入力されていません、LINE=".__LINE__); }

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$tb_names = $db->list_tables(DBNAME);
	for($i = 0; $i < count($tb_names); $i++){
		if(DBTYPE == 3){
			if(preg_match('/^'.DBPREFIX.'past[0-9]+$/', $tb_names[$i]['name'])){
				$tb_past[] = $tb_names[$i]['name'];
			}
		}else{
			if(preg_match('/^'.DBPREFIX.'past[0-9]+$/', $tb_names[$i])){
				$tb_past[] = $tb_names[$i];
			}
		}
	}
	$tb_names_rev = array_reverse($tb_past);
	unset($tb_past);
	array_unshift($tb_names_rev, DBPREFIX."master");
	reset($tb_names_rev);
	$match_cnt = 0;
	$write_cnt = 0;
	$list_detail = "";
	// 検索開始
	while(list(,$value) = each($tb_names_rev)){
		if($pagenow >= 2 && $write_cnt >= PAGEVIEW){
			continue;
		}else{
			$list_detail .= search_detail($db,$value,$keywords,$pagenow,$num_start,$match_cnt,$write_cnt);
		}
	}
	if($pagenow == 1){
		$rows_all = $match_cnt;
	}else{
		$rows_all = $_GET['hit'];
	}
	$db->close();
	// ページコントロール
	if($rows_all < $pagenow * PAGEVIEW){
		$num_end = $rows_all;
	}else{
		$num_end = $pagenow * PAGEVIEW;
	}
	foreach(array('rows_all', 'num_start', 'num_end') as $key){
		$key_new = $key . "_format";
		$$key_new = str_replace(",", "&#44;", number_format($$key));
	}

	// ページコントロール
	$argv = "${script_name4html}?mode=do_search&kw=${keyword_encoded}&hit=${rows_all}";
	$pageing = $num_start_format."&nbsp;-&nbsp;".$num_end_format."&nbsp;(&nbsp;".$rows_all_format."&nbsp;件中&nbsp;)<br><br>\n";
	$pageing = "";
	$pages_str = get_control_pages($rows_all,$pagenow,$num_start,$num_end,"search",$argv,$pageing,PAGEVIEW,PAGE_CONTROL);
	$keyword_input4html = t2h($keyword_input);

	if($rows_all > 0){
		$time_end = get_microtime();
		$time = $time_end - $time_start;
		$time = t2h(round($time, 2));
		$pagectl_details = "<center><b>${keyword_input4html}</b>の検索結果&nbsp;&nbsp;<b>${rows_all_format}</b>&nbsp;件中&nbsp;<b>${num_start_format}</b>&nbsp;-&nbsp;<b>${num_end_format}</b>&nbsp;件目&nbsp;(<b>${time}</b>&nbsp;秒)&nbsp;</center>";
	}else{
		$pagectl_details = "<center><b>${keyword_input4html}</b>の検索結果&nbsp;&nbsp;<b>${rows_all_format}</b>&nbsp;件</center>";
	}

	require(PATH_TEMPLATE."do_search.html");
}
#------------#
#  検索処理  #
#------------#
function search_detail(&$db,&$targetfile,&$keywords,&$pagenow,&$num_start,&$match_cnt,&$write_cnt){
	global $COLORS;

	$str = "";
	$sql = "SELECT count(*) FROM ${targetfile}";
	for($i = 0; $i < count($keywords); $i++){
		if($i == 0){
			$where = " WHERE ";
		}else{
			$where .= " AND ";
		}
		$where .= "(name LIKE '%${keywords[$i]}%' OR ";
		$where .= "title LIKE '%${keywords[$i]}%' OR ";
		$where .= "comment LIKE '%${keywords[$i]}%')";
	}
	$sql .= $where;

	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
if($rows_all > 0){
	$sql = str_replace("SELECT count(*) FROM", "SELECT * FROM", $sql);
	$sql .= " ORDER BY thread DESC, id, ref";
	$items = $db->get_rows($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	foreach($items as $item){
		$match_cnt++;
		if($match_cnt >= $num_start && $match_cnt <= ($pagenow * PAGEVIEW)){
			if($item['name'] == ""){	$item['name'] = NONAME;		}
			if($item['title'] == ""){	$item['title'] = NOTITLE;	}
			if($item['email']){
				$name_tag = "<a href=\"mailto:${item['email']}\">${item['name']}</a>";
			}else{
				$name_tag = $item['name'];
			}
			if(strstr($name_tag, TRIP_SIGN)){
				$name_tag = "<font color=\"".TRIP_COLOR."\">${name_tag}</font>";
			}
			for($i = 0; $i < count($keywords); $i++){
				foreach(array('name', 'title', 'comment') as $key){
					$item[$key] = str_replace($keywords[$i], "<strong>".$keywords[$i]."</strong>", $item[$key]);
					$kw_upper = strtoupper($keywords[$i]);
					$item[$key] = str_replace($kw_upper, "<strong>".$kw_upper."</strong>", $item[$key]);
				}
			}
			$str .= <<<EOM
<b>${item['title']}&nbsp;&nbsp;${name_tag}</b>&nbsp;&nbsp;${item['date']}&nbsp;&nbsp;No.<b>${item['id']}</b><br><br>
<blockquote><font color="${COLORS[$item['color']]}">${item['comment']}</font></blockquote>
<hr class="horizon">\n
EOM;
			$write_cnt++;
		}
	}
}
	return $str;
}
#----------------#
#  過去ログ作成  #
#----------------#
function make_past(&$db,$id){

	// テーブル定義
	if(DBSTR == "mysql"){
		require_once(PATH_LIB."define_table_".DBSTR.".php");
	}else{
		require_once(PATH_LIB."define_table_sqlite.php");
	}
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

	// 過去ログ番号確認
	$sql = "SELECT past_no FROM ".DBPREFIX."chk";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);

	$tb_past_name = DBPREFIX."past".$item['past_no'];
	$flag_match = false;
	$tb_names = $db->list_tables(DBNAME);
	for($i = 0; $i < count($tb_names); $i++){
		if(DBTYPE == 3){
			if($tb_names[$i]['name'] == $tb_past_name){
				$flag_match = true;
				break;
			}
		}else{
			if($tb_names[$i] == $tb_past_name){
				$flag_match = true;
				break;
			}
		}
	}
	if($flag_match){
		$sql = "SELECT count(*) FROM ${tb_past_name}";
		$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
		if($rows >= PAST_MAX){
			$item['past_no']++;
			$tb_past_name = DBPREFIX."past".$item['past_no'];
			// 過去ログ用テーブル作成
			$sql = "CREATE TABLE ${tb_past_name} ";
			if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
			$sql .= $define_master;
			if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
			$result = $db->query($sql);
		}
	}else{
		// 過去ログ用テーブル作成
		$sql = "CREATE TABLE ${tb_past_name} ";
		if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
		$sql .= $define_master;
		if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
		$result = $db->query($sql);
	}
	// 過去ログテーブルに追加
	$sql = "INSERT INTO ${tb_past_name} SELECT * FROM ".DBPREFIX."master";
	$sql .= " WHERE id = ${id} OR ref = ${id}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("過去ログ作成中にエラーが発生しました、LINE=".__LINE__);	}
	// 過去ログに移動したデータ削除
	$sql = "DELETE FROM ".DBPREFIX."master WHERE id = ${id} OR ref = ${id}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ削除中にエラーが発生しました、LINE=".__LINE__);	}
	return $item['past_no'];
}
#--------------------#
#  バックアップ処理  #
#--------------------#
function backup(&$db){

	if(DBSTR == "mysql"){
		backup_mysql($db);
	}else{
		backup_sqlite($db);
	}
}
#-------------------------------#
#  バックアップ処理（SQLite用） #
#-------------------------------#
function backup_sqlite(&$db){
	global $timezone;

	// テーブル定義
	require_once(PATH_LIB."define_table_sqlite.php");

	$today = gmdate("Ymd",time()+$timezone*60*60);

	// バックアップ管理テーブル確認
	$sql = "SELECT * FROM ".DBPREFIX."backup";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($today == $item['lastbak']){	return; }
	$count = intval($item['count']);
	$count++;
	if($count > BACKCNT){	$count = 1; }
	$len = strlen(DBNAME);
	$rpos = strrpos(DBNAME, ".");
	if(!$rpos){
		$backname = DBNAME."-backup".$count;
	}else{
		$str1_len = $rpos;
		$str2_len = $len - $rpos;
		$str1 = substr(DBNAME, 0, $str1_len);
		$str2 = substr(DBNAME, $str1_len, $str2_len);
		$backname = $str1."-backup".$count.$str2;
	}
	if(!file_exists(PATH_DB.DBNAME)){
		error("データベース（SQLite用）が存在しません「".PATH_DB.DBNAME."」、LINE=".__LINE__);
	}
	if(!copy(PATH_DB.DBNAME, PATH_DB.$backname)){
		error("バックアップ中にエラーが発生しました、LINE=".__LINE__);
	}
	@chmod(PATH_DB.$backname, 0606);
}
#-------------------------------#
#  バックアップ処理（MySQL用）  #
#-------------------------------#
function backup_mysql(&$db){
	global $timezone;

	// テーブル定義
	require_once(PATH_LIB."define_table_mysql.php");

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

	// 過去ログ番号
	$sql = "SELECT lastbak FROM ".DBPREFIX."chk";
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	$today = gmdate("Ymd",time()+$timezone*60*60);
	if($today != $item['lastbak']){
		$tb_bak_name = DBPREFIX."bak";
		$flag_match = false;
		$tb_names = $db->list_tables(DBNAME);
		for($i = 0; $i < count($tb_names); $i++){
			if(DBTYPE == 3){
				if($tb_names[$i]['name'] == $tb_bak_name){
					$flag_match = true;
					break;
				}
			}else{
				if($tb_names[$i] == $tb_bak_name){
					$flag_match = true;
					break;
				}
			}
		}
		if($flag_match){
			$sql = "TRUNCATE TABLE ${tb_bak_name}";
			$result = $db->query($sql);
		}else{
			// バックアップ用テーブル作成
			$sql = "CREATE TABLE ${tb_bak_name} ";
			if(!$flag_storage_engine){	$define_master = str_replace("ENGINE=", "TYPE=", $define_master); }
			$sql .= $define_master;
			if($flag_defaults_charset){	$sql .= " default charset=utf8;"; }
			$result = $db->query($sql);
		}
		$sql = "INSERT INTO ${tb_bak_name} SELECT * FROM ".DBPREFIX."master";
		$result = $db->query($sql);
		$rows = $db->affected_rows();
		if(!$rows){	error("バックアップ処理中にエラーが発生しました、LINE=".__LINE__);	}

		$sql = "UPDATE ".DBPREFIX."chk SET ";
		$sql .= "lastbak=${today}";
		$result = $db->query($sql);
		$rows = $db->affected_rows();
		if(!$rows){	error("投稿管理テーブル処理中にエラーが発生しました、LINE=".__LINE__);	}
	}
}
#----------------#
#  デコード処理  #
#----------------#
function decode(){

	if($_SERVER['REQUEST_METHOD'] == "GET"){	error("不正な投稿です、LINE=".__LINE__); }

	foreach($_POST as $key => $value){
		// バックスラッシュの排除
		if(get_magic_quotes_gpc()){	$value = stripslashes($value); }

		// タグ処理
		$value = htmlspecialchars($value);

		// 区切り文字","をタグ用に処理
		$value = str_replace(",", "&#44;", $value);

		// 改行処理
		if($key == "comment"){
			$value = preg_replace('/[\t\0]/', '', $value);
			$value = str_replace("\r\n", "<br>", $value);
			$value = str_replace("\r", "<br>", $value);
			$value = str_replace("\n", "<br>", $value);
		}else{
			$value = preg_replace('/[\r\n\t\0]/', '', $value);
		}
		$value = trim($value);
		$_POST[$key] = $value;
	}
}
#--------------------------#
#  デコード処理（検索用）  #
#--------------------------#
function decode_search(){

	if($_SERVER['REQUEST_METHOD'] == "POST"){	error("不正なリクエストです、LINE=".__LINE__); }

	foreach($_GET as $key => $value){
		// バックスラッシュの排除
		if(get_magic_quotes_gpc()){	$value = stripslashes($value); }

		if($key == "kw"){	$value = rawurldecode($value); }

		$value = strip_tags($value);
		$value = preg_replace('/[\r\n\t\0]/', '', $value);
		$value = preg_replace('/style[^=]*=/i', '', $value);
		$value = preg_replace('/on(Blur|Change|Click|Focus|Load|Mouse|Select|Submit|Reset|Unload)[^=]*=/i', '', $value);
		$value = trim($value);
		$_GET[$key] = $value;
	}
}
?>