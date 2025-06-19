<?
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 01
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

//== 게시판 code 체크
if(!$_GET[code]) js_action(1, "게시판코드를 찾을수 없습니다.", "/", -1);

//== 접근 권한 설정
include ("inc/levelCheck_Inc.php");

//== GET 디코드
foreach($_GET AS $key=>$val) $key."=".urldecode($val);

//== 전체 게시물 질의어 생성
$sql_str_1 = "SELECT COUNT(idx) from $b_cfg_tb[1] WHERE code='$_GET[code]' AND notice != 1";
$sql_str_2 = "SELECT * FROM $b_cfg_tb[1] WHERE code='$_GET[code]' AND notice != 1";

//== 승인형게시판
if($board_info[approve]>0 && (member_session(1)==false && $board_info[adminid] != $_SESSION[my_id])) {
	$sql_str_1 .= " AND approve > 0";
	$sql_str_2 .= " AND approve > 0";
}

//== 카테고리별 검색
if($board_info[board_class] && $_GET[b_class]) {
	$sql_str_1 .= " AND b_class='$_GET[b_class]'";
	$sql_str_2 .= " AND b_class='$_GET[b_class]'";
}

if($_GET[keyword] && ($_GET[s_1] || $_GET[s_2] || $_GET[s_3])) {
	$sql_str_1 .= " AND";
	$sql_str_2 .= " AND";
	if($_GET[s_1]) {
		$sql_str_1 .= " $_GET[s_1] LIKE '%$_GET[keyword]%' OR";
		$sql_str_2 .= " $_GET[s_1] LIKE '%$_GET[keyword]%' OR";
	}
	if($_GET[s_2]) {
		$sql_str_1 .= " $_GET[s_2] LIKE '%$_GET[keyword]%' OR";
		$sql_str_2 .= " $_GET[s_2] LIKE '%$_GET[keyword]%' OR";
	}
	if($_GET[s_3]) {
		$sql_str_1 .= " $_GET[s_3] LIKE '%$_GET[keyword]%' OR";
		$sql_str_2 .= " $_GET[s_3] LIKE '%$_GET[keyword]%' OR";
	}
	$sql_str_1 = substr($sql_str_1,0,-2);
	$sql_str_2 = substr($sql_str_2,0,-2);
}

if($_GET[code]=="6_1" OR $_GET[code]=="4_1") {
	$_GET[align_record]="signdate";
	$_GET[align_type]="DESC";
}

//== 정렬기준
if($board_info[alignField]=='1') $alignRecord="idx"; else if($board_info[alignField]=='2') $alignRecord="signdate"; else $alignRecord="fid";
if($_GET[align_type]) $align_type=$_GET[align_type]; else $align_type="DESC";
if($_GET[align_record]) $align_record=$_GET[align_record]; else $align_record=$alignRecord;
if($board_info[alignField]=='2') $addAlign=", signtime DESC";
$sql_str_2 .= " ORDER BY ".$align_record." ".$align_type.$addAlign.", thread ASC";

//echo $sql_str_2;
//== 총 레코드수 추출
$B_total = $db->getOne($sql_str_1);
if(DB::isError($B_total)) die($B_total->getMessage());

