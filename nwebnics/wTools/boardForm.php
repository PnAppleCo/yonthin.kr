<?php
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2016. 02. 22
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");
session_cache_limiter('nocache, must-revalidate');

if(member_session(1) == false) redirect(1, "/nwebnics/wTools/", "관리자 로그인후 이용하세요.", 1);

if($_GET['mode'] === "edit") {																													//== 수정
	if(!$_GET['idx']) js_action(1, "중요정보를 찾을수 없습니다.","",-1);
	$sql_str="SELECT * FROM $b_cfg_tb[0] WHERE idx='$_GET[idx]'";
	$board_info = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
	if(DB::isError($board_info)) die($board_info->getMessage());
	$button_info="수정하기";
}else {																																												//== 등록
	//== 등록된 게시판이 있는지 확인
	$board_total = $db->getOne("SELECT COUNT(idx) FROM $b_cfg_tb[0]");
	if(DB::isError($board_total)) die($board_total->getMessage());
	if($board_total>0) {
		$sql_str="SELECT * FROM $b_cfg_tb[0] LIMIT 1";
		$board_info = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
		if(DB::isError($board_info)) die($board_info->getMessage());
	}
	$button_info="등록하기";
}
$board_info['board_head'] = stripslashes($board_info['board_head']);
$board_info['board_tail'] = stripslashes($board_info['board_tail']);
$board_info['title_bar_text'] = stripslashes($board_info['title_bar_text']);
$board_info['meta_keyword'] = stripslashes($board_info['meta_keyword']);
$board_info['doctype'] = htmlspecialchars($board_info['doctype']);

