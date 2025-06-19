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
?>
<!DOCTYPE <?=$doctypeSet;?>>
<!--[if lt IE 7 ]> <html class="no-js ie6 oldie" lang="<?=$languageSet;?>"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7 oldie" lang="<?=$languageSet;?>"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8 oldie" lang="<?=$languageSet;?>"> <![endif]-->
<!--[if IE 9 ]>    <html class="no-js ie9" lang="<?=$languageSet;?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" class="no-js" lang="<?=$languageSet;?>">
<!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/meanmenu.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/css.css" />
		<script type="text/javascript" src="/nwebnics/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jquery.meanmenu.js"></script>
		<script type="text/javascript" src="js/listCheck.js"></script>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script language="javascript">
		<!--
		<?=poupOpen(1);?>
		//-->
		</script>
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
			<?include $_SERVER["DOCUMENT_ROOT"]."/inc/contents_headInc.htm";?>
			<div id="contents">
				<div id="contentsView">
					<p class="tHead"><span><?=$Site_Path;?></span></p>
					<p class="tTitle"><img src="<?="/img/".str_pad($gnbPath[0],2,'0',0)."/".$_GET[code]."_title.jpg";?>" class="img_respons" alt="타이틀" /></p>

						<!-- 메인 콘텐츠 시작 -->
						<div style="width:90%; margin:0 auto; min-height:50%; max-height:100%; padding:50px 0 80px 0; vertical-align:middle; text-align:center;">

							<div class="boxShadow">
								<h3 style="line-height:150%; padding-top:50px;">법률상담 접수가 완료되었습니다. 꼭 메일 혹은 유선으로 답변 드리겠습니다.</h3>
								<p style="clear:both; padding:50px 0 20px 0; text-align:center;"><a href="/"><span class="btnStyle">홈으로 이동</span></a> <a href="/nwebnics/wBoard/write.php?code=6_1"><span class="btnStyle">법률상담 하기</span></a></p>
							</div>

						</div>
						<!-- 메인 콘텐츠 종료 -->

				</div>
				<div id="quick_right">
					<img src="/img/comm/quick_right.gif" class="img_respons" alt="상담예약" />
				</div>
			</div>
			<footer><?include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?></footer>
		</div>

		<script>
		jQuery(document).ready(function () {
			jQuery('nav').meanmenu();
		});
		</script>
	</body>
</html>
<?$db->disconnect();?>