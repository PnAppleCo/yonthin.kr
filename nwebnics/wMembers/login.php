<?
//==  기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 전달 파라메터 체크
if(preg_match("/[\s,]+/", $_POST[mId])) js_action(1,"올바른 아이디를 입력하셔야 합니다.","",-1);
if(preg_match("/[\s,]+/", $_POST[pass])) js_action(1,"올바른 비밀번호를 입력하셔야 합니다.","",-1);

//== 회원 등록여부 검사
$sqlStr = "SELECT COUNT(idx) FROM wMember WHERE mId='$_POST[mId]'";
$r_mem = $db->getOne($sqlStr);
if(DB::isError($r_mem)) die($r_mem->getMessage());
if($r_mem <= 0) js_action(1,"아이디 혹은 비밀번호가 일치하지 않습니다.","",-1);

//== 회원 등록이 되어 있을경우 기본정보 추출
$sqlStr = "SELECT idx, mId, passwd, mName, nickname, ulevel, email FROM wMember WHERE mId = '$_POST[mId]'";
$r_login = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
if(DB::isError($r_login)) die($r_login->getMessage());
	if(!strcmp(md5($_POST[pass]),$r_login[passwd])) {

		//== 세션등록
		session_starting($r_login[idx], $_POST[mId], $r_login[mName], $r_login[nickname], $r_login[ulevel], getenv("REMOTE_ADDR"));
		//== 로그인 횟수 증가
		$sqlStr = "UPDATE wMember SET login = login+1, lastLogin=now() WHERE mId = '$_POST[mId]'";
		$rst = $db->query($sqlStr);
		if(DB::isError($rst)) die($rst->getMessage());
		//== 아이디 쿠키굽기
		if($_POST[saveid]==="ok") setcookie(md5($siteDomain),$_POST[mId],time()+31536000,"/"); else setcookie(md5($siteDomain),"",0,"/");
		if($_GET[want_url]) {
			redirect(1, $_GET[want_url],"로그인중입니다. 잠시만 기다려 주십시오.",1);
		}else {
			//== 관리자 로그인 URL 설정
			if($wtoolType=="1") $adminUrl="/nwebnics/wTools/"; else if($wtoolType=="2") $adminUrl="/nwebnics/wTools/"; else $adminUrl="/nwebnics/wTools/";
			//== 회원이 관리자와 일반회원을 구분하여 이동
			if($r_login[ulevel]==1) $Move_Url=$adminUrl; else $Move_Url="/";

			redirect(1, $Move_Url,"로그인 중입니다. 잠시만 기다려 주십시오.",1);
		}
	}else { js_action(1,"아이디 혹은 비밀번호가 일치하지 않습니다.","",-1); }
?>