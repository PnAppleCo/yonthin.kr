<?
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 01
//==================================================================
//== 게시판 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 입력 데이터 유효성 체크
if($_POST[name]) {
	if(!preg_match("/^([가-힣a-zA-Z]+[0-9]*){3,15}$/u", $_POST[name])) error_view(999, "입력하신 성명이 적당하지 않습니다.","성명을 다시 입력해주세요.");
}

if($_POST[id]) {
	if(!preg_match('/^[a-z\d_-]{5,20}$/i', $_POST[id])) js_action(1,"아이디는 최소5~20자의 영,숫가 조합되어야 합니다.","",-1);
}

if($_POST[password1]) {
	//if(!preg_match("(^[0-9a-zA-Z]{5,}$)", $_POST[passwd])) js_action(1,"비밀번호는 최소5~20자의 영,숫자가 조합되어야 합니다.","",-1);
}
if($_POST[birthmonth]) {
	if(!checkdate($_POST[birthmonth],$_POST[birthday], $_POST[birthyear])) js_action(1,"생년월일이 적당하지 않습니다.","",-1);
}
if($_POST[email_a] && $_POST[email_b]) {
	$c_email=$_POST[email_a]."@".$_POST[email_b];
	if(!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $c_email)) error_view(999, "입력하신 전자우편 주소가 적당하지 않습니다.","적당한 전자우편 주소를 다시 입력해주세요.");
}

//== 기본입력값 체크
if($_GET[mode]==="add" || $_GET[mode]==="edit") {
	if(!$_POST[hzipcode1] || !$_POST[hzipcode2]) js_action(1,"우편번호 데이터를 입력받지 못했습니다.","",-1);
	if(!$_POST[haddress1] || !$_POST[haddress2]) js_action(1,"주소 데이터를 입력받지 못했습니다.","",1);
	if(!$_POST[tel_1] || !$_POST[tel_2] || !$_POST[tel_3]) js_action(1,"일반전화를 입력받지 못했습니다.","",-1);
}else if($_GET[mode]==="del") {
	if(!$_GET[idx]) js_action(1,"idx 파라메터를 찾을수 없습니다.","",-1);
}else {
	if(!$_GET[mode]) js_action(1,"작업정보를 찾을수 없습니다.","",-1);
}

//== 데이터 조합
if($_GET[mode]==="add" || $_GET[mode]==="edit") {
	$birth_mix=$_POST[birthyear]."-".$_POST[birthmonth]."-".$_POST[birthday];
	$tel_mix=$_POST[tel_1]."-".$_POST[tel_2]."-".$_POST[tel_3];
	$fax_mix=$_POST[fax_1]."-".$_POST[fax_2]."-".$_POST[fax_3];
	$sel_mix=$_POST[sel_1]."-".$_POST[sel_2]."-".$_POST[sel_3];
	if($tel_mix==="--") $tel_mix="";
	if($sel_mix==="--") $sel_mix="";
	if($_POST[email_b]) {
		$email_mix=$_POST[email_a]."@".$_POST[email_b];
	}else if($_POST[email_c]) {
		$email_mix=$_POST[email_a]."@".$_POST[email_c];
	}else {
		js_action(1,"전자우편을 다시 입력해 주세요.","",-1);
	}
}

$_POST[haddress1]=str_replace("&nbsp;", " ", $_POST[haddress1]);
$_POST[haddress2]=str_replace("&nbsp;", " ", $_POST[haddress2]);

//== 신규등록일경우 중복 제거 =========================================================================

if($_GET[mode]==="add") {
	//== 자동가입 방지 비교
	if(strcmp(base64_decode($_POST[Divi_Str_s]),$_POST[Divi_Str_d]) !=0) js_action(1,"자동가입방지 문자열이 일치하지 않습니다.","",-1);
	/*
	//== 개인(주민등록번호) 중복 방지
	$sql_str = "SELECT COUNT(idx) FROM wMember WHERE jumin1 = '".md5($_POST[jumin1])."' AND jumin2 = '".md5($_POST[jumin2])."'";
	$nums = $db->getOne($sql_str);
	if(DB::isError($nums)) { die($nums->getMessage()); }else { if($nums>0) js_action(1,"이미 등록된 주민등록번호 입니다.","",-1); }
	*/
	//== 아이디 중복 방지
	$sql_str = "SELECT COUNT(idx) FROM wMember WHERE id = '$_POST[id]';";
	$nums = $db->getOne($sql_str);
	if(DB::isError($nums)) { die($nums->getMessage()); }else { if($nums>0) js_action(1,"이미등록된 아이디 입니다.","",-1); }
}

