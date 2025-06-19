function check_member(mode,m_idx,v_page,s_word,s_field) {
	var thisform = document.setForm;
	if(!thisform.id.value) { alert("아이디를 입력하세요!"); thisform.id.focus(); return false; }
	if(!thisform.name.value) { alert("성명을 입력하세요!"); thisform.name.focus(); return false; }
	if(!id_check(document.setForm.id.name)) {
		alert('아이디는 5 ~ 12자의 영문소문자나 숫자 또는 조합된 문자열이어야 합니다!');
		thisform.id.focus();
		thisform.id.select();
		return false;
	}
	if(mode == 'add') {
		if((!thisform.password1.value) || (thisform.password1.value.length < 4)) {
			alert('4자리 이상의 첫번째 비밀번호를 입력하세요!');
			thisform.password1.focus();
			return false;
		}
		if(!thisform.password2.value || (thisform.password2.value.length < 4)) {
			alert('4자리 이상의 두번째 비밀번호를 입력하세요!');
			thisform.password2.focus();
			return false;
		}
		if((thisform.password1.value) != (thisform.password2.value)) {
			alert("비밀번호가 같지 않습니다.");
			thisform.password1.value="";
			thisform.password2.value="";
			thisform.password1.focus();
			return false;
		}
	}
	if(!thisform.iQuestion.value) { alert('비밀번호 찾기 연상문구를 선택하세요.'); thisform.iQuestion.focus(); thisform.iQuestion.select(); return false; }
	if(!thisform.iAnswer.value) { alert("비밀번호 연상문구의 답변을 입력하세요!"); thisform.iAnswer.focus(); return false; }
	if(!thisform.email_a.value) { alert('전자우편주소를 다시입력하세요.'); thisform.email_a.focus(); thisform.email_a.select(); return false; }
	if(!thisform.email_b.value) { alert('전자우편주소를 다시입력하세요.'); thisform.email_b.focus(); thisform.email_b.select(); return false; }
	/*
	if(!thisform.hzipcode1.value) { alert('우편번호를 입력하세요!'); thisform.hzipcode1.focus(); return false; }
	if(!number_check(thisform.hzipcode1.name)) { alert('숫자만을 사용하세요!'); thisform.hzipcode1.focus(); thisform.hzipcode1.select(); return false; }
	if(!thisform.hzipcode2.value) { alert('우편번호를 입력하세요!'); thisform.hzipcode2.focus(); return false; }
	if(!number_check(thisform.hzipcode2.name)) { alert('숫자만을 사용하세요!'); thisform.hzipcode2.focus(); thisform.hzipcode2.select(); return false; }
	if(!thisform.haddress1.value) { alert('자택주소를 입력하세요!'); thisform.haddress1.focus(); return false; }
	if(!thisform.haddress2.value) { alert('나머지 주소를 입력하세요!'); thisform.haddress2.focus(); return false; }
	*/
	if(!thisform.tel_1.value) { alert('일반전화번호를 입력하세요!'); thisform.tel_1.focus(); return false; }
	if(!number_check(thisform.tel_1.name)) { alert('숫자만을 사용하세요!'); thisform.tel_1.focus(); thisform.tel_1.select(); return false; }
	if(!thisform.tel_2.value) { alert('일반전화번호를 입력하세요!'); thisform.tel_2.focus(); return false; }
	if(!number_check(thisform.tel_2.name)) { alert('숫자만을 사용하세요!'); thisform.tel_2.focus(); thisform.tel_2.select(); return false; }
	if(!thisform.tel_3.value) { alert('일반전화번호를 입력하세요!'); thisform.tel_3.focus(); return false; }
	if(!number_check(thisform.tel_3.name)) { alert('숫자만을 사용하세요!'); thisform.tel_3.focus(); thisform.tel_3.select(); return false; }
	if(mode=='edit') {
		thisform.action = 'joinExe.php?mode='+mode+'&idx='+m_idx+'&page='+v_page+'&keyword='+s_word+'&keyfield='+s_field;
		thisform.method = 'POST';
		thisform.submit();
	}else if(mode=='add') {
		if(!thisform.Divi_Str_d.value || (thisform.Divi_Str_d.value.length < 5)) { alert('자동가입방지 문자열 5자리를 입력하세요!'); thisform.Divi_Str_d.focus(); return false; }
		thisform.action = 'joinExe.php?mode='+mode;
		thisform.method = 'POST';
		thisform.submit();
	}else {
		alert('작업모드가 선택되지 않았습니다.!');
	}
}

function jsTab(arg,nextname,len) {
	if(arg.value.length==len) {
		nextname.focus();
	return false;
	}
}

function id_check(formname) {
	var form = eval("document.setForm." + formname);
		if(form.value.length < 5 || form.value.length > 12) {
		return false;
		}
		for(var i = 0; i < form.value.length; i++) {
			var chr = form.value.substr(i,1);
			if((chr < '0' || chr > '9') && (chr < 'a' || chr > 'z')) {
			return false;
		}
	}
	return true;
}

function pw_check(formname) {
	var form = eval("document.setForm." + formname);
		if(form.value.length < 4 || form.value.length > 8) {
		return false;
		}
		for(var i = 0; i < form.value.length; i++) {
			var chr = form.value.substr(i,1);
			if((chr < '0' || chr > '9') && (chr < 'a' || chr > 'z') && (chr < 'A' || chr > 'Z')) {
			return false;
		}
	}
	return true;
}

function number_check(formname) {
	var form = eval("document.setForm." + formname);
		for(var i = 0; i < form.value.length; i++) {
		var chr = form.value.substr(i,1);
			if(chr < '0' || chr > '9') {
			return false;
		}
	}
	return true;
}

function dupl_id_check(mode) {
		if(!document.setForm.id.value) { 
			alert('아이디(ID)를 입력하신 후에 확인하세요!'); document.setForm.id.focus(); return;
		}else {
			url = "idCheck.php?id="+document.setForm.id.value+"&divi="+mode;
			var window_left = (screen.width-640)/2;
			var window_top = (screen.height-480)/2;
			window.open(url,"id_check_window",'width=410,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=' + window_top + ',left=' + window_left + '');
		}
}

function post_find(mode) {
		var window_left = (screen.width-640)/2;
		var window_top = (screen.height-480)/2;
		window.open('postFind.php?mode=' + mode + '','find_window','width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=' + window_top + ',left=' + window_left + '');
}

function address_same() {
	thisform.ozipcode1.value = thisform.hzipcode1.value;
	thisform.ozipcode2.value =  thisform.hzipcode2.value; 
	thisform.oaddress1.value = thisform.haddress1.value; 
	thisform.oaddress2.value = thisform.haddress2.value; 
}

function move_url(name,mode,idx,photo) {
	if(mode=='del') {
		var ok_no = confirm(name+"회원님을 삭제하시겠습니까?");
		if(ok_no == true) {
			location.href='joinExe.php?idx='+idx+'&mode='+mode;
		}else {
			return;
		}
	}else if(mode=='edit'){
		location.href='joinForm.htm?idx='+idx+'&mode='+mode;
	}else {
		alert('작업모드를 찾을수 없습니다.');
	}
}

function emailCheck(form) {
	if(form.email_c.value == ""){
		form.email_b.readOnly = false;
		form.email_b.value = "";
		form.email_b.focus();
	}else{
		form.email_b.value = "";
		form.email_b.value = form.email_c.value;
		form.email_b.readOnly = true;
	}
}