if(!$_GET[page]) $_GET[page] = 1;
if(!$B_total) {
		$first = 1;
		$last = 0;
	}else {
		$first = $board_info[num_per_page]*($_GET[page]-1);
		$last = $board_info[num_per_page]*$_GET[page];
		$next = $B_total - $last;
		if($next > 0) {
			$last -= 1;
		}else {
			$last = $B_total - 1;
		}
	}

	//== 총 페이지수 계산
	$total_page = ceil($B_total/$board_info[num_per_page]);
	//== 레코드 개수에 따른 일련번호 설정
	$article_num = $B_total - $board_info[num_per_page]*($_GET[page]-1);
	//== 오늘 등록된 게시물
	$sql_str = "SELECT COUNT(idx) FROM $b_cfg_tb[1] WHERE code='$_GET[code]' AND signdate=NOW()";
	$today = $db->getOne($sql_str);
	if(DB::isError($today)) die($today->getMessage());

	$time_limit = 60*60*24*1;
	//== 전체 리스트 질의
	$view = $db->getAll($sql_str_2,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	//== 페이지 현황정보
	$page_state="전체:".$B_total." 오늘:".$today."&nbsp;";
	if(!$_GET[keyword] && !$_GET[keyfield]) {
		$page_state .= "페이지:".$_GET[page]."/".$total_page;
	}else {
		$page_state .= "검색결과:".$_GET[page]."/".$total_page;
	}


	//== 총게시물/검색 내용확인
	if($_GET[keyword] && ($_GET[s_1] || $_GET[s_2] || $_GET[s_3])) {
		if($B_total>0) {
			$rst_print="<span style=\"color:#FF6633;\"><strong>\"".$_GET[keyword]."\"</strong></span>(으)로 게시물 <span style=\"color:#FF6633;\"><strong>".$B_total."</strong></span>개를 검색하였습니다.&nbsp;&nbsp;&nbsp;<a href=\"list.php?code=".$_GET[code]."\">전체게시물보기</a>";
		}else {
			$rst_print="<span style=\"color:#FF6633\"><strong>\"".$_GET[keyword]."\"</strong></span>(으)로 검색된 게시물이 없습니다.&nbsp;&nbsp;&nbsp;<a href=\"list.php?code=".$_GET[code]."\">전체게시물보기</a>";
		}
	}else {
		if($B_total<=0) $rst_print="현재 등록된 게시물이 없습니다.";
	}

	//== 페이징
	$paging = new paging();
	$view_paging=$paging->page_display($B_total,$board_info[num_per_page], $board_info[num_per_block],$next);
?>
<!DOCTYPE <?=$doctypeSet;?>>
<!--[if lt IE 7 ]><html class="no-js ie6 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" lang="<?=$languageSet;?>"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" class="no-js" lang="<?=$languageSet;?>">
<!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
		<!-- ROBOTS SET -->

		<meta name="robots" content="index, follow" />
		<!-- BEGIN OPENGRAPH -->
		<meta name="naver-site-verification" content="101cc8a804a8b30ac8d82b8a767bace7926417b2" />
		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="<?=$siteName;?>" >
		<meta property="og:title" content="<?=$Title_Txt;?>" />
		<meta property="og:description" content="<?=$Description_Txt;?>" />
		<meta property="og:image" content="http://<?=$siteDomain;?>/img/comm/og.jpg" />
		<meta property="og:url" content="http://<?=$siteDomain;?>" />
		<!-- END OPENGRAPH -->

		<!-- BEGIN TWITTERCARD -->
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@WEBSITE" />
		<meta name="twitter:title" content="<?=$Title_Txt;?>" />
		<meta name="twitter:description" content="<?=$Description_Txt;?>" />
		<meta name="twitter:url" content="http://<?=$siteDomain;?>" />
		<meta name="twitter:image" content="http://<?=$siteDomain;?>/iimg/comm/og.jpg" />
		<!-- END TWITTERCARD -->

		<title><?=$Title_Txt;?></title>
		<link rel="shortcut icon" href="/img/comm/favicon.ico">
		<!--FontAwesome-->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<script type="text/javascript" src="/js/jquery-1.12.1.min.js"></script>
		<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="js/listCheck.js"></script>
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/js/css3-mediaqueries.js"></script>
		<script type="text/javascript" src="/js/respond.min.js"></script>
		<script type="text/javascript" src="/js/html5shiv.min.js"></script>
		<![endif]-->
		<!--[if lte IE 6]><script type="text/javascript">location.href='/NoticeIE6.htm';</script><![endif]-->
	</head>
	<body>
		<h1 class="blind"><?=$siteName;?> 홈페이지에 오신것을 환영합니다.</h1>
		<hr/>
		<!-- 스킵 바로가기 메뉴 -->
		<ul id="skipmenu">
			<li><a href="#navi-quick">메인메뉴 바로가기</a></li>
			<li><a href="#content-quick">콘텐츠 바로가기</a></li>
			<li><a href="#footer-quick">카피라이터 바로가기</a></li>
		</ul>
		<div id="layoutWrap">
			<hr/>
			<h2 class="blind"><a name="navi-quick" id="navi-quick" href="#navi-quick">메인 메뉴</a></h2>
			<!-- 헤더 섹션 시작 -->
			<?include $_SERVER["DOCUMENT_ROOT"]."/inc/headInc.htm";?>
			<!-- 헤더 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">콘텐츠</a></h2>
			<!-- 콘텐츠 섹션 시작 -->
			<div id="contentsWrap">
				<div id="contentsArea">

					<!-- 로컬 메뉴 섹션 시작 -->
					<?include $_SERVER["DOCUMENT_ROOT"]."/inc/lnbInc.htm";?>
					<!-- 로컬 메뉴 섹션 종료 -->

					<!-- 콘텐츠 시작 -->
					<div id="contentsView">
						<div id="contentsPrint">
						<!-- 메인 콘텐츠 시작 -->
						<?if($board_info[skin]) require "./skin/".$board_info[skin]."/list_skin.php"; else error_view(999, "스킨이 선택되지 않았습니다.","관리자에게 문의 하세요.");?>
						<!-- 메인 콘텐츠 종료 -->
						</div>

					</div>
					<!-- 콘텐츠 종료 -->

				</div>
			</div>
			<!-- 콘텐츠 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#navi-quick">카피라이터</a></h2>
			<!-- 풋터 섹션 시작 -->
			<?include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>

		<div id="gotop" class="gotop">
			<div></div>
		</div>

	</body>
</html>
<?addCount(); $db->disconnect();?>