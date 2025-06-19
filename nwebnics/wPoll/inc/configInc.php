<?
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 접근권한 설정
//$allow_ip = array("109.2.91.135");
//if(!in_array($_SERVER["REMOTE_ADDR"], $allow_ip)) { header('Location:/main.htm'); exit; }
//== 타이틀바
$Title_Txt="애니타로";
//== 메타키워드
$Keywords_Txt="애니타로";
//== 작성자
$Description_Txt="웹닉스솔루션";
//== 제작자
$Author_Txt="웹닉스";

//== 첫 페이지 설정
if(!$_GET[page]) $_GET[page] = 1;
//== 페이지수 설정
$num_per_page=20;
//== 블럭당 페이지수 설정
$num_per_block=10;
?>