<?php
#---------------------------#
#  �Ź�������饹��Mcrypt�� #
#---------------------------#
class CodeMcrypt{

	var $hd;

	// ���󥹥ȥ饯��
	function CodeMcrypt($pass){
		// �Ź�⥸�塼��OPEN
		$this->hd = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
		if(preg_match('/Windows/i', getenv("OS"))){
			$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($this->hd), MCRYPT_RANDOM);
		}else{
			$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($this->hd), MCRYPT_DEV_RANDOM);
		}
		$ks = mcrypt_enc_get_key_size($this->hd);
		// �Ź��ѥ�������
		$cryptkey = substr(md5($pass), 0, $ks);
		// �Хåե������
		@mcrypt_generic_init($this->hd, $cryptkey, $iv);
	}
	// �Ź沽
	function EncMcrypt($id){
		$encrypted = mcrypt_generic($this->hd, $id);
		return trim($encrypted);
	}
	// ���沽
	function DecMcrypt($encrypted){
		$decrypted = mdecrypt_generic($this->hd, $encrypted);
		return trim($decrypted);
	}
	// �Ź�⥸�塼��CLOSE
	function CloseMcrypt(){
		mcrypt_generic_deinit($this->hd);
		mcrypt_module_close($this->hd);
	}
}
#------------------------#
#  �Ź�������饹��XOR�� #
#------------------------#
class CodeXOR{

	var $seed;	// �Ź沽�˻Ȥ���������

	// ���󥹥ȥ饯��
	function CodeXOR($pass,$seed){
		for($i = 0, $this->seed = ""; $i < strlen($pass); $i++){
			$this->seed .= strpos($seed, $pass{$i});
		}
	}
	// �Ź沽
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
	// ���沽
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
#  �Ź�������饹��AddSub�� #
#---------------------------#
class CodeAddSub{

	var $seed;	// �Ź沽�˻Ȥ���������

	// ���󥹥ȥ饯��
	function CodeAddSub($pass,$seed){
		for($i = 0, $this->seed = ""; $i < strlen($pass); $i++){
			$this->seed .= strpos($seed, $pass{$i});
		}
	}
	// �Ź沽
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
	// ���沽
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
#  ǧ�ڥ�������  #
#----------------#
function make_regkey(){
	global $REG;

	mt_srand((double)microtime()*1000000);
	$id = sprintf('%04d', mt_rand(0, 9999)) . time();

	// �Ź沽
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
#  ǧ�ڥ�������  #
#----------------#
function decode_regkey($encrypted){
	global $REG;

	// ���沽
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
#  ǧ�ڥ��������å�  #
#--------------------#
function check_regkey($decrypted){
	global $REG;

	$err_msg = "";
	if($REG['check']){
		// ǧ�ڥ�������
		$regkey_master = substr($decrypted, 0, 4);
		$regkey_time = substr($decrypted, 4);
		// ���ϥǡ��������å�
		if($_POST['regkey'] == $regkey_master){
			$interval = time() - $regkey_time;
			if($interval > $REG['expire'] * 60){
				$err_msg = "ǧ�ڥ�����ͭ�����¤��᤮�ޤ�����<br>��ƥե���������ɽ�����ơ����ꤵ�줿���������Ϥ��Ʋ�������";
			}
		}else{
			$err_msg = "ǧ�ڥ����������Ǥ���<br>��ƥե���������ɽ�����ơ����ꤵ�줿���������Ϥ��Ʋ�������";
		}
	}
	return $err_msg;
}
?>