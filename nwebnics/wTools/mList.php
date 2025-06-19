<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1);

//============================================================= 회원검색 질의 ===============================================================//
$sqlStr1 = "SELECT COUNT(DISTINCT aT.idx) FROM wMember aT";
$sqlStr2 = "SELECT aT.* FROM wMember aT";

//== 회원검색 질의
if($_GET[gmLevel]) $addSql .= " aT.ulevel='$_GET[gmLevel]' AND";
if($_GET[gArea]) $addSql .= " aT.haddress1 LIKE '$_GET[gArea]%' AND";
if($_GET[gField] && $_GET[gWord]) $addSql .=" aT.$_GET[gField] like '%$_GET[gWord]%' AND";
if($_GET[start_date] && $_GET[stop_date]) $addSql .= " DATE_FORMAT(aT.signDate,'%Y-%m-%d') BETWEEN DATE_FORMAT('$_GET[start_date]','%Y-%m-%d') AND DATE_FORMAT('$_GET[stop_date]','%Y-%m-%d') AND";
if($_GET[termMode]==1) $addSql .= " DATE_FORMAT(aT.signDate, '%Y-%m-%d') = CURDATE() AND";
if($_GET[termMode]==2) $addSql .= " DATE_FORMAT(aT.signDate, '%Y-%m-%d') = (CURDATE() - INTERVAL 1 DAY) AND";
if($_GET[termMode]==3) $addSql .= " DATE_FORMAT(aT.signDate, '%Y-%m-%d') >= (CURRENT_DATE() - INTERVAL 7 DAY) AND";
if($_GET[termMode]==4) $addSql .= " DATE_FORMAT(aT.signDate, '%Y-%m-%d') >= (CURRENT_DATE() - INTERVAL 1 MONTH) AND";
if($_GET[limitDate]>0) $addSql .= " DATE_FORMAT(aT.limitDate, '%Y-%m-%d') != '0000-00-00' AND";
if($_GET[glogCount]) $addSql .= " aT.login>$_GET[glogCount] AND";
if($_GET[greceiptEmail]>0) $addSql .= " aT.newsDm=1 AND";
if($_GET[greceiptSms]>0) $addSql .= " aT.smsDm=1 AND";
if($_GET[gbirthDay]) $addSql .= " DATE_FORMAT(aT.birthDay, '%m-%d')=DATE_FORMAT(CURDATE(), '%m-%d') AND";
if($_GET[gokPoint]) $addSql .= " aT.point>$_GET[gokPoint] AND";

//== 주문검색 질의
if($_GET[shoptStart] && $_GET[shopStop]) $shopSql .= " DATE_FORMAT(bT.settle_date,'%Y-%m-%d') BETWEEN DATE_FORMAT('$_GET[startStart]','%Y-%m-%d') AND DATE_FORMAT('$_GET[shopStop]','%Y-%m-%d') AND";
if($_GET[gbuyAmount]) $havingSql .= " SUM(bT.item_total_price)>$_GET[gbuyAmount] AND";
if($_GET[gbuyCount]) $havingSql .= " COUNT(bT.idx)>$_GET[gbuyCount] AND";
if($havingSql) $havingSql = " HAVING (".substr($havingSql,0,-3).") AND";

//== 회원관련 조건문과 쇼핑관련 조건문 조합
if($shopSql) $addSql .= $shopSql;
//== 쇼핑관련 조건질의가 있을 경우 테이블 조인
if($shopSql || $havingSql) {
	if($addSql) $inAnd=" AND"; else $inAnd="";
	$whereStr=", Settle_List bT WHERE aT.id=bT.member_id".$inAnd;
}else { $whereStr=" WHERE"; }
//== HAVING 조건절 앞에 'AND'가 있는 경우 AND 삭제
if($havingSql) {
	if($addSql) $addSql = substr($addSql,0,-3).$havingSql; else $addSql .= $havingSql;
}
//== 조건 질의어 생성
if($addSql) {
	$sqlStr1 .= $whereStr.substr($addSql,0,-3);
	$sqlStr2 .= $whereStr.substr($addSql,0,-3);
}

