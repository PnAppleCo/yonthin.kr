<?php
//============================================================= 검색 질의 ===============================================================//
$sqlStr1 = "SELECT COUNT(idx) FROM wBoard";
$sqlStr2 = "SELECT * FROM wBoard";

//== 검색옵션
if($_GET['langType']) $addSql .= " langType='".$_GET['langType']."' AND";
if($_GET['pType']) $addSql .= " pType='".$_GET['pType']."' AND";
if($_GET['rStatus']) $addSql .= " rStatus='".$_GET['rStatus']."' AND";
if($_GET['rDate'] && !$_GET['rDates']) $addSql .= " DATE_FORMAT(rDate, '%Y-%m-%d') = '".$_GET['rDate']."' AND";
if($_GET['rDate'] && $_GET['rDates']) $addSql .= " DATE_FORMAT(rDate,'%Y-%m-%d') BETWEEN DATE_FORMAT('$_GET[rDate]','%Y-%m-%d') AND DATE_FORMAT('$_GET[rDates]','%Y-%m-%d') AND";

if($_GET['gField'] && $_GET['gWord']) $addSql .=" $_GET[gField] like '%$_GET[gWord]%' AND";

//== 조건 질의어 생성
if($addSql) {
	$sqlStr1 .= " WHERE".substr($addSql,0,-3);
	$sqlStr2 .= " WHERE".substr($addSql,0,-3);
}

//== 정렬필드와 차순결정
if($_GET['aField']) $alignField=$_GET['aField']; else $alignField="idx";
if($_GET['aType']) $alignType=$_GET['aType']; else $alignType="DESC";

$sqlStr2 .= " ORDER BY ".$alignField." ".$alignType;
//== 다음 정렬 차순 결정
if($alignType=="DESC") $alignType="ASC"; else if($alignType=="ASC") $alignType="DESC";
//echo $sqlStr1.'<br>'.$sqlStr2;

$excelDown="sqlStr=".urlencode($sqlStr2)."&rType=".$_GET['rType'];

//==================================================================질의 종료 ================================================================//

$listTotal = $db->getOne($sqlStr1);
if(DB::isError($listTotal)) die($listTotal->getMessage());
//== 페이지 설정
$page_set = 12;																																			//== 한페이지 줄수
$block_set = 10;																																			//== 한페이지 블럭수

$total_page = ceil ($listTotal / $page_set);																	//== 총페이지수(올림함수)
$total_block = ceil ($total_page / $block_set);														//== 총블럭수(올림함수)

if(!$_GET['page']) $_GET['page'] = 1;																					//== 현재페이지(넘어온값)
$block = ceil ($_GET['page'] / $block_set);																//== 현재블럭(올림함수)
$limit_idx = ($_GET['page'] - 1) * $page_set;																//== limit 시작위치

if($_GET['page']==1) $listIdx=$listTotal; else $listIdx=$listTotal-($page_set*($_GET['page']-1));									//== 순번

//================================================================ 페이지번호 & 블럭 설정 ======================================================//
$first_page = (($block - 1) * $block_set) + 1;																		//== 첫번째 페이지번호
$last_page = min ($total_page, $block * $block_set);													//== 마지막 페이지번호

$prev_page = $_GET['page'] - 1;																											//== 이전페이지
$next_page = $_GET['page'] + 1;																											//== 다음페이지

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
$pagePrint .= ($prev_block > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=".$prev_block_page.$addGet."' class='bt first'>···</a>" : "<span class='bt first'>처음</span>";
$pagePrint .= ($prev_page > 0) ? "<a href='".$_SERVER['PHP_SELF']."?page=".$prev_page.$addGet."' class='bt prev'>이전</a>" : "<span class='bt prev'>이전</span>";

for ($i=$first_page; $i<=$last_page; $i++) {
	$pagePrint .= ($i != $_GET['page']) ? "<a href='".$_SERVER['PHP_SELF']."?page=".$i.$addGet."' class='num on'>".$i."</a>" : "<span class='num'>".$i."</span>";
}

$pagePrint .= ($next_page <= $total_page) ? "<a href='".$_SERVER['PHP_SELF']."?page=".$next_page.$addGet."' class='bt next'>다음</a>" : "<span class='bt next'>다음</span>";
$pagePrint .= ($next_block <= $total_block) ? "<a href='".$_SERVER['PHP_SELF']."?page=".$next_block_page.$addGet."' class='bt last'>···</a>" : "<span class='bt last'>마지막</span>";
?>
<!--     <a href="#" class="bt first">처음</a>
    <a href="#" class="bt prev">이전</a>
    <a href="#" class="num">1</a>
    <a href="#" class="num on">2</a>
    <a href="#" class="num">3</a>
    <a href="#" class="num">4</a>
    <a href="#" class="num">5</a>
    <a href="#" class="bt next">다음</a>
    <a href="#" class="bt last">마지막</a> -->