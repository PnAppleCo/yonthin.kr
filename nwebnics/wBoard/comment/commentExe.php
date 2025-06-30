<?php
//== 게시판 기본정보 로드
include ("../inc/boardLib.php");

//===================
//== 코멘트 입력 처리
//===================
if($_POST['mode']==="write") {
	//== 게시판 mode 체크
	if(!$_GET['b_idx']) js_action(1, "게시물의 idx정보를 찾을수 없습니다.", "", -1);

	//== 입력데이터 체크
	require ('../inc/postCheck_Inc.php');

	if(strlen($_POST['ucontents'])>4000) js_action(1, "댓글의 길이가 용량을 초과하였습니다.", "", -1);
	$mdpasswd = substr(md5($_POST['passwd']),0,16);

	//== 새로 올릴 게시물의 idx값
	$rows=$db->getOne("select max(idx) from $b_cfg_tb[2]");
	if(DB::isError($rows)) die($rows->getMessage());
	if($rows>0) $new_idx = $rows + 1; else $new_idx = 1;
	//== 코멘트에 특수문자 처리
	$ucontents = addslashes($_POST['ucontents']);
	if(login_session() == true && $board_info['private_board']>0) $_POST['name']=$_SESSION['my_nickname'];
	//== DB에 코멘트 입력
	$sql_str = "insert into $b_cfg_tb[2] (idx, board_idx, code, mem_id, name, ucontents, passwd, signdate, html, user_ip, char_img) values ('$new_idx', '$_GET[b_idx]', '$_GET[code]', '$_SESSION[my_id]', '$_POST[name]', '$ucontents', password('$_POST[passwd]'), now(), '$_POST[html]', '".getenv('REMOTE_ADDR')."', '$_POST[char_img]')";
	$m_ment="코멘트를 등록하였습니다.";
	$m_url="../view.php?code=$_GET[code]&idx=$_GET[b_idx]";

//===================
//== 코멘트 삭제 처리
//===================
}else if($_POST['mode']==="delete") {
	//== 게시판 mode 체크
	if(!$_GET['c_idx']) js_action(1, "코멘트의 idx정보를 찾을수 없습니다.", "", -1);
	//== 비밀번호 적정성 체크
	if($_POST['passwd']) {
		// PHP82 변환
		// if(!ereg("(^[0-9a-zA-Z]{4,}$)", $_POST['passwd'])) js_action(1, "입력하신 문자는 비밀번호로 적당하지 않습니다.", "", -1);
		if(!preg_match("(^[0-9a-zA-Z]{4,}$)", $_POST['passwd'])) js_action(1, "입력하신 문자는 비밀번호로 적당하지 않습니다.", "", -1);
	}
	//== 관리자도 아니고 자신의 글도 안일경우 비밀번호 비교
	if(login_session() == false || (member_session(1,1) == false && strcmp($_GET['c_mem_id'],$_SESSION['my_id']))) {
		if(compare_pass($b_cfg_tb[2], $_GET['code'], $_GET['c_idx'], $_POST['passwd']) == false) js_action(1, "죄송합니다. 비밀번호가 일치하지 않습니다.", "", -1);
	}
	$sql_str = "delete from $b_cfg_tb[2] where code='$_GET[code]' and board_idx='$_GET[b_idx]' and idx='$_GET[c_idx]'";
	$m_ment="코멘트를 삭제하였습니다.";
	$m_url="../view.php?code=$_GET[code]&idx=$_GET[b_idx]";
}else {
	error_view(999, "작업모드가 선택되지 않았습니다.","올바른 방법으로 이용해 보세요.");
}

$rst=$db->query($sql_str);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, $m_url, $m_ment, 0);
$db->disconnect();
?>