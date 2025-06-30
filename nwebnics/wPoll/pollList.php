<?php
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(!$_GET[code]) js_action(1,"중요정보를 찾을수 없습니다.","",-1);

//============================================================= 검색 질의 ===============================================================//
$sqlStr1 = "SELECT COUNT(DISTINCT idx) FROM wPoll";
$sqlStr2 = "SELECT * FROM wPoll";

$addSql .=" code='$_GET[code]' AND";
if($_GET[gWork]) $addSql .=" wType='$_GET[gWork]' AND";
if($_GET[gWord] && $_GET[cField]) $addSql .=" $_GET[cField] like '%$_GET[gWord]%' AND";
if($_GET[myjob]) $addSql .=" wUser like '%$_SESSION[my_name]%' AND";

//== 조건 질의어 생성
if($addSql) {
	$sqlStr1 .= " WHERE".substr($addSql,0,-3);
	$sqlStr2 .= " WHERE".substr($addSql,0,-3);
}

//== 정렬필드와 차순결정
if($_GET[aField]) $alignField=$_GET[aField]; else $alignField="idx";
if($_GET[aType]) $alignType=$_GET[aType]; else $alignType="DESC";
$sqlStr2 .= " ORDER BY ".$alignField." ".$alignType;
//== 다음 정렬 차순 결정
if($alignType=="DESC") $alignType="ASC"; else if($alignType=="ASC") $alignType="DESC";
//echo $sqlStr1.'<br>'.$sqlStr2;

$excelDown="sqlStr=".urlencode($sqlStr2);

//==================================================================질의 종료 ================================================================//

