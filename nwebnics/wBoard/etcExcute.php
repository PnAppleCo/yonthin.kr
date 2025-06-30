<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'jisuk'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

if(!$_POST['idx']) js_action(1, "게시물의 idx정보를 찾을수 없습니다.", "", -1);
if(!$_POST['code']) js_action(1, "게시물의 CODE정보를 찾을수 없습니다.", "", -1);

if($_POST['etc_mode']==="recommend") {															//== 추천
	$r_cookiename="reco".$_POST['code'].$_POST['idx'];
	if(!$_COOKIE["$r_cookiename"]) {
		//== 추천은 1번만 12시간(쿠키저장)
		setcookie($r_cookiename,$r_cookiename,time()+43200,"/");
		$sql_str = "UPDATE $b_cfg_tb[1] SET recommend=recommend+$_POST[recommend] WHERE code='$_POST[code]' AND idx=$_POST[idx]";
	}else { js_action(1, "추천은 하루에 한번만 가능합니다.", "", -1); }
}else if($_POST['etc_mode']==="grade") {														//== 평가
	//== 관리자도 아니고 자신의 글도 안일경우 비밀번호 비교
	if(login_session() == false || (member_session(1) == false && strcmp($_POST['mem_id'],$_SESSION['my_id']))) {
		if(compare_pass($b_cfg_tb[1], $_POST['code'], $_POST['idx'], $_POST['sPass'])==false) js_action(1, "죄송합니다. 글작성시 입력한 비밀번호와 일치하지 않습니다.", "", -1);
	}
	$sql_str = "UPDATE $b_cfg_tb[1] SET svc_grade=$_POST[svc_grade] WHERE code='$_POST[code]' AND idx=$_POST[idx]";
}
$rst=$db->query($sql_str);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, "./view.php?code=$_POST[code]&idx=$_POST[idx]", "", 0);
?>