<?php

//== 기본정보 로드
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 콘텐츠 로드
if(!$_GET['code']) js_action(1,"중요정보를 찾을 수 없습니다. 관리자에게 문의하세요.","",-1);

?>
<!DOCTYPE <?=$doctypeSet;?>>
<!--[if lt IE 7 ]><html class="no-js ie6 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8 oldie" lang="<?=$languageSet;?>"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" lang="<?=$languageSet;?>"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" class="no-js" lang="<?=$languageSet;?>">
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
		<script type="text/javascript">
			function checkReview()
			{
				alert("후기공모전 공고기간이 아닙니다.");
			}

			function onVisible(id)
			{
				if(id == "2024"){
					//2024수상작 보이기
					document.getElementById("2024gd").style.display = "flex";
					document.getElementById("2023gd").style.display = "none";

					document.getElementById("2024btn").className = "on";
					document.getElementById("2023btn").className = "";
				}
				else{
					//2023수상작 보이기
					document.getElementById("2024gd").style.display = "none";
					document.getElementById("2023gd").style.display = "flex";

					document.getElementById("2024btn").className = "";
					document.getElementById("2023btn").className = "on";
				}
			}
			window.onload=function()
			{
				// 현재 페이지의 URL에서 파라미터 부분(쿼리 문자열)을 가져옵니다.
				var urlParams = new URLSearchParams(window.location.search);

				// 'name'이라는 파라미터 값을 가져옵니다.
				var id = urlParams.get('id').substr(0,4);

				if(id== "2023") onVisible(id);
			}
		</script>
	</head>
    <body>
        <div id="layoutWrap">
			<hr/>
			<h2 class="blind"><a name="navi-quick" id="navi-quick" href="#navi-quick">메인 메뉴</a></h2>
			<!-- 헤더 섹션 시작 -->
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/headInc.htm";?>
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
					 <?php

					 	$loadFile=$_SERVER["DOCUMENT_ROOT"]."/contents/".str_pad($gnbPath[0],2,'0',0)."/".$_GET['code'].".htm";
						
						if(file_exists($loadFile))
						{
							include_once($loadFile); 
						}
						else
						{
							include_once($_SERVER["DOCUMENT_ROOT"]."/inc/no_File.htm");
						}
							
					 ?>
					 
					<!-- 콘텐츠 종료-->
				</div>
			</div>
			<!-- 콘텐츠 섹션 종료 -->

			<hr/>
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#navi-quick">카피라이터</a></h2>
			<!-- 풋터 섹션 시작 -->
			<?php include $_SERVER["DOCUMENT_ROOT"]."/inc/footInc.htm";?>
			<!-- 풋터 섹션 종료 -->
		</div>
    </body>
</html>
<?php addCount(); $db->disconnect(); ?>


