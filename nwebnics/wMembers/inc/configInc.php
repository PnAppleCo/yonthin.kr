<?PHP
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 첫 페이지 설정
if(!$_GET['page']) $_GET['page'] = 1;
//== 페이지수 설정
$num_per_page=20;
//== 블럭당 페이지수 설정
$num_per_block=10;
?>