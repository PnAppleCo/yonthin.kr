<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2015. 01. 06
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//== 등록 처리 ====================================================================================

if($_GET['mode']==="add") {
	//== 새로 등록할 팝업창의 고유번호 생성
	$max_idx = $db->getOne("select max(idx) from infoTbl");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
		$m_sql_str = "INSERT INTO infoTbl (idx, c_name, c_cate, c_event, zipcode, haddress1, haddress2, c_num, c_t_num, c_ceo, c_user, c_tel, c_fax) VALUES ($new_idx, '$_POST[c_name]', '$_POST[c_cate]', '$_POST[c_event]', '$_POST[zipcode]', '$_POST[haddress1]', '$_POST[haddress2]', '$_POST[c_num]', '$_POST[c_t_num]', '$_POST[c_ceo]', '$_POST[c_user]', '$_POST[c_tel]', '$_POST[c_fax]');";

//== 수정 처리 ====================================================================================

}else if($_GET['mode']==="edit") {
	if(!$_GET['idx']) js_action(1,"idx정보를 찾을수 없습니다.","",-1);
	if($_GET['code']=="1") {
		$m_sql_str = "UPDATE infoTbl SET c_name='$_POST[c_name]', c_cate='$_POST[c_cate]', c_event='$_POST[c_event]', zipcode='$_POST[zipcode]', haddress1='$_POST[haddress1]', haddress2='$_POST[haddress2]', c_num='$_POST[c_num]', c_t_num='$_POST[c_t_num]', c_ceo='$_POST[c_ceo]', c_user='$_POST[c_user]', c_tel='$_POST[c_tel]', c_fax='$_POST[c_fax]' WHERE idx=$_GET[idx]";
	}else if($_GET['code']=="2") {
		$m_sql_str = "UPDATE infoTbl SET agreeinfo='$_POST[agreeinfo]' WHERE idx=$_GET[idx]";
	}else if($_GET['code']=="3") {
		$m_sql_str = "UPDATE infoTbl SET privateinfo='$_POST[privateinfo]' WHERE idx=$_GET[idx]";
	}else if($_GET['code']=="4") {
		$m_sql_str = "UPDATE infoTbl SET c_title='$_POST[c_title]', c_meta='$_POST[c_meta]', c_noid='$_POST[c_noid]' WHERE idx=$_GET[idx]";
	}else if($_GET['code']=="5") {
		$m_sql_str =  "UPDATE infoTbl SET private_agree='$_POST[private_agree]' WHERE idx=$_GET[idx]";
	}

//== 삭제 처리 ====================================================================================

}else if($_GET['mode']==="del") {
	if(!$_GET['idx']) js_action(1,"idx정보를 찾을수 없습니다.","",-1);

	$m_sql_str= "DELETE FROM infoTbl WHERE idx=$_GET[idx]";

}else {
	js_action(1,"작업정보를 찾을수 없습니다.","",-1);
}

//== 질의 작업 처리 =====================================================================================
if($_GET['code']=="1") $vTitle="회사정보"; else if($_GET['code']=="2") $vTitle="이용약관"; else if($_GET['code']=="3") $vTitle="개인정보보호방침";
if($_GET['mode']=="add") {
	$p_ment=$vTitle." 등록중입니다. 완료후 이동하겠습니다.";
}else if($_GET['mode']=="edit") {
	$p_ment=$vTitle." 수정중입니다. 완료후 이동하겠습니다.";
}else if($_GET['mode']=="del") {
	$p_ment=$vTitle." 삭제중입니다. 완료후 이동하겠습니다.";
}else {
	$p_ment="예기치 못한 상황이 발생하였습니다.";
}

$rst=$db->query($m_sql_str);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, "./infoForm.php?mode=edit&idx=".$_GET['idx']."&code=".$_GET['code'], $p_ment, 1);
$db->disconnect();
?>