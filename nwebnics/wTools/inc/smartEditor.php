												<?php
													echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
													echo "<textarea id=\"ucontents\" name=\"ucontents\" rows=\"10\" cols=\"100\" style=\"width:100%; height:500px; min-width:610px; display:none;\">".$view['ucontents']."</textarea></div>";
												?>
												<script type="text/javascript">
													var oEditors = [];
													nhn.husky.EZCreator.createInIFrame({
														oAppRef: oEditors,
														elPlaceHolder: "ucontents",
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
														oEditors.getById["ucontents"].exec("PASTE_HTML", [sHTML]);
													}

													function showHTML() {
														var sHTML = oEditors.getById["ucontents"].getIR();
														alert(sHTML);
													}

													function submitContents(elClickedObj) {
														oEditors.getById["ucontents"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
														// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ucontents").value를 이용해서 처리하면 됩니다.
														try {
															elClickedObj.form.submit();
														} catch(e) {}
													}

													function setDefaultFont() {
														var sDefaultFont = '궁서';
														var nFontSize = 24;
														oEditors.getById["ucontents"].setDefaultFont(sDefaultFont, nFontSize);
													}
												</script>