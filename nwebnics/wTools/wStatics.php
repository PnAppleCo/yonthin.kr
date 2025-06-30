<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 01
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");
include ("inc/staticsInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

if(isset($_GET['nYear'])) $vYear=$_GET['nYear']; else $vYear=date('Y');
if(isset($_GET['nMonth'])) $vMonth=$_GET['nMonth']; else $vMonth=date('m');
if(isset($_GET['nDay'])) $vDay=$_GET['nDay']; else $vDay=date('d');
if($_GET['sStandard']==1) {
	$vStatistics=monthStatics($vYear,$vMonth,$vDay);
}else if($_GET['sStandard']==2) {
	$vStatistics=dayStatics($vYear,$vMonth,$vDay);
}else if($_GET['sStandard']==3) {
	$vStatistics=weekStatics($vYear,$vMonth,$vDay);
}else if($_GET['sStandard']==4) {
	$vStatistics=timeStatics($vYear,$vMonth,$vDay);
}else {
	$vStatistics .= monthStatics($vYear,$vMonth,$vDay);
	$vStatistics .= dayStatics($vYear,$vMonth,$vDay);
	$vStatistics .= timeStatics($vYear,$vMonth,$vDay);
	$vStatistics .= weekStatics($vYear,$vMonth,$vDay);
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
		<meta name="robots" content="noindex,nofollow" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title><?=$Title_Txt;?></title>
		<link rel="stylesheet" type="text/css" href="/css/wglobal.css" />
		<link rel="stylesheet" type="text/css" href="/css/wtools.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
		//== 일자 기간 세팅 함수 (inputbox)  kjh
		function setDate(objFrName,objToName,span) {
			today = new Date();
			var toYear = today.getFullYear();
			var toMonth = fillWithZero(today.getMonth()+1,2);
			var toDay = fillWithZero(today.getDate(),2);
			fromday = new Date(shiftTime(today,0,0,span,0));
			var fromYear = fromday.getFullYear();
			var fromMonth = fillWithZero(fromday.getMonth()+1,2);
			var fromDay = fillWithZero(fromday.getDate(),2);
			document.dForm.start_date.value = fromYear + "-" + fromMonth + "-" + fromDay;
			document.dForm.stop_date.value = toYear + "-" + toMonth + "-" + toDay;
		//	document.getElementsByName(objToName).value = toYear + "-" + toMonth + "-" + toDay;
		}

		//== 일자 초기화 (전체기간검색)
		function initDate(objFrName,objToName) {
			document.dForm.start_date.value = '';
			document.dForm.stop_date.value = '';
		}

		function shiftTime(basDate,y,m,d,h) {												//== moveTime(time,y,m,d,h)
			basDate.setFullYear(basDate.getFullYear() + y);				//== y년을 더함
			basDate.setMonth(basDate.getMonth() + m);						//== m월을 더함
			basDate.setDate(basDate.getDate() + d);									//== d일을 더함
			basDate.setHours(basDate.getHours() + h);							//== h시를 더함
			return basDate;
		}

		function fillWithZero(val, digit) {
			var digitStr = "1";
			var decimal = 0;
			for(i=1 ; i<digit; i++) { digitStr += "0"; }
			decimal = parseInt(digitStr);
			if(val < decimal) {
				tmp = val + "";
				for(i=0 ; i < digit - tmp.length; i++) { tmp = "0" + tmp; }
			}else {
				tmp = val + "";
			}
			return tmp;
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
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">접속로그 보기</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div style="margin-bottom:10px;">
								<form name="dForm" method="GET">
									<table class="offLineTbl" summary="로그검색">
										<caption>접속로그 검색</caption>
										<colgroup>
											<col width="100%" />
										</colgroup>
										<tbody>
										<!--
										<tr>
											<td width="100" align="center" bgcolor="#EEEEEE"><b>검색기간</b></td>
											<td>
												<input type="text" name="start_date" size="10" maxlength="10" onClick="changeCal2('',''); ret_name = document.dForm.start_date;showXY(document.all.start_date);" value="" class="textbox1"> ~
												<input type="text" name="stop_date" size="10" maxlength="10" onClick="changeCal2('',''); ret_name = document.dForm.stop_date;showXY(document.all.stop_date);" value="" class="textbox1">
												<a href="javascript:setDate('start_date','stop_date',0);" class="mainMenu">오늘</a>
												<a href="javascript:setDate('start_date','stop_date',-1);" class="mainMenu">어제</a>
												<a href="javascript:setDate('start_date','stop_date',-6);" class="mainMenu">1주일</a>
												<a href="javascript:setDate('start_date','stop_date',-30);" class="mainMenu">1개월</a>
												<a href="javascript:setDate('start_date','stop_date',-180);" class="mainMenu">6개월</a>
												<a href="javascript:setDate('start_date','stop_date',-365);" class="mainMenu">1년</a>
												<a href="javascript:initDate('start_date','stop_date');" class="mainMenu">전체</a>
											</td>
											<td width="70" align="center" rowspan="2"><input type="image" src="/webnics/WebnicsMall/img/oSearch.gif" width="60" height="40" align="absmiddle" border="0" alt="검색하기"></td>
										</tr>
										-->
										<tr>
											<td>
												<select name="nYear" class="selectbox">
													<option value="">--년--</option>
														<?php 
															for($i=2016; $i<=date('Y'); $i++) {
																if(isset($_GET['nYear'])) $yearS=$_GET['nYear']; else $yearS=date('Y');
																if($yearS==$i) $ySelected=" selected"; else $ySelected="";
																echo "<option value=\"$i\"".$ySelected.">$i</option>";
															}
														?>
												</select>
												<select name="nMonth" class="selectbox">
													<option value="">--월--</option>
														<?php 
															for($i=1; $i<=12;$i++) {
																if(isset($_GET['nMonth'])) $monthS=$_GET['nMonth']; else $monthS=date('m');
																if($monthS==$i) $mSelected=" selected"; else $mSelected="";
																echo "<option value=\"".sprintf('%02d',$i)."\"".$mSelected.">$i</option>";
															}
														?>
													</select>
													<select name="nDay" class="selectbox">
														<option value="">--일--</option>
															<?php 
																for($i=1; $i<=31;$i++) {
																	if(isset($_GET['nDay'])) $dayS=$_GET['nDay']; else $dayS=date('d');
																	if($dayS==$i) $dSelected=" selected"; else $dSelected="";
																	echo "<option value=\"".sprintf('%02d',$i)."\"".$dSelected.">$i</option>";
																}
															?>
													</select>
													<input type="radio" name="sStandard" value="1"<?php if($_GET['sStandard']==1) echo " checked";?> style="vertical-align:middle;" />월별
													<input type="radio" name="sStandard" value="2"<?php if($_GET['sStandard']==2) echo " checked";?> style="vertical-align:middle;" />일별
													<input type="radio" name="sStandard" value="3"<?php if($_GET['sStandard']==3) echo " checked";?> style="vertical-align:middle;" />요일별
													<input type="radio" name="sStandard" value="4"<?php if($_GET['sStandard']==4) echo " checked";?> style="vertical-align:middle;" />시간별
													<input type="image" src="../wTools/img/search.gif" style="vertical-align:middle;" alt="검색하기" />
											</td>
										</tr>
										</tbody>
									</table>
								</form>
							</div>
							<div><?=$vStatistics;?></div>

						</div>
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