<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 06. 10
//==================================================================
//== 기본정보 로드
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

if($_GET[mode]==='edit') {
	if(!$_GET[idx]) error_view(999, "IDX 정보를 찾을수 없습니다.","관리자에게 문의하시기 바랍니다.");
	$sqlStr="SELECT * FROM bannerTbl WHERE idx=$_GET[idx]";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$view[bName]=stripslashes($view[bName]);
	$view[bName]=htmlspecialchars($view[bName]);
	$homeUrl=htmlspecialchars($view[linkUrl]);
	if($view[filename0]) $addFile01=" <a href=\"".$bannerDir.$view[filename0]."\">".$view[filename0]."</a>";
	if($view[filename1]) $addFile02=" <a href=\"".$bannerDir.$view[filename1]."\">".$view[filename1]."</a>";
}
//==스마트에디터 업로드 폴더 설정
if($_GET[mode]=='edit') {
	$imgFolder="banner_".$view[idx];
}else if($_GET[mode]=='add') {
	$maxIdx = $db->getOne("SELECT MAX(idx) FROM bannerTbl");
	if(DB::isError($maxIdx)) die($maxIdx->getMessage());
	if($maxIdx<=0) $newIdx=1; else $newIdx=$maxIdx+1;
	$imgFolder="banner_".$newIdx;
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
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
		<script>
			function fCheck(mode, idx, page) {
				if(mode == "add" || mode == "edit") {
					//if($(".chkBox:checked").length<=0) { alert('쿠폰북을 하나이상 선택하세요.'); $("input[name='itemName[]']").eq(0).focus();  return false; }
					if(!document.setForm.sections.value) { alert('기업지원 구분을 선택하세요.'); document.setForm.sections.focus(); return false; }
					if(!document.setForm.bName.value) { alert('지원기관명을 입력하세요.'); document.setForm.bName.focus(); return false; }
					oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
					if(!document.getElementById("contents").value || document.getElementById("contents").value == "<p>&nbsp;</p>") { alert("지원내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}

				if(mode == "edit") vtext="수정"; else if(mode == "del") vtext="삭제"; else if(mode == "add") vtext="등록";
				var ok_no = confirm(vtext+"하시겠습니까?");
				if(ok_no == true) {
					document.setForm.action = 'bannerExe.php?mode='+mode+'&idx='+idx+'&page='+page;
					document.setForm.method = 'POST';
					document.setForm.submit();
				}else { return; }
			}

			function openDaumPostcode() {
				new daum.Postcode({
					oncomplete: function(data) {
						// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
						// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
						document.getElementById('zipCode').value = data.zonecode; //5자리 새우편번호 사용
						document.getElementById('hAddress1').value = data.address;
						//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
						//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
						//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
						//document.getElementById('addr').value = addr;
						document.getElementById('hAddress2').focus();
					}
				}).open();
			}

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
									//document.getElementById('zipCode').value = data.zonecode;
									document.getElementById('hAddress1').value = data.address;
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

			$(document).ready(function(){
				$('#cateA').on("change", function(){
					$.ajax({
							type : "POST",
							async : true,
							url : "returnAjax.php",
							dataType : "html",						//전송받을 데이터의 타입("xml", "html", "script", "json" 등) 미지정시 자동 판단
							timeout : 30000,								//제한시간 지정
							cache : false,									//true, false
							//, data: "uselno="+escape(document.setForm.uselno.value)+"&uname="+escape(document.setForm.uname.value)
							data: "cateA="+document.setForm.cateA.value,
							//, data : $("#setForm").serialize() //서버에 보낼 파라메터
							//form에 serialize() 실행시 a=b&c=d 형태로 생성되며 한글은 UTF-8 방식으로 인코딩
							//"a=b&c=d" 문자열로 직접 입력 가능
							contentType: "application/x-www-form-urlencoded; charset=UTF-8",
							error : function(request, status, error) {																										//통신 에러 발생시 처리
							 alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
							},
							success : function(response, status, request) {																				//통신 성공시 처리
								$('#cateB').find("option").remove();
								$('#cateB').append(response);
							},
							beforeSend: function() {																																				//통신을 시작할때 처리
								$('#ajax_indicator').show().fadeIn('fast');
							},
							complete: function() {																																						//통신이 완료된 후 처리
								$('#ajax_indicator').fadeOut();
								$('#ajax_result').fadeIn();
							}
					});
				});
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
			<?if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">기업지원 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm" method="post" enctype="multipart/form-data">
									<fieldset>
										<legend>기업지원 관리</legend>
										<table summary="기업지원 정보 관리">
											<caption>기업지원 정보 관리</caption>
											<colgroup>
												<col width="20%" />
												<col width="80%" />
											</colgroup>
											<tbody>
												<tr>
													<th><label for="sections" class="requiredFeild">기업지원구분</label></th>
													<td colspan="3">
														<select name="sections" id="sections" class="wSbox">
															<option value="">지원구분</option>
															<?for($i=1;$i<=count($sectionArr);$i++) {
																	if($view[sections]==$i) $iselected=" selected"; else $iselected="";
																	echo "<option value=\"".$i."\"".$iselected.">".$sectionArr[$i]."</option>";
																}?>
														</select>
													</td>
												</tr>
												<tr>
													<th><label for="bName" class="requiredFeild">지원기관명</label></th>
													<td><input type="text" name="bName" size="50" maxlength="255" value="<?=$view[bName];?>" class="wTbox" placeholder="지원기관명 입력" /></td>
												</tr>
												<tr>
													<th><label for="linkUrl">홈페이지</label></th>
													<td><input type="text" name="linkUrl" size="70" maxlength="255" value="<?=$view[linkUrl];?>" class="wTbox" placeholder="홈페이지 입력" /></td>
												</tr>

												<tr>
													<th><label for="aSummary" class="requiredFeild">기업지원내용</label></th>
													<td>
														<?
															echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
															echo "<textarea id=\"contents\" name=\"contents\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view[contents]."</textarea></div>";
														?>
														<script type="text/javascript">
															var oEditors = [];
															nhn.husky.EZCreator.createInIFrame({
																oAppRef: oEditors,
																elPlaceHolder: "contents",
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
																oEditors.getById["contents"].exec("PASTE_HTML", [sHTML]);
															}

															function showHTML() {
																var sHTML = oEditors.getById["contents"].getIR();
																alert(sHTML);
															}

															function submitContents(elClickedObj) {
																oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
																// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("contents").value를 이용해서 처리하면 됩니다.
																try {
																	elClickedObj.form.submit();
																} catch(e) {}
															}

															function setDefaultFont() {
																var sDefaultFont = '궁서';
																var nFontSize = 24;
																oEditors.getById["contents"].setDefaultFont(sDefaultFont, nFontSize);
															}
														</script>
													</td>
												</tr>
												<tr>
													<th>배너이미지</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename0]) echo "<span style=\"color:#ff6200;\">".$addFile01."</span></a><input type=\"checkbox\" name=\"fChk0\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<!--
												<tr>
													<th>이미지파일 2[썸]</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename1]) echo "<span style=\"color:#ff6200;\">".$addFile02."</span></a><input type=\"checkbox\" name=\"fChk1\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th>이미지파일 3[썸]</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename2]) echo "<span style=\"color:#ff6200;\">".$addFile03."</span></a><input type=\"checkbox\" name=\"fChk2\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th>이미지파일 4[디]</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename3]) echo "<span style=\"color:#ff6200;\">".$addFile04."</span></a><input type=\"checkbox\" name=\"fChk3\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th>이미지파일 6[디]</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename4]) echo "<span style=\"color:#ff6200;\">".$addFile05."</span></a><input type=\"checkbox\" name=\"fChk4\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th>이미지파일 7[디]</th>
													<td>
														<input type="file" name="filename[]" size="48" maxlength="100" class="wTbox" />
														<?if($view[filename5]) echo "<span style=\"color:#ff6200;\">".$addFile06."</span></a><input type=\"checkbox\" name=\"fChk5\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												-->
												<tr>
													<th><label for="sStatus" class="requiredFeild">노출상태</label></th>
													<td>
														<select name="sStatus" class="wTbox" title="노출상태">
															<option value="">노출상태</option>
															<?for($i=1;$i<=count($bStatus);$i++) {
																	if($view[sStatus]==$i) $iselected=" selected"; else $iselected="";
																	echo "<option value=\"".$i."\"".$iselected.">".$bStatus[$i]."</option>";
																}?>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
										<input type="hidden" name="upFile[]" value="<?=$view[filename0];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view[filename1];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view[filename2];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view[filename3];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view[filename4];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view[filename5];?>" />
										<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>" />
									</fieldset>
								</form>
							</div>
							<div class="wdiv">
								<?if($_GET[mode]=="add") $v_text="등 록"; else if($_GET[mode]=="edit") $v_text="수 정";?>
								<input type="button" value="<?=$v_text;?>" onClick="fCheck('<?=$_GET[mode];?>','<?=$view[idx];?>','<?=$_GET[page];?>'); return false;" class="wBtn" />
								<input type="button" value="삭 제" onClick="fCheck('del','<?=$_GET[idx];?>','<?=$_GET[page];?>');" class="wBtn" />
								<input type="button" value="목 록" onclick="history.back();" class="wBtn" />
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