<?
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(!$_GET[code]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);
if(!$_GET[idx]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

$sqlStr="SELECT *, (vote0 + vote1 + vote2 + vote3 + vote4 + vote5 + vote6 + vote7 + vote8 + vote9) AS tcnt FROM wPoll WHERE idx=$_GET[idx]";
$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
if(DB::isError($view)) die($view->getMessage());
$sContents=stripslashes($view[sContents]);
//== 조회수 증가
$rst=$db->query("UPDATE wPoll SET click = click+1 WHERE idx=$_GET[idx]");
if(DB::isError($rst)) die($rst->getMessage());

$jsLink="'$view[code]','$view[idx]','$_GET[page]','$_GET[mnv]'";
if(member_session(1) == true || (login_session() == true && !strcmp($view[wUserid],$_SESSION[my_id]))) {
	$delLink_js="addChk(document.pollForm,'del',$jsLink); return false;";
	$editLink_js="addChk(document.pollForm,'edit',$jsLink); return false;";
}else {
	$delLink_js="alert('삭제 권한이 없습니다.');";
	$editLink_js="alert('수정 권한이 없습니다.');";
}

//== 투표가능 시간 및 유무 확인
/*
if($_COOKIE['voteok'] == $_SESSION[my_id].$view[idx]) {
	$btnName="<img src=\"/img/comm/poll_app_finish.gif\" alt=\"투표완료\" onClick=\"alert('".$_SESSION[my_name]."님은 이미 투표하셨습니다.');\" />";
}else if(strtotime("today")>strtotime($view[endDate])) {
	$btnName="<img src=\"/img/comm/poll_app_end.gif\" alt=\"투표종료\" onClick=\"alert('투표가 종료되었습니다.');\" />";
}else {
	$btnName="<img src=\"/img/comm/poll_app.gif\" alt=\"투표하기\" onClick=\"addChk(document.pollForm,'vote',".$jsLink."); return false;\" />";
}
*/
if(login_session()) {
	if($_COOKIE['voteok'] == $_SESSION[my_id].$view[idx]) {
		$btnName="<img src=\"/img/comm/poll_app_finish.gif\" alt=\"투표완료\" onClick=\"alert('".$_SESSION[my_name]."님은 이미 투표하셨습니다.');\" />";
	}else {
		$btnName="<img src=\"/img/comm/poll_app.gif\" alt=\"투표하기\" onClick=\"addChk(document.pollForm,'vote',".$jsLink."); return false;\" />";
	}
}else {
		$btnName="<img src=\"/img/comm/poll_app_login.gif\" alt=\"로그인후 투표하세요.\" onClick=\"alert('로그인후 투표하세요.');\" />";
}

if($_GET[code]=="discuss" && $view[endDate]) $vendate="종료일 : ".strtr($view[endDate],"-",".");
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
		<script type="text/javascript" src="/nwebnics/js/slides.min.jquery.js"></script>
		<script type="text/javascript">
			function addChk(thisform,mode,code,m_idx,v_page,g_mnv) {
				if(mode=='edit') {
					thisform.action = 'pollForm.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+g_mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else if(mode=='vote') {
					if ($.trim($(':radio[name="vote"]:checked').val()) == "" ) { alert("투표하실 항목을 선택해주세요."); return false; }
					thisform.action = 'pollExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+g_mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else if(mode=='list') {
					location = 'pollList.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+g_mnv;
				}else if(mode=='del') {
					if(!confirm("정말 삭제 하시겠습니까?")) { return false; }
					thisform.action = 'pollExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&page='+v_page+'&mnv='+g_mnv;
					thisform.method = 'POST';
					thisform.submit();
				}else { alert('작업모드가 선택되지 않았습니다.!'); }
			}
		//-->
		</script>
	</head>
	<body>
		<h1 class="blind"><?=$Site_Info_Company;?> 홈페이지에 오신것을 환영합니다.</h1>
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
<br /><br />
						<div class="tblList">
							<form name="pollForm">
								<fieldset>
									<legend>설문장</legend>
									<table class="boardView" summary="설문장">
										<caption><?=$porcessName;?></caption>
										<colgroup>
											<col width="50%" />
											<col width="50%" />
										</colgroup>
										<thead>
											<th colspan="2" style="text-align:center;"><?=$view[sSubject];?></th>
										</thead>
										<tbody>
											<tr>
												<td><?//if($view[wName]) echo $view[wName]."[".$view[wUserid]."]";?></td>
												<td style="text-align:right; padding-right:5px;">조회 <?=$view[click];?> 등록일 <?=strtr($view[signDate],"-",".");;?> <?=$vendate;?></td>
											</tr>
											<tr>
												<td colspan="2" style="vertical-align:top;"><?=$sContents;?></td>
											</tr>
											<tr>
												<td colspan="2">
													<table class="offLineTbl">
														<caption>통계</caption>
														<colgroup>
															<col width="5%" />
															<col width="40%" />
															<col width="55%" />
														</colgroup>
														<tbody>
														<?
															for($i=0; $i<10; $i++) {
																if($view[censitem.$i]) {
																	echo "<tr><td><input type=\"radio\" name=\"vote\" value=\"".$i."\" style=\"vertical-align:middle;\" /></td><td>".$view[censitem.$i]."</td>";
																	echo "<td><img src=\"/img/comm/bar.gif\" width=\"".totalAve(1, $view[tcnt],$view[vote.$i])."\" height=\"10\" /> 총".$view[vote.$i]."명 [".number_format(totalAve(1, $view[tcnt],$view[vote.$i]))."%]</td></tr>";
																}
															}
														?>
														</tbody>
													</table>
													<?if($view[sNum]>0) {?>
													<div style="padding:5px 0 5px 0; text-align:center;"><a href="javascript:<?=$onLink;?>"><?=$btnName;?></a></div>
													<?}?>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="padding:0; margin:0;">
													<span class="tblLeft">
													</span>
													<span class="tblRight">
														<img src="/img/comm/i_list.gif" alt="목록" onclick="addChk(this.form,'list',<?=$jsLink;?>);" style="cursor:pointer;" />
														<?if(login_session()) {?>
														<img src="/img/comm/i_modify.gif" alt="수정" onclick="<?=$editLink_js;?>" style="cursor:pointer;" />
														<img src="/img/comm/i_delete.gif" alt="삭제" onclick="<?=$delLink_js;?>" style="cursor:pointer;" />
														<?}?>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</fieldset>
							</form>
						</div>
						<div style="padding:10px; text-align:center;"><a href="javascript:history.go(-1);"><img src="/img/comm/v_list_btn.gif" alt="뒤로이동" /></a></div>
						<?include ('pollMent.php');?>
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