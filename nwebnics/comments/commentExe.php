<?PHP
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2015. 1. 12
//==================================================================
//== 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//===================
//== 코멘트 입력 처리
//===================
if($_GET['mode']==="add") {
	//== 게시판 mode 체크
	if(!$_POST['prarentIdx']) js_action(1, "게시물의 idx정보를 찾을수 없습니다.", "", -1);

	if(strlen($_POST['comments'])>4000) js_action(1, "댓글의 길이가 용량을 초과하였습니다.", "", -1);
	$_POST['passwd'] = substr(md5($_POST['passwd']),0,16);

	//== 새로 올릴 게시물의 idx값
	$maxCnt=$db->getOne("SELECT MAX(idx) FROM wComments");
	if(DB::isError($maxCnt)) die($maxCnt->getMessage());
	if($maxCnt>0) $new_idx = $maxCnt + 1; else $new_idx = 1;
	//== 코멘트에 특수문자 처리
	$_POST['comments'] = addslashes($_POST['comments']);

	//* 회원정보 처리 *//
	if(login_session()) {
		$_POST['mId']=$_SESSION['my_id'];
		$_POST['cName']=$_SESSION['my_name'];
	}else {
		$_POST['mId']="";
		$_POST['cName']=$_POST['cName'];
}
	//== DB에 코멘트 입력
	$sqlStr = "INSERT INTO wComments (idx, code, prarentIdx, cName, mId, passwd, selPhone, comments, userIp, signDate, signTime) VALUES ('$new_idx', '$_POST[code]', '$_POST[prarentIdx]', '$_POST[cName]', '$_POST[mId]', '$_POST[passwd]', '$_POST[selPhone]', '$_POST[comments]', '".getenv('REMOTE_ADDR')."', now(), now())";
	$m_ment="코멘트를 등록하였습니다.";
	$m_url=urldecode($returnUrl);

//===================
//== 코멘트 삭제 처리
//===================
}else if($_GET['mode']==="del") {
	if(!$_GET['idx']) js_action(1, "코멘트의 중요정보를 찾을수 없습니다.", "", -1);

	$sqlStr = "DELETE FROM wComments WHERE idx='$_GET[idx]'";

	$m_ment="코멘트를 삭제하였습니다.";
	$m_url=urldecode($returnUrl);

//===================
//== 추천 비추 처리
//===================
}else if($_GET['mode']==="rec") {
	if(!$_GET['idx']) js_action(1, "코멘트의 중요정보를 찾을수 없습니다.", "", -1);
	if(!$_GET['prarentIdx']) js_action(1, "코멘트의 중요정보를 찾을수 없습니다.", "", -1);

	$sqlStr = "UPDATE wComments SET ".$_GET['field']."=".$_GET['field']."+1 WHERE idx='$_GET[idx]'";
	$m_ment="코멘트를 추천하였습니다.";
	$m_url="/article/read.php?mode=read&idx=$_GET[prarentIdx]";
}else {
	error_view(999, "작업모드가 선택되지 않았습니다.","올바른 방법으로 이용해 보세요.");
}

$rst=$db->query($sqlStr);
if(DB::isError($rst)) die($rst->getMessage()); else redirect(1, $m_url, $m_ment, 0);
$db->disconnect();
?>