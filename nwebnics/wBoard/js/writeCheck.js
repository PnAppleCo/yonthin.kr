//== 데이터 체크
function checkSubmit(thisform,code,mode,page,a_1,b_2,c_3,d_4,e_5,use_editor) {
	if(!trim_js(thisform.subject.value)) { alert('제목을 입력하세요!'); thisform.subject.focus(); return; }
	oEditors.getById["ucontents"].exec("UPDATE_CONTENTS_FIELD", []);
	if(!document.getElementById("ucontents").value || document.getElementById("ucontents").value == "<p>&nbsp;</p>") { alert("내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }

	if(mode=='write') {
		thisform.action = 'execute.php?code='+code;
	}else if(mode=='reple') {
		thisform.action = 'execute.php?code='+code+'&page='+page;
	}else if(mode=='edit') {
		thisform.action = 'execute.php?code='+code+'&page='+page+'&idx='+a_1+'&keyword='+b_2+'&s_1='+c_3+'&s_2='+d_4+'&s_3='+e_5;
	}
	thisform.mode.value = mode;
	thisform.method = 'POST';
	thisform.submit();
}

//== 빈공백, 엔터 등제거
function trim_js(rst) { return rst.replace(/^\s+|\s+$/g,''); }

function isNull( text ) {
	if( text == null ) return true;
	var result = text.replace(/(^\s*)|(\s*$)/g, "");
	if( result ) return false; else return true;
}