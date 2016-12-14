<?php
//	MySQL操作用クラス
class DB{

	var $linkid;
	var $result;
	var $version;

	// コンストラクタでデータベースに接続
	function DB($host,$user,$pass,$dbname){
		$this->linkid = @mysql_connect($host,$user,$pass) or die("Could not connect MySQL server");
		@mysql_select_db($dbname) or die("Could not use selected database");
		$sql = "select version();";
		$this->result = @mysql_query($sql) or die("Could not query [ MySQL select version() ]");
		$item = @mysql_fetch_array($this->result);
		$this->version = $item['version()'];
		$ver_arr = split("\.", $item['version()']);
		if(intval($ver_arr[0]) >= 4 && intval($ver_arr[1]) >= 1){
			$sql = "SET NAMES utf8";
			$this->result = @mysql_query($sql) or die("Could not query [ MySQL SET NAMES utf8 ]");
		}
		return $this->linkid;
	}
	// データベースCLOSE
	function close(){
		if(isset($this->handle)){	@mysql_close($this->linkid) or die("Could not close database"); }
	}
	// クエリ実行
	function query($sql){
		$this->result = @mysql_query($sql) or die("Could not query [ MySQL sql ]");
		return $this->result;
	}
	// 処理件数問い合わせ（INSERT、UPDATE、DELETE用）
	function affected_rows(){
		$this->result = @mysql_affected_rows() or die("Could not query [ mysql_affected_rows ]");
		return $this->result;
	}
	// 挿入したレコードのID取得（auto_incrementの値）
	function insert_id($script,$function="",$line=""){
		$this->result = @mysql_insert_id($this->linkid) or die("Could not query [ mysql_insert_id SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		return $this->result;
	}
	function fetch_array_assoc($sql,$script="",$function="",$line=""){
		$this->result = @mysql_query($sql) or die("Could not query [ MySQL sql ]");
		$this->result = @mysql_fetch_array($this->result, MYSQL_ASSOC) or die("Could not query [ mysql_fetch_array SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		return $this->result;
	}
	function get_rows($sql,$script,$function="",$line=""){
		$arr = array();
		$this->result = @mysql_query($sql) or die("Could not query [ MySQL sql ]");
		while($item = @mysql_fetch_array($this->result, MYSQL_ASSOC)){	$arr[] = $item; }
		@mysql_free_result($this->result);
		return $arr;
	}
	function get_count($sql){
		$arr = array();
		$result = @mysql_query($sql);
		$arr = @mysql_fetch_array($result, MYSQL_ASSOC);
		if(isset($arr['count(*)'])){
			$count = $arr['count(*)'];
		}else{
			$count = @mysql_num_rows($result);
		}
		@mysql_free_result($result);
		return $count;
	}
	function list_tables($dbname){
		$result = @mysql_list_tables($dbname);
		$i = 0;
		$tb_names = array();
		while($i < @mysql_num_rows($result)){
			$tb_names[$i] = @mysql_tablename($result, $i);
			$i++;
		}
		return $tb_names;
	}
	function escape_string($str){
		if($str == ""){	return $str; }
		return @mysql_real_escape_string($str);
	}
	function transaction_begin(){
		$this->result = @mysql_query("BEGIN") or die("Could not query [ MySQL BEGIN ]");
		return $this->result;
	}
	function transaction_commit(){
		$this->result = @mysql_query("COMMIT") or die("Could not query [ MySQL COMMIT ]");
		return $this->result;
	}
	function transaction_rollback(){
		$this->result = @mysql_query("ROLLBACK") or die("Could not query [ MySQL ROLLBACK ]");
		return $this->result;
	}
	function check_version(){
		$sql = "select version();";
		$this->result = @mysql_query($sql) or die("Could not query [ MySQL select version() ]");
		$item = @mysql_fetch_array($this->result);
		return $item['version()'];
	}
}
?>