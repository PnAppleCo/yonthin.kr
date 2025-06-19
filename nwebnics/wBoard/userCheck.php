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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/css.css" />
		<script type="text/javascript" src="/nwebnics/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
		<script language=javascript>
			var siren_window;
			function openSiren24(){
				siren_window = window.open('', 'authWindow', 'width=410, height=450, resizable=0, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );
				document.sirenForm.action = 'https://name.siren24.com/vname/jsp/vname_j10.jsp';  // 가상식별 실명확인서비스 URL
				document.sirenForm.target = 'authWindow';
				document.sirenForm.submit();
			}

			function openIpin() {
				wWidth = 360;
				wHight = 120;
				wX = (window.screen.width - wWidth) / 2;
				wY = (window.screen.height - wHight) / 2;
				var w = window.open("http://www.gmbo.kr/auth/g-pin/AuthRequest.php?return_url=http://www.gmbo.kr/", "authWindow", "directories=no,toolbar=no,left="+wX+",top="+wY+",width="+wWidth+",height="+wHight);
			}
		</script>
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
						<p id="siteDepth"><?=$Site_Path;?></p>
						<h3 id="headTitle"><img src="<?=$Title_Bar_Image;?>" /></h3>
						<!-- 콘텐츠 시작 -->
						<p id="contentsBody">

							<div id="usement">
								<h3><img src="img/usement.gif" alt="게시판 이용안내" /></h3>
								<p>이 게시판은 누구나 자유롭게 의견을 게시할 수 있는 열린공간이며, 자율과 책임이 공존하는 토론문화 조성을 위하여 <strong>실명인증제</strong>로 운영하고 있습니다.<br /><br />
									상업성 광고, 저속한 표현, 특정인에 대한 비방, 정치적 목적이나 성향, 반복성이 있는 게시물등은 관리자에 의해 통보없이 삭제될 수 있으며, 홈페이지를 통하여 <strong>불법유해 정보를 게시하거나 배포</strong>하면 정보통신망이용촉진 및 정보보호등에 관한 법률 제 74조에 따라 <strong>1년이하의 징역 또는 1천만원 이하의 벌금</strong>에 처해질 수 있습니다.<br /><br />
									여러분의 개인정보보호를 위하여 가상식별번호 방식과 공공I-PIN 방식의 실명인증을 실시하고 있음으로 조금 불편하시더라도 양의 부탁드립니다.
								</p>
							</div>
							<div id="div-wrap">
								<div id="siren21">
									<h3 style="text-align:center;"><a href="javascript:openSiren24();"><img src="img/siren21.gif" alt="실명인증" style="vertical-align:middle;" /></a></h3>
									<ul>
										<li>타인의 주민등록번호를 도용하여 부정사용하는 자는 주민등록법 제21조에 의거 3년 이하의 징역 또는 1천만원 이하의 벌금이 부과될 수 있습니다.</li>
										<li>실명인증이 되지 않는 경우, 서울신용평가정보(주)로 문의하시기 바랍니다. - 전화 : 1566-1006</li>
									</ul>
									<form name="sirenForm" method="post">
										<input type="hidden" name="id" value="KUN001"><!--회원사 아이디-->
										<input type="hidden" name="reqNum" value="2381"><!--요청번호(영문,숫자 혼합 30자 이내, 대소문자 구분)-->
										<input type="hidden" name="retUrl" value = "http://www.gmbo.kr/nwebnics/wBoard/write.php?code=<?=$_GET[code];?>">
									</form>
								</div>
								<div id="i-pin">
									<h3 style="text-align:center;"><a href="javascript:openIpin();"><img src="img/i-pin.gif" alt="공공I-PIN 인증" /></a></h3>
									<ul>
										<li>공공 I-PIN은 Government Personal Identification Number의 약자로 인터넷상 개인식별번호를 의미합니다.</li>
										<li>공공 I-PIN은 인터넷에서 주민등록번호를 사용하지 않고도 본인임을 확인할수 있는 수단입니다.</li>
									</ul>
								</div>
							</div>

						</p>
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