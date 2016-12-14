<?php
#---------------------------#
#  暗号管理クラス（Mcrypt） #
#---------------------------#
class CodeMcrypt{

	var $hd;

	// コンストラクタ
	function CodeMcrypt($pass){
		// 暗号モジュールOPEN
		$this->hd = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
		if(preg_match('/Windows/i', getenv("OS"))){
			$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($this->hd), MCRYPT_RANDOM);
		}else{
			$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($this->hd), MCRYPT_DEV_RANDOM);
		}
		$ks = mcrypt_enc_get_key_size($this->hd);
		// 暗号用キー作成
		$cryptkey = substr(md5($pass), 0, $ks);
		// バッファ初期化
		@mcrypt_generic_init($this->hd, $cryptkey, $iv);
	}
	// 暗号化
	function EncMcrypt($id){
		$encrypted = mcrypt_generic($this->hd, $id);
		return trim($encrypted);
	}
	// 復号化
	function DecMcrypt($encrypted){
		$decrypted = mdecrypt_generic($this->hd, $encrypted);
		return trim($decrypted);
	}
	// 暗号モジュールCLOSE
	function CloseMcrypt(){
		mcrypt_generic_deinit($this->hd);
		mcrypt_module_close($this->hd);
	}
}
#------------------------#
#  暗号管理クラス（XOR） #
#------------------------#
class CodeXOR{

	var $seed;	// 暗号化に使うシード値

	// コンストラクタ
	function CodeXOR($pass,$seed){
		for($i = 0, $this->seed = ""; $i < strlen($pass); $i++){
			$this->seed .= strpos($seed, $pass{$i});
		}
	}
	// 暗号化
	function EncXOR($id){
		$len_id = strlen($id);
		$len_seed = strlen($this->seed);
		if($len_id > $len_seed){
			for($i = 1; $i <= $len_id - $len_seed; $i++){
				$this->seed .= "0";
			}
		}elseif($len_id < $len_seed){
			$this->seed = substr($this->seed, 0, $len_id);
		}
		$encrypted = $id ^ $this->seed;
		return $encrypted . "&" . $len_id;
	}
	// 復号化
	function DecXOR($encrypted){
		if(!preg_match('/&[0-9]+$/', $encrypted)){	return $decrypted = ""; }
		list($encrypted,$len_id) = explode("&", $encrypted);
		$len_seed = strlen($this->seed);
		if($len_id > $len_seed){
			for($i = 1; $i <= $len_id - $len_seed; $i++){
				$this->seed .= "0";
			}
		}elseif($len_id < $len_seed){
			$this->seed = substr($this->seed, 0, $len_id);
		}
		$decrypted = $encrypted ^ $this->seed;
		return $decrypted;
	}
}
#---------------------------#
#  暗号管理クラス（AddSub） #
#---------------------------#
class CodeAddSub{

	var $seed;	// 暗号化に使うシード値

	// コンストラクタ
	function CodeAddSub($pass,$seed){
		for($i = 0, $this->seed = ""; $i < strlen($pass); $i++){
			$this->seed .= strpos($seed, $pass{$i});
		}
	}
	// 暗号化
	function EncAddSub($id){
		$len_id = strlen($id);
		$len_seed = strlen($this->seed);
		if($len_id > $len_seed){
			for($i = 1; $i <= $len_id - $len_seed; $i++){
				$this->seed .= "0";
			}
		}elseif($len_id < $len_seed){
			$this->seed = substr($this->seed, 0, $len_id);
		}
		$encrypted = bcadd($id, $this->seed);
		return $encrypted . "&" . $len_id;
	}
	// 復号化
	function DecAddSub($encrypted){
		if(!preg_match('/^[0-9]+&[0-9]+$/', $encrypted)){	return $decrypted = ""; }
		list($encrypted,$len_id) = explode("&", $encrypted);
		$len_seed = strlen($this->seed);
		if($len_id > $len_seed){
			for($i = 1; $i <= $len_id - $len_seed; $i++){
				$this->seed .= "0";
			}
		}elseif($len_id < $len_seed){
			$this->seed = substr($this->seed, 0, $len_id);
		}
		$decrypted = bcsub($encrypted, $this->seed);
		return $decrypted;
	}
}
#----------------#
#  認証キー作成  #
#----------------#
function make_regkey(){
	global $REG;

	mt_srand((double)microtime()*1000000);
	$id = sprintf('%04d', mt_rand(0, 9999)) . time();

	// 暗号化
	if($REG['check']){
		switch($REG['crypt']):
			case '0':	// XOR
				$cd = new CodeXOR($REG['pass'],$REG['seed']);
				$encrypted = $cd->EncXOR($id);
				break;
			case '1':	// Mcrypt
				$cd = new CodeMcrypt($REG['pass']);
				$encrypted = $cd->EncMcrypt($id);
				$cd->CloseMcrypt();
				break;
			case '2':	// AddSub
				$cd = new CodeAddSub($REG['pass'],$REG['seed']);
				$encrypted = $cd->EncAddSub($id);
				break;
			default:
				$encrypted = "";
				break;
		endswitch;
	}else{
		$encrypted = "";
	}
	if(!empty($encrypted)){	$encrypted = encode_url_raw($encrypted); }
	return $encrypted;
}
#----------------#
#  認証キー復号  #
#----------------#
function decode_regkey($encrypted){
	global $REG;

	// 復号化
	if($REG['check']){
		switch($REG['crypt']):
			case '0':	// XOR
				$cd = new CodeXOR($REG['pass'],$REG['seed']);
				$decrypted = $cd->DecXOR($encrypted);
				break;
			case '1':	// Mcrypt
				$cd = new CodeMcrypt($REG['pass']);
				$decrypted = $cd->DecMcrypt($encrypted);
				$cd->CloseMcrypt();
				break;
			case '2':	// AddSub
				$cd = new CodeAddSub($REG['pass'],$REG['seed']);
				$decrypted = $cd->DecAddSub($encrypted);
				break;
			default:
				$decrypted = "";
				break;
		endswitch;
	}else{
		$decrypted = "";
	}
	return $decrypted;
}
#--------------------#
#  認証キーチェック  #
#--------------------#
function check_regkey($decrypted){
	global $REG;

	$err_msg = "";
	if($REG['check']){
		// 認証キー方式
		$regkey_master = substr($decrypted, 0, 4);
		$regkey_time = substr($decrypted, 4);
		// 入力データチェック
		if($_POST['regkey'] == $regkey_master){
			$interval = time() - $regkey_time;
			if($interval > $REG['expire'] * 60){
				$err_msg = "認証キーの有効期限が過ぎました。<br>投稿フォームを再度表示して、指定された数字を入力して下さい。";
			}
		}else{
			$err_msg = "認証キーが不正です。<br>投稿フォームを再度表示して、指定された数字を入力して下さい。";
		}
	}
	return $err_msg;
}
?>