//== 총 레코드수 추출
$total = $db->getOne($sqlStr1);
if(DB::isError($total)) die($total->getMessage());

	if(!$total) {
		$first = 1;
		$last = 0;
	}else {
		$first = $num_per_page*($_GET[page]-1);
		$last = $num_per_page*$_GET[page];
		$next = $total - $last;
		if($next > 0) {
			$last -= 1;
		}else {
			$last = $total - 1;
		}
	}
	//== 총 페이지수
	$total_page = ceil($total/$num_per_page);
	//== 일련번호 설정
	$article_num = $total - $num_per_page*($_GET[page]-1);
	//== 오늘 등록된 게시물
	$sql_str = "SELECT COUNT(idx) FROM wPoll WHERE signDate=now()";
	$today = $db->getOne($sql_str);
	if(DB::isError($today)) die($today->getMessage());

	//== 질의수행
	$view = $db->getAll($sqlStr2,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	//== 페이지 현황정보
	$page_state="총 : <strong style=\"color:#F96807;\">".$total."</strong>개의 글이 있습니다. ";
	if(!$_GET[gWork] && !$_GET[gField] & !$_GET[gWord]) {
		//$page_state .= $_GET[page]." / ".$total_page." 페이지";
	}else {
		//$page_state .= "검색결과 : ".$_GET[page]." / ".$total_page;
	}
	//== 페이징
	$paging = new paging();
	$viewPaging=$paging->page_display($total,$num_per_page, $num_per_block,$next);
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

						<div id="boardHead">
							<span class="tblLeft"><?=$page_state;?></span>
							<span class="tblRight">
								<?
								if(member_session(4) && $_GET[code] != "discuss" && $_GET[code] != "talk06") {
									$linkbtn="<a href=\"pollForm.php?mode=add&code=".$_GET[code]."&mnv=".$_GET[mnv]."\"><img src=\"/img/comm/i_write.gif\" alt=\"글쓰기\" /></a>";
								}else if(member_session(1) && $_GET[code] = "discuss") {
									$linkbtn="<a href=\"pollForm.php?mode=add&code=".$_GET[code]."&mnv=".$_GET[mnv]."\"><img src=\"/img/comm/i_write.gif\" alt=\"글쓰기\" /></a>";
								}
								echo $linkbtn;
								?>
							</span>
						</div>
						<div class="tblList">
							<table class="boardList" summary="<?=$porcessName;?> LIST">
								<caption><?=$porcessName;?> LIST</caption>
								<colgroup>
									<col width="10%" />
									<col width="70%" />
									<col width="10%" />
									<col width="10%" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">순번</th>
										<th scope="col">제 목</th>
										<th scope="col">조회</th>
										<th scope="col">등록일</th>
									</tr>
								</thead>
								<tbody>
								<?
								echo $dLinks;
								if(!$total) echo "<tr><td colspan=\"5\">현재 등록/검색된 게시물이 없습니다.</td></tr>";
									for($i = $first; $i <= $last; $i++) {
										$linkOption="?idx=".$view[$i][idx]."&mnv=".$_GET[mnv]."&code=".$_GET[code];
										$link1="<a href=\"pollView.php".$linkOption."\">";
										$link2="</a>";
										$newDate=$view[$i][signDate];
										$newTime=$view[$i][regTime];
										//$today=time();
										$today=strtotime("today");
										$write_day =strtotime($view[$i][signDate].$view[$i][regTime]);
										$arr_day=explode("-",$newDate);
										$arr_time=explode(":",$newTime);
										//$write_day = mktime ($arr_time[0],$arr_time[1],$arr_time[2], $arr_day[1] , $arr_day[2], $arr_day[0]);
										$write_day =strtotime($view[$i][signDate].$view[$i][regTime]);
										//$view_day = $write_day+((60*60)*72);
										$ddd=strtotime(date("Y-m-d", strtotime($view[$i][signDate]))."+3 day");
										//echo $today." ".$ddd."<br/>";
										$view_day = strtotime("+3 day", $view[$i][signDate].$view[$i][regTime]);

										if($today < $ddd) {
											$newIcon = "<span style=\"padding-left:2px;\"><img src=\"/img/comm/new.gif\" alt=\"new\"></span>";
											//echo $today."--신규--".$view_day."<br>";
										}else {
											if($view[$i][editDate]) {									//== 수정된 경우
												$tmpDate=explode(" ",$view[$i][editDate]);
												$eDate=$tmpDate[0];
												$eime=$tmpDate[1];
												$arrDay=explode("-",$eDate);
												$arrTime=explode(":",$eTime);
												//$writeDay = mktime ($arrTime[0],$arrTime[1],$arrTime[2], $arrDay[1] , $arrDay[2], $arrDay[0]);
												$writeDay = strtotime($view[$i][editDate]);
												$viewDay = $writeDay+((60*60)*72);
												if($today < $viewDay) {
													$newIcon = "<span style=\"padding-left:2px;\"><img src=\"".$imgDir."edit.gif\" alt=\"edit\"></span>";
											//echo $today."--수정--".$viewDay."<br>";
												}
											}
										}

										//== 코멘트 카운트
										$cCount=mentCount($view[$i]['idx'], $view[$i][code], "wpollMent");
										if($cCount>0) $cMentv=" <span style=\"color:#F97501;\">[".$cCount."]</span>"; else $cMentv="";
										//== 제목 길이 제한
										if(strlen($view[$i][wSubject]) > 58) $view[$i][wSubject] = han_cut($view[$i][wSubject], 70, "..");
										if(strlen($view[$i][wUser]) > 9) $view[$i][wUser] = han_cut($view[$i][wUser], 9, "");
										//== 선택 레코드 색상 선택
										if($_GET[eidx]==$view[$i][idx]) $selectedcolor="#CCFFCC"; else $selectedcolor="#FFFFFF";
								?>
									<tr style="background:<?=$selectedcolor;?>;">
										<td><?=$article_num;?></td>
										<td class="ListAlign"><?=$link1.$view[$i][sSubject].$link2.$newIcon.$cMentv;?></td>
										<td><?=$link1.$view[$i][click].$link2;?></td>
										<td><?=$link1.substr(strtr($view[$i][signDate],"-","."),2).$link2;?></td>
									</tr>
								<?$article_num--; }?>
								</tbody>
							</table>
						</div>
						<div id="boardTail">
							<span class="tblLeft"><?=$viewPaging;?></span>
							<span class="tblRight">
								<form name="searchForm" method="get" action="<?=$PHP_SELF;?>">
								<fieldset >
									<legend>검색</legend>
									<select name="cField" class="selectbox">
										<option value="">검색</option>
										<option value="sSubject"<?if($_GET[cField]=="sSubject") echo " selected";?>>제목</option>
										<option value="sContents"<?if($_GET[cField]=="sContents") echo " selected";?>>내용</option>
									</select>
									<input type="hidden" name="code" value="<?=$_GET['code'];?>" />
									<input type="text" name="gWord" size="15" maxlength="20" class="textbox" title="검색 키워드 입력" value="<?=$_GET[gWord];?>" />
									<input type="image" src="/nwebnics/wBoard/skin/default/img/search.gif" style="vertical-align:-6px;" title="검색" />
								</fieldset>
							</form>
							</span>
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