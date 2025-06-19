<?
$listTotal = $db->getOne($sqlStr1);
if(DB::isError($listTotal)) die($listTotal->getMessage());
//== 페이지 설정
$page_set = 20;																																			//== 한페이지 줄수
$block_set = 10;																																			//== 한페이지 블럭수

$total_page = ceil ($listTotal / $page_set);																	//== 총페이지수(올림함수)
$total_block = ceil ($total_page / $block_set);														//== 총블럭수(올림함수)

if(!$_GET[page]) $_GET[page] = 1;																					//== 현재페이지(넘어온값)
$block = ceil ($_GET[page] / $block_set);																//== 현재블럭(올림함수)
$limit_idx = ($_GET[page] - 1) * $page_set;																//== limit 시작위치

if($_GET[page]==1) $listIdx=$listTotal; else $listIdx=$listTotal-($page_set*($_GET[page]-1));									//== 순번

//================================================================ 페이지번호 & 블럭 설정 ======================================================//
$first_page = (($block - 1) * $block_set) + 1;																		//== 첫번째 페이지번호
$last_page = min ($total_page, $block * $block_set);													//== 마지막 페이지번호

$prev_page = $_GET[page] - 1;																											//== 이전페이지
$next_page = $_GET[page] + 1;																											//== 다음페이지

$prev_block = $block - 1;																																//== 이전블럭
$next_block = $block + 1;																																//== 다음블럭

// 이전블럭을 블럭의 마지막
$prev_block_page = $prev_block * $block_set;																	//== 이전블럭 페이지번호
// 이전블럭을 블럭의 첫페이지
//$prev_block_page = $prev_block * $block_set - ($block_set - 1);
$next_block_page = $next_block * $block_set - ($block_set - 1);				//== 다음블럭 페이지번호

$viewVal = new paging();
$addGet=$viewVal->val_reset();

// 페이징 화면
$pagePrint .= ($prev_block > 0) ? "<a href='".$_SERVER[PHP_SELF]."?page=".$prev_block_page.$addGet."' class='pageBlock'>···</a>" : "<span class='pageBlockS'>...</span>";
$pagePrint .= ($prev_page > 0) ? "<a href='".$_SERVER[PHP_SELF]."?page=".$prev_page.$addGet."' class='pageBlock'><</a>" : "<span class='pageBlock'><</span>";

for ($i=$first_page; $i<=$last_page; $i++) {
	$pagePrint .= ($i != $_GET[page]) ? "<a href='".$_SERVER[PHP_SELF]."?page=".$i.$addGet."' class='pageBlockN'>".$i."</a>" : "<span class='pageBlockS'>".$i."</span>";
}

$pagePrint .= ($next_page <= $total_page) ? "<a href='".$_SERVER[PHP_SELF]."?page=".$next_page.$addGet."' class='pageBlock'>></a>" : "<span class='pageBlock'>></span>";
$pagePrint .= ($next_block <= $total_block) ? "<a href='".$_SERVER[PHP_SELF]."?page=".$next_block_page.$addGet."' class='pageBlock'>···</a>" : "<span class='pageBlockS'>...</span>";

$pageStatus = "총".$listTotal."개 페이지".$_GET[page]."/".$total_page;
?>