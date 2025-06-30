<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

if($_GET['sYear']) $nYear=$_GET['sYear']; else $nYear=date('Y');
if($_GET['sMonth']) $nMonth = $_GET['sMonth']; else $nMonth = "";
if($_GET['sDay']) $nDay = $_GET['sDay']; else $nDay = "";
?>
<!DOCTYPE <?=$doctypeSet;?>>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$languageSet;?>" lang="<?=$languageSet;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$characterSet;?>" />
		<meta name="Title" content="<?=$Title_Txt;?>" />
		<meta name="Description" content="<?=$Description_Txt;?>" />
		<meta name="Keywords" content="<?=$Keywords_Txt;?>" />
		<meta name="Author" content="<?=$Author_Txt;?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="robots" content="none" />
		<meta name="robots" content="noindex, nofollow" />
		<meta name="robots" content="noimageindex" />
		<meta name="robots" content="noarchive" />
		<meta name="googlebot" content="noindex, nofollow" />
		<meta name="googlebot" content="noimageindex" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/wglobal.css" />
		<link rel="stylesheet" type="text/css" href="/css/wtools.css" />
		<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script>
			//== 슬라이딩 콘텐츠
			$(document).ready(function(){
				$("#aa_col_total").text(parseInt($("#aa11").text()) + parseInt($("#aa12").text()) + parseInt($("#aa13").text()) + parseInt($("#aa14").text()) + parseInt($("#aa15").text()) + parseInt($("#aa16").text()) + parseInt($("#aa17").text()));
				$("#ab_col_total").text(parseInt($("#ab11").text()) + parseInt($("#ab12").text()) + parseInt($("#ab13").text()) + parseInt($("#ab14").text()) + parseInt($("#ab15").text()) + parseInt($("#ab16").text()) + parseInt($("#ab17").text()));
				$("#ac_col_total").text(parseInt($("#ac11").text()) + parseInt($("#ac12").text()) + parseInt($("#ac13").text()) + parseInt($("#ac14").text()) + parseInt($("#ac15").text()) + parseInt($("#ac16").text()) + parseInt($("#ac17").text()));

				$("#at01").text(parseInt($("#aa11").text()) + parseInt($("#ab11").text()) + parseInt($("#ac11").text()));
				$("#at02").text(parseInt($("#aa12").text()) + parseInt($("#ab12").text()) + parseInt($("#ac12").text()));
				$("#at03").text(parseInt($("#aa13").text()) + parseInt($("#ab13").text()) + parseInt($("#ac13").text()));
				$("#at04").text(parseInt($("#aa14").text()) + parseInt($("#ab14").text()) + parseInt($("#ac14").text()));
				$("#at05").text(parseInt($("#aa15").text()) + parseInt($("#ab15").text()) + parseInt($("#ac15").text()));
				$("#at06").text(parseInt($("#aa16").text()) + parseInt($("#ab16").text()) + parseInt($("#ac16").text()));
				$("#at07").text(parseInt($("#aa17").text()) + parseInt($("#ab17").text()) + parseInt($("#ac17").text()));

				$("#ba_col_total").text(parseInt($("#ba11").text()) + parseInt($("#ba12").text()) + parseInt($("#ba13").text()) + parseInt($("#ba14").text()) + parseInt($("#ba15").text()));
				$("#bb_col_total").text(parseInt($("#bb11").text()) + parseInt($("#bb12").text()) + parseInt($("#bb13").text()) + parseInt($("#bb14").text()) + parseInt($("#bb15").text()));
				$("#bc_col_total").text(parseInt($("#bc11").text()) + parseInt($("#bc12").text()) + parseInt($("#bc13").text()) + parseInt($("#bc14").text()) + parseInt($("#bc15").text()));

				$("#bt01").text(parseInt($("#ba11").text()) + parseInt($("#bb11").text()) + parseInt($("#bc11").text()));
				$("#bt02").text(parseInt($("#ba12").text()) + parseInt($("#bb12").text()) + parseInt($("#bc12").text()));
				$("#bt03").text(parseInt($("#ba13").text()) + parseInt($("#bb13").text()) + parseInt($("#bc13").text()));
				$("#bt04").text(parseInt($("#ba14").text()) + parseInt($("#bb14").text()) + parseInt($("#bc14").text()));
				$("#bt05").text(parseInt($("#ba15").text()) + parseInt($("#bb15").text()) + parseInt($("#bc15").text()));

				$("#ca_col_total").text(parseInt($("#ca11").text()));
				$("#cb_col_total").text(parseInt($("#ca12").text()));
				$("#cc_col_total").text(parseInt($("#ca13").text()));

				$("#ct01").text(parseInt($("#ca11").text()) + parseInt($("#ca12").text()) + parseInt($("#ca13").text()));

				$("#da_col_total").text(parseInt($("#da11").text()) + parseInt($("#da12").text()) + parseInt($("#da13").text()));
				$("#db_col_total").text(parseInt($("#db11").text()) + parseInt($("#db12").text()) + parseInt($("#db13").text()));
				$("#dc_col_total").text(parseInt($("#dc11").text()) + parseInt($("#dc12").text()) + parseInt($("#dc13").text()));

				$("#dt01").text(parseInt($("#da11").text()) + parseInt($("#db11").text()) + parseInt($("#dc11").text()));
				$("#dt02").text(parseInt($("#da12").text()) + parseInt($("#db12").text()) + parseInt($("#dc12").text()));
				$("#dt03").text(parseInt($("#da13").text()) + parseInt($("#db13").text()) + parseInt($("#dc13").text()));
			});
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
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">상담 통합 정보</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div style="text-align:center;">
								<form name="sForm" action="<?php $_SERVER['PHP_SELF'];?>" method="get">
									<fieldset>
										<legend>교육 신청자 관리</legend>
										<span style="font-size:1.2em; font-weight:bold;">기준년도</span>
										<select name="sYear" class="wSbox">
											<!-- <option value="">- 년도 -</option> -->
											<?php for($i=2014; $i<=date('Y'); $i++) {
													if($nYear==$i) $iCheck=" selected"; else $iCheck="";
													echo "<option value=\"".$i."\"".$iCheck.">".$i."</option>\n";
												}?>
										</select>
										<select name="sMonth" class="wSbox">
											<option value="">- 월 -</option>
											<?php for($i=1; $i<=12; $i++) {
													if($nMonth==$i) $iCheck=" selected"; else $iCheck="";
													echo "<option value=\"".$i."\"".$iCheck.">".$i."</option>\n";
												}?>
										</select>
										<select name="sDay" class="wSbox">
											<option value="">- 일 -</option>
											<?php for($i=1; $i<=31; $i++) {
													if($nDay==$i) $iCheck=" selected"; else $iCheck="";
													echo "<option value=\"".$i."\"".$iCheck.">".$i."</option>\n";
												}?>
										</select>
										<select name="counselSsam" class="wSbox">
											<option value="">상담자</option>
											<?php for($i=1; $i<=count($ssamArr); $i++) {
													if($_GET['counselSsam']==$ssamArr[$i]) $iCheck=" selected"; else $iCheck="";
													echo "<option value=\"".$ssamArr[$i]."\"".$iCheck.">".$ssamArr[$i]."</option>\n";
												}?>
										</select>
										<input type="submit" value="보기" class="button" />
										<input type="button" value="등록" onclick="location.href='conForm.php?mode=add'" class="button" />
										<input type="button" value="상담관리목록" onclick="location.href='/nwebnics/wTools/conList.php'" class="button" />
									</fieldset>
								</form>
							</div>
							<br />

							<div class="nTbl">
								<h4>어린이집 지원</h4>
								<table id="tblReport01" summary="통합요약정보 테이블 - 어린이집 지원">
									<caption>통합요약정보 테이블 - 어린이집 지원</caption>
									<colgroup>
										<col width="8%" />
										<col width="12%" />
										<col width="12%" />
										<col width="12%" />
										<col width="12%" />
										<col width="12%" />
										<col width="12%" />
										<col width="12%" />
										<col width="8%" />
									</colgroup>
									<thead>
										<tr>
											<th>구분</th>
											<th>센터교육</th>
											<th>아동학대</th>
											<th>보육컨설팅</th>
											<th>보수 및 승급교육</th>
											<th>교육경력관리<br />(호봉·수당)</th>
											<th>어린이집운영</th>
											<th>기타</th>
											<th class="tdbg1">소계</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>전화</th>
											<td id="aa11"><?=conHistory('aa11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa12"><?=conHistory('aa12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa13"><?=conHistory('aa13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa14"><?=conHistory('aa14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa15"><?=conHistory('aa15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa16"><?=conHistory('aa16', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="aa17"><?=conHistory('aa17', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="aa_col_total"></td>
										</tr>
										<tr>
											<th>온라인</th>
											<td id="ab11"><?=conHistory('ab11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab12"><?=conHistory('ab12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab13"><?=conHistory('ab13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab14"><?=conHistory('ab14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab15"><?=conHistory('ab15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab16"><?=conHistory('ab16', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ab17"><?=conHistory('ab17', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="ab_col_total"></td>
										</tr>
										<tr>
											<th>방문</th>
											<td id="ac11"><?=conHistory('ac11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac12"><?=conHistory('ac12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac13"><?=conHistory('ac13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac14"><?=conHistory('ac14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac15"><?=conHistory('ac15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac16"><?=conHistory('ac16', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ac17"><?=conHistory('ac17', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="ac_col_total"></td>
										</tr>
										<tr>
											<th class="tdbg1">소계</th>
											<td class="tdbg1" id="at01"></td>
											<td class="tdbg1" id="at02"></td>
											<td class="tdbg1" id="at03"></td>
											<td class="tdbg1" id="at04"></td>
											<td class="tdbg1" id="at05"></td>
											<td class="tdbg1" id="at06"></td>
											<td class="tdbg1" id="at07"></td>
											<td class="tdbg1" id="at08"></td>
										</tr>
									</tbody>
								</table>
								<br />
								<h4>부모·기타</h4>
								<table summary="통합요약정보 테이블 - 부모, 기타">
									<caption>통합요약정보 테이블 - 부모, 기타</caption>
									<colgroup>
										<col width="8%" />
										<col width="12.6%" />
										<col width="12.6%" />
										<col width="12.6%" />
										<col width="12.6%" />
										<col width="12.6%" />
										<col width="8%" />
										<col width="12.6%" />
										<col width="8%" />
									</colgroup>
									<thead>
										<tr>
											<th rowspan="2">구분</th>
											<th colspan="6">부모</th>
											<th colspan="2">기타</th>
										</tr>
										<tr>
											<th>센터교육</th>
											<th>센터이용</th>
											<th>양육</th>
											<th>어린이집이용</th>
											<th>아동학대</th>
											<th class="tdbg1">소계</th>
											<th>홈페이지</th>
											<th class="tdbg1">소계</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>전화</th>
											<td id="ba11"><?=conHistory('ba11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ba12"><?=conHistory('ba12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ba13"><?=conHistory('ba13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ba14"><?=conHistory('ba14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="ba15"><?=conHistory('ba15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="ba_col_total"></td>
											<td id="ca11"><?=conHistory('ca11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="ca_col_total"></td>
										</tr>
										<tr>
											<th>온라인</th>
											<td id="bb11"><?=conHistory('bb11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bb12"><?=conHistory('bb12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bb13"><?=conHistory('bb13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bb14"><?=conHistory('bb14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bb15"><?=conHistory('bb15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="bb_col_total"></td>
											<td id="ca12"><?=conHistory('ca12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="cb_col_total"></td>
										</tr>
										<tr>
											<th>방문</th>
											<td id="bc11"><?=conHistory('bc11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bc12"><?=conHistory('bc12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bc13"><?=conHistory('bc13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bc14"><?=conHistory('bc14', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="bc15"><?=conHistory('bc15', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="bc_col_total"></td>
											<td id="ca13"><?=conHistory('ca13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="cc_col_total"></td>
										</tr>
										<tr>
											<th class="tdbg1">소계</th>
											<td class="tdbg1" id="bt01"></td>
											<td class="tdbg1" id="bt02"></td>
											<td class="tdbg1" id="bt03"></td>
											<td class="tdbg1" id="bt04"></td>
											<td class="tdbg1" id="bt05"></td>
											<td class="tdbg1"></td>
											<td class="tdbg1"></td>
											<td class="tdbg1" id="ct01"></td>
										</tr>
									</tbody>
								</table>
								<br />
								<h4>총계</h4>
								<table summary="통합요약정보 테이블 - 부모, 기타">
									<caption>통합요약정보 테이블 - 부모·기타</caption>
									<colgroup>
										<col width="8%" />
										<col width="28%" />
										<col width="28%" />
										<col width="28%" />
										<col width="8%" />
									</colgroup>
									<thead>
										<tr>
											<th>구분</th>
											<th>어린이집지원</th>
											<th>부모</th>
											<th>기타</th>
											<th class="tdbg1">소계</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>전화</th>
											<td id="da11"><?=conHistory('da11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="da12"><?=conHistory('db11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="da13"><?=conHistory('dc11', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="da_col_total"></td>
										</tr>
										<tr>
											<th>온라인</th>
											<td id="db11"><?=conHistory('da12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="db12"><?=conHistory('db12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="db13"><?=conHistory('dc12', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="db_col_total"></td>
										</tr>
										<tr>
											<th>방문</th>
											<td id="dc11"><?=conHistory('da13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="dc12"><?=conHistory('db13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td id="dc13"><?=conHistory('dc13', $nYear, $nMonth, $nDay, $_GET['counselSsam']);?></td>
											<td class="tdbg1" id="dc_col_total"></td>
										</tr>
										<tr>
											<th class="tdbg1">소계</th>
											<td class="tdbg1" id="dt01"></td>
											<td class="tdbg1" id="dt02"></td>
											<td class="tdbg1" id="dt03"></td>
											<td class="tdbg1" id="dt04"></td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>
						<!-- 콘텐츠 종료 -->
					</div>
					<!-- 콘텐츠 우측 -->
					<?php if($Right_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Right_Inc_File);?>
				</div>
			</div>
			<!-- 주소 및 보텀 메뉴 시작 -->
			<h2 class="blind"><a name="footer-quick" id="footer-quick" href="#footer-quick">주소 및 카피라이터 메뉴</a></h2>
			<?php if($Foot_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Foot_Inc_File);?>
		</div>
	</body>
</html>
<?php $db->disconnect();?>