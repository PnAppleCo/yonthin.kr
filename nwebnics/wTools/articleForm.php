<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2018. 06. 10
//==================================================================
//== 기본정보 로드
include_once ($_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);

if($_GET['mode']==='edit') {
	if(!$_GET['idx']) error_view(999, "IDX 정보를 찾을수 없습니다.","관리자에게 문의하시기 바랍니다.");
	$sqlStr="SELECT * FROM wBoard WHERE idx='".$_GET['idx']."'";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$view['subject']=stripslashes($view['subject']);
	$view['subject']=htmlspecialchars($view['subject']);
	$view['ucontents']=stripslashes($view['ucontents']);
	if($view['filename0']) $addFile01=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename0']."\">".$view['filename0']."</a>";
	if($view['filename1']) $addFile02=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename1']."\">".$view['filename1']."</a>";
	if($view['filename2']) $addFile03=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename2']."\">".$view['filename2']."</a>";
	if($view['filename3']) $addFile04=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename3']."\">".$view['filename3']."</a>";
	if($view['filename4']) $addFile05=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename4']."\">".$view['filename4']."</a>";
	if($view['filename5']) $addFile05=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename5']."\">".$view['filename5']."</a>";
	if($view['filename6']) $addFile05=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename6']."\">".$view['filename6']."</a>";
	if($view['filename7']) $addFile05=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename7']."\">".$view['filename7']."</a>";
	if($view['filename8']) $addFile05=" <a href=\"".$boardDir.$_GET['code']."/".$view['filename8']."\">".$view['filename8']."</a>";

	//==스마트에디터 업로드 폴더 설정(수정)
	$imgFolder="b_".$_GET['code']."_".$view['idx'];
	$bidx=$view['idx'];
}else if($_GET['mode']=='add') {
	//==스마트에디터 업로드 폴더 설정(등록)
	$maxIdx = $db->getOne("SELECT MAX(idx) FROM wBoard");
	if(DB::isError($maxIdx)) die($maxIdx->getMessage());
	if($maxIdx<=0) $newIdx=1; else $newIdx=$maxIdx+1;
	$imgFolder="b_".$_GET['code']."_".$newIdx;
	$bidx=$newIdx;
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
		<script>
			function fCheck(mode, idx, page) {
				if(mode == "add" || mode == "edit") {
					if(!document.setForm.code.value) { alert('게시판을 선택하세요.'); document.setForm.code.focus(); return false; }
					if(!document.setForm.subject.value) { alert('제목을 입력하세요.'); document.setForm.subject.focus(); return false; }
					oEditors.getById["ucontents"].exec("UPDATE_CONTENTS_FIELD", []);
					if(!document.getElementById("ucontents").value || document.getElementById("ucontents").value == "<p>&nbsp;</p>") { alert("글내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}

				if(mode == "edit") vtext="수정"; else if(mode == "del") vtext="삭제"; else if(mode == "add") vtext="등록";
				var ok_no = confirm(vtext+"하시겠습니까?");
				if(ok_no == true) {
					document.setForm.action = 'articleExe.php?mode='+mode+'&idx='+idx+'&page='+page;
					document.setForm.method = 'POST';
					document.setForm.submit();
				}else { return; }
			}

			$(function(){
				$('#etc01').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
				$('#etc02').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
				$('#signdate').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});

			$(document).ready(function(){
				$("#cateA").on("change", function(){
					//== 스마트에디터 업로드 폴더 설정
					var tcode="b_" + $(this).val() + "_" + $("input[type=hidden][name=bidx]").val();
					$('#tcode').val(tcode);
					$.ajax({
							type : "POST",
							async : true,
							url : "returnAjax.php",
							dataType : "html",						//전송받을 데이터의 타입("xml", "html", "script", "json" 등) 미지정시 자동 판단
							timeout : 30000,								//제한시간 지정
							cache : false,									//true, false
							//, data: "uselno="+escape(document.setForm.uselno.value)+"&uname="+escape(document.setForm.uname.value)
							data: "cateA="+$(this).val(),
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
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">게시물 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm" method="post" enctype="multipart/form-data">
									<fieldset>
										<legend>게시물 관리</legend>
										<table summary="게시물 정보 관리">
											<caption>게시물 정보 관리</caption>
											<colgroup>
												<col width="20%" />
												<col width="80%" />
											</colgroup>
											<tbody>
												<tr>
													<th><label class="requiredFeild">게시판구분</label></th>
													<td style="text-align:left; font-weight:600">
														<select name="code" id="cateA" class="wSbox">
															<option value="">선택하세요</option>
															<?=boardCode($view['code']);?>
														</select>
														<select name="b_class" id="cateB" class="wSbox">
															<option value="">선택하세요</option>
															<?php if($view['b_class']) echo "<option value=\"".$view['b_class']."\" selected>".$view['b_class']."</option>";?>
														</select>
													</td>
												</tr>
												<tr>
													<th><label class="requiredFeild">글제목</label></th>
													<td style="text-align:left;">
														<input type="text" name="subject" id="subject" size="80" class="wTbox" maxlength="255" value="<?=$view['subject'];?>" placeholder="글제목 입력" title="글제목 입력" />
													</td>
												</tr>
												<tr>
													<th><label>작성자</label></th>
													<td style="text-align:left;">
														<input type="text" name="name" id="name" size="20" class="wTbox" maxlength="50" value="<?php if(!$view['name']) echo "청년독립지원청"; else echo $view['name'];?>" placeholder="작성자명 입력" title="작성자명 입력" />
													</td>
												</tr>
												<!-- <tr>
													<th><label>글옵션</label></th>
													<td style="text-align:left;">
														<input type="checkbox" name="notice" value="1" class="align_left_middle"> 공지사항
													</td>
												</tr> -->
												<tr>
													<th>글내용</th>
													<td><?php include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/smartEditor.php";?></td>
												</tr>
												<tr>
													<th><label for="filename1">첨부파일1</label></th>
													<td colspan="3">
														<input type="file" name="filename[]" size="35" maxlength="255" class="wTbox" />
														<?php if($view['filename0']) echo "<span style=\"color:#ff6200;\">".$addFile01."</span></a><input type=\"checkbox\" name=\"fChk0\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th><label for="filename2">첨부파일2</label></th>
													<td colspan="3">
														<input type="file" name="filename[]" size="35" maxlength="255" class="wTbox" />
														<?php if($view['filename1']) echo "<span style=\"color:#ff6200;\">".$addFile02."</span></a><input type=\"checkbox\" name=\"fChk1\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th><label for="filename2">첨부파일3</label></th>
													<td colspan="3">
														<input type="file" name="filename[]" size="35" maxlength="255" class="wTbox" />
														<?php if($view['filename2']) echo "<span style=\"color:#ff6200;\">".$addFile03."</span></a><input type=\"checkbox\" name=\"fChk2\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th><label for="filename2">첨부파일4</label></th>
													<td colspan="3">
														<input type="file" name="filename[]" size="35" maxlength="255" class="wTbox" />
														<?php if($view['filename3']) echo "<span style=\"color:#ff6200;\">".$addFile04."</span></a><input type=\"checkbox\" name=\"fChk3\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th><label for="filename2">첨부파일5</label></th>
													<td colspan="3">
														<input type="file" name="filename[]" size="35" maxlength="255" class="wTbox" />
														<?php if($view['filename4']) echo "<span style=\"color:#ff6200;\">".$addFile05."</span></a><input type=\"checkbox\" name=\"fChk4\" value=\"chk\" class=\"align_left_middle\">삭제";?>
													</td>
												</tr>
												<tr>
													<th>참여일정</th>
													<td colspan="3">
														<input type="text" name="etc01" id="etc01" size="15" maxlength="255" class="wTbox" value="<?=$view['etc01'];?>" placeholder="시작일자" />
														<input type="text" name="etc02" id="etc02" size="15" maxlength="255" class="wTbox" value="<?=$view['etc02'];?>" placeholder="종료일자" />
													</td>
												</tr>
												<tr>
													<th>구글독스</th>
													<td colspan="3">
														<input type="text" name="etc03" size="60" maxlength="255" class="wTbox" value="<?=$view['etc03'];?>" />
													</td>
												</tr>
												<tr>
													<th>등록날짜</th>
													<td colspan="3">
														<input type="text" name="signdate" id="signdate" size="15" maxlength="255" class="wTbox" value="<?=$view['signdate'];?>" placeholder="등록날짜" />
														<input type="text" name="signtime" id="signtime" size="15" maxlength="255" class="wTbox" value="<?=$view['signtime'];?>" placeholder="등록시간" />예) 18:19:17
													</td>
												</tr>
												<tr>
													<th>유튜브코드</th>
													<td colspan="3">
														<input type="text" name="youtube" size="30" maxlength="255" class="wTbox" value="<?=$view['youtube'];?>" />
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
										<input type="hidden" name="upFile[]" value="<?=$view['filename6'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename7'];?>" />
										<input type="hidden" name="upFile[]" value="<?=$view['filename8'];?>" />
										<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>" />
										<input type="hidden" name="bidx" id="bidx" value="<?=$bidx;?>" />
										<input type="hidden" name="oCode" id="oCode" value="<?=$view['code'];?>" />
										<input type="hidden" name="MAX_FILE_SIZE" value="<?=$board_info['upload_max_size'];?>" />
									</fieldset>
								</form>
							</div>
							<div class="wdiv">
								<?php if($_GET['mode']=="add") $v_text="등 록"; else if($_GET['mode']=="edit") $v_text="수 정";?>
								<input type="button" value="<?=$v_text;?>" onClick="fCheck('<?=$_GET['mode'];?>','<?=$view['idx'];?>','<?=$_GET['page'];?>'); return false;" class="wBtn" />
								<input type="button" value="삭 제" onClick="fCheck('del','<?=$_GET['idx'];?>','<?=$_GET['page'];?>');" class="wBtn" />
								<input type="button" value="목 록" onclick="history.back();" class="wBtn" />
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