//== 첨부파일 업로드
$filename = $_FILES['filename']['name'];
$filetype = $_FILES['filename']['type'];
$filesize = $_FILES['filename']['size'];
$filetmp = $_FILES['filename']['tmp_name'];
$fileerror = $_FILES['filename']['error'];
$up_load = file_upload($filename, $filetype, $filesize, $filetmp, $fileerror, 10240000, "./files","Y");
//== 리턴받은 파일이름 지정(마지막에 ","가 삽입되어 있으므로 하나 빼줌)
$name_count = explode(",", $up_load);
	for($i=0;$i<sizeof($name_count)-1;$i++) $filename[$i] = $name_count[$i];

//== 회원 등록 처리 ====================================================================================

if($_GET[mode]==="add") {
	//== 새로 등록할 회원의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM wMember");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	/*
		$sqlStr = "INSERT INTO wMember(idx, id, passwd, name, nickname, jumin1, jumin2, birthday, solu, email, hzipcode1, hzipcode2, haddress1, haddress2, tel, sel, route, ujob, news_dm, signdate) VALUES ('".$new_idx."', '".$_POST[id]."', '".md5($_POST[password1])."', '".$_POST[name]."', '".$_POST[nickname]."', '".md5($_POST[jumin1])."', '".md5($_POST[jumin2])."', '".$birth_mix."', '".$_POST[solu]."', '".$email_mix."', '".$_POST[hzipcode1]."', '".$_POST[hzipcode2]."', '".$_POST[haddress1]."', '".$_POST[haddress2]."', '".$tel_mix."', '".$sel_mix."', '".$_POST[route]."', '".$_POST[ujob]."', '".$_POST[news_dm]."', now())";
	*/
	$sqlStr = "INSERT INTO wMember(idx, id, passwd, name, nickname, iQuestion, iAnswer, birthday, solu, email, hzipcode1, hzipcode2, haddress1, haddress2, tel, sel, route, ujob, news_dm, signdate) VALUES ('".$new_idx."', '".$_POST[id]."', '".md5($_POST[password1])."', '".$_POST[name]."', '".$_POST[nickname]."', '".$_POST[iQuestion]."', '".$_POST[iAnswer]."', '".$birth_mix."', '".$_POST[solu]."', '".$email_mix."', '".$_POST[hzipcode1]."', '".$_POST[hzipcode2]."', '".$_POST[haddress1]."', '".$_POST[haddress2]."', '".$tel_mix."', '".$sel_mix."', '".$_POST[route]."', '".$_POST[ujob]."', '".$_POST[news_dm]."', now())";

//== 회원 수정 처리 ====================================================================================

}else if($_GET[mode]==="edit") {

	if(login_session()==true) {
		$sqlStr = "UPDATE wMember SET name='".$_POST[name]."', nickname='".$_POST[nickname]."', iQuestion='".$_POST[iQuestion]."', iAnswer='".$_POST[iAnswer]."', birthday='".$birth_mix."', solu='".$_POST[solu]."', email='".$email_mix."', hzipcode1='".$_POST[hzipcode1]."', hzipcode2='".$_POST[hzipcode2]."', haddress1='".$_POST[haddress1]."', haddress2='".$_POST[haddress2]."', tel='".$tel_mix."',sel='".$sel_mix."', route='".$_POST[route]."', ujob='".$_POST[ujob]."', news_dm='".$_POST[news_dm]."'";
		if($_POST[password1]) $sqlStr .= ", passwd='".md5($_POST[password1])."'";
		$sqlStr .= " WHERE idx='".$_SESSION[my_idx]."' AND id='".$_SESSION[my_id]."'";
	}else { js_action(1,"로그인후 이용하시기 바랍니다.","",-1); }

//== 회원 삭제 처리 ====================================================================================

}else if($_GET[mode]==="del" && $_GET[idx]) {
	if(member_session(2)==true) {
		$sqlStr= "DELETE FROM wMember WHERE idx=".$_GET[idx]."";
	}else { js_action(1,"관리자만이 삭제할수 있습니다.","",-1); }
}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 작업 처리 =====================================================================================

if($_GET[mode]==="add") {
	//== 가입축하메일 발송
//	require "../WebnicsMaili/Join_Mail/Join_Mail.htm";
//	send_mail(2,$email_mix,$_POST[name],"webmaster@".$siteDomain,$siteName,$siteName."의 가족이 되신것을 축하드립니다.",$mailing,"","");

	if(member_session(2)==true) $m_url="/nwebnics/wTools/mList.php"; else $m_url="joinOk.php?code=6_11&mode=ok";
	$p_ment="회원 가입 처리중입니다. 가입해주셔서 감사합니다.";
}else if($_GET[mode]==="edit") {
	if(member_session(2)==true) $m_url="/nwebnics/wTools/mList.php"; else $m_url="joinForm.php?mode=edit";
	$p_ment="회원정보 수정중입니다. 완료후 이동하겠습니다.";
}else if($_GET[mode]==="del") {
	$m_url="/nwebnics/wTools/mList.php";
	$p_ment="회원 삭제중입니다. 완료후 이동하겠습니다.";
}else {
	$m_url="/";
}

$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$m_url,$p_ment,1);
$db->disconnect();
?>