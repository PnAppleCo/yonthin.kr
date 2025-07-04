<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'boram'
//== last modify date : 2018. 06. 26
//==================================================================
//== 기본정보 로드
//include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

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
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="Title" content="웹닉스 솔루션 설치" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
		<!-- ROBOTS SET -->
		<meta name="robots" content="index, follow" />
		<!-- NAVER -->
		<meta name="naver-site-verification" content="795d8624d184b95f3a77496e5647787fe9f329e8"/>
		<!-- BEGIN OPENGRAPH -->
		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="<?=$siteName;?>" />
		<meta property="og:title" content="웹닉스 솔루션 설치" />
		<meta property="og:description" content="<?=$Description_Txt;?>" />
		<meta property="og:image" content="<?=$snsImage;?>" />
		<meta property="og:url" content="<?=$snsUrl;?>" />
		<!-- END OPENGRAPH -->

		<!-- BEGIN TWITTERCARD -->
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@WEBSITE" />
		<meta name="twitter:title" content="웹닉스 솔루션 설치" />
		<meta name="twitter:description" content="<?=$Description_Txt;?>" />
		<meta name="twitter:url" content="<?=$snsUrl;?>" />
		<meta name="twitter:image" content="<?=$snsImage;?>" />
		<!-- END TWITTERCARD -->

		<title>웹닉스 솔루션 설치</title>
		<link rel="canonical" href="http://<?=$siteDomain;?>" />
		<link rel="shortcut icon" href="/img/comm/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/jquery.bxslider.css" media="all" />

		<!--FontAwesome-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
		<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="/js/jquery.slides.min.js"></script>
		<script type="text/javascript" src="/js/jcarousellite_1.0.1.js"></script>
		<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/js/jquery.bxslider.min.js"></script>
		<script type="text/javascript" src="/js/common_adm.js"></script>
		<script type="text/javascript" src="/js/placeholders.min.js"></script>
		<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>

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
			<?//include $_SERVER["DOCUMENT_ROOT"]."/inc/contents_headInc.htm";?>
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
							<h3 id="contentsTitle" style="clear:both;"><?=$cTitle;?></h3>
							<div id="contentsDepth"><?//=$Site_Path;?></div>
						</div>
						<div id="contentsPrint">

							<div class="pop-container">
								<div class="pop-conts">
									<form name="installForm">
									<p class="ctxt mb20"></p>
										<div class="localTitles" style="postion:relative; width:100%; height:56px;margin:0 auto; background:#5789BF;">
											<div style="color:#fff; font-family:arial; font-size:30px; padding:20px 13px; font-weight:bold;">INSTALL</div>
										</div>
										<div style="clear:both; margin-top:20px;">
											<dl>
												<dt style="text-align:center;"><input type="text" name="mId" size="15" maxlength="20" class="id_blur" style="width:192px; padding:6px 5px; font-size:13px;" value="" onfocus="this.className='id_focus'" onblur="if ( this.value == '' ) { this.className='id_blur' }" placeholder="관리자 아이디" /></dt>
												<dt style="text-align:center;"><input type="password" name="pass" size="15" maxlength="20" style="width:192px; padding:6px 5px; font-size:13px; margin-top:10px;" onfocus="this.className='pw_focus'" onblur="if ( this.value == '' ) { this.className='pw_blur' }" onkeydown="if (event.keyCode == 13) installStart();" class="pw_blur" placeholder="관리자 비밀번호" /></dt>
											</dl><br />
											<div class="btn-r" style="text-align:center;">
												<a href="#" onclick="installStart();" class="cbtn1" style="width:174px; text-align:center; font-weight:bold; line-height:230%; border-radius:3px;">설치</a>
											</div>
										</div>
									</form>
								</div>
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
			<?//include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>
		<div id="gotop" class="gotop">
			<div></div>
		</div>

	</body>
</html>