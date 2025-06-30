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
	$sqlStr="SELECT * FROM signTbl WHERE idx=$_GET[idx]";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$pContents=stripslashes($view['uContents']);
	if($view['filename0']) $addFile01=" <a href=\"/downLoad.php?downfile=".urlencode($appDir.$view['filename0'])."\">".$view['filename0']."</a>";
	if($view['filename1']) $addFile02=" <a href=\"/downLoad.php?downfile=".urlencode($appDir.$view['filename1'])."\">".$view['filename1']."</a>";
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
					if(!document.setForm.sName.value) { alert('성명을 입력하세요.'); document.setForm.sName.focus(); return false; }
				}

				if(mode == "edit") vtext="수정"; else if(mode == "del") vtext="삭제"; else if(mode == "add") vtext="등록";
				var ok_no = confirm(vtext+" 하시겠습니까?");
				if(ok_no == true) {
					document.setForm.action = 'appExe.php?mode='+mode+'&idx='+idx+'&page='+page;
					document.setForm.method = 'POST';
					document.setForm.submit();
				}else { return; }
			}
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
										document.getElementById('zipCode').value = data.zonecode; //5자리 새우편번호 사용
										document.getElementById('hAddress1').value = fullAddr;
										// 커서를 상세주소 필드로 이동한다.
										document.getElementById('hAddress2').focus();
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
			<?php if($Top_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Top_Inc_File);?>
			<!-- 콘텐츠 시작 -->
			<h2 class="blind"><a name="content-quick" id="content-quick" href="#content-quick">메인 콘텐츠</a></h2>
			<div id="container_wrap">
				<div id="sub_container">
					<!-- 콘텐츠 좌측 -->
					<?php if($Left_Inc_File) include($_SERVER['DOCUMENT_ROOT'].$Left_Inc_File);?>
					<!-- 콘텐츠 메인 -->
					<div id="contents_container">
						<h3 id="headTitle">종전평화 서명 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm" method="post" action="/appExe.php?mode=<?=$_GET['mode'];?>&idx=<?=$view['idx'];?>&page<?=$_GET['page'];?>" enctype="multipart/form-data">
									<fieldset>
										<legend>신청자 관리</legend>
											<h3 style="padding: 5px 0;">필수입력사항</h3>
											<table class="tblComm noThead" width="100%">
												<caption>신청 정보 관리</caption>
												<colgroup>
													<col width="20%" />
													<col width="80%" />
												</colgroup>
												<tbody>
													<tr>
														<th><label>성 명</label></th>
														<td style="text-align:left;"><input type="text" name="sName" id="sName" size="20" maxlength="100" class="wTbox" value="<?=$view['sName'];?>" placeholder="성명 입력" title="성명 입력" /></td>
													</tr>
													<tr>
														<th><label>주 소</label></th>
														<td style="text-align:left;">
															<input type="text" name="aDdress" id="aDdress" size="30" class="wTbox" maxlength="100" value="<?=$view['aDdress'];?>" placeholder="주소 입력" title="주소 입력" />
														</td>
													</tr>
													<tr>
														<th><label>응원한마디</label></th>
														<td style="text-align:left;">
															<input type="text" name="wordSupport" id="wordSupport" size="50" class="wTbox" maxlength="255" value="<?=$view['wordSupport'];?>" placeholder="응원한마디 입력" title="응원한마디 입력" />
														</td>
													</tr>
												</tbody>
											</table>
									</fieldset>
								</form>
							</div>
							<div class="wdiv">
								<?php if($_GET['mode']==="add") $v_text="등 록"; else if($_GET['mode']==="edit") $v_text="수 정";?>
								<input type="submit" value="<?=$v_text;?>" onClick="fCheck('<?=$_GET['mode'];?>','<?=$view['idx'];?>','<?=$_GET['page'];?>'); return false;" class="button" />
								<input type="button" value="목 록" onclick="history.back();" class="button" />
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