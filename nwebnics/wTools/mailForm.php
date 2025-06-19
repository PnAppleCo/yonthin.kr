<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2013. 01. 05
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

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
			function formCheck(thisform) {
				if(!thisform.mailType.value) { alert('메일구분을 선택하세요.'); thisform.mailType.focus(); return;}
				if(thisform.mailType[1].selected) {
					if(!thisform.receiverName.value) { alert('수신회원 닉네임 입력하세요.'); thisform.receiverName.focus(); return;}
					//if(!thisform.receiverEmail.value) { alert('수신회원 이메일을 입력하세요.'); thisform.receiverEmail.focus(); return;}
				}
				if(!thisform.mailSubject.value) { alert('메일제목을 입력하세요.'); thisform.mailSubject.focus(); return;}
				oEditors.getById["mailContents"].exec("UPDATE_CONTENTS_FIELD", []);
				if(!document.getElementById("mailContents").value || document.getElementById("mailContents").value == "<p>&nbsp;</p>") { alert("메일내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }

				if(confirm('작성한 메일을 발송하시겠습니까?')) {
					thisform.action = 'mailSend.php';
					thisform.method = 'POST';
					thisform.submit();
				}else { return; }
			}

			function proviewContents(thisform) {
				var myWin = window.open("", "proviewContents", "width=700,height=600,scrollbars=1,resizable=1,status=0" );
				myWin.document.open();
				myWin.focus();
				myWin.document.write(thisform.mailContents.value);
				myWin.document.write("<div align=\"center\"><input type=\"button\" style=\"font: 9pt 굴림; height:23px; width:80px\" value=\" 닫 기 \" onclick=\"javascript:window.close();\"></div>");
				myWin.document.close();
			}

			function mailSelect(tmp_value) {
				if(!tmp_value) return;
				var obj='sub'+document.mailForm.mailType.value;
				if(document.getElementById) {
				var el = document.getElementById(obj);
				var ar = document.getElementById("masterdiv").getElementsByTagName("p");
					if(el.style.display != "block") {
						for(var i=0; i<ar.length; i++) {
							if(ar[i].style.display = "block") ar[i].style.display = "none";
						}
						el.style.display = "block";
					}else {
						el.style.display = "none";
					}
				}
				if(!tmp_value || tmp_value == 2 || tmp_value == 3) el.style.display = "none";
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
						<h3 id="headTitle">메일링 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="mailForm" enctype="multipart/form-data">
									<fieldset>
										<legend>메일발송</legend>
										<table summary="메일발송">
											<caption>메일발송</caption>
											<colgroup>
												<col width="20%" />
												<col width="80%" />
											</colgroup>
											<tbody>
												<tr>
													<th>메일구분</th>
													<td>
														<select name="mailType" class="selectbox" onchange="mailSelect(document.mailForm.mailType.value);">
															<option value="">-받는회원선택-</option>
															<option value="1">개인회원</option>
															<option value="2">전체회원</option>
															<option value="3">메일수신동의회원</option>
															<option value="4">옵션선택</option>
														</select>
													</td>
												</tr>
												<tr>
													<th></th>
													<td>

														<div id="masterdiv">
															<p id="sub1" style="display: none;">
																닉네임 : <input type="text" name="receiverName" size="12" maxlength="15" class="textbox"> 아이디 : <input type="text" name="receiverId" size="12" maxlength="15" class="textbox"> 메일 : <input type="text" name="receiverEmail" size="30" maxlength="50" class="textbox"> AND 검색 발송
															</p>
															<p id="sub2" style="display: none;"></p>
															<p id="sub3" style="display: none;"></p>
															<p id="sub4" style="display: none;">
																옵션선택 :
																<select name="sex" class="selectbox">
																	<option value="">성별</option>
																	<option value="M">남자</option>
																	<option value="W">여자</option>
																</select>
																<select name="level" class="selectbox">
																	<option value="">회원등급</option>
																	<?
																		for($i=1;$i<count($mLevel)+1;$i++) {
																			if($_GET[gmLevel]==$i) $iselected=" selected"; else $iselected="";
																			echo "<option value=\"".$i."\"".$iselected.">".$mLevel[$i]."</option>";
																		}
																	?>
																</select>
																<!--<input type="checkbox" name="birthDay" value="1" class="align_left_middle">이번달 생일-->
																<input type="checkbox" name="joinDay" value="1" class="align_left_middle">이번달 가입
															</p>
														</div>

													</td>
												</tr>
												<tr>
													<th>메일제목</th>
													<td><input type="text" name="mailSubject" size="62" maxlength="100" class="textbox"></td>
												</tr>
												<tr>
													<td colspan="2">
															<?
																echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
																echo "<textarea id=\"mailContents\" name=\"mailContents\" rows=\"10\" cols=\"100\" style=\"width:100%; height:300px; min-width:610px; display:none;\">".$view[mailContents]."</textarea></div>";
															?>
															<script type="text/javascript">
																var oEditors = [];
																nhn.husky.EZCreator.createInIFrame({
																	oAppRef: oEditors,
																	elPlaceHolder: "mailContents",
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
																	oEditors.getById["mailContents"].exec("PASTE_HTML", [sHTML]);
																}

																function showHTML() {
																	var sHTML = oEditors.getById["mailContents"].getIR();
																	alert(sHTML);
																}

																function submitContents(elClickedObj) {
																	oEditors.getById["mailContents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
																	// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("popup_contents").value를 이용해서 처리하면 됩니다.
																	try {
																		elClickedObj.form.submit();
																	} catch(e) {}
																}

																function setDefaultFont() {
																	var sDefaultFont = '궁서';
																	var nFontSize = 24;
																	oEditors.getById["mailContents"].setDefaultFont(sDefaultFont, nFontSize);
																}
															</script>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="text-align:center;">
														<!--<input type="button" value="미리보기" onClick="proviewContents(this.form); return false;" class="button">-->
														<input type="button" value="발송하기" onClick="formCheck(this.form); return false;" class="button">
														<input type="reset" value="다시작성" class="button">
														<input type="button" value="취소하기" onClick="history.back();" class="button">
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