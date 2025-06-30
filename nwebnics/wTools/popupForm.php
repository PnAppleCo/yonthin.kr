<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2016. 07. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);
if($_GET['mode']==='edit') {
	if(!$_GET['idx']) error_view(999, "IDX 정보를 찾을수 없습니다.","관리자에게 문의하시기 바랍니다.");
	$sqlStr="SELECT * FROM wPopup WHERE idx=$_GET[idx]";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$o_popup_contents=stripslashes($view['popupTitle']);
	//if($view[html]>0) $o_popup_contents = htmlspecialchars($o_popup_contents);
	if($view['filename0']) $addFile01=" <a href=\"".$popupDir.$view['filename0']."\">".$view['filename0']."</a>";
	if($view['filename1']) $addFile02=" <a href=\"".$popupDir.$view['filename1']."\">".$view['filename1']."</a>";
	if($view['filename2']) $addFile03=" <a href=\"".$popupDir.$view['filename2']."\">".$view['filename2']."</a>";
	if($view['filename3']) $addFile04=" <a href=\"".$popupDir.$view['filename3']."\">".$view['filename3']."</a>";
	if($view['filename4']) $addFile05=" <a href=\"".$popupDir.$view['filename4']."\">".$view['filename4']."</a>";
	if($view['filename5']) $addFile06=" <a href=\"".$popupDir.$view['filename5']."\">".$view['filename5']."</a>";
}

