function formCheck(thisform) {
//	if(!thisform.service_divi.value) { alert('서비스의 종류를 선택하세요.'); thisform.service_divi.focus(); return; }
	thisform.action = './boardExe.php';
	thisform.method = 'POST';
	thisform.submit();
}

function number_check(formname) {
	var form = eval("document.signform." + formname);
		for(var i = 0; i < form.value.length; i++) {
		var chr = form.value.substr(i,1);
			if(chr < '0' || chr > '9') {
			return false;
		}
	}
	return true;
}