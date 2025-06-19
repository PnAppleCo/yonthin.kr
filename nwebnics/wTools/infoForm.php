<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2015. 01. 06
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);
if($_GET[mode]==='edit') {
	if(!$_GET[idx]) error_view(999, "IDX 정보를 찾을수 없습니다.","관리자에게 문의하시기 바랍니다.");
	$sql_str="SELECT * FROM infoTbl WHERE idx=$_GET[idx]";
	$view = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$o_agreeinfo=stripslashes($view[agreeinfo]);
	$o_privateinfo=stripslashes($view[privateinfo]);
	$o_privat_agree=stripslashes($view[private_agree]);
	if($_GET[code]=="1") $vTitle="사업정보"; else if($_GET[code]=="2") $vTitle="이용약관"; else if($_GET[code]=="3") $vTitle="개인정보보호방침"; else if($_GET[code]=="4") $vTitle="사이트 정보"; else if($_GET[code]=="5") $vTitle="개인정이용 및 파기";
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
		<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
		<script type="text/javascript">
			function formChk(thisform,mode,m_idx,v_page, code) {
				if(code == '1') {
					if(!thisform.c_name.value) { alert("상호명을 입력하세요!"); thisform.c_name.focus(); return; }
					if(!thisform.haddress1.value) { alert("사업장주소를 입력하세요!"); thisform.haddress1.focus(); return; }
					if(!thisform.haddress2.value) { alert("사업장 나머지 주소를 입력하세요!"); thisform.haddress2.focus(); return; }
					if(!thisform.c_tel.value) { alert("회사 전화번호를 입력하세요!"); thisform.c_tel.focus(); return; }
					if(!thisform.c_fax.value) { alert("회사 팩스번호를 입력하세요!"); thisform.c_fax.focus(); return; }
				}else if(code == '2') {
					oEditors.getById["agreeinfo"].exec("UPDATE_CONTENTS_FIELD", []);
					if(!document.getElementById("agreeinfo").value || document.getElementById("agreeinfo").value == "<p>&nbsp;</p>") { alert("이용약관을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}else if(code == '3') {
					oEditors.getById["privateinfo"].exec("UPDATE_CONTENTS_FIELD", []);
					if(!document.getElementById("privateinfo").value || document.getElementById("privateinfo").value == "<p>&nbsp;</p>") { alert("개인정보 보호정책을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}else if(code == '4') {
					if(!thisform.c_title.value) { alert("상호명을 입력하세요!"); thisform.c_title.focus(); return; }
					if(!thisform.c_meta.value) { alert("업태를 입력하세요!"); thisform.c_meta.focus(); return; }
				}else if(code == '5') {
					oEditors.getById["private_agree"].exec("UPDATE_CONTENTS_FIELD", []);
					if(!document.getElementById("private_agree").value || document.getElementById("private_agree").value == "<p>&nbsp;</p>") { alert("개인정보 이용 및 파기정책을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}
				if(mode=='edit') {
					thisform.action = './infoExe.php?mode='+mode+'&idx='+m_idx+'&page='+v_page+'&code='+code;
					thisform.method = 'POST';
					thisform.submit();
				}else if(mode=='add') {
					thisform.action = './infoExe.php?mode='+mode;
					thisform.method = 'POST';
					thisform.submit();
				}else {
					alert('작업모드가 선택되지 않았습니다.!');
				}
			}
			function isNull( text ) {
				if( text == null ) return true;
				var result = text.replace(/(^\s*)|(\s*$)/g, "");
				if( result ) return false; else return true;
			}
		//-->
		</script>
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
		<script>
				function DaumPostcode(mode) {
					new daum.Postcode({
						oncomplete: function(data) {
							// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

							// 각 주소의 노출 규칙에 따라 주소를 조합한다.
							// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
							var fullAddr = ''; // 최종 주소 변수
							var extraAddr = ''; // 조합형 주소 변수

							// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
							if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
									fullAddr = data.roadAddress;

							} else { // 사용자가 지번 주소를 선택했을 경우(J)
									fullAddr = data.jibunAddress;
							}

							// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
							if(data.userSelectedType === 'R'){
									//법정동명이 있을 경우 추가한다.
									if(data.bname !== ''){
											extraAddr += data.bname;
									}
									// 건물명이 있을 경우 추가한다.
									if(data.buildingName !== ''){
											extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
									}
									// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
									fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
							}
							if(mode == 1) {
								// 우편번호와 주소 정보를 해당 필드에 넣는다.
								document.getElementById('zipcode').value = data.zonecode; //5자리 새우편번호 사용
								document.getElementById('haddress1').value = fullAddr;
								// 커서를 상세주소 필드로 이동한다.
								document.getElementById('haddress2').focus();
							}else {
								// 우편번호와 주소 정보를 해당 필드에 넣는다.
								document.getElementById('czipcode').value = data.zonecode; //5자리 새우편번호 사용
								document.getElementById('caddress1').value = fullAddr;
								// 커서를 상세주소 필드로 이동한다.
								document.getElementById('caddress2').focus();
							}
						}
					}).open();
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
						<h3 id="headTitle"><?=$vTitle;?></h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm" enctype="multipart/form-data">
									<fieldset>
										<legend>정보존 정보 관리</legend>
										<table summary="정보 정보 등록 및 수정 테이블">
											<caption>정보존 정보 관리</caption>
											<colgroup>
												<col width="15%" />
												<col width="35%" />
												<col width="15%" />
												<col width="35%" />
											</colgroup>
											<tbody>
												<?if($_GET[code]=="1") {?>
												<tr>
													<th>상 호 명</th>
													<td colspan="3"><input type="text" name="c_name" size="40" maxlength="100" class="wTbox" value="<?=$view[c_name];?>" /></td>
												</tr>
												<tr>
													<th>업 태</th>
													<td><input type="text" name="c_cate" size="20" maxlength="100" class="wTbox" value="<?=$view[c_cate];?>" /></td>
													<th>종 목</th>
													<td><input type="text" name="c_event" size="20" maxlength="100" class="wTbox" value="<?=$view[c_event];?>" /></td>
												</tr>
												<tr>
													<th><label for="hzipcode1">사업장주소</label></th>
													<td colspan="3">
														<div><input type="text" id="zipcode" name="zipcode" size="5" maxlength="5" value="<?=$view[zipcode];?>" class="wTbox" readonly title="우편번호"><span onClick="DaumPostcode('1');" style="cursor:pointer;">주소찾기</span></div>
															<div><input type="text" id="haddress1" name="haddress1" size="45" maxlength="60" value="<?=$view[haddress1];?>" class="wTbox" title="주소 입력">
															<input type="text" id="haddress2" name="haddress2" size="30" maxlength="30" value="<?=$view[haddress2];?>" class="wTbox" title="나머지 상세주소 입력"></div>
													</td>
												</tr>
												<tr>
													<th>사업자번호</th>
													<td><input type="text" name="c_num" size="20" maxlength="20" class="wTbox" value="<?=$view[c_num];?>" /></td>
													<th>통신판매신고번호</th>
													<td><input type="text" name="c_t_num" size="20" maxlength="50" class="wTbox" value="<?=$view[c_t_num];?>" /></td>
												</tr>
												<tr>
													<th>대표자명</th>
													<td><input type="text" name="c_ceo" size="20" maxlength="20" class="wTbox" value="<?=$view[c_ceo];?>" /></td>
													<th>개인정보책임자명</th>
													<td><input type="text" name="c_user" size="20" maxlength="20" class="wTbox" value="<?=$view[c_user];?>" /></td>
												</tr>
												<tr>
													<th>전화번호</th>
													<td><input type="text" name="c_tel" size="15" maxlength="20" class="wTbox" value="<?=$view[c_tel];?>" /></td>
													<th>팩스번호</th>
													<td><input type="text" name="c_fax" size="15" maxlength="20" class="wTbox" value="<?=$view[c_tel];?>" /></td>
												</tr>
												<?}else if($_GET[code]=="2") {?>
												<tr>
													<td colspan="4">
													<?
														echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
														echo "<textarea id=\"agreeinfo\" name=\"agreeinfo\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view[agreeinfo]."</textarea></div>";
													?>
													<script type="text/javascript">
														var oEditors = [];
														nhn.husky.EZCreator.createInIFrame({
															oAppRef: oEditors,
															elPlaceHolder: "agreeinfo",
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
															oEditors.getById["agreeinfo"].exec("PASTE_HTML", [sHTML]);
														}

														function showHTML() {
															var sHTML = oEditors.getById["agreeinfo"].getIR();
															alert(sHTML);
														}

														function submitContents(elClickedObj) {
															oEditors.getById["agreeinfo"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
															// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("agreeinfo").value를 이용해서 처리하면 됩니다.
															try {
																elClickedObj.form.submit();
															} catch(e) {}
														}

														function setDefaultFont() {
															var sDefaultFont = '궁서';
															var nFontSize = 24;
															oEditors.getById["agreeinfo"].setDefaultFont(sDefaultFont, nFontSize);
														}
													</script>
													</td>
												</tr>
												<?}else if($_GET[code]=="3") {?>
												<tr>
													<td colspan="4">
													<?
														echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
														echo "<textarea id=\"privateinfo\" name=\"privateinfo\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view[privateinfo]."</textarea></div>";
													?>
													<script type="text/javascript">
														var oEditors = [];
														nhn.husky.EZCreator.createInIFrame({
															oAppRef: oEditors,
															elPlaceHolder: "privateinfo",
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
															oEditors.getById["privateinfo"].exec("PASTE_HTML", [sHTML]);
														}

														function showHTML() {
															var sHTML = oEditors.getById["privateinfo"].getIR();
															alert(sHTML);
														}

														function submitContents(elClickedObj) {
															oEditors.getById["privateinfo"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
															// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("privateinfo").value를 이용해서 처리하면 됩니다.
															try {
																elClickedObj.form.submit();
															} catch(e) {}
														}

														function setDefaultFont() {
															var sDefaultFont = '궁서';
															var nFontSize = 24;
															oEditors.getById["privateinfo"].setDefaultFont(sDefaultFont, nFontSize);
														}
													</script>
													</td>
												</tr>
												<?}else if($_GET[code]=="4") {?>
												<tr>
													<th>사이트 타이틀</th>
													<td colspan="3"><input type="text" name="c_title" size="50" maxlength="255" class="wTbox" value="<?=$view[c_title];?>" /></td>
												</tr>
												<tr>
													<th>메타키워드</th>
													<td colspan="3"><input type="text" name="c_meta" size="50" maxlength="255" class="wTbox" value="<?=$view[c_meta];?>" /></td>
												</tr>
												<tr>
													<th>가입 불가ID</th>
													<td colspan="3"><input type="text" name="c_noid" size="50" maxlength="255" class="wTbox" value="<?=$view[c_noid];?>" /></td>
												</tr>
												<?}else if($_GET[code]=="5") {?>
												<tr>
													<td colspan="4">
													<?
														echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
														echo "<textarea id=\"private_agree\" name=\"private_agree\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view[private_agree]."</textarea></div>";
													?>
													<script type="text/javascript">
														var oEditors = [];
														nhn.husky.EZCreator.createInIFrame({
															oAppRef: oEditors,
															elPlaceHolder: "private_agree",
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
															oEditors.getById["private_agree"].exec("PASTE_HTML", [sHTML]);
														}

														function showHTML() {
															var sHTML = oEditors.getById["private_agree"].getIR();
															alert(sHTML);
														}

														function submitContents(elClickedObj) {
															oEditors.getById["private_agree"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
															// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("private_agree").value를 이용해서 처리하면 됩니다.
															try {
																elClickedObj.form.submit();
															} catch(e) {}
														}

														function setDefaultFont() {
															var sDefaultFont = '궁서';
															var nFontSize = 24;
															oEditors.getById["private_agree"].setDefaultFont(sDefaultFont, nFontSize);
														}
													</script>
													</td>
												</tr>
												<?}?>
												<tr>
													<td colspan="4" style="text-align:center;">
														<input type="hidden" name="upfile" value="<?=$view[filename0];?>">
														<?if($_GET[mode]==="add") $v_text="등 록"; else if($_GET[mode]==="edit") $v_text="수 정";?>
															<input type="button" value="<?=$v_text;?>" onClick="formChk(this.form,'<?=$_GET[mode];?>','<?=$view[idx];?>','<?=$_GET[page];?>','<?=$_GET[code];?>'); return false;" class="button">
														<input type="reset" value="재작성" class="button">
														<input type="button" value="뒤 로" onclick="history.back();" class="button">
													</td>
												</tr>
											</tbody>
										</table>
									</fieldset>
								</form>
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