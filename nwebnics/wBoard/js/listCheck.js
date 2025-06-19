function checkUserSelect() {
	var check_nums = document.signform.elements.length;
	for(var i = 0; i < check_nums; i++) {
		var checkbox_obj = eval("document.signform.elements[" + i + "]");
		if(checkbox_obj.checked == true) {
		break;
		}
	}
	if(i == check_nums) {
		alert("읽을 게시물을 선택하세요.");
		return;
	} else {
		document.signform.submit();
	} 
}

function url_move(mode,code,a,b,c,d,e,f,g) {
	if(mode=='write') {
		location.href = 'write.php?code='+code;
	}else if(mode=='view') {
		location.href = 'view.php?code='+code+'&page='+a+'&idx='+b+'&keyword='+c+'&s_1='+d+'&s_2='+e+'&s_3='+f+'&secret_mode='+g;
	}else if(mode=='delete') {
		if(confirm("정말 삭제하시겠습니까?")) {
			document.Del_Form.action = './execute.php?code='+code+'&page='+a+'&idx='+b+'&keyword='+c+'&s_1='+d+'&s_2='+e+'&s_3='+f;
			document.Del_Form.method='POST';
			document.Del_Form.submit();
		}else {
			return;
		}
	}
}

//== 목록에서 데이터 체크
function Guest_Check(code) {
	var thisform = document.signform;
	if(!Php_Trim(thisform.name.value)) { alert('이름을 입력하세요!'); thisform.name.focus(); return; }
	if(!Php_Trim(thisform.passwd.value)) { alert('비밀번호를 입력하세요!'); thisform.passwd.focus(); return; }
	if(!Php_Trim(thisform.comment.value)) { alert('내용을 입력하세요!'); thisform.comment.focus(); return; }
	thisform.action = './execute.php?code='+code;
	thisform.method = 'POST';
	thisform.submit();
}

//== 빈공백, 엔터 등제거
function Php_Trim(rst) { return rst.replace(/^\s+|\s+$/g,''); }

//== 관리자 로그인
function logincheck() {
	if(!document.loginForm.id.value) { alert('아이디를 입력하세요!'); document.loginForm.id.focus(); return false; }
	if(!document.loginForm.pass.value) { alert('비밀번호를 입력하세요');  document.loginForm.pass.focus(); return false; }
	if(document.loginForm.pass.value.length < 4) { alert('최소 4자리 이상 입력!'); document.loginForm.pass.focus(); return false; }
	document.loginForm.action='/nwebnics/wMembers/login.php';
	document.loginForm.method='POST';
	document.loginForm.submit();
}

$(document).ready(function(){
	$("#adminBtn").click(function(){
		$("#adminLogin").slideToggle(500);
	});
});