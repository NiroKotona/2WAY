<?php
#--------------------------#
#  管理室ログイン画面表示  #
#--------------------------#
function login(){
	global $script_name4html;

	require(PATH_TEMPLATE."login.html");
}
#----------------------#
#  管理室初期画面表示  #
#----------------------#
function do_login(){
	global $CNF,$COLORS,$title_name,$homeurl,$script_name4html;

	// パスワードチェック
	if($_POST['pass'] != ADMIN_PASS){	error("パスワードが違います、LINE=".__LINE__); }

	$list_detail = "";

	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE ref = 0";
	$rows_all = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if($rows_all < 1){	error("データー件数：0件、LINE=".__LINE__); }
	$list_detail .= <<<EOM
[ <a href="${script_name4html}">&lt;&lt; ${title_name}に戻る</a> ]<br><br>
<form action="${script_name4html}" method="post"><font color="#CC6633">▼</font>処理するスレッドにチェックを入れてから送信ボタン押して下さい。 <br>
<input type="hidden" name="pass" value="${_POST['pass']}">
<select name="mode"><option value="top_disp">TOP表示（管理者用）<option value="notop_disp">TOP解除（管理者用）</option></select> <input type="submit" value="送信"><br><br>
<table border="0" cellspacing="1" cellpadding="4" width="100%" class="solid_t">
<tr><th class="bg" nowrap align="center"></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">No.</font></th>
    <th class="bg" align="center"><font color="#FFFFFF">スレッド名</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">レス</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">ヒット</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">投稿者</font></th>
    <th class="bg" nowrap align="center"><font color="#FFFFFF">最終更新日</font></th></tr>
EOM;
	$sql = str_replace("count(*)", "*", $sql);
	if(SORT_FLG){
		$sql .= " ORDER BY top_flg DESC, lastmodify DESC ";
	}else{
		$sql .= " ORDER BY top_flg DESC, id DESC ";
	}
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
		$list_detail .= "<tr><td bgcolor=\"#FFFFFF\" align=\"center\"><input type=\"radio\" name=\"chk_id\" value=\"${item['id']}\"></td>\n";
		$list_detail .= "<td bgcolor=\"#FFFFFF\" align=\"center\">${item['id']}</td>\n";
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
	$list_detail .= "</table><br></form>\n";

	$db->close();

	require(PATH_TEMPLATE."do_login.html");
}
#----------------------#
#  TOP表示（管理者用） #
#----------------------#
function top_disp(){
	top_disp_rtn();
}
#----------------------#
#  TOP解除（管理者用） #
#----------------------#
function notop_disp(){
	top_disp_rtn();
}
#-------------------#
#  TOP表示/解除処理 #
#-------------------#
function top_disp_rtn(){
	global $CNF,$COLORS,$title_name,$homeurl;

	$_POST['chk_id'] = intval($_POST['chk_id']);
	// パスワードチェック
	if($_POST['pass'] != ADMIN_PASS){	error("パスワードが違います、LINE=".__LINE__); }
	// ラジオボタンチェック
	if($_POST['chk_id'] == ""){
		error("処理するスレッドにチェックが入っていません。ラジオボタンにチェックを入れて下さい、LINE=".__LINE__);
	}
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['chk_id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("スレッドが削除されている為、処理を中断しました、LINE=".__LINE__); }

	$result = $db->transaction_begin();

	$sql = "UPDATE ".DBPREFIX."master SET ";
	if($_POST['mode'] == "top_disp"){
		$sql .= "top_flg=1 ";
	}else{
		$sql .= "top_flg=0 ";
	}
	$sql .= " WHERE id = ${_POST['chk_id']} OR ref = ${_POST['chk_id']}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ更新中にエラーが発生しました、LINE=".__LINE__);	}

	$result = $db->transaction_commit();

	$db->close();

	// 管理室初期画面再表示
	do_login();
}
#-----------------------------#
#  スレッド終了（ロックオン） #
#-----------------------------#
function lockon(){
	thread_lock();
}
#-----------------------------#
#  スレッド再開（ロックオフ） #
#-----------------------------#
function lockoff(){
	thread_lock();
}
#-------------------------#
#  スレッド終了/再開処理  #
#-------------------------#
function thread_lock(){
	global $title_name,$script_name4html;

	$_POST['id'] = intval($_POST['id']);
	$db = new DB(DBHOST,DBUSER,DBPASS,DBNAME);
	$sql = "SELECT count(*) FROM ".DBPREFIX."master WHERE id = ${_POST['id']}";
	$rows = $db->get_count($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);
	if(!$rows){	error("スレッドが削除されている為、処理を中断しました、LINE=".__LINE__); }
	$sql = str_replace("count(*)", "*", $sql);
	$item = $db->fetch_array_assoc($sql,$_SERVER['SCRIPT_NAME'],__FUNCTION__,__LINE__);

	// パスワードチェック
	if(md5($_POST['pass']) != $item['pass'] && $item['pass'] != "" && $_POST['pass'] != ADMIN_PASS){
		error("パスワードが違います、LINE=".__LINE__);
	}
	if($item['ref'] != 0){	error("処理対象外データです。変更・削除で対応して下さい、LINE=".__LINE__); }
	if($_POST['mode'] == "lockoff" && $item['thread_flg'] == 0){
		error("スレッドは終了（ロック）されていません。スレッド再開の必要はありません、LINE=".__LINE__);
	}

	$result = $db->transaction_begin();

	$sql = "UPDATE ".DBPREFIX."master SET ";
	if($_POST['mode'] == "lockon"){
		$sql .= "thread_flg=1 ";
	}else{
		$sql .= "thread_flg=0 ";
	}
	$sql .= " WHERE id = ${_POST['id']} OR ref = ${_POST['id']}";
	$result = $db->query($sql);
	$rows = $db->affected_rows();
	if(!$rows){	error("データ更新中にエラーが発生しました、LINE=".__LINE__);	}

	$result = $db->transaction_commit();
	$db->close();

	// スレッド終了/再開メッセージ出力
	if($_POST['mode'] == "lockon"){
		$msg_title = "スレッド終了（ロックオン）完了";
		$msg_detail = <<<EOM
<p><b>スレッド終了（ロックオン）完了</b></p>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
EOM;
	}else{
		$msg_title = "スレッド再開（ロックオフ）完了";
		$msg_detail = <<<EOM
<p><b>スレッド再開（ロックオフ）完了</b></p>
<p align="center"><a href="${script_name4html}">${title_name}に戻る</a></p>
EOM;
	}

	require(PATH_TEMPLATE."finish.html");
}
?>
