<?php
##======================================================##
##  認証キー用画像出力管理                              ##
##======================================================##
// ユーザー設定ライブラリ
require_once("./lib/config.php");
// ユーザー設定ライブラリ
require_once("./lib/reglib.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){	exit; }

show_image_regkey();
exit;
#----------------------#
#  認証キー用画像表示  #
#----------------------#
function show_image_regkey(){
	global $CNF,$REG;

	if($_SERVER['QUERY_STRING'] == ""){	exit; }
	$encrypted = rawurldecode($_SERVER["QUERY_STRING"]);
	$decrypted = decode_regkey($encrypted);
	if(preg_match('/[^0-9]+/', $decrypted)){	die("Could not Decode of [ Regkey ]"); }
	$regkey_master = substr($decrypted, 0, 4);

	$img = imagecreate($REG['size_x'], $REG['size_y']) or die("Could not Initialize of [ new GD image stream ]");
	$Colors = setup_colors($REG['regkey_bgc'], $CNF['bgcolor']);	// 背景色
	$color_background = imagecolorallocate($img, $Colors[0], $Colors[1], $Colors[2]);
	$Colors = setup_colors($REG['regkey_fc'], "#D2691E");	// 文字色　chocolate	#D2691E
	$color_text = imagecolorallocate($img, $Colors[0] , $Colors[1] , $Colors[2]);
	$Colors = setup_colors($REG['regkey_blc'], "#E0E0E0");	// 背景線の色
	$color_line_background = imagecolorallocate($img, $Colors[0] , $Colors[1] , $Colors[2]);

	srand();
	for($i = 0; $i < intval($REG['regkey_bln']); $i++){
		// 文字の背景に線を引く
		imageline($img,rand(0, $REG['size_x'] - 1),rand(0, $REG['size_y'] - 1),rand(0, $REG['size_x'] - 1),rand(0, $REG['size_y'] - 1),$color_line_background);
	}
	for($i = 0; $i < strlen($regkey_master); $i++) {
		$x += 10 + rand(0, 2);
		$y = 5 + rand(0, 5);
		//１文字ずつランダムに描画する
		imagechar($img, rand(4, 5), $x, $y, substr($regkey_master, $i, 1), $color_text);
	}
	// 指定した角度に回転
	$img = imagerotate($img, rand(- $REG['angle_rotation'], $REG['angle_rotation']), $color_background);

	// 画像出力
	header("Content-type: image/png");
	header("Cache-control: no-cache");
	imagepng($img);
	imagedestroy($img);
}
#----------------------#
#  認証キー用画像表示  #
#----------------------#
function setup_colors($color, $color_default){

	$Colors = array();
	if(preg_match('/^#[0-9a-fA-F]{6}$/', $color)){
		$Colors[0] = hexdec(substr($color, 1, 2));
		$Colors[1] = hexdec(substr($color, 3, 2));
		$Colors[2] = hexdec(substr($color, 5, 2));
	}elseif(preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $color)){
		$Colors = split("\.", $color);
		$flag = 0;
		for($i = 0; $i < 3; $i++){
			$Colors[$i] = intval($Colors[$i]);
			if($Colors[$i] > 255){	$flag = 1; break; }
		}
		if($flag){
			$Colors[0] = hexdec(substr($color_default, 1, 2));
			$Colors[1] = hexdec(substr($color_default, 3, 2));
			$Colors[2] = hexdec(substr($color_default, 5, 2));
		}
	}else{
		$Colors[0] = hexdec(substr($color_default, 1, 2));
		$Colors[1] = hexdec(substr($color_default, 3, 2));
		$Colors[2] = hexdec(substr($color_default, 5, 2));
	}
	return $Colors;
}
?>