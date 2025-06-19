<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danah'
//== last modify date : 2016. 06. 05
//==================================================================
//== 기본정보 로드
include ("inc/configInc.php");
include ("inc/staticsInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

if(isset($_GET[nYear])) $vYear=$_GET[nYear]; else $vYear=date('Y');
if(isset($_GET[nMonth])) $vMonth=$_GET[nMonth]; else $vMonth=date('m');
if(isset($_GET[nDay])) $vDay=$_GET[nDay]; else $vDay=date('d');

//============================================================= 검색 질의 ===============================================================//
$sqlStr = "SELECT COUNT(idx) AS vCount, idx, siteCode, pageUrl, visitDate FROM wStatics";

if($_GET[nYear] && $_GET[nMonth]) {
	$sDate=$_GET[nYear]."-".sprintf('%02d',$_GET[nMonth]);
	$addSql .= " DATE_FORMAT(visitDate,'%Y-%m') = '".$sDate."' AND";
}else if($_GET[nYear] && !$_GET[nMonth]) {
	$sDate=$_GET[nYear];
	$addSql .= " DATE_FORMAT(visitDate,'%Y') = '".$sDate."' AND";
}else if(!$_GET[nYear] && $_GET[nMonth]) {
	$sDate=sprintf('%02d',$_GET[nMonth]);
	$addSql .= " DATE_FORMAT(visitDate,'%m') = '".$sDate."' AND";
}

//== 조건 질의어 생성
if($addSql) {
	$sqlStr .= " WHERE".substr($addSql,0,-3);
}

//== 그룹 정렬
$sqlStr .= " GROUP BY siteCode ORDER BY vCount DESC";

$result = $db->query($sqlStr);
if(DB::isError($result)) die($result->getMessage());


//==================================================================질의 종료 ================================================================//

?>
<!DOCTYPE <?=$doctypeSet;?>>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="robots" content="noindex,nofollow" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
						<h3 id="headTitle">페이지뷰</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div style="margin-bottom:10px;">
								<form name="dForm" method="GET" action="<?=$_SERVER[PHP_SELF];?>">
									<table class="offLineTbl" summary="페이지뷰 검색">
										<caption>페이지뷰 검색</caption>
										<colgroup>
											<col width="100%" />
										</colgroup>
										<tbody>
										<tr>
											<td>
												<select name="nYear" class="selectbox">
													<option value="">--년--</option>
														<?for($i=2016; $i<=date('Y'); $i++) {
																if(isset($_GET[nYear])) $yearS=$_GET[nYear];
																if($yearS==$i) $ySelected=" selected"; else $ySelected="";
																echo "<option value=\"$i\"".$ySelected.">$i</option>";
															}?>
												</select>
												<select name="nMonth" class="selectbox">
													<option value="">--월--</option>
														<?for($i=1; $i<=12;$i++) {
																if(isset($_GET[nMonth])) $monthS=$_GET[nMonth];
																if($monthS==$i) $mSelected=" selected"; else $mSelected="";
																echo "<option value=\"".sprintf('%02d',$i)."\"".$mSelected.">$i</option>";
															}?>
													</select>
													<input type="image" src="../wTools/img/search.gif" style="vertical-align:middle;" alt="검색하기" />
											</td>
										</tr>
										</tbody>
									</table>
								</form>
							</div>

							<div class="wList">
								<table summary="목록">
									<caption>목록</caption>
									<colgroup>
										<col width="10%" />
										<col width="40%" />
										<col width="10%" />
										<col width="40%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">순번</th>
											<th scope="col">콘텐츠명</th>
											<th scope="col">뷰카운트</th>
											<th scope="col">접속URL</th>
										</tr>
									</thead>
									<tbody>
									<?
										$i=1;
										while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
											if(!$view) echo "<tr><td colspan=\"7\">현재 등록/검색된 페이지뷰 정보가 없습니다.</td></tr>";
											$link1="<a href=\"".$view[pageUrl]."\" target=\"_blank\">";
											$link2="</a>";
											//== 위치 네비게이션
											$gnbCode=explode("_",$view[siteCode]);
											$sitePath=$gnb_Arr[$gnbCode[0]];
											if($gnbCode[1]) $sitePath .= " > ".$lnb_Arr[$gnbCode[0]][$gnbCode[1]];
											if($gnbCode[2]) $sitePath .= " > ".$cnb_Arr[$gnbCode[0]][$gnbCode[1]][$gnbCode[2]];
											if(!$sitePath) $sitePath="인덱스";
											if(mb_strlen($view[pageUrl], 'UTF-8') > 50) $view[pageUrl] = han_cut($view[pageUrl], 50, "..");
									?>
										<tr>
											<td><?=$i;?></td>
											<td class="ListAlign"><?=$link1.$sitePath.$link2;?></td>
											<td><?=$link1.$view[vCount].$link2;?></td>
											<td class="ListAlign"><?=$link1.$view[pageUrl].$link2;?></td>
										</tr>
									<?$i++; }?>
									</tbody>
								</table>
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