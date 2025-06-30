<?PHP
//== 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 세션종료
session_ending();
if($_GET['mode']==="board") {
	//redirect(1, "../wBoard/list.php?code=$_GET[code]","안전하게 로그아웃 중입니다. 잠시만 기다려 주십시오.",1);
	redirect(1, "/","안전하게 로그아웃 중입니다. 잠시만 기다려 주십시오.",1);
}else if($_GET['mode']==="member") {
	//redirect(1, "/nwebnics/wTools/","안전하게 로그아웃 중입니다. 잠시만 기다려 주십시오.",1);
}else {
	redirect(1, "/","로그아웃 중입니다. 잠시후 홈으로 이동합니다.",1);
}
?>