<?php
//	SQLite3操作用クラス
class DB extends SQLite3{

	var $result;

	// コンストラクタでデータベースをオープン or 作成
	function DB($host="",$user="",$pass="",$dbname){
		$this->open(PATH_DB.$dbname, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
	}
	// 処理件数問い合わせ（INSERT、UPDATE、DELETE用）
	function affected_rows(){
		$this->result = $this->changes();
		return $this->result;
	}
	// 挿入したレコードのID取得（auto_incrementの値）
	function insert_id($script="",$function="",$line=""){
		$this->result = $this->lastInsertRowID();
		return $this->result;
	}
	function fetch_array_assoc($sql,$script="",$function="",$line=""){
		$this->result = $this->querySingle($sql, true);
		return $this->result;
	}
	function get_rows($sql,$script="",$function="",$line=""){
		$arr = array();
		$result = $this->query($sql);
		while($item = $result->fetchArray(SQLITE3_ASSOC)){	$arr[] = $item; }
		return $arr;
	}
	function get_count($sql,$script="",$function="",$line=""){
		$arr = array();
		$result = $this->query($sql);
		$arr = $result->fetchArray(SQLITE3_ASSOC);
		return $arr['count(*)'];
	}
	function list_tables($dbname=""){
		$arr = array();
		$sql = "select name from sqlite_master where type='table' order by name";
		$result = $this->query($sql);
		while($item = $result->fetchArray(SQLITE3_ASSOC)){	$arr[] = $item; }
		return $arr;
	}
	function escape_string($str){
		if($str == ""){	return $str; }
		return $this->escapeString($str);
	}
	function transaction_begin(){
		$this->result = $this->query("BEGIN");
		return $this->result;
	}
	function transaction_commit(){
		$this->result = $this->query("COMMIT");
		return $this->result;
	}
	function transaction_rollback(){
		$this->result = $this->query("ROLLBACK");
		return $this->result;
	}
}
?>