	<!-- 게시판 뷰 테이블 기본스킨 -->
	<div class="tblView">
		<table summary="게시물 정보 열람">
			<caption>게시물 정보 열람</caption>
			<colgroup>
				<col width="15%" />
				<col width="85%" />
			</colgroup>
			<tbody>
				<tr>
					<th>공고제목</th>
					<td><strong><?=$view[subject];?></strong></td>
				</tr>
				<tr>
					<th>담당부서</th>
					<td><span class="tblLeft"><?=$v_names;?>[<?=$view[etc03]?>]</span><span class="tblRight"><?=$signdate;?></span></td>
				</tr>
				<tr>
					<th>공고구분</th>
					<td><span class="tblLeft"><span style="font-weight:bold;"><?=$view[b_class];?></span> 기간 : <?=strtr($view[etc01],"-",".");?>~<?=strtr($view[etc02],"-",".");?></td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="photoCnt"><?if($o_img_photo) echo $o_img_photo;?></div>
						<div class="contentsPrint"><?=$view[ucontents];?></div>
					</td>
				</tr>
				<tr>
					<th>첨부파일</th>
					<td>
						<span class="tblLeft"><?=$o_data;?></span>
						<span class="tblRight">
							<ul id="sendSns">
								<li><a href="#" onClick="snsModule.share_fb();"><img src="http://festival.webnics.co.kr/img/comm/icon_facebook_cont.gif" alt="페이스북에 이 페이지 공유하기"></a></li>
								<li><a href="#" onClick="snsModule.twitter();"><img src="http://festival.webnics.co.kr/img/comm/icon_twitter_cont.gif" alt="트위터에 이 페이지 공유하기"></a></li>
								<li><a href="#" onClick="snsModule.kakaostory();"><img src="http://festival.webnics.co.kr/img/comm/icon_kakaostory_cont.gif" alt="카카오스토리에 이 페이지 공유하기"></a></li>
								<li><a href="#" onClick="snsModule.naverblog();"><img src="http://festival.webnics.co.kr/img/comm/icon_blog_cont.gif" alt="네이버 블로그에 이 페이지 공유하기"></a></li>
								<li><a href="#" onClick="snsModule.band();"><img src="http://festival.webnics.co.kr/img/comm/icon_band_cont.gif" alt="네이버 밴드에 이 페이지 공유하기"></a></li>
							</ul>
						</span>
					</td>
				</tr>
				<tr>
					<th>검색태그</th>
					<td><?if($view[keytag]) echo $view[keytag]; else echo "미등록";?></span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?
		//== 민원처리
		if($board_info[ps_center]>0 && $view[notice]==0) require ('inc/psCenter_Inc.php');
		//==코멘트 출력
		if($board_info[write_comment]>0) require ('comment/commentForm.php');
	?>

	<div id="boardTail" style="text-align:center;">
		<?
			$basicLevel=count($mLevel);
			if($_SESSION[my_level]=='') $_SESSION[my_level]=$basicLevel;
			if($board_info[list_level] >= $_SESSION[my_level] || ($board_info[list_level]==$basicLevel)) {
				$Board_Menu .= "<a href=\"javascript:url_move('list','$_GET[code]','$_GET[page]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]','$_GET[idx]');\" title=\"목록\"><span class=\"boardBtn\">목록</span></a>";
			}
			if(($board_info[write_level] >= $_SESSION[my_level] || $board_info[write_level]==$basicLevel)) {
				$Board_Menu .= " <a href=\"javascript:url_move('write','$_GET[code]');\" title=\"쓰기\" /><span class=\"boardBtn\">쓰기</span></a>";
			}
			if(($board_info[noreplebtn]<=0)&&($board_info[reply_level] >= $_SESSION[my_level]) || ($board_info[reply_level]==$basicLevel)) {
				$Board_Menu .= " <a href=\"javascript:url_move('reple','$_GET[code]','$_GET[page]','$_GET[idx]');\" title=\"답변\"><span class=\"boardBtn\">답변</span></a>";
			}
			//== 글수정 : 관리자와 회원게시판(회원자신이 작성한글)은 비번입력생략(수정폼으로 스킵)
			if(member_session(1) == true || (login_session() == true && (!strcmp($view[mem_id],$_SESSION[my_id]) || !strcmp($board_info[adminid], $_SESSION[my_id])))) {
				$Board_Menu .= " <a href=\"javascript:url_move('edit','$_GET[code]','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]');\" title=\"수정\"><span class=\"boardBtn\">수정</span></a>";
			}else {
				if($board_info[edit_level] >= $_SESSION[my_level] || ($board_info[edit_level]==$basicLevel)) {
					$Board_Menu .= " <a href=\"javascript:formShow('aeForm');\" title=\"수정\"><span class=\"boardBtn\">수정</span></a>";
				}
			}
			//== 글삭제 : 관리자와 회원게시판(회원자신이 작성한글)은 비번입력생략(삭제폼으로 스킵)
			if(member_session(1) == true || (login_session() == true && (!strcmp($view[mem_id],$_SESSION[my_id]) || !strcmp($board_info[adminid], $_SESSION[my_id])))) {
				$Board_Menu .= " <a href=\"javascript:url_move('delete','$_GET[code]','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]');\" title=\"삭제\"><span class=\"boardBtn\">삭제</span></a>";
			}else {
				if($board_info[delete_level] >= $_SESSION[my_level] || ($board_info[delete_level]==$basicLevel)) {
					$Board_Menu .= " <a href=\"javascript:formShow('adForm');\" title=\"삭제\"><span class=\"boardBtn\">삭제</span></a>";
				}
			}
			echo $Board_Menu;
		?>
	</div>

	<div id="aeForm" class="passForm" style="display:none;">
		<form name="aeForm" onsubmit="passCheck(this,'check',''); return false;">
			<div><input type="password" name="passwd" size="15" maxlength="12" class="passbox" /><input type="hidden" name="mode" value="" /><input type="hidden" name="fid" value="<?=$view[fid];?>" /><input type="hidden" name="thread" value="<?=$view[thread];?>" /><input type="hidden" name="mem_id" value="<?=$view[mem_id];?>" /> <input type="image" src="/img/board/delete(btn).gif" width="30" height="20" style="vertical-align:middle;" /> <a href="#endLayer" onclick="formShow('aeForm');"><input type="image" src="/img/board/close(btn).gif" style="vertical-align:middle;" /></a></div>
		</form>
	</div>

	<div id="adForm" class="passForm" style="display:none;">
		<form name="adForm" onsubmit="passCheck(this,'delete',''); return false;">
			<div><input type="password" name="passwd" size="15" maxlength="12" class="passbox" /><input type="hidden" name="mode" value="" /><input type="hidden" name="fid" value="<?=$view[fid];?>" /><input type="hidden" name="thread" value="<?=$view[thread];?>" /><input type="hidden" name="mem_id" value="<?=$view[mem_id];?>" /> <input type="image" src="/img/board/delete(btn).gif" width="30" height="20" style="vertical-align:middle;" /> <a href="#endLayer" onclick="formShow('adForm');"><input type="image" src="/img/board/close(btn).gif"  style="vertical-align:middle;" /></a></div>
		</form>
	</div>
	<?if($board_info[relation_text]>0) require ('inc/relationInc.php');?>
	<form name="Del_Form">
		<input type="hidden" name="mode" value="delete">
		<input type="hidden" name="fid" value="<?=$view[fid];?>">
		<input type="hidden" name="thread" value="<?=$view[thread];?>">
		<input type="hidden" name="mem_id" value="<?=$view[mem_id];?>">
	</form>
	<?
	//== 게시판 보텀 출력
	if($board_info[board_tail]) echo $board_info[board_tail];
	//== 비밀번호 입력 레이어폼
	require "inc/passForm.htm";
	?>
<iframe name="mail_cipher" src="./sendmail.php" style="display:none;"></iframe>