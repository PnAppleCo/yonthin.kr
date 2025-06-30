<?php
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 접근권한 설정 [삭제]
//$allow_ip = array("109.2.91.135");
//if(!in_array($_SERVER["REMOTE_ADDR"], $allow_ip)) { header('Location:/main.htm'); exit; }
//== 타이틀바
$Title_Txt=$siteName;
//== 메타키워드
$Keywords_Txt=$siteName;
//== 작성자
$Description_Txt=$siteName;
//== 제작자
$Author_Txt=$siteName;
//== 호출파일
$Top_Inc_File="/nwebnics/wTools/inc/headInc.htm";
$Left_Inc_File="/nwebnics/wTools/inc/leftInc.htm";
$Right_Inc_File="";
$Foot_Inc_File="/nwebnics/wTools/inc/footInc.htm";

//== 첫 페이지 설정
if(!$_GET['page']) $_GET['page'] = 1;
//== 페이지수 설정
$num_per_page=20;
//== 블럭당 페이지수 설정
$num_per_block=20;
//== 기본환경설정 테이블(환경설정, 게시판, 코멘트)
$b_cfg_tb = array('wboardConfig', 'wBoard', 'wComment');
?>