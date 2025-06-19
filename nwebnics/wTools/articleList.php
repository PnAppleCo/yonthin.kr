<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 06. 10
//==================================================================
//== 기본정보 로드
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

//============================================================= 검색 질의 ===============================================================//
$sqlStr1 = "SELECT COUNT(idx) FROM wBoard";
$sqlStr2 = "SELECT * FROM wBoard";

if($_GET[code]) $addSql .= " code='".$_GET[code]."' AND";
if($_GET[rStatus]) $addSql .= " rStatus='".$_GET[rStatus]."' AND";
if($_GET[rDate] && !$_GET[rDates]) $addSql .= " DATE_FORMAT(rDate, '%Y-%m-%d') = '".$_GET[rDate]."' AND";
if($_GET[rDate] && $_GET[rDates]) $addSql .= " DATE_FORMAT(rDate,'%Y-%m-%d') BETWEEN DATE_FORMAT('$_GET[rDate]','%Y-%m-%d') AND DATE_FORMAT('$_GET[rDates]','%Y-%m-%d') AND";

if($_GET[gField] && $_GET[gWord]) $addSql .=" $_GET[gField] like '%$_GET[gWord]%' AND";

//== 조건 질의어 생성
if($addSql) {
	$sqlStr1 .= " WHERE".substr($addSql,0,-3);
	$sqlStr2 .= " WHERE".substr($addSql,0,-3);
}

//== 정렬필드와 차순결정

if($_GET[aField]) $alignField=$_GET[aField]; else $alignField="fid";
if($_GET[aType]) $alignType=$_GET[aType]; else $alignType="DESC";

$sqlStr2 .= " ORDER BY ".$alignField." ".$alignType.", thread ASC";
//== 다음 정렬 차순 결정
if($alignType=="DESC") $alignType="ASC"; else if($alignType=="ASC") $alignType="DESC";
//echo $sqlStr1.'<br>'.$sqlStr2;

$excelDown="sqlStr=".urlencode($sqlStr2)."&rType=".$_GET[rType];
//==================================================================질의 종료 ================================================================//

include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/listInc.php");
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
						<h3 id="headTitle">게시물 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div id="boardHead">
								<span class="tblLeft">총<?=$listTotal;?>개</span>
								<span class="tblRight">[<a href="articleForm.php?mode=add&appIdx=<?=$_GET[appIdx];?>">등록</a>] <!-- [<a href="excelExport.php?mode=2&<?=$excelDown;?>&appIdx=<?=$_GET[appIdx];?>">엑셀</a>]</span> -->
							</div>
							<div class="wList">
								<table summary="목록">
									<caption>목록</caption>
									<colgroup>
										<col width="10%" />
										<col width="10%" />
										<col width="50%" />
										<col width="10%" />
										<col width="10%" />
										<col width="10%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">순번</th>
											<th scope="col">게시판명</th>
											<th scope="col">제 목</th>
											<th scope="col">등록자</th>
											<th scope="col">등록일자</th>
											<th scope="col">관리</th>
										</tr>
									</thead>
									<tbody>
										<?

										//== 게시판명
										function boardName($bCode) {
											global $db;
											$sqlStr = "SELECT board_summary FROM wboardConfig WHERE code='".$bCode."'";
											$codeName = $db->getOne($sqlStr);
											if(DB::isError($codeName)) die($codeName->getMessage());
											return $codeName;
										}

										$res =& $db->limitQuery($sqlStr2, $limit_idx, $page_set);
										for($i=0; $view =& $res->fetchRow(DB_FETCHMODE_ASSOC); $i++) {
											//foreach($view AS $key => $value) ${$key} = $value;
											$linkOption="idx=".$view[idx]."&code=".$view[code]."&page=".$_GET[page];
											$linkUrl="articleForm.php?".$linkOption;
											if(!$view[name]) $view[name]="관리자";
											//== 이미지
											if(file_exists($_SERVER["DOCUMENT_ROOT"].$view["filename0"])) $imgPath="/img/03/no-image.gif"; else $imgPath=$appDir.$view["filename0"];
											if(mb_strlen($view[hAddress1], 'UTF-8') > 24) $view[hAddress1] = han_cut($view[hAddress1], 24, "..");
										?>
										<tr>
											<td><?=$listIdx;?></td>
											<td><?=$link1.boardName($view[code]).$link2;?></td>
											<td style="text-align:left;"><?=$link1.$view[subject].$link2;?></td>
											<td><?=$link1.$view[name].$link2;?></td>
											<td><?=$link1.strtr($view[signdate],"-",".").$link2;?></td>
											<td><a href="articleForm.php?mode=edit&<?=$linkOption;?>"><img src="/nwebnics/img/edit_btn.gif" alt="수정" /></a> <a href="articleExe.php?mode=del&<?=$linkOption;?>" onClick="return confirm('삭제하시겠습니까?');"><img src="/nwebnics/img/del_btn.gif" alt="삭제" /></a></td>
										</tr>
										<?
										$listIdx--; }
										if($i<=0) echo "<td colspan=\"8\">등록(검색)된 게시물이 없습니다.</td>";
										?>
									</body>
								</table>
							</div>

							<div id="boardPage">
								<div style="text-align:center; padding:.5em 0 1em 0;"><?=$pagePrint;?></div>
								<div class="tblRight">
									<form name="searchForm" method="get" action="<?=$_SERVER['PHP_SELF'];?>" class="cf">
										<fieldset class="searchFrm cf">
											<legend>검색</legend>
											<select name="code" class="wSbox">
												<option value="">선택하세요</option>
												<?=boardCode($_GET[code]);?>
											</select>
											<select name="gField" class="radiusS">
												<option value="subject"<?if($_GET[gField]=='subject') echo " selected";?>>제목</option>
												<option value="ucontents"<?if($_GET[gField]=='ucontents') echo " selected";?>>내용</option>
												<option value="name"<?if($_GET[gField]=='name') echo " selected";?>>작성자</option>
											</select>
											<input type="text" name="gWord" size="15" maxlength="255" title="검색 키워드 입력" value="<?=$_GET[gWord];?>" />
											<button type="submit" title="검색" value="검색" />검색</button>
										</fieldset>
									</form>
								</div>
							</div>
<br />
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