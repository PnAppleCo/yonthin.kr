<?
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'jisuk'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

//== 게시판 code 체크
if(!$_GET[code]) js_action(1, "게시판코드를 찾을 수 없습니다.", "/", -1);
if(!$_GET[idx]) js_action(1, "중요정보를 찾을 수 없습니다.", "/", -1);

//== 접근 권한 설정
include ("inc/levelCheck_Inc.php");

	//== 선택한 게시물 질의
	$sql_str="SELECT * FROM $b_cfg_tb[1] WHERE code='$_GET[code]' and idx=$_GET[idx]";
	$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	if(!$view) error_view(999, "죄송합니다!. 고객님의 요청과 일치하는 정보를 찾을수 없습니다.","올바른 방법으로 이용하세요.");
	if(!$view[subject]) $view[subject]="제목이 입력되지 않았습니다.";
	//== 태그 및 개행처리
	$view[subject] = stripslashes($view[subject]);
	$view[ucontents] = stripslashes($view[ucontents]);
	$view[subject] = htmlspecialchars($view[subject]);
	/* 제품소개 */
	$specification =$view[ucontents];
	$feature =nl2br($view[feature]);

	$titleBars=$view[subject];
	//if($view[html] !=1) $view[ucontents] = htmlspecialchars($view[ucontents]);
	if($view[auto_enter]>0) $view[ucontents] = nl2br($view[ucontents]);
	if($board_info[ps_center]>0) {
		$view[svc_reply] = stripslashes($view[svc_reply]);
		$view[svc_reply] = htmlspecialchars($view[svc_reply]);
		$view[svc_reply] = nl2br($view[svc_reply]);
	}
	//== 승인제 게시판일경우
	if($board_info[approve]>0 && !member_session(1)) {
		if($view[approve]<1 && member_session(1)==false) error_view(999, "죄송합니다. 관리자 승인이 이루어지지 않았습니다.","관리자에게 문의하세요.");
	}
	//== 비공개글일 경우 세션체크와 비밀번호 요청
	if($view[secret]>0) {
		$secretCookie="secret".$_GET[code].$_GET[idx];
		//== 자기글도 아니도 열람 비버도 모르고 관리자도 아니면
		if($view[mem_id]!=$_SESSION[my_id] && $_COOKIE[$secretCookie]!=base64_encode($secretCookie) && member_session(1) == false) error_view(999, "죄송합니다. 비공개글입니다.","올바른 접근 경로를 통하여 열람하시기 바랍니다.");
	}
	//== 검색했을 경우 붉은색 처리
	if($_GET[keyword]) {
		$view[subject] = eregi_replace($_GET[keyword], "<span style=\"color:#FF6633;\"><strong>$_GET[keyword]</strong></span>", $view[subject]);
		$view[ucontents] = eregi_replace($_GET[keyword], "<span style=\"color:#FF6633;\"><strong>$_GET[keyword]</strong></span>", $view[ucontents]);
	}
	$signdate=strtr($view[signdate],"-",".");

	//== 전자우편설정
	if($view[email]) $o_email = "<a href=\"./sendmail.php?email=".base64_encode($view[email])."\" target=\"mail_cipher\"><img src=\"/img/board/yes_email.gif\" align=\"absmiddle\"></a>"; else $o_email = "<img src=\"/img/board/no_email.gif\" align=\"absmiddle\">";
	//== 홈페이지 설정
	if($view[homepage]) $o_homepage = "<a href=\"".$view[homepage]."\" target=\"_blank\"><img src=\"/img/board/yes_homepage.gif\" align=\"absmiddle\"></a>"; else $o_homepage = "<img src=\"/img/board/no_homepage.gif\" align=\"absmiddle\">";

	//== 조회수 증가
	$cookiename=$_GET[code].$_GET[idx].$view[fid];
	if(!$_COOKIE[$cookiename]) {
		$rst=$db->query("UPDATE $b_cfg_tb[1] SET click = click+1 WHERE code='$_GET[code]' and idx=$_GET[idx]");
		if(DB::isError($rst)) die($rst->getMessage());
		//== 조회수는 1번만 12시간(쿠키저장)
		setcookie($cookiename,$cookiename,time()+85000,"/");
	}
	//== 회원 비회원 구분
	if($board_info[private_board]>0 && $view[mem_id]) $v_names=$view[name]."[".$view[mem_id]."]"; else $v_names=$view[name];

	//== 등록파일 형식 출력(다운로드)
	if($board_info[upload_count]>0) {
		//== 파일 경로
		$savedir="./files/".$_GET[code];
		for($i=0; $i<$board_info[upload_count]; $i++) {
			if($view[filename.$i]) {
				//== 이미지 기본정보 처리

				//== 확장자 추출
				$upfile=explode(".",$view[filename.$i]);
				switch ($upfile[1]) {
					case ("wmv") : case ("asf") : case ("mpg") : case ("mpeg") :
							//== 동영상보기
						$movie_path="files/".$_GET[code]."/".$view[filename.$i];
						$o_img_news .= "<div><embed src=\"".$movie_path."\" type=\"application/x-mplayer2\" width=\"280\" height=\"240\" autostart=\"false\" loop=\"false\"></embed></div><br>";

						$o_data .= "<span style=\"padding:1em;\"><a href=\"./down.php?code=".$_GET[code]."&save_dir=".$savedir."&filename=".urlencode($view[filename.$i])."\">".file_view("",$savedir,$view[filename.$i])."</a></span>";

					break;
					case ("gif") : case ("GIF") : case ("jpg") : case ("JPG") : case ("jpeg") : case ("bmp") : case ("png") : case ("PNG") :
					$img_dir = $savedir."/".$view[filename.$i];
						$img_size = @getimagesize($img_dir);
						$o_data .= "<span style=\"padding:1em;\"><a href=\"./down.php?code=".$_GET[code]."&save_dir=".$savedir."&filename=".urlencode($view[filename.$i])."\">".file_view("",$savedir,$view[filename.$i])."</a></span>";
					break;
					default :
						$o_data .= "<span style=\"padding:1em;\"><a href=\"./down.php?code=".$_GET[code]."&save_dir=".$savedir."&filename=".urlencode($view[filename.$i])."\">".file_view("",$savedir,$view[filename.$i])."</a></span>";
				}
			}else {
				$o_img_news .= "";
				$o_img_photo .= "";
				$o_data .= "";
			}
		}
		if(empty($o_data)) $o_data="";
	}
	//== 메타태그 설정(2016.03.22)
	$Title_Txt=$titleBars;
	//$Description_Txt=strip_tags(str_replace("&nbsp;","",$view[ucontents]));
	$desTxt=han_cut($view[ucontents], 50, "..");
	$Description_Txt=strip_tags(str_replace("&nbsp;","",$desTxt));
	$Keywords_Txt=strip_tags($view[keytag]);
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
		<!-- NAVER -->
		<meta name="naver-site-verification" content="795d8624d184b95f3a77496e5647787fe9f329e8"/>
		<!-- BEGIN OPENGRAPH -->
		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="<?=$siteName;?>" />
		<meta property="og:title" content="<?=$Title_Txt;?>" />
		<meta property="og:description" content="<?=$Description_Txt;?>" />
		<meta property="og:image" content="<?=$snsImage;?>" />
		<meta property="og:url" content="<?=$snsUrl;?>" />
		<!-- END OPENGRAPH -->

		<!-- BEGIN TWITTERCARD -->
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@WEBSITE" />
		<meta name="twitter:title" content="<?=$Title_Txt;?>" />
		<meta name="twitter:description" content="<?=$Description_Txt;?>" />
		<meta name="twitter:url" content="<?=$snsUrl;?>" />
		<meta name="twitter:image" content="<?=$snsImage;?>" />
		<!-- END TWITTERCARD -->

		<title><?=$Title_Txt;?></title>
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
		<!-- <script type="text/javascript" src="/js/jquery-migrate.min.js"></script> -->
		<script type="text/javascript" src="js/viewCheck.js"></script>
		<!-- 카카오 공유하기 플러그인 -->
		<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
		<script src="//connect.facebook.net/ko_KR/sdk.js"></script>
		<script>

		$(document).ready(function(){
			$('#urlCopy').click(function() {
				var copy_url=$('#clip_url').val();
				var t = document.createElement("textarea");
				document.body.appendChild(t);
				t.value = copy_url;
				t.select();
				document.execCommand('copy');
				document.body.removeChild(t);
				alert('클립보드에 주소가 복사되었습니다. Ctrl + V 로 붙여넣기 하세요.');
			});
		});

		var snsModule = (function(){
			'use strict';
			var title   = '<?=$Title_Txt;?>', // 공유할 페이지 타이틀
			url		 = '<?=$snsUrl;?>',   // 공유할 페이지 URL
			tags		= "<?=$siteName;?>",   // 공유할 태그
			imageUrl	= '<?=$snsImage;?>', // 공유할 이미지
			description = '<?=$Description_Txt;?>'; // 공유할 설명

			var encodeTitle = encodeURIComponent(title),
			encodeUrl   = encodeURIComponent(url),
			encodeTags  = encodeURIComponent(tags),
			encodeImage = encodeURIComponent(imageUrl);				// 공유할 이미지

			Kakao.init('831d8ecbca07f239121edfd7d83ff031');					// 카카오 인증 초기화
			FB.init({																																	// 페이스북 인증 초기화
					appId	  : '118794868757267',
					xfbml	  : true,
					version	: 'v2.10'
			});

			var snsModule = {
					facebook:function(){
							FB.ui({
											method: 'feed',
											picture: imageUrl,
											name: title,
											description: description,
											//caption: document.location.href,// 설명
											link: url
									}, function(response){
									}
							);
					},
					kakaotalk:function(){
							Kakao.Link.sendTalkLink({
									label: title+'\n'+url,
									image:{
											src : imageUrl,
											width : 200,
											height : 200,
									},
							});
					},
					kakaostory:function(){
							Kakao.Story.share({
									url: url,
									text: title,
							});
					},
					band:function(){
							window.open("http://band.us/plugin/share?body="+encodeTitle+encodeURIComponent("\n")+encodeUrl+"&route="+encodeUrl, "band", "width=410, height=540, resizable=no");
					},
					pholar:function(){
							window.open("http://www.pholar.co/spi/rephol?title="+encodeTitle+"&url="+encodeUrl, "pholar", "width=410, height=540, resizable=no");
					},
					naverblog:function(){
							window.open("http://blog.naver.com/openapi/share?title="+encodeTitle+"&url="+encodeUrl, "naver blog", "width=410, height=540, resizable=no");
					},
					google:function(){
							window.open("https://plus.google.com/share?t="+encodeTitle+"&url="+encodeUrl, "google+", "width=500, height=550, resizable=no");
					},
					pinterest:function(){
							window.open("http://www.pinterest.com/pin/create/button/?url="+encodeUrl+"&media="+encodeImage+"&description="+encodeTitle, "pinterest", "width=800, height=550, resizable=no");
					},
					tumblr:function(){
							window.open("http://www.tumblr.com/widgets/share/tool?posttype=photo&title="+encodeTitle+"&content="+encodeImage+"&canonicalUrl="+encodeUrl+"&tags="+encodeTags+"&caption="+encodeTitle+encodeURIComponent("\n")+encodeUrl,"tumblr", "width=540, height=600, resizable=no");
					},
					twitter:function(){
							window.open("http://www.twitter.com/intent/tweet?text="+encodeTitle+"&url="+encodeUrl+"&hashtags="+encodeTags, 'twitter', "width=500, height=450, resizable=no");
					},
					line : function(){
							window.open("http://line.me/R/msg/text/?"+encodeTitle+" "+encodeUrl);
					},
					telegram : function(){
							window.open("https://telegram.me/share/url?url="+encodeUrl);
					},
					share_fb : function(){
						window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeUrl,'facebook-share-dialog',"width=626, height=436")
					}
			};
			return snsModule;
		}());
		</script>
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
						<?if($board_info[skin]) require "./skin/".$board_info[skin]."/view_skin.php"; else error_view(999, "스킨이 선택되지 않았습니다.","관리자에게 문의 하세요.");?>
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