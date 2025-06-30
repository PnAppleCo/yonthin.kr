<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danah'
//== last modify date : 2016. 03. 05
//==================================================================
//== 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

if(!login_session()) redirect(1, "/", "회원 로그인후 이용하세요.", 1);
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
		<!-- META TAG COMMON -->
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<title><?=$Title_Txt;?></title>

		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" /> -->
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
		<meta name="twitter:image" content="http://<?=$siteDomain;?>/img/comm/og.jpg" />
		<!-- END TWITTERCARD -->

		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
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
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/contents_headInc.htm";?>
			<!-- 헤더 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">콘텐츠</a></h2>
			<!-- 콘텐츠 섹션 시작 -->
			<div id="contentsWrap">
				<div id="contentsArea">
					<!-- 로컬 메뉴 섹션 시작 -->
					<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/lnbInc.htm";?>
					<!-- 로컬 메뉴 섹션 종료 -->

					<!-- 콘텐츠 시작 -->
					<div id="contentsView">
						<div id="titleWrap">
							<div id="contentsDepth"><?=$Site_Path;?></div>
							<h3 id="contentsTitle"><?=$cTitle;?></h3>
						</div>
						<div id="contentsPrint">

							<h3 class="title1">아이누리 신청 내역</h3>
							<div class="tblList">
								<table summary="목록">
									<caption>목록</caption>
									<colgroup>
										<col width="10%" />
										<col width="15%" />
										<col width="50%" />
										<col width="10%" />
										<col width="15%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">순번</th>
											<th scope="col">구분</th>
											<th scope="col">예약자명/사용자명</th>
											<th scope="col">예약일자</th>
											<th scope="col">예약상태</th>
										</tr>
									</thead>
									<tbody>
									<?=inuriList($_SESSION['my_id']);?>
									</tbody>
								</table>
							</div>
							<br />
							<h3 class="title1">교육 및 행사 신청 내역</h3>
							<div class="tblList">
								<table summary="목록">
									<caption>목록</caption>
									<colgroup>
										<col width="10%" />
										<col width="15%" />
										<col width="50%" />
										<col width="10%" />
										<col width="15%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col">순번</th>
											<th scope="col">구분</th>
											<th scope="col">교 육 명</th>
											<th scope="col">교육일자</th>
											<th scope="col">접수상태</th>
										</tr>
									</thead>
									<tbody>
									<?=edueventList($_SESSION['my_id']);?>
									</tbody>
								</table>
								<p style="text-align:right; padding:1em 0; color:#FD544F;">※ 예약 취소 시, 당일 취소는 불가능합니다.<br />※ 복구 요청은 군산시육아종합지원센터로 문의바랍니다.</p>
							</div>

						</div>
					</div>
					<!-- 콘텐츠 종료 -->
				</div>
			</div>
			<!-- 콘텐츠 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#navi-quick">카피라이터</a></h2>
			<!-- 풋터 섹션 시작 -->
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>
		<div id="gotop" class="gotop">
			<div></div>
		</div>
	</body>
</html>
<?php addCount(); $db->disconnect();?>