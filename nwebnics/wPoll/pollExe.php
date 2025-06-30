<?php
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(login_session() == false) redirect(1, "/", "회원 로그인후 이용하세요.", 1);

if(!$_GET['code']) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

//== 등록 처리 ====================================================================================
if($_GET['mode']==="add") {
	//== 새로 등록할 TDL의 고유번호 생성
	$max_idx = $db->getOne("SELECT MAX(idx) FROM wPoll");
	if(DB::isError($max_idx)) die($max_idx->getMessage());
	if($max_idx<=0) $new_idx=1; else $new_idx=$max_idx+1;
	$mSqlStr = "INSERT INTO wPoll (
	idx,
	code,
	sSubject,
	wName,
	endDate,
	wUserid,
	sContents,
	sNum,
	censitem0,
	censitem1,
	censitem2,
	censitem3,
	censitem4,
	censitem5,
	censitem6,
	censitem7,
	censitem8,
	censitem9,
	signDate
	) VALUES (
	$new_idx,
	'$_POST[code]',
	'$_POST[sSubject]',
	'$_POST[wName]',
	'$_POST[endDate]',
	'$_SESSION[my_id]',
	'$_POST[sContents]',
	'$_POST[sNum]',
	'".$_POST['censitem'][0]."',
	'".$_POST['censitem'][1]."',
	'".$_POST['censitem'][2]."',
	'".$_POST['censitem'][3]."',
	'".$_POST['censitem'][4]."',
	'".$_POST['censitem'][5]."',
	'".$_POST['censitem'][6]."',
	'".$_POST['censitem'][7]."',
	'".$_POST['censitem'][8]."',
	'".$_POST['censitem'][9]."',
	now());";

//== 수정 처리 ====================================================================================
}else if($_GET['mode']==="edit" && $_GET['idx']) {
	if(!$_GET['idx']) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

	$mSqlStr = "UPDATE wPoll SET
	code='$_POST[code]',
	sSubject='$_POST[sSubject]',
	wName='$_POST[wName]',
	endDate='$_POST[endDate]',
	sContents='$_POST[sContents]',
	sNum='$_POST[sNum]',
	censitem0='".$_POST['censitem'][0]."',
	censitem1='".$_POST['censitem'][1]."',
	censitem2='".$_POST['censitem'][2]."',
	censitem3='".$_POST['censitem'][3]."',
	censitem4='".$_POST['censitem'][4]."',
	censitem5='".$_POST['censitem'][5]."',
	censitem6='".$_POST['censitem'][6]."',
	censitem7='".$_POST['censitem'][7]."',
	censitem8='".$_POST['censitem'][8]."',
	censitem9='".$_POST['censitem'][9]."'";
	$mSqlStr .= " WHERE idx='$_GET[idx]'";

//== 삭제 처리 ====================================================================================
}else if($_GET['mode']==="del" && $_GET['idx']) {

	//== 등록된 코멘트삭제
	$sqlStr="DELETE FROM wpollMent WHERE board_idx=$_GET[idx]";
	$rst=$db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());

	$mSqlStr= "DELETE FROM wPoll WHERE idx=$_GET[idx]";

//== 투표 처리 ====================================================================================
}else if($_GET['mode']==="vote" && $_GET['idx']) {

	$cookieName=$_SESSION['my_id'].$_GET['idx'];
	if($_COOKIE['voteok']==getenv('REMOTE_ADDR').$_GET['idx']) {
		js_action(1,"해당 PC에서는 이미 투표 하셨습니다.","",-1);
	}else {
		setcookie("voteok",$cookieName,time()+259200,"/");
	}

	$mSqlStr = "UPDATE wPoll SET vote".$_POST['vote']."=vote".$_POST['vote']."+1";
	$mSqlStr .= " WHERE idx='$_GET[idx]'";

}else if($_GET['mode']==="rec" && $_GET['idx']) {

	$pollcookievalue="reco".$_GET['code'].$_GET['idx'];
	if($_COOKIE['pollcookie']!=$pollcookievalue) {
		//== 추천은 1번만 12시간(쿠키저장)
		setcookie("pollcookie",$pollcookievalue,time()+43200,"/");
		$mSqlStr = "UPDATE wPoll SET $_POST[sfield]=$_POST[sfield]+1 WHERE code='$_GET[code]' AND idx=$_GET[idx]";
	}else { js_action(1, "추천은 하루에 한번만 가능합니다.", "", -1); }

}else { js_action(1,"작업 구분정보를 찾을수 없습니다.","",-1); }

//== 질의 작업 처리 =====================================================================================
if($_GET['mode']==="add") {
	$p_ment="등록 처리중입니다. 잠시만 기다려주십시오.";
	$vfileName="pollList.php?code=".$_GET['code']."&eidx=".$_GET['idx']."&page=".$_GET['page']."&mnv=".$_GET['mnv'];
}else if($_GET['mode']==="edit") {
	$p_ment="수정 처리중입니다. 잠시만 기다려주십시오.";
	$vfileName="pollList.php?code=".$_GET['code']."&eidx=".$_GET['idx']."&page=".$_GET['page']."&mnv=".$_GET['mnv'];
}else if($_GET['mode']==="del") {
	$p_ment="삭제 처리중입니다. 잠시만 기다려주십시오.";
	$vfileName="pollList.php?code=".$_GET['code']."&eidx=".$_GET['idx']."&page=".$_GET['page']."&mnv=".$_GET['mnv'];
}else if($_GET['mode']==="vote") {
	$p_ment="투표 처리중입니다. 잠시만 기다려주십시오.";
	if($_GET['page']) $vfileName="pollView.php?idx=".$_GET['idx']."&code=".$_GET['code']."&mnv=".$_GET['mnv']; else $vfileName="/";
}else if($_GET['mode']==="rec") {
	$p_ment="추천 처리중입니다. 잠시만 기다려주십시오.";
	$vfileName="pollView.php?idx=".$_GET['idx']."&code=".$_GET['code']."&mnv=".$_POST['mnv'];
}else { $p_ment="오류가 발생하였습니다."; }

$rst=$db->query($mSqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1,$vfileName,$p_ment,1);
$db->disconnect();
?>