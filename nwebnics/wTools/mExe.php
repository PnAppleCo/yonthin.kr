<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 01
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1);

//== 입력 데이터 유효성 체크
if($_POST['mName']) {
	if(!preg_match("/^([가-힣a-zA-Z]+[0-9]*){3,15}$/u", $_POST['mName'])) error_view(999, "입력하신 성명이 적당하지 않습니다.","성명을 다시 입력해주세요.");
}

if($_POST['mId']) {
	if(!preg_match('/^[a-z\d_-]{5,20}$/i', $_POST['mId'])) js_action(1,"아이디는 최소5~20자의 영,숫가 조합되어야 합니다.","",-1);
}

if($_POST['password1']) {
	//if(!preg_match("(^[0-9a-zA-Z]{5,}$)", $_POST[passwd])) js_action(1,"비밀번호는 최소5~20자의 영,숫자가 조합되어야 합니다.","",-1);
}
if($_POST['birthMonth']) {
	if(!checkdate($_POST['birthMonth'],$_POST['birthDay'], $_POST['birthYear'])) js_action(1,"생년월일이 적당하지 않습니다.","",-1);
}

//== 데이터 조합
$birthMix=$_POST['birthYear']."-".$_POST['birthMonth']."-".$_POST['birthDay'];
$telMix=$_POST['telNum01']."-".$_POST['telNum02']."-".$_POST['telNum03'];
$ctelMix=$_POST['ctelNum01']."-".$_POST['ctelNum02']."-".$_POST['ctelNum03'];
$selMix=$_POST['selNum01']."-".$_POST['selNum02']."-".$_POST['selNum03'];
if($telMix==="--") $telMix="";
if($selMix==="--") $selMix="";
if($ctelMix==="--") $ctelMix="";
if($_POST['email_b']) $emailMix=$_POST['email_a']."@".$_POST['email_b']; else if($_POST['email_c']) $emailMix=$_POST['email_a']."@".$_POST['email_c'];
if($emailMix) {
	if(!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $emailMix)) error_view(999, "입력하신 전자우편 주소가 적당하지 않습니다.","적당한 전자우편 주소를 다
	시 입력해주세요.");
}
if($_POST['birtMonth']) if(!checkdate($_POST['birthMonth'],$_POST['birthDay'], $_POST['birthYear'])) js_action(1,"생년월일이 적당하지 않습니다.","",-1);

$_POST['haddress1']=str_replace("&nbsp;", " ", $_POST['haddress1']);
$_POST['haddress2']=str_replace("&nbsp;", " ", $_POST['haddress2']);

//== 신규등록일경우 중복 제거 =========================================================================

if($_GET['mode']==="add") {
	//== 자동가입 방지 비교
	//if(strcmp(base64_decode($_POST[Divi_Str_s]),$_POST[Divi_Str_d]) !=0) js_action(1,"자동가입방지 문자열이 일치하지 않습니다.","",-1);
	//== 아이디 중복 방지
	$sql_str = "SELECT COUNT(idx) FROM wMember WHERE mIid = '$_POST[mId]';";
	$nums = $db->getOne($sql_str);
	if(DB::isError($nums)) { die($nums->getMessage()); }else { if($nums>0) js_action(1,"이미등록된 아이디 입니다.","",-1); }
}

//== 회원 등록 처리 ====================================================================================
if($_GET['mode']==="add") {
	//== 새로 등록할 회원의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM wMember");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$sqlStr = "INSERT INTO wMember(idx, mId, passwd, mName, nickName, iQuestion, iAnswer, birthDay, solu, sex, email, zipcode, haddress1, haddress2, telNum, selNum, route, uJob, newsDm, smsDm, ulevel, signDate, careCenter, ctelNum, czipcode, caddress1, caddress2) VALUES ('".$new_idx."', '".$_POST['mId']."', '".md5($_POST['password1'])."', '".$_POST['mName']."', '".$_POST['nickName']."', '".$_POST['iQuestion']."', '".$_POST['iAnswer']."', '".$birthMix."', '".$_POST['solu']."', '".$_POST['sex']."', '".$emailMix."', '".$_POST['zipcode']."', '".$_POST['haddress1']."', '".$_POST['haddress2']."', '".$telMix."', '".$selMix."', '".$_POST['route']."', '".$_POST['uJob']."', '".$_POST['newsDm']."', '".$_POST['smsDm']."', '".$_POST['ulevel']."', now(), '".$_POST['careCenter']."', '".$ctelMix."', '".$_POST['czipcode']."', '".$_POST['caddress1']."', '".$_POST['caddress2']."')";
	$vMent="등록";

//== 회원 수정 처리 ====================================================================================
}else if($_GET['mode']==="edit") {
	$sqlStr = "UPDATE wMember SET mName='".$_POST['mName']."', nickName='".$_POST['nickName']."', iQuestion='".$_POST['iQuestion']."', iAnswer='".$_POST['iAnswer']."', birthDay='".$birthMix."', solu='".$_POST['solu']."', email='".$emailMix."', zipcode='".$_POST['zipcode']."', haddress1='".$_POST['haddress1']."', haddress2='".$_POST['haddress2']."', telNum='".$telMix."',selNum='".$selMix."', route='".$_POST['route']."', uJob='".$_POST['uJob']."', newsDm='".$_POST['newsDm']."', ulevel='".$_POST['ulevel']."', adminMemo='".$_POST['adminMemo']."', careCenter='".$_POST['careCenter']."', ctelNum='".$ctelMix."', czipcode='".$_POST['czipcode']."', caddress1='".$_POST['caddress1']."', caddress2='".$_POST['caddress2']."', noshowCnt='".$_POST['noshowCnt']."', limitDate='".$_POST['limitDate']."', endDate='".$_POST['endDate']."'";
	if($_POST['password1']) $sqlStr .=", passwd='".md5($_POST['password1'])."'";
	$sqlStr .=" WHERE idx='".$_GET['idx']."'";
	$vMent="수정";

//== 회원 삭제 처리 ====================================================================================
}else if($_GET['mode']=="del" && $_GET['idx']) {
	$sqlStr= "DELETE FROM wMember WHERE idx=".$_GET['idx']."";
	$vMent="삭제";
}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 작업 처리 =====================================================================================
$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,"mList.php?page=".$_GET['page'],$vMent."작업후 이동합니다.",2);
$db->disconnect();
?>