//==스마트에디터 업로드 폴더 설정
if($_GET['mode']==='edit') {
	$imgFolder="popup_".$view['idx'];
}else if($_GET['mode']==='add') {
	$maxIdx = $db->getOne("SELECT MAX(idx) FROM wPopup");
	if(DB::isError($maxIdx)) die($maxIdx->getMessage());
	if($maxIdx<=0) $newIdx=1; else $newIdx=$maxIdx+1;
	$imgFolder="popup_".$newIdx;
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
		<script type="text/javascript">
			$(function(){
				$('#startDate').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});
			$(function(){
				$('#stopDate').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});

		function formCheck(mode,m_idx,v_page) {
			var thisform=document.popupForm;
			if(!thisform.popupTitle.value) { alert("팝업타이틀을 입력하세요!"); thisform.popupTitle.focus(); return; }
			if(!thisform.startDate.value) { alert("시작일을 선택하세요!"); thisform.startDate.focus(); return; }
			if(!thisform.stopDate.value) { alert("종료일을 선택하세요!"); thisform.stopDate.focus(); return; }
			oEditors.getById["uContents"].exec("UPDATE_CONTENTS_FIELD", []);
			//if(!document.getElementById("uContents").value || document.getElementById("uContents").value == "<p>&nbsp;</p>") { alert("글내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }

			if(mode == "edit") vtext="수정"; else if(mode == "del") vtext="삭제"; else vtext="등록";
			var ok_no = confirm(vtext+"하시겠습니까?");
			if(ok_no == true) {
				thisform.action = 'popupExe.php?mode='+mode+'&idx='+m_idx+'&page='+v_page;
				thisform.method = 'POST';
				thisform.submit();
			}else { return; }
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
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">팝업창 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="popupForm" enctype="multipart/form-data">
									<fieldset>
										<legend>팝업존 정보 관리</legend>
										<table summary="팝업 정보 등록 및 수정 테이블">
											<caption>팝업존 정보 관리</caption>
											<colgroup>
												<col width="20%" />
												<col width="80%" />
											</colgroup>
											<tbody>
												<tr>
													<th>팝업제목</th>
													<td><input type="text" name="popupTitle" size="80" maxlength="255" class="wTbox" value="<?=$view['popupTitle'];?>" placeholder="팝업 제목 입력" /></td>
												</tr>
												<tr>
													<th>팝업종류</th>
													<td>
														<input type="radio" name="popupType" value="1"<?php if($view['popupType']==1) echo " checked";?> class="align_left_middle" />새창
														<input type="radio" name="popupType" value="2"<?php if($view['popupType']==2) echo " checked";?> class="align_left_middle" />레이어
														<input type="radio" name="popupType" value="3"<?php if($view['popupType']==3) echo " checked";?> class="align_left_middle" />슬라이드
													</td>
												</tr>
												<tr>
													<th>팝업옵션</th>
													<td>
														<input type="checkbox" name="ingTime" value="1"<?php if($view['ingTime']==1) echo " checked";?> class="align_left_middle" />1일제한
														<input type="checkbox" name="scrollbar" value="1"<?php if($view['scrollbar']==1) echo " checked";?> class="align_left_middle" />스크롤바
													</td>
												</tr>
												<tr>
													<th>시작/종료</th>
													<td>
														<input type="text" id="startDate" name="startDate" size="10" class="wTbox" maxlength="10" value="<?=$view['startDate'];?>" placeholder="시작일 선택" /> ~
														<input type="text" id="stopDate" name="stopDate" size="10" class="wTbox" maxlength="10" value="<?=$view['stopDate'];?>" placeholder="종료일 선택" />
													</td>
												</tr>
												<tr>
													<th>창 크 기</th>
													<td>
														<input type="text" name="popWidth" size="5" maxlength="5" class="wTbox" value="<?=$view['popWidth'];?>" /> /
														<input type="text" name="popHeight" size="5" maxlength="5" class="wTbox" value="<?=$view['popHeight'];?>" />
													</td>
												</tr>
												<tr>
													<th>창 위 치</th>
													<td>
														<input type="text" name="locationTop" size="5" maxlength="5" class="wTbox" value="<?=$view['locationTop'];?>" /> /
														<input type="text" name="locationLeft" size="5" maxlength="5" class="wTbox" value="<?=$view['locationLeft'];?>" /> 좌측/상단
													</td>
												</tr>
												<tr>
													<th>링크주소</th>
													<td>
														<input type="text" name="linkUrl" size="30" maxlength="255" class="wTbox" value="<?=$view['linkUrl'];?>" placeholder="링크주소 입력" />
														<select name="linkTarget" class="wSbox">
															<option value="1"<?php if($view['linkTarget']==1) echo " selected";?>>자체</option>
															<option value="2"<?php if($view['linkTarget']==2) echo " selected";?>>새창</option>
															<option value="3"<?php if($view['linkTarget']==3) echo " selected";?>>부모</option>
														</select>
														<font color="#FF6200">※ 절대주소</font>
													</td>
												</tr>
												<tr>
													<th>팝업파일</th>
													<td>
														<input type="file" name="filename[]" size="30" maxlength="255" />(1080 * 420)
														<?php if($view['filename0']) echo "<span style=\"color:#ff6200;\">".$addFile01."</span></a><input type=\"checkbox\" name=\"fChk0\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
<!--
												<tr>
													<th>팝업파일(mobile)</th>
													<td>
														<input type="file" name="filename[]" size="30" maxlength="255" />(426 * 354)
														<?php if($view['filename1']) echo "<span style=\"color:#ff6200;\">".$addFile02."</span></a><input type=\"checkbox\" name=\"fChk1\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
 -->
												<tr>
													<th>팝업내용</th>
													<td>
														<?php
															echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
															echo "<textarea id=\"uContents\" name=\"uContents\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view['uContents']."</textarea></div>";
														?>
														<script type="text/javascript">
															var oEditors = [];
															nhn.husky.EZCreator.createInIFrame({
																oAppRef: oEditors,
																elPlaceHolder: "uContents",
																sSkinURI: "/nwebnics/htmlEditor/SE2.3.10/SmartEditor2Skin.html",
																htParams : {
																	bUseToolbar : true,								// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
																	bUseVerticalResizer : true,			// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
																	bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
																	//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
																	fOnBeforeUnload : function(){
																		//alert("완료!");
																	}
																}, //boolean
																fOnAppLoad : function(){
																	//예제 코드
																	//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
																},
																fCreator: "createSEditor2"
															});

															function pasteHTML() {
																var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
																oEditors.getById["uContents"].exec("PASTE_HTML", [sHTML]);
															}

															function showHTML() {
																var sHTML = oEditors.getById["uContents"].getIR();
																alert(sHTML);
															}

															function submitContents(elClickedObj) {
																oEditors.getById["uContents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
																// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("uContents").value를 이용해서 처리하면 됩니다.
																try {
																	elClickedObj.form.submit();
																} catch(e) {}
															}

															function setDefaultFont() {
																var sDefaultFont = '궁서';
																var nFontSize = 24;
																oEditors.getById["uContents"].setDefaultFont(sDefaultFont, nFontSize);
															}
														</script>
													</td>
												</tr>
											</tbody>
										</table>
										<input type="hidden" name="upFile[]" value="<?=$view['filename0'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename1'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename2'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename3'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename4'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename5'];?>" />
										<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>" />
									</fieldset>
								</form>
							</div>
							<div class="wdiv">
								<?php if($_GET['mode']==="add") $v_text="등 록"; else if($_GET['mode']==="edit") $v_text="수 정";?>
									<input type="button" value="<?=$v_text;?>" onClick="formCheck('<?=$_GET['mode'];?>','<?=$view['idx'];?>','<?=$_GET['page'];?>'); return false;" class="button">
								<input type="button" value="뒤 로" onclick="history.back();" class="button">
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