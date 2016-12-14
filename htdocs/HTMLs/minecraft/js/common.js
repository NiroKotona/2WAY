<!--
function checkForm(_captcha,_js){

	// メッセージチェック
	if(document.form1.comment.value == ""){
		alert("メッセージを入力して下さい。");
		document.form1.comment.focus();
		return false;
	}
	if(_captcha){
		// 認証キーチェック
		if(document.form1.regkey.value == ""){
			alert("認証キーを入力して下さい。");
			document.form1.regkey.focus();
			return false;
		}
		if(!document.form1.regkey.value.match(/^[0-9]{4}$/)){
			alert("認証キーが不正です。");
			document.form1.regkey.focus();
			return false;
		}
	}
	if(_js){	showAction(); }

	return true;
}
function showAction(){
	document.form1.action = document.form1.script.value
	return true;
}
//-->