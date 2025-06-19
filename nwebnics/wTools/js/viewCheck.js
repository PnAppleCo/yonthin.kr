function move_url(name,mode,idx,page) {
	var thisform = document.setForm;
	if(mode=='del') {
		var ok_no = confirm(name+"회원님을 삭제하시겠습니까?");
		if(ok_no == true) {
			location.href='mExe.php?idx='+idx+'&mode='+mode+'&page='+page;
		}else { return; }
	}else if(mode=='edit') {
		if(thisform.password1.value && thisform.password2.value) {
			if((!thisform.password1.value) || (thisform.password1.value.length < 4)) { alert('4자리 이상의 첫번째 비밀번호를 입력하세요!'); thisform.password1.focus(); return false; }
			if(!thisform.password2.value || (thisform.password2.value.length < 4)) { alert('4자리 이상의 두번째 비밀번호를 입력하세요!'); thisform.password2.focus(); return false; }
			if((thisform.password1.value) != (thisform.password2.value)) {
				alert("비밀번호가 같지 않습니다.");
				thisform.password1.value="";
				thisform.password2.value="";
				thisform.password1.focus();
				return false;
			}
		}
		if(!thisform.mName.value) { alert("성명을 입력하세요!"); thisform.mName.focus(); return false; }
		if(!thisform.email_a.value) { alert('전자우편주소를 다시입력하세요.'); thisform.email_a.focus(); thisform.email_a.select(); return false; }
		if(!thisform.email_b.value) { alert('전자우편주소를 다시입력하세요.'); thisform.email_b.focus(); thisform.email_b.select(); return false; }
		if(!thisform.zipcode.value) { alert('우편번호를 입력하세요!'); thisform.zipcode.focus(); return false; }
		if(!number_check(thisform.zipcode.name)) { alert('숫자만을 사용하세요!'); thisform.zipcode.focus(); thisform.hzipcode1.select(); return false; }
		if(!thisform.haddress1.value) { alert('주소를 입력하세요!'); thisform.haddress1.focus(); return false; }
		if(!thisform.haddress2.value) { alert('나머지 주소를 입력하세요!'); thisform.haddress2.focus(); return false; }
		thisform.action = 'mExe.php?mode='+mode+'&idx='+idx;
		thisform.method = 'POST';
		thisform.submit();
	}else { alert('작업모드를 찾을수 없습니다.'); }
}

function jsTab(arg,nextname,len) {
	if(arg.value.length==len) {
		nextname.focus();
	return false;
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

function dupl_id_check(mode) {
		if(!document.setForm.mId.value) { 
			alert('아이디(ID)를 입력하신 후에 확인하세요!'); document.setForm.mId.focus(); return;
		}else {
			url = "/nwebnics/wMembers/idCheck.php?mId="+document.setForm.mId.value+"&divi="+mode;
			var window_left = (screen.width-640)/2;
			var window_top = (screen.height-480)/2;
			window.open(url,"id_check_window",'width=410,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=' + window_top + ',left=' + window_left + '');
		}
}

function post_find(mode) {
		var window_left = (screen.width-640)/2;
		var window_top = (screen.height-480)/2;
		window.open('/nwebnics/wMembers/postFind.php?mode=' + mode + '','find_window','width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=' + window_top + ',left=' + window_left + '');
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