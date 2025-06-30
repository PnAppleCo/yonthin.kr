<?php
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//
//== 게시판 기본정보 로드
include ("inc/configInc.php");

//== 코멘트에 특수문자 처리
$ucontents = addslashes($_POST['ucontents']);

//== 답변 등록 처리 ====================================================================================
if($_GET['mode']==="add") {
	//== 새로 올릴 게시물의 idx값
	$rows=$db->getOne("SELECT MAX(idx) FROM wpollMent");
	if(DB::isError($rows)) die($rows->getMessage());
	if($rows>0) $new_idx = $rows + 1; else $new_idx = 1;

	//== DB에 코멘트 입력
	$mSqlStr = "INSERT INTO wpollMent (idx, board_idx, code, mem_id, name, ucontents, signdate, user_ip) VALUES ('$new_idx', '$_GET[idx]', '$_GET[code]', '$_SESSION[my_id]', '$_POST[name]', '$ucontents', now(),'".getenv('REMOTE_ADDR')."')";


//== 답변 수정 처리 ====================================================================================
}else if($_GET['mode']==="edit" && $_GET['cidx']) {
	if(!$_GET['idx']) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

	$mSqlStr = "UPDATE wpollMent SET ucontents='$ucontents' WHERE idx='$_GET[cidx]'";

//== 답변 삭제 처리 ====================================================================================
}else if($_GET['mode']=="del" && $_GET['cidx']) {
	$mSqlStr = "DELETE FROM wpollMent WHERE idx='$_GET[cidx]' AND code='$_GET[code]'";

}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 작업 처리 =====================================================================================
if($_GET['mode']==="add") {
	$p_ment="댓글 등록 처리중입니다. 잠시만 기다려주십시오.";
}else if($_GET['mode']==="edit") {
	$p_ment="댓글 수정 처리중입니다. 잠시만 기다려주십시오.";
}else if($_GET['mode']==="del") {
	$p_ment="댓글 삭제 처리중입니다. 잠시만 기다려주십시오.";
}else { $p_ment="오류가 발생하였습니다."; }

$vfileName="pollView.php";

$rst=$db->query($mSqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$vfileName."?code=".$_GET['code']."&idx=".$_GET['idx']."&mnv=".$_GET['mnv'],$p_ment,1);
$db->disconnect();
?>