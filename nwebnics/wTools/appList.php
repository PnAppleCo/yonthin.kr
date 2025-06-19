<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//============================================================= 검색 질의 ===============================================================//
$sqlStr1 = "SELECT COUNT(DISTINCT idx) FROM signTbl";
$sqlStr2 = "SELECT * FROM signTbl";

if($_GET[gField] && $_GET[gWord]) $addSql .=" $_GET[gField] like '%$_GET[gWord]%' AND";

//== 조건 질의어 생성
if($addSql) {
	$sqlStr1 .= " WHERE".substr($addSql,0,-3);
	$sqlStr2 .= " WHERE".substr($addSql,0,-3);
}

//== 정렬필드와 차순결정
if($_GET[aField]) $alignField=$_GET[aField]; else $alignField="idx";
if($_GET[aType]) $alignType=$_GET[aType]; else $alignType="DESC";
$sqlStr2 .= " ORDER BY ".$alignField." ".$alignType;
//== 다음 정렬 차순 결정
if($alignType=="DESC") $alignType="ASC"; else if($alignType=="ASC") $alignType="DESC";
//echo $sqlStr1.'<br>'.$sqlStr2;

$excelDown="sqlStr=".urlencode($sqlStr2);

//==================================================================질의 종료 ================================================================//

//== 총 레코드수 추출
$total = $db->getOne($sqlStr1);
if(DB::isError($total)) die($total->getMessage());

	if(!$total) {
		$first = 1;
		$last = 0;
	}else {
		$first = $num_per_page*($_GET[page]-1);
		$last = $num_per_page*$_GET[page];
		$next = $total - $last;
		if($next > 0) $last -= 1; else $last = $total - 1;
	}
	//== 총 페이지수
	$total_page = ceil($total/$num_per_page);
	//== 일련번호 설정
	$article_num = $total - $num_per_page*($_GET[page]-1);
	//== 오늘 등록된 게시물
	/*
	$sqlStr = "SELECT COUNT(idx) FROM orderTbl WHERE signdate=now()";
	$todays = $db->getOne($sqlStr);
	if(DB::isError($todays)) die($todays->getMessage());
	*/

	//== 질의수행
	$view = $db->getAll($sqlStr2,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	//== 페이지 현황정보
	$page_state="총:".$total."개";
	if(!$_GET[keyword] && !$_GET[keyfield]) $page_state .= " 페이지:".$_GET[page]." / ".$total_page; else $page_state .= " 검색결과:".$_GET[page]." / ".$total_page;
	$paging = new paging(); $viewPaging=$paging->page_display($total,$num_per_page, $num_per_block,$next);
?>
<!DOCTYPE <?=$doctypeSet;?>>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="robots" content="none" />
		<meta name="robots" content="noindex, nofollow" />
		<meta name="robots" content="noimageindex" />
		<meta name="robots" content="noarchive" />
		<meta name="googlebot" content="noindex, nofollow" />
		<meta name="googlebot" content="noimageindex" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/wglobal.css" />
		<link rel="stylesheet" type="text/css" href="/css/wtools.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<!-- 스킵 바로가기 메뉴 -->
		<ul id="skipmenu">
			<li><a href="#navi-quick">메인메뉴 바로가기</a></li>
			<li><a href="#content-quick">콘텐츠 바로가기</a></li>
			<li><a href="#footer-quick">카피라이터 바로가기</a></li>
		</ul>
		<!-- 바디 시작 -->
		<div id="wrapper">
			<h2 class="blind"><a name="navi-quick" id="navi-quick" href="#navi-quick">메인 메뉴</a></h2>
			<!-- 헤더 -->
			<?if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">종전평화 서명 목록</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div id="boardHead">
								<span class="tblLeft"><?=$page_state;?></span>
								<span class="tblRight">[<a href="appForm.php?mode=add&appIdx=<?=$_GET[appIdx];?>">등록</a>] [<a href="excelExport.php?mode=4&<?=$excelDown;?>&appIdx=<?=$_GET[appIdx];?>">엑셀</a>]</span>
							</div>
							<div class="wList">
								<table summary="목록">
									<caption>목록</caption>
									<colgroup>
										<col width="5%" />
										<col width="10%" />
										<col width="20%" />
										<col width="35%" />
										<col width="10%" />
										<col width="10%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">순번</th>
											<th scope="col">성명</th>
											<th scope="col">주소</th>
											<th scope="col">응원한마디</th>
											<th scope="col">서명일</th>
											<th scope="col">관리</th>
										</tr>
									</thead>
									<tbody>
									<?
									if(!$total) echo "<tr><td colspan=\"8\">현재 등록/검색된 정보가 없습니다.</td></tr>";
										for($i = $first; $i <= $last; $i++) {
											foreach($view AS $key => $value) ${$key} = $value;
											$linkOption="&idx=".$view[$i][idx]."&appIdx=".$_GET[appIdx]."&page=".$_GET[page];
											$link1="<a href=\"appForm.php?mode=edit".$linkOption."\">";
											$link2="</a>";

									?>
										<tr>
											<td><?=$article_num;?></td>
											<td><?=$link1.$view[$i][sName].$link2;?></td>
											<td><?=$link1.$view[$i][aDdress].$link2;?></td>
											<td style="text-align:left;"><?=$link1.$view[$i][wordSupport].$link2;?></td>
											<td><?=$link1.strtr($view[$i][signDate],"-",".")." ".$view[$i][signTime].$link2;?></td>
											<td><a href="appForm.php?mode=edit<?=$linkOption;?>"><img src="/nwebnics/img/edit_btn.gif" alt="수정" /></a> <a href="appExe.php?mode=del<?=$linkOption;?>" onClick="return confirm('삭제하시겠습니까?');"><img src="/nwebnics/img/del_btn.gif" alt="삭제" /></a></td>
										</tr>
									<?$article_num--; }?>
									</tbody>
								</table>
							</div>
							<div id="boardTail">
								<div style="text-align:center; padding:.5em 0 1em 0;"><?=$viewPaging;?></div>
								<div class="tblRight">
									<form name="searchForm" method="get" action="<?=$_SERVER['PHP_SELF'];?>" class="cf">
										<fieldset class="searchFrm cf">
											<legend>검색</legend>
											<select name="gField" class="radiusS">
												<option value="sName"<?if($_GET[gField]=='sName') echo " selected";?>>성명</option>
												<option value="aDdress"<?if($_GET[gField]=='aDdress') echo " selected";?>>주소</option>
											</select>
											<input type="hidden" name="appIdx" value="<?=$_GET[appIdx];?>" />
											<input type="text" name="gWord" size="15" maxlength="255" title="검색 키워드 입력" value="<?=$_GET[gWord];?>" />
											<button type="submit" title="검색" value="검색" />검색</button>
										</fieldset>
									</form>
								</div>
							</div>

						</div>
						<!-- 콘텐츠 종료 -->
					</div>
					<!-- 콘텐츠 우측 -->
					<?if($Right_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Right_Inc_File);?>
				</div>
			</div>
			<!-- 주소 및 보텀 메뉴 시작 -->
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#footer-quick">주소 및 카피라이터 메뉴</a></h2>
			<?if($Foot_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Foot_Inc_File);?>
		</div>
	</body>
</html>
<?$db->disconnect();?>