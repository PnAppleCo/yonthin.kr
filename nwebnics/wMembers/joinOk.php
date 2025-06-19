<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2014. 11. 12
//==================================================================
//== 게시판 기본정보 로드
session_cache_limiter('nocache, must-revalidate');
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 작업모드 설정/체크
if(!$_GET[mode]) {
	js_action(1,"작업모드를 찾을수 없습니다.","",-1);
}else {
	if($_GET[mode]==="edit") {
		//== 수정할 회원 질의
		$sql_str="SELECT * FROM wMember WHERE";
		if(member_session(2)==true) {
			if($_GET[idx]) $sql_str .= " idx=$_GET[idx]"; else $sql_str .= " idx='$_SESSION[my_idx]' and id='$_SESSION[my_id]'";
		}else if(login_session()==true) {
			$sql_str .= " idx='$_SESSION[my_idx]' and id='$_SESSION[my_id]'";
		}else {
			js_action(1,"로그인후 이용하세요.","",-1);
		}
		$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
		if(DB::isError($view)) die($view->getMessage());
		//== 데이터 분리
	$jumin=substr($view[jumin2],0,1);
	$view[ment]=stripslashes($view[ment]);
	$view[ment]=htmlspecialchars($view[ment]);
	$view[ment]=nl2br($view[ment]);
	$arrEmail=explode("@",$view[email]);
	$arrTel=explode("-",$view[tel]);
	$arrSel=explode("-",$view[sel]);
	$v_birth=explode("-",$view[birthday]);
		if(!$view[idx] && !$view[id]) error_view(999, "죄송합니다. 고객님의 정보를 찾을수 없습니다.","로그아웃후에 다시 로그인하세요.");
	}
}

//== 자동가입 랜덤 이미지
$Rst_Str=explode(":", Not_Aoto_Join(10, 5));
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
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0">
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/css.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/css/responsive.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" media="all"/>
		<script type="text/javascript" src="/nwebnics/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jquery.slides.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jcarousellite_1.0.1.js"></script>
		<script type="text/javascript" src="/nwebnics/js/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
		<script type="text/javascript" src="js/formCheck.js"></script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script>
		<script>
			/*
			function openDaumPostcode() {
				new daum.Postcode({
					oncomplete: function(data) {
						// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
						// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
						document.getElementById('hzipcode1').value = data.postcode1;
						document.getElementById('hzipcode2').value = data.postcode2;
						document.getElementById('haddress1').value = data.address;
						//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
						//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
						//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
						//document.getElementById('addr').value = addr;
						document.getElementById('haddress2').focus();
					}
				}).open();
			}
			*/
			// 우편번호 찾기 iframe을 넣을 element
			var element = document.getElementById('iwrap');
			function foldDaumPostcode() {
					// iframe을 넣은 element를 안보이게 한다.
					var element = document.getElementById('iwrap');
					element.style.display = 'none';
			}
			function expandDaumPostcode() {
					// 현재 scroll 위치를 저장해놓는다.
					var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
					var element = document.getElementById('iwrap');
					new daum.Postcode({
							oncomplete: function(data) {
									// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
									// 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
									document.getElementById('hzipcode1').value = data.postcode1;
									document.getElementById('hzipcode2').value = data.postcode2;
									document.getElementById('haddress1').value = data.address;
									// iframe을 넣은 element를 안보이게 한다.
									element.style.display = 'none';
									// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
									document.body.scrollTop = currentScroll;
							},
							// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
							// iframe을 넣은 element의 높이값을 조정한다.
							onresize : function(size) {
									element.style.height = size.height + "px";
							},
							width : '100%',
							height : '100%'
					}).embed(element);
					// iframe을 넣은 element를 보이게 한다.
					element.style.display = 'block';
			}
		</script>
		<!-- <script type="text/javascript" src="/nwebnics/js/jquery-migrate.min.js"></script> -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/nwebnics/js/css3-mediaqueries.js"></script>
		<script type="text/javascript" src="/nwebnics/js/respond.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/html5shiv.min.js"></script>
		<![endif]-->
		<!--[if lte IE 6]><script type="text/javascript">location.href='/contents/NoticeIE6.php';</script><![endif]-->
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

					<!-- 콘텐츠 시작 -->
					<div id="contentsView" style="position:relative; float:none; max-width:740px; margin:0 auto;">
						<div id="titleWrap">
							<div id="contentsDepth"><?=$Site_Path;?></div>
							<h3 id="contentsTitle"><?=$lnb_Arr[$gnbPath[0]][$gnbPath[1]];?></h3>
						</div>

						<div id="contentsPrint">

							<div id="okTitle">회원가입이 완료 되었습니다.</div>
							<div id="okThanks">감사합니다.</div>
							<div id="okMain"><a href="/"><img src="/img/member/go_index.gif" alt="메인으로 이동" /></a></div>

						</div>

					</div>
					<!-- 콘텐츠 종료 -->

					<!-- 로컬 메뉴 섹션 시작 -->
					<?//if($gnbPath[0]!=6) include $_SERVER["DOCUMENT_ROOT"]."/inc/lnbInc.htm";?>
					<!-- 로컬 메뉴 섹션 종료 -->

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

		<script type='text/javascript' src='/nwebnics/js/bunyad-theme.js'></script>

	</body>
</html>
<?
$db->disconnect();
?>