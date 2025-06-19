//== 데이터 체크
function ucontents_Check(thisform,code,a_1,b_2,c_3) {
	if(c_3 < 1) {
		if(!Php_Trim(thisform.name.value)) { alert('이름을 입력하세요!'); thisform.name.focus(); return; }
		if(!Php_Trim(thisform.passwd.value)) { alert('비밀번호를 입력하세요!'); thisform.passwd.focus(); return; }
	}
	if(!Php_Trim(thisform.ucontents.value)) { alert('코멘트를 입력하세요!'); thisform.ucontents.focus(); return; }
	thisform.action = './comment/commentExe.php?code='+code+'&b_idx='+a_1+'&table_name='+b_2;
	thisform.method = 'POST';
	thisform.submit();
}

//== 링크 이동
function url_move(mode,a_1,b_2,c_3,d_4,e_5,f_6,g_7,h_8,i_9) {
	if(mode=='list') {
		location.href = 'list.php?code='+a_1+'&page='+b_2+'&keyword='+c_3+'&s_1='+d_4+'&s_2='+e_5+'&s_3='+f_6+'&nowread='+g_7;
	}else if(mode=='write') {
		location.href = 'write.php?code='+a_1;
	}else if(mode=='reple') {
		location.href = 'reple.php?code='+a_1+'&page='+b_2+'&idx='+c_3;
	}else if(mode=='edit') {
		location.href = 'edit.php?mode='+mode+'&code='+a_1+'&page='+b_2+'&idx='+c_3+'&keyword='+d_4+'&s_1='+e_5+'&s_2='+f_6+'&s_3='+g_7;
	}else if(mode=='delete') {
		if(confirm("정말 삭제하시겠습니까?")) {
			document.Del_Form.action = './execute.php?code='+a_1+'&page='+b_2+'&idx='+c_3+'&keyword='+d_4+'&s_1='+e_5+'&s_2='+f_6+'&s_3='+g_7;
			document.Del_Form.method='POST';
			document.Del_Form.submit();
		}else {
			return;
		}
	}else if(mode=='view') {
		location.href = 'view.php?code='+a_1+'&page='+b_2+'&idx='+c_3+'&keyword='+d_4+'&s_1='+e_5+'&s_2='+f_6+'&s_3='+g_7+'&secret='+h_8;
	}else if(mode=='ucontents') {
		if(confirm("정말 삭제하시겠습니까?")) {
			document.Del_Form.action = './comment/commentExe.php?code='+a_1+'&b_idx='+b_2+'&c_idx='+c_3+'&c_mem_id='+d_4;
			document.Del_Form.method='POST';
			document.Del_Form.submit();
		}else {
			return;
		}
	}else { alert('작업모드가 선택되지 않았습니다.'); }
}

//== 민원평가 및 추천수 체크
function gradeCheck(thisform, etc_mode) {
	var grade_checked=0;
	if(etc_mode==1) {
		for(var i=0;i<thisform.recommend.length;i++) {
			if(thisform.recommend[i].checked==true) { grade_checked=grade_checked+1; }
		}
		if(grade_checked==0) { alert("추천 점수를 선택하세요."); thisform.recommend[0].focus(); return; }
	}else if(etc_mode==2) {
		for(var i=0;i<thisform.svc_grade.length;i++) {
			if(thisform.svc_grade[i].checked==true) { grade_checked=grade_checked+1; }
		}
		if(grade_checked==0) { alert("평가 항목을 선택하세요."); thisform.svc_grade[0].focus(); return; }
		if(!Php_Trim(thisform.psPass.value)) { alert('글작성시 입력한 비밀번호를 입력하세요!'); thisform.psPass.focus(); return; }
	}
	thisform.action = './etcExcute.php';
	thisform.method = 'POST';
	thisform.submit();
}

//== 빈공백, 엔터 등제거
function Php_Trim(rst) { return rst.replace(/^\s+|\s+$/g,''); }