//== 정렬필드와 차순결정
if($_GET[aField]) $alignField=$_GET[aField]; else $alignField="idx";
if($_GET[aType]) $alignType=$_GET[aType]; else $alignType="DESC";
$sqlStr2 .= " ORDER BY aT.".$alignField." ".$alignType;
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
	$sql_str = "SELECT COUNT(idx) FROM wMember WHERE signDate=now()";
	$today = $db->getOne($sql_str);
	if(DB::isError($today)) die($today->getMessage());

	//== 질의수행
	$view = $db->getAll($sqlStr2,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	//== 페이지 현황정보
	$page_state="&nbsp;전체 : ".$total." 오늘 : ".$today."&nbsp;";
	if(!$_GET[keyword] && !$_GET[keyfield]) {
		$page_state .= "페이지 : ".$_GET[page]." / ".$total_page;
	}else {
		$page_state .= "검색결과 : ".$_GET[page]." / ".$total_page;
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
		<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" />
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#start_date').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});
			$(function(){
				$('#stop_date').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});
		</script>
		<script type="text/javascript">
		<!--
		function allSelect() {
			var thisform = document.sForm;
			var delbox = document.sForm['sBox[]'];

			//== 배열이 없을경우(하나도 없을경우)
			if(delbox == null) return;
			//== 전체선택
			if(thisform.allSbox.checked == true) {
				//== 여러개일경우
				if(document.sForm['sBox[]'].length>1) {
					for(i=0; i<document.sForm['sBox[]'].length; i++) document.sForm['sBox[]'][i].checked = true;
				//== 단 하나일경우
				}else {
					document.sForm.elements[1].checked = true;
				}
				return;
			//== 선택해제
			}else if(thisform.allSbox.checked == false) {
				//== 여러개일경우
				if(document.sForm['sBox[]'].length>1) {
					for(i=0; i<document.sForm['sBox[]'].length; i++) document.sForm['sBox[]'][i].checked = false;
				//== 단 하나일경우
				}else {
					document.sForm.elements[1].checked = false;
				}
				return;
			}else {
				alert('올바른 방법을 이용하세요.!');
			}
		}

		//== 상세검색 뷰
		function detailView(obj) {
			var vlayer = document.getElementById('detailView');
			if(obj.src.indexOf('_show.gif') != -1) {
				obj.src = obj.src.replace('_show.gif', '_hide.gif');
				vlayer.style.display = 'block';
				document.getElementById('detailCheck').value=1;
			}else {
				obj.src = obj.src.replace('_hide.gif', '_show.gif');
				vlayer.style.display = 'none';
				document.getElementById('detailCheck').value=2;
			}
		}

		//== 쇼핑검색 뷰
		function shopView(obj) {
			var vlayer = document.getElementById('shopView');
			if(obj.src.indexOf('_show.gif') != -1) {
				obj.src = obj.src.replace('_show.gif', '_hide.gif');
				vlayer.style.display = 'block';
				document.getElementById('shopCheck').value=1;
			}else {
				obj.src = obj.src.replace('_hide.gif', '_show.gif');
				vlayer.style.display = 'none';
				document.getElementById('shopCheck').value=2;
			}
		}

		//== 선택SMS
		function allSms() {
			var sboxObj = document.getElementsByName("sBox[]");
			var count = 0;
			for(i=0; i<sboxObj.length; i++) { if(sboxObj[i].checked) count++; }
			if(count < 2) { alert("2명 이상을 선택하셔야합니다."); return; }
			window.open("","allSms","width=220, height=290, status=no, scrollbars=no,resizable=no, menubar=no, top=100, left=100");
			document.sForm.method = 'POST';
			document.sForm.action="/webnics/WebnicsSms/smsForm.php?rid=sUser";
			document.sForm.target = "allSms";
			document.sForm.submit();
		}
		//-->
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
						<h3 id="headTitle">회원 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div id="sOption">
								<form name="dForm" method="GET">
								<table class="offLineTbl" summary="검색 옵션" style="border:1px solid #CCCCCC;">
									<caption>검색 옵션</caption>
									<colgroup>
										<col width="10%" />
										<col width="74%" />
										<col width="8%" />
										<col width="8%" />
									</colgroup>
									<tbody>
										<tr>
											<th>검색옵션</th>
											<td>
												<ul>
													<li>
														<select name="gmLevel" class="selectbox" title="회원등급">
															<option value="">-회원등급-</option>
															<?
																for($i=1;$i<count($mLevel)+1;$i++) {
																	if($_GET[gmLevel]==$i) $iselected=" selected"; else $iselected="";
																	echo "<option value=\"".$i."\"".$iselected.">".$mLevel[$i]."</option>";
																}
															?>
														</select>
													</li>
													<li>
														<select name="sex" class="selectbox" title="성별">
															<option value="">-성별-</option>
															<option value="1"<?if($_GET[sex]=="1") echo " selected";?>>남</option>
															<option value="2"<?if($_GET[sex]=="2") echo " selected";?>>여</option>
														</select>
													</li>
													<li>
														<select name="gArea" class="selectbox" title="지역">
															<option value="">-지역-</option>
															<option value="서울"<?if($_GET[gArea]==="서울") echo " selected";?>>서울</option>
															<option value="부산"<?if($_GET[gArea]==="부산") echo " selected";?>>부산</option>
															<option value="대구"<?if($_GET[gArea]==="대구") echo " selected";?>>대구</option>
															<option value="인천"<?if($_GET[gArea]==="인천") echo " selected";?>>인천</option>
															<option value="광주"<?if($_GET[gArea]==="광주") echo " selected";?>>광주</option>
															<option value="대전"<?if($_GET[gArea]==="대전") echo " selected";?>>대전</option>
															<option value="울산"<?if($_GET[gArea]==="울산") echo " selected";?>>울산</option>
															<option value="경기"<?if($_GET[gArea]==="경기") echo " selected";?>>경기</option>
															<option value="강원"<?if($_GET[gArea]==="강원") echo " selected";?>>강원</option>
															<option value="충남"<?if($_GET[gArea]==="충남") echo " selected";?>>충남</option>
															<option value="충북"<?if($_GET[gArea]==="충북") echo " selected";?>>충북</option>
															<option value="경북"<?if($_GET[gArea]==="경북") echo " selected";?>>경북</option>
															<option value="경남"<?if($_GET[gArea]==="경남") echo " selected";?>>경남</option>
															<option value="전북"<?if($_GET[gArea]==="전북") echo " selected";?>>전북</option>
															<option value="전남"<?if($_GET[gArea]==="전남") echo " selected";?>>전남</option>
															<option value="제주"<?if($_GET[gArea]==="제주") echo " selected";?>>제주</option>
														</select>
													</li>
													<li>
														<select name="gField" class="selectbox" title="검색항목선택">
															<option value="">-검색항목선택-</option>
															<option value="mId"<?if($_GET[gField]=="mId") echo " selected";?>>아이디</option>
															<option value="mName"<?if($_GET[gField]=="mName") echo " selected";?>>성 명</option>
															<option value="haddress1"<?if($_GET[gField]=="haddress1") echo " selected";?>>주 소</option>
															<option value="tel"<?if($_GET[gField]=="tel") echo " selected";?>>일반전화</option>
															<option value="sel"<?if($_GET[gField]=="sel") echo " selected";?>>휴대전화</option>
															<option value="email"<?if($_GET[gField]=="email") echo " selected";?>>전자우편</option>
														</select>
													</li>
													<li>
														<input type="text" name="gWord" size="20" maxlength="30" class="textbox" value="<?=$_GET[gWord];?>" title="검색 키워드 입력" />
													</li>
													<li>
														<input type="hidden" name="detailCheck" value="<?=$_GET[detailCheck];?>" /><input type="hidden" name="shopCheck" value="<?=$_GET[shopCheck];?>" />
													</li>
												</ul>
											</td>
											<td rowspan="2"><input type="image" src="img/oSearch.gif" title="검색하기" class="align_right_bottom" /></td>
											<td rowspan="2"><a href="excelExport.php?mode=1&<?=$excelDown;?>" title="엑셀파일로 다운로드"><img src="img/excelicon.gif" class="align_right_bottom" alt="엑셀" /></a></td>
										</tr>
										<tr>
											<th>기간검색</th>
											<td>
												<input type="text" name="start_date" id="start_date" size="12" maxlength="10" value="<?=$_GET[start_date];?>" class="textbox" title="검색 시작날짜 선택" /> ~
												<input type="text" name="stop_date" id="stop_date" size="12" maxlength="10" value="<?=$_GET[stop_date];?>" class="textbox" title="검색 종료날짜 선택" />
												<a href="<?=$_SERVER[PHP_SELF];?>?termMode=1" title="오늘 기준 검색">오늘</a>
												<a href="<?=$_SERVER[PHP_SELF];?>?termMode=2" title="어제 기준 검색">어제</a>
												<a href="<?=$_SERVER[PHP_SELF];?>?termMode=3" title="일주일 기준 검색">1주일</a>
												<a href="<?=$_SERVER[PHP_SELF];?>?termMode=4" title="1개월 기준 검색">1개월</a>
												<input type="checkbox" name="limitDate" value="1" class="align_left_middle"<?if($_GET[limitDate]>0) echo " checked";?> />접수제한
											</td>
										</tr>
									</tbody>
								</table>
								</form>
							</div>

							<div id="boardHead">
								<span class="tblLeft"><?=$page_state;?></span>
								<!--<span class="tblRight">[<a href="#">등록</a>]</span>-->
							</div>

							<div class="wList">
								<table summary="회원 목록">
									<caption>회원 목록</caption>
									<colgroup>
										<col width="5%" />
										<col width="10%" />
										<col width="10%" />
										<col width="40%" />
										<col width="5%" />
										<col width="5%" />
										<col width="15%" />
										<col width="10%" />
									</colgroup>
									<thead>
										<tr>
											<th scope="col"><input type="checkbox" name="allSbox" onclick="allSelect();" title="선택"></th>
											<th scope="col"><a href="<?=$_SERVER[PHP_SELF];?>?aField=mName&aType=<?=$alignType;?>" class="boardBold" title="성명순 정렬">성 명</a></th>
											<th scope="col"><a href="<?=$_SERVER[PHP_SELF];?>?aField=mId&aType=<?=$alignType;?>" class="boardBold" title="아이디순 정렬">아이디</a></th>
											<th scope="col">주 소</th>
											<th scope="col"><a href="<?=$_SERVER[PHP_SELF];?>?aField=login&aType=<?=$alignType;?>" class="boardBold" title="방문순 정렬">방 문</a></th>
											<th scope="col"><a href="<?=$_SERVER[PHP_SELF];?>?aField=ulevel&aType=<?=$alignType;?>" class="boardBold" title="등급순 정렬">등 급</a></th>
											<th scope="col">연락처</th>
											<th scope="col"><a href="<?=$_SERVER[PHP_SELF];?>?aField=signDate&aType=<?=$alignType;?>" class="boardBold" title="가입일순 정렬">가입일</a></th>
										</tr>
									</thead>
									<tbody>
										<?
										if(!$total) echo "<tr><td colspan=\"9\">현재 등록/검색된 회원이 없습니다.</td></tr>";

										for($i = $first; $i <= $last; $i++) {
											$link1="<a href=\"mView.php?page=".$_GET[page]."&keyword=".$_GET[keyword]."&keyfield=".$_GET[keyfield]."&idx=".$view[$i][idx]."\" class=\"basic\">";
											$link2="</a>";
											//== useSms 아직 미설정
											if($smsCorp>0) {
												$smsLink="javascript:void(window.open('/webnics/WebnicsSms/smsForm.php?rid=".$view[$i][id]."', 'smsForm', 'width=220, height=290, status=0, resizable=0, scrollbars=0, 1, 1'));";
												$allSms="javascript:allSms();";
											}else {
												$smsLink="javascript:alert('SMS는 유료서비스입니다.');";
												$allSms="javascript:alert('SMS는 유료서비스입니다.');";
											}
										?>
										<tr>
											<td><input type="checkbox" name="sBox[]" value="<?=$view[$i][idx];?>"></td>
											<td><?=$link1.$view[$i][mName].$link2;?></td>
											<td><?=$link1.$view[$i][mId].$link2;?></td>
											<td class="ListAlign"><?=$view[$i][haddress1];?></td>
											<td><?=number_format($view[$i][login]);?></td>
											<td><?=$mLevel[$view[$i][ulevel]];?></td>
											<td><?=$view[$i][selNum];?></td>
											<td><?=strtr($view[$i][signDate],"-",".");?></td>
											<?if($siteType==2) echo "<td><a href=\"javascript:void(window.open('./buyDetail.php?mName=".$view[$i][name]."&mId=".$view[$i][id]."','buyDetail','width=500,height=500'));\"><img src=\"./img/shopping.gif\" width=\"30\" height=\"14\" align=\"absmiddle\" border=\"0\" alt=\"구매내역\"></a> <a href=\"javascript:void(window.open('./pointDetail.php?mName=".$view[$i][name]."&mId=".$view[$i][id]."','saveDetail','width=500,height=500'));\"><img src=\"./img/point.gif\" width=\"30\" height=\"14\" align=\"absmiddle\" border=\"0\" alt=\"적립내역\"></a></td>";?>
										</tr>
										<?$article_num--; }?>
									</tbody>
								</table>
							</div>

							<div id="boardTail">
								<span class="float_left">[<a href="<?=$allSms;?>">선택SMS</a>]</span>
								<span class="float_right"><?$paging = new paging(); $view_paging=$paging->page_display($total,$num_per_page, $num_per_block,$next); echo $view_paging;?></span>
							</div>
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