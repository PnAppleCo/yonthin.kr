<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'danha'
//== last modify date : 2017. 06. 15
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

if(member_session(1) == false) redirect(1, "/", "관리자 로그인후 이용하세요.", 1);
if($_GET['mode']==='edit') {
	if(!$_GET['idx']) error_view(999, "IDX 정보를 찾을수 없습니다.","관리자에게 문의하시기 바랍니다.");
	$sqlStr="SELECT * FROM cmsList WHERE idx=$_GET[idx]";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$pContents=stripslashes($view['uContents']);
	if($view['filename0']) $addFile01=" <a href=\"".$cmsDir.$view['filename0']."\">".$view['filename0']."</a>";
	if($view['filename1']) $addFile02=" <a href=\"".$cmsDir.$view['filename1']."\">".$view['filename1']."</a>";
}
//==스마트에디터 업로드 폴더 설정
if($_GET['mode']==='edit') {
	$imgFolder="cms_".$view['idx'];
}else if($_GET['mode']==='add') {
	$maxIdx = $db->getOne("SELECT MAX(idx) FROM cmsList");
	if(DB::isError($maxIdx)) die($maxIdx->getMessage());
	if($maxIdx<=0) $newIdx=1; else $newIdx=$maxIdx+1;
	$imgFolder="cms_".$newIdx;
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
			$(function(){
				$('#startDate').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
				$('#stopDate').datepicker({
				 dateFormat: 'yy-mm-dd',
				 showMonthAfterYear:true,
				 buttonText: "달력",
				 monthNames: ['년 1월','년 2월','년 3월','년 4월','년 5월','년 6월','년 7월','년 8월','년 9월','년 10월','년 11월','년 12월'],
				 dayNamesMin: ['일', '월', '화', '수', '목', '금', '토']
				});
			});

			function fCheck(mode, idx, page) {
				if(mode == "add" || mode == "edit") {
					if(!document.setForm.cmsName.value) { alert('콘텐츠명을 입력하세요.'); document.setForm.cmsName.focus(); return false; }
					if(!document.setForm.cmsCode.value) { alert('콘텐츠 코드를 입력하세요.'); document.setForm.cmsCode.focus(); return false; }
					oEditors.getById["uContents"].exec("UPDATE_CONTENTS_FIELD", []);
				if(!document.getElementById("uContents").value || document.getElementById("uContents").value == "<p>&nbsp;</p>") { alert("콘텐츠 내용을 입력하세요"); oEditors[0].exec("FOCUS",[]); return; }
				}

				if(mode == "edit") vtext="수정"; else if(mode == "del") vtext="삭제"; else if(mode == "add") vtext="등록";
				var ok_no = confirm(vtext+"하시겠습니까?");
				if(ok_no == true) {
					document.setForm.action = 'cmsExe.php?mode='+mode+'&idx='+idx+'&page='+page;
					document.setForm.method = 'POST';
					document.setForm.submit();
				}else { return; }
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
						<h3 id="headTitle">콘텐츠 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="setForm" method="post" enctype="multipart/form-data">
									<fieldset>
										<legend>콘텐츠 관리</legend>
											<table summary="콘텐츠 테이블">
												<caption>콘텐츠 테이블</caption>
												<colgroup>
													<col width="15%" />
													<col width="85%" />
												</colgroup>
												<tbody>
													<tr>
														<th>구&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;분</th>
														<td>
															<select name="cmsDivi" class="wSbox">
																<?php for($i=1; $i<=count($cmsArr); $i++) {
																		if($view['cmsDivi']==$i) $pCheck=" selected"; else $pCheck="";
																		echo "<option value=\"".$i."\"".$pCheck.">".$cmsArr[$i]."</option>n";
																	}?>
															</select>
														</td>
													</tr>
													<tr>
														<th><label for="cmsName">콘텐츠명</label></th>
														<td>
															<input type="text" name="cmsName" size="50" maxlength="255" value="<?=$view['cmsName'];?>" class="wTbox" placeholder="콘텐츠명 입력" />
														</td>
													</tr>
													<tr>
														<th><label for="cmsCode">코 드</label></th>
														<td><input type="text" name="cmsCode" size="20" class="wTbox" maxlength="20" value="<?=$view['cmsCode'];?>" placeholder="코드 입력" /></td>
													</tr>
														<th><label for="cmsPath">위 치</label></th>
														<td><input type="text" name="cmsPath" size="30" class="wTbox" maxlength="255" value="<?=$view['cmsPath'];?>" placeholder="위치 입력" /></td>
													</tr>
													<tr>
														<th><label for="uContents">내 용</label></th>
														<td><?php include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/wTools/inc/smartEditor.php";?></td>
													</tr>
													</tr>
														<th><label for="cStatus">퍼블리싱</label></th>
														<td>
															<select name="cStatus" class="wSbox">
																<?php for($i=1; $i<=count($cStatusArr); $i++) {
																		if($view['cStatus']==$i) $pCheck=" selected"; else $pCheck="";
																		echo "<option value=\"".$i."\"".$pCheck.">".$cStatusArr[$i]."</option>n";
																	}?>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
											<input type="hidden" name="upFile[]" value="<?=$view['filename0'];?>" />
											<input type="hidden" name="upFile[]" value="<?=$view['filename1'];?>" />
											<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>" />
									</fieldset>
								</form>
							</div>
							<div class="wdiv">
								<?php if($_GET['mode']==="add") $v_text="등 록"; else if($_GET['mode']==="edit") $v_text="수 정";?>
								<input type="button" value="<?=$v_text;?>" onClick="fCheck('<?=$_GET['mode'];?>','<?=$view['idx'];?>','<?=$_GET['page'];?>'); return false;" class="button" />
								<input type="button" value="삭 제" onClick="fCheck('del','<?=$_GET['idx'];?>','<?=$_GET['page'];?>');" class="button" />
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