//== 스킨 출력
$dir_path = $_SERVER["DOCUMENT_ROOT"]."/nwebnics/wBoard/skin/";
$now_dir=explode("|",Dir_View($dir_path,"올바른 디렉토리가 선택되지 않았습니다."));
for($i=0; $i<count($now_dir); $i++) {
	if($board_info['skin'] === $now_dir[$i]) $now_dir_select=" selected"; else $now_dir_select="";
	$o_skin .= "<option value=\"".$now_dir[$i]."\"".$now_dir_select.">".$now_dir[$i]."</option>\n";
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
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/formCheck.js"></script>
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
						<h3 id="headTitle">게시판 관리</h3>
						<!-- 콘텐츠 시작 -->
						<div id="contentsBody">

							<div class="wView">
								<form name="signform">
									<fieldset>
										<legend>게시판 관리 정보입력</legend>
											<p class="titleClass">게시판 기보정보</p>
												<table summary="게시판 등록.">
													<caption>게시판관리</caption>
														<colgroup>
														<col width="17%" />
														<col width="28%" />
														<col width="17%" />
														<col width="28%" />
													</colgroup>
													<tbody>
														<tr>
															<th><label for="code">게시판 코드</label></th>
															<td><input type="text" name="code" size="12" maxlength="12" value="<?=$board_info['code'];?>" class="wTbox" <?php if($_GET['mode']==="edit") echo" readonly";?> title="게시판코드"></td>
															<th>스킨설정</th>
															<td>
																<select name="skin" class="wSbox">
																	<?=$o_skin;?>
																</select>
															</td>
														</tr>
														<tr>
															<th>게시판 설명</th>
															<td colspan="3"><input type="text" name="board_summary" size="25" maxlength="255" value="<?=$board_info['board_summary'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>게시판 타이틀</th>
															<td colspan="3"><input type="text" name="title_bar_text" size="40" maxlength="255" value="<?=$board_info['title_bar_text'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>메타키워드</th>
															<td colspan="3"><input type="text" name="meta_keyword" size="50" maxlength="255" value="<?=$board_info['meta_keyword'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>문서종류</th>
															<td colspan="3"><input type="text" name="doctype" size="50" maxlength="255" value="<?=$board_info['doctype'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>문자셋</th>
															<td><input type="text" name="charset" size="20" maxlength="50" value="<?=$board_info['charset'];?>" class="wTbox"></td>
															<th>언어셋</th>
															<td><input type="text" name="language" size="20" maxlength="50" value="<?=$board_info['language'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>전체목록수</th>
															<td><input type="text" name="num_per_page" size="2" maxlength="2" value="<?=$board_info['num_per_page'];?>" class="wTbox"></td>
															<th>전체블럭수</th>
															<td><input type="text" name="num_per_block" size="2" maxlength="2" value="<?=$board_info['num_per_block'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>답변글 Indent</th>
															<td><input type="text" name="reply_indent" size="2" maxlength="2" value="<?=$board_info['reply_indent'];?>" class="wTbox"></td>
															<th>핫클릭</th>
															<td><input type="text" name="hotclick" size="5" maxlength="5" value="<?=$board_info['hotclick'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>성인전용</th>
															<td><input type="checkbox" name="adult" value="1"<?php if($board_info['adult']==1) echo " checked";?>></td>
															<th>리플제거</th>
															<td><input type="checkbox" name="noreplebtn" value="1"<?php if($board_info['noreplebtn']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>답변글 삭제 제한</th>
															<td><input type="checkbox" name="allow_delete_thread" value="1"<?php if($board_info['allow_delete_thread']==1) echo " checked";?>></td>
															<th>미리보기설정</th>
															<td><input type="checkbox" name="overview" value="1"<?php if($board_info['overview']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>선택읽기 선택</th>
															<td><input type="checkbox" name="twinread" value="1"<?php if($board_info['twinread']==1) echo " checked";?>></td>
															<th>게시물 승인형</th>
															<td><input type="checkbox" name="approve" value="1"<?php if($board_info['approve']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>목록파일보기</th>
															<td><input type="checkbox" name="pds_view" value="1"<?php if($board_info['pds_view']==1) echo " checked";?>></td>
															<th>이미지만 업로드</th>
															<td><input type="checkbox" name="only_img" value="1"<?php if($board_info['only_img']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>쎔네일(넓이)</th>
															<td><input type="text" name="thumbnail_width" size="3" maxlength="3" value="<?=$board_info['thumbnail_width'];?>" class="wTbox"></td>
															<th>썸네일(폭)</th>
															<td><input type="text" name="thumbnail_height" size="3" maxlength="3" value="<?=$board_info['thumbnail_height'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>업로드 최대크기</th>
															<td><input type="text" name="upload_max_size" size="12" maxlength="12" value="<?=$board_info['upload_max_size'];?>" class="wTbox"></td>
															<th>업로드 파일수</th>
															<td><input type="text" name="upload_count" size="2" maxlength="2" value="<?=$board_info['upload_count'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>업로드이미지최대크기</th>
															<td><input type="text" name="img_max_upload_width" size="4" maxlength="4" value="<?=$board_info['img_max_upload_width'];?>" class="wTbox">px</td>
															<th>이미지뷰 넓이</th>
															<td><input type="text" name="img_view_size" size="4" maxlength="4" value="<?=$board_info['img_view_size'];?>" class="wTbox">px</td>
														</tr>
														<tr>
															<th>관련글 보기</th>
															<td>
																<select name="relation_text" class="wSbox">
																	<option value=""<?php if($board_info['relation_text'] == 0) echo " selected";?>>관련글 없음</option>
																	<option value="1"<?php if($board_info['relation_text'] == 1) echo " selected";?>>이전/이후글</option>
																	<option value="2"<?php if($board_info['relation_text'] == 2) echo " selected";?>>전체게시물</option>
																	<option value="3"<?php if($board_info['relation_text'] == 3) echo " selected";?>>관련 리플</option>
																</select>
															</td>
															<th>코멘트 사용</th>
															<td><input type="checkbox" name="write_comment" value="1"<?php if($board_info['write_comment']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>코멘트 글자수제한</th>
															<td><input type="text" name="comment_limit" size="4" maxlength="4" value="<?=$board_info['comment_limit'];?>" class="wTbox"></td>
															<th>카테고리</th>
															<td><input type="text" name="board_class" size="25" maxlength="100" value="<?=$board_info['board_class'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>글작성시 쿠키설정</th>
															<td><input type="checkbox" name="set_cookie_data" value="1"<?php if($board_info['set_cookie_data']==1) echo " checked";?>></td>
															<th>글추천기능</th>
															<td><input type="checkbox" name="recommend" value="1"<?php if($board_info['recommend']==1) echo " checked";?>></td>
														</tr>
														<tr>
															<th>로그인방법</th>
															<td>
																<select name="log_info" class="wSbox">
																	<option value="1"<?php if($board_info['log_info'] == 1) echo " selected";?>>세션</option>
																	<option value="2"<?php if($board_info['log_info'] == 2) echo " selected";?>>쿠키</option>
																	<option value="3"<?php if($board_info['log_info'] == 3) echo " selected";?>>기타</option>
																</select>
															</td>
															<th>제목줄임</th>
															<td><input type="text" name="subject_cut" size="5" maxlength="5" value="<?=$board_info['subject_cut'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>레이어 호출</th>
															<td><input type="text" name="view_layer" size="20" maxlength="255" value="<?=$board_info['view_layer'];?>" class="wTbox"></td>
															<th>메뉴깊이</th>
															<td><input type="text" name="menu_depth" size="25" maxlength="255" value="<?=$board_info['menu_depth'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>관리자아이디</th>
															<td><input type="text" name="adminid" size="12" maxlength="12" value="<?=$board_info['adminid'];?>" class="wTbox"></td>
															<th>관리자 비밀번호</th>
															<td><input type="text" name="adminpass" size="12" maxlength="12" value="<?=$board_info['adminpass'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>정렬기준</th>
															<td colspan="3">
																<select name="alignField" class="wSbox">
																	<option value="1"<?php if($board_info['alignField'] == 1) echo " selected";?>>순서</option>
																	<option value="2"<?php if($board_info['alignField'] == 2) echo " selected";?>>날짜</option>
																</select>
															</td>
														</tr>
														<tr>
															<th>베스트글 추출</th>
															<td><input type="checkbox" name="best_img" value="1"<?php if($board_info['best_img']>0) echo " checked";?>></td>
															<th>Html Editor</th>
															<td>
																<select name="html_editor" class="wSbox">
																	<option value="0"<?php if($board_info['html_editor'] == 0) echo " selected";?>>Default</option>
																	<option value="1"<?php if($board_info['html_editor'] == 1) echo " selected";?>>fckeditor1</option>
																	<option value="2"<?php if($board_info['html_editor'] == 2) echo " selected";?>>fckeditor2</option>
																	<option value="3"<?php if($board_info['html_editor'] == 3) echo " selected";?>>fckeditor3</option>
																	<option value="4"<?php if($board_info['html_editor'] == 4) echo " selected";?>>daumeditor</option>
																	<option value="5"<?php if($board_info['html_editor'] == 5) echo " selected";?>>fckeditor4</option>
																	<option value="6"<?php if($board_info['html_editor'] == 6) echo " selected";?>>ckeditor</option>
																	<option value="7"<?php if($board_info['html_editor'] == 7) echo " selected";?>>smarteditor</option>
																</select>
															</td>
														</tr>
														<tr>
															<th>민원처리</th>
															<td><input type="checkbox" name="ps_center" value="1"<?php if($board_info['ps_center']>0) echo " checked";?>></td>
															<th>실명인증</th>
															<td><input type="checkbox" name="real_name" value="1"<?php if($board_info['real_name']>0) echo " checked";?>></td>
														</tr>
														<tr>
															<th>일반상단호출</th>
															<td><input type="text" name="top_inc" size="25" maxlength="255" value="<?=$board_info['top_inc'];?>" class="wTbox"></td>
															<th>일반하단호출</th>
															<td><input type="text" name="bottom_inc" size="25" maxlength="255" value="<?=$board_info['bottom_inc'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>일반좌측호출</th>
															<td><input type="text" name="left_inc" size="25" maxlength="255" value="<?=$board_info['left_inc'];?>" class="wTbox"></td>
															<th>일반우측호출</th>
															<td><input type="text" name="right_inc" size="25" maxlength="255" value="<?=$board_info['right_inc'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>관리자상단호출</th>
															<td><input type="text" name="atop_inc" size="25" maxlength="255" value="<?=$board_info['atop_inc'];?>" class="wTbox"></td>
															<th>관리자하단호출</th>
															<td><input type="text" name="abottom_inc" size="25" maxlength="255" value="<?=$board_info['abottom_inc'];?>" class="wTbox"></td>
														</tr>
														<tr>
															<th>관리자좌측호출</th>
															<td><input type="text" name="aleft_inc" size="25" maxlength="255" value="<?=$board_info['aleft_inc'];?>" class="wTbox"></td>
															<th>관리자우측호출</th>
															<td><input type="text" name="aright_inc" size="25" maxlength="255" value="<?=$board_info['aright_inc'];?>" class="wTbox"></td>
														</tr>
													</tbody>
												</table>

											<p class="titleClass">권한 설정</p>
												<table class="wtoolsView">
													<caption>권한 설정</caption>
													<colgroup>
														<col width="19%" />
														<col width="81%" />
													</colgroup>
													<tbody>
														<tr>
															<th>회원레벨</th>
															<td>
																<select name="list_level" class="wSbox">
																	<option value="">목록설정</option>
																	<?php 
																		$mLevel = $mLevel ?? []; // null 방지
																		for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['list_level']==$i) $check_a=" selected"; else $check_a="";
																			echo "<option value=\"".$i."\"".$check_a.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="view_level" class="wSbox">
																	<option value="">내용설정</option>
																	<?php
																		$mLevel = $mLevel ?? []; // null 방지 
																		for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['view_level']==$i) $check_b=" selected"; else $check_b="";
																			echo "<option value=\"".$i."\"".$check_b.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="write_level" class="wSbox">
																	<option value="">새글설정</option>
																	<?php for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['write_level']==$i) $check_c=" selected"; else $check_c="";
																			echo "<option value=\"".$i."\"".$check_c.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="edit_level" class="wSbox">
																	<option value="">수정설정</option>
																	<?php for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['edit_level']==$i) $check_z=" selected"; else $check_z="";
																			echo "<option value=\"".$i."\"".$check_z.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="reply_level" class="wSbox">
																	<option value="">답글설정</option>
																	<?php for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['reply_level']==$i) $check_d=" selected"; else $check_d="";
																			echo "<option value=\"".$i."\"".$check_d.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="delete_level" class="wSbox">
																	<option value="">삭제설정</option>
																	<?php for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['delete_level']==$i) $check_e=" selected"; else $check_e="";
																			echo "<option value=\"".$i."\"".$check_e.">".$mLevel[$i]."</option>";
																	}?>
																</select>
																<select name="down_level" class="wSbox">
																	<option value="">다운설정</option>
																	<?php for($i=1; $i<=count($mLevel); $i++) {
																			if($board_info['down_level']==$i) $check_f=" selected"; else $check_f="";
																			echo "<option value=\"".$i."\"".$check_f.">".$mLevel[$i]."</option>";
																	}?>
																</select>
															</td>
														</tr>
														<tr>
															<th>게시판 상단</th>
															<td>
																<textarea name="board_head" rows="10" class="wTarea"><?=$board_info['board_head'];?></textarea>
															</td>
														</tr>
														<tr>
															<th>게시판 하단</th>
															<td>
																<textarea name="board_tail" rows="10" class="wTarea"><?=$board_info['board_tail'];?></textarea>
															</td>
														</tr>
													</tbody>
												</table>
												<div style="padding:5px; text-align:center;">
													<input type="hidden" name="idx" value="<?=$board_info['idx'];?>">
													<input type="hidden" name="mode" value="<?=$_GET['mode'];?>">
													<input type="hidden" name="page" value="<?=$_GET['page'];?>">
													<input type="button" value="<?=$button_info;?>" onClick="formCheck(this.form); return false;" class="button">
													<input type="reset" value="다시작성" class="button">
													<input type="button" value="취소하기" onclick="history.back();" class="button">
												</div>
									<fieldset>
								</form>
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