<?PHP
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'jisuk'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

//== 게시판 code 체크
if(!$_GET['code']) js_action(1, "게시판코드를 찾을수 없습니다.", "/", -1);
if(!$_GET['idx']) js_action(1,"답변할 글의 idx를 찾을수 없습니다.","",-1);

//== 접근 권한 설정
include ("inc/levelCheck_Inc.php");

//== 선택한 게시물 질의
$sql_str="SELECT * FROM $b_cfg_tb[1] WHERE code = '$_GET[code]' AND idx = $_GET[idx]";
$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
if(DB::isError($view)) die($view->getMessage());

//== 제목과 내용의 문자열 원상복구
$view['subject'] = stripslashes($view['subject']);
$view['ucontents'] = stripslashes($view['ucontents']);

//== 중복되는 "[RE]"를 제거 +//
// PHP82 변환
// $view['subject'] = eregi_replace("^\[RE\] ", "",$view['subject']);
$view['subject'] = str_replace("^\[RE\] ", "",$view['subject']);

//== 원글과 답변글을 구분하기 위해 원글의 각 줄앞에 콜론(:)을 추가하여 출력
$view['ucontents'] = ":" . $view['ucontents'];
// PHP82 변환
// $view['ucontents'] = eregi_replace("\n", "\n:", $view['ucontents']);
$view['ucontents'] = str_replace("\n", "\n:", $view['ucontents']);
$view['ucontents'] = $view['name'] . "님의 글입니다.\n\n" . $view['ucontents'];
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
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi"> -->
		<title><?=$Title_Txt;?></title>

		<!-- ROBOTS SET -->
		<meta name="robots" content="none" />
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

		<link rel="shortcut icon" href="/img/comm/favicon.ico">
		<!--FontAwesome-->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/media.css" media="all"/>
		<script type="text/javascript" src="/js/jquery-1.12.1.min.js"></script>
		<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="js/writeCheck.js"></script>
		<!-- <script type="text/javascript" src="/js/jquery-migrate.min.js"></script> -->
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
			<?PHP include $_SERVER["DOCUMENT_ROOT"]."/inc/contents_headInc.htm";?>
			<!-- 헤더 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">콘텐츠</a></h2>
			<!-- 콘텐츠 섹션 시작 -->
			<div id="contentsWrap">
				<div id="contentsArea">

					<!-- 로컬 메뉴 섹션 시작 -->
					<?PHP include $_SERVER["DOCUMENT_ROOT"]."/inc/lnbInc.htm";?>
					<!-- 로컬 메뉴 섹션 종료 -->

					<!-- 콘텐츠 시작 -->
					<div id="contentsView">
						<div id="titleWrap">
							<!-- <div id="contentsDepth"><?=$Site_Path;?></div> -->
							<h3 id="contentsTitle"><?=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];?></h3>
						</div>
						<div id="contentsPrint">
						<!-- 메인 콘텐츠 시작 -->
						<?PHP if($board_info['skin']) require "./skin/".$board_info['skin']."/write_skin.php"; else error_view(999, "스킨이 선택되지 않았습니다.","관리자에게 문의 하세요.");?>
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
			<?PHP include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>

		<div id="gotop" class="gotop">
			<div></div>
		</div>
	</body>
</html>
<?PHP addCount(); $db->disconnect();?>