<?php
//	SQLite操作用クラス
class DB{

	var $handle;
	var $resource;
	var $result;
	var $row = array();

	// コンストラクタでデータベースをオープン or 作成
	function DB($host="",$user="",$pass="",$dbname){
		$this->handle = @sqlite_open(PATH_DB.$dbname, 0666, $error) or die("Could not open SQLite [ $error ]");
		return $this->handle;
	}
	// データベースCLOSE
	function close(){
		if($this->handle){	@sqlite_close($this->handle); }
	}
	// クエリ実行
	function query($sql){
		$this->result = @sqlite_query($this->handle, $sql, SQLITE_ASSOC, $error) or die("Could not query [ SQLite sql ]");
		return $this->result;
	}
	// 処理件数問い合わせ（INSERT、UPDATE、DELETE用）
	function affected_rows(){
		$this->result = @sqlite_changes($this->handle) or die("Could not query [ sqlite_changes ]");
		return $this->result;
	}
	// 挿入したレコードのID取得（auto_incrementの値）
	function insert_id($script="",$function="",$line=""){
		$this->result = @sqlite_last_insert_rowid($this->handle) or die("Could not query [ sqlite_last_insert_rowid SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		return $this->result;
	}
	function fetch($result,$script="",$function="",$line=""){
		$this->result = @sqlite_fetch_array($result, SQLITE_ASSOC) or die("Could not query [ sqlite_fetch_array SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
	}
	function fetch_array_assoc($sql,$script="",$function="",$line=""){
		$this->result = @sqlite_query($this->handle, $sql, SQLITE_ASSOC, $error) or die("Could not query [ SQLite sql ]");
		$this->result = @sqlite_fetch_array($this->result, SQLITE_ASSOC) or die("Could not query [ sqlite_fetch_array SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		return $this->result;
	}
	function get_rows($sql,$script="",$function="",$line=""){
		$arr = array();
		$arr = @sqlite_array_query($this->handle, $sql, SQLITE_ASSOC) or die("Could not query [ sqlite_array_query SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		return $arr;
	}
	function get_count($sql){
		$arr = array();
		$result = @sqlite_query($this->handle, $sql, SQLITE_ASSOC, $error) or die("Could not query [ SQLite $sql ]");
		$arr = @sqlite_fetch_array($result, SQLITE_ASSOC) or die("Could not query [ sqlite_fetch_array SCRIPT=".t2h($script)." MODE=".t2h($_REQUEST['mode'])." FUNCTION=${function} LINE=${line} ]");
		if(isset($arr['count(*)'])){
			$count = $arr['count(*)'];
		}else{
			$count = @sqlite_num_rows($result);
		}
		return $count;
	}
	function list_tables($dbname=""){
		$arr = array();
		$tb_names = array();
		$sql = "select name from sqlite_master where type='table' order by name";
		$arr = @sqlite_array_query($this->handle, $sql, SQLITE_ASSOC);
		foreach($arr as $item){	$tb_names[] = $item[name]; }
		return $tb_names;
	}
	function escape_string($str){
		if($str == ""){	return $str; }
		return @sqlite_escape_string($str);
	}
	function transaction_begin(){
		$this->result = @sqlite_query($this->handle, "BEGIN", SQLITE_ASSOC, $error) or die("Could not query [ SQLite BEGIN ]");
		return $this->result;
	}
	function transaction_commit(){
		$this->result = @sqlite_query($this->handle, "COMMIT", SQLITE_ASSOC, $error) or die("Could not query [ SQLite COMMIT ]");
		return $this->result;
	}
	function transaction_rollback(){
		$this->result = @sqlite_query($this->handle, "ROLLBACK", SQLITE_ASSOC, $error) or die("Could not query [ SQLite ROLLBACK ]");
		return $this->result;
	}
}
?>