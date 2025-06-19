<?
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'jisuk'
//== last modify date : 2011. 03. 02
//==================================================================
//== 게시판 기본정보 로드
include ("inc/boardLib.php");

//== 게시판 code 체크
if(!$_GET[code]) js_action(1, "게시판코드를 찾을수 없습니다.", "/", -1);
if(!$_GET[idx]) js_action(1, "중요정보를 찾을수 없습니다.", "/", -1);

//== 접근 권한 설정
include ("inc/levelCheck_Inc.php");

	//== 선택한 게시물 질의
	$sql_str="select * from $b_cfg_tb[1] where code='$_GET[code]' and idx=$_GET[idx]";
	$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	if(!$view) error_view(999, "죄송합니다!. 고객님의 요청과 일치하는 정보를 찾을수 없습니다.","올바른 방법으로 이용하세요.");
	if(!$view[subject]) $view[subject]="제목이 입력되지 안았습니다.";
	//== 태그 및 개행처리
	$view[subject] = stripslashes($view[subject]);
	$view[ucontents] = stripslashes($view[ucontents]);
	$view[subject] = htmlspecialchars($view[subject]);
	if($view[html] !=1) $view[ucontents] = htmlspecialchars($view[ucontents]);
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
		$view[subject] = eregi_replace($_GET[keyword], "<font color=\"#FF6633\"><b>$_GET[keyword]</b></font>", $view[subject]);
		$view[ucontents] = eregi_replace($_GET[keyword], "<font color=\"#FF6633\"><b>$_GET[keyword]</b></font>", $view[ucontents]);
	}
	$signdate=strtr($view[signdate],"-",".");

	//== 전자우편설정
	if($view[email]) $o_email = "<a href=\"./sendmail.php?email=".base64_encode($view[email])."\" target=\"mail_cipher\"><img src=\"./skin/".$board_info[skin]."/img/yes_email.gif\" width=\"12\" height=\"12\" border=\"0\" align=\"absmiddle\"></a>"; else $o_email = "<img src=\"./skin/".$board_info[skin]."/img/no_email.gif\" width=\"12\" height=\"12\" border=\"0\" align=\"absmiddle\">";
	//== 홈페이지 설정
	if($view[homepage]) $o_homepage = "<a href=\"".$view[homepage]."\" target=\"_blank\"><img src=\"./skin/".$board_info[skin]."/img/yes_homepage.gif\" width=\"12\" height=\"12\" border=\"0\" align=\"absmiddle\"></a>"; else $o_homepage = "<img src=\"./skin/".$board_info[skin]."/img/no_homepage.gif\" width=\"12\" height=\"12\" border=\"0\" align=\"absmiddle\">";

	//== 조회수 증가
	$cookiename=$_GET[code].$_GET[idx].$view[fid];
	if(!$_COOKIE[$cookiename]) {
		$rst=$db->query("update $b_cfg_tb[1] set click = click+1 where code='$_GET[code]' and idx=$_GET[idx]");
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
				$o_data .= "<a href=\"./down.php?code=".$_GET[code]."&save_dir=".$savedir."&filename=".$view[filename.$i]."\">".file_view("",$savedir,$view[filename.$i])."</a> ";
				//== 확장자 추출
				$upfile=explode(".",$view[filename.$i]);
				switch ($upfile[1]) {
					case ("wmv") : case ("asf") : case ("mpg") : case ("mpeg") :
							//== 동영상보기
						$movie_path="files/".$_GET[code]."/".$view[filename.$i];
						$o_img_news .= "<div><embed src=\"".$movie_path."\" type=\"application/x-mplayer2\" width=\"280\" height=\"240\" autostart=\"false\" loop=\"false\"></embed></div><br>";
					break;
					case ("gif") : case ("GIF") : case ("jpg") : case ("JPG") : case ("jpeg") : case ("bmp") :
					$img_dir = $savedir."/".$view[filename.$i];
						$img_size = @getimagesize($img_dir);
							//== 팝업 창크기설정
							if($img_size[0]>1024) {
								$pop_width=1000;
								$scroll_status="yes";
							}else {
								$pop_width=$img_size[0]+6;
								$scroll_status="no";
							}
							if($img_size[1]>768) {
								$pop_height=700;
								$scroll_status="yes";
							}else {
								$pop_height=$img_size[1]+6;
								$scroll_status="no";
							}
							//== 이미지크기설정(비율에 맞게 조정)
							if($img_size[0]>$board_info[img_view_size]) {
								$img_width=$board_info[img_view_size];																										//== 가로 비율
								$img_height=(($board_info[img_view_size]*$img_size[1])/$img_size[0]);			//== 세로 비율
							}else {
								$img_width=$img_size[0];
								$img_height=$img_size[1];
							}
							//== 한글이미지 엔코드
							if(eregi ("(([^/a-zA-Z]){1,})(\.jpg|\.jpeg|\.bmp|\.png|\.gif)",$img_dir ,$regs)) $v_img_dir = str_replace ($regs[1], urlencode($regs[1]),$img_dir); else $v_img_dir = $img_dir;
							//== 이미지 넓이가 적은경우 이미지 좌측 배열설정
							if($img_width!=0 && $img_width<=350) $v_width_style=" style=\"float:left\""; else $v_width_style="";
							$alt_img_size = "[".$img_size[0]." ×".$img_size[1]."]";
							//== 뉴스 이미지보기
							$o_img_news .= "<table width=\"$img_width\" height=\"$img_height\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table_outline\"><tr><td align=\"center\"><a href=\"javascript:void(window.open('./zoom.php?image=$img_dir','photo_zoom','width=$pop_width,height=$pop_height,status=no,resizable=0,scrollbars=$scroll_status,1,1'));\"><img src=\"$v_img_dir\" width=\"$img_width\" height=\"$img_height\" align=\"center\"".$v_width_style." border=\"0\" alt=\"$alt_img_size\"></a></td></tr><tr><td align=\"center\">$view[email]</td></tr></table>";
							//== 포토게시판 이미지보기
							$o_img_photo .= "<a href=\"javascript:void(window.open('./zoom.php?image=$img_dir','photo_zoom','width=$pop_width,height=$pop_height,status=no,resizable=0,scrollbars=$scroll_status,1,1'));\"><img src=\"$v_img_dir\" width=\"$img_width\" height=\"$img_height\" align=\"center\"".$v_width_style." border=\"0\" alt=\"$alt_img_size\"></a><br><br>";
					break;
					default :
						$o_data .= "";
				}
			}else {
				$o_img_news .= "";
				$o_img_photo .= "";
				$o_data .= "";
			}
			if(!$o_data) $o_data="미등록";
		}
	}
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
		<style type="text/css">
			@media print {
			body * { display:none; }
			#tblList {
				display:block;
				width: auto;
				border: 0;
				margin: 0 5%;
				padding: 0;
				float: none !important;
			}
		</style>
		<script type="text/javascript" src="/nwebnics/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/nwebnics/js/common.js"></script>
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

							<div id="tblList">
								<table id="boardView" summary="게시물 출력">
									<caption>게시물 출력</caption>
									<colgroup>
										<col width="80%" />
										<col width="20%" />
									</colgroup>
									<tbody>
										<tr>
											<td><strong><?=$view[subject];?></strong></td>
											<td style="text-align:right;"><?=$v_names."/".$signdate;?></td>
										</tr>
										<tr>
											<td colspan="2">
												<?if($o_img_photo) $o_img_photo=$o_img_photo."<br />";?>
												<div class="contentsPrint"><?=$o_img_photo.$view[ucontents];?></div>
											</td>
										</tr>
									</tbody>
								</table>
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