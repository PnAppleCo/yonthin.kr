<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 08. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//== 등록 처리 ====================================================================================

if($_GET[mode]==="add") {
	//== 새로 등록할 구독의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM signTbl");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$sqlStr = "INSERT INTO signTbl(idx, sName, aDdress, wordSupport, signDate) VALUES ('".$new_idx."', '".$_POST[sName]."', '".$_POST[aDdress]."', '".$_POST[wordSupport]."', now())";

//== 수정 처리 ====================================================================================

}else if($_GET[mode]==="edit") {

	if(member_session(1)==true && $_GET[idx]) {
		$sqlStr = "UPDATE signTbl SET sName='".$_POST[sName]."', aDdress='".$_POST[aDdress]."', wordSupport='".$_POST[wordSupport]."', WHERE idx=".$_GET[idx]."";

	}else { js_action(1,"관리자 권한입니다.","",-1); }

//== 삭제 처리 ====================================================================================

}else if($_GET[mode]==="del") {
	if(member_session(1)==true && $_GET[idx]) {
		$sqlStr= "DELETE FROM signTbl WHERE idx=".$_GET[idx]."";
	}else { js_action(1,"관리자만이 삭제할수 있습니다.","",-1); }
}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 처리 =====================================================================================

if($_GET[mode]==="add") {
	if(member_session(1)==true) $m_url="appList.php?appIdx=".$_POST[appIdx];
	$p_ment="종전평화 서명 등록중입니다.";
}else if($_GET[mode]==="edit") {
	$m_url="appList.php?page=".$_GET[page]."&appIdx=".$_POST[appIdx];
	$p_ment="종전평화 서명 수정중입니다.";
}else if($_GET[mode]==="del") {
	$m_url="appList.php?page=".$_GET[page]."&appIdx=".$_GET[appIdx];
	$p_ment="종전평화 서명 삭제중입니다.";
}else {
	$m_url="/";
}

$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$m_url,$p_ment,1);
$db->disconnect();
?>