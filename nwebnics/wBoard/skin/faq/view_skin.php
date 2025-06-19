<?
	$pdfPath = "<img src=\"./files/".$_GET[code]."/".urlencode($view[filename0])."\">";
?>
<script language="javascript">
	function sendSns(type,option) {
		var nUrl = window.location.href;
		var nTitle = '<?=$view[subject];?>';
		switch(type){
			case "tw":
				var wp = window.open("http://twitter.com/home?status=" + encodeURIComponent(nTitle) + " " + encodeURIComponent(nUrl), 'twitter', '');
				if(wp) wp.focus();
			break;
			case "me":
				mTitle = "\""+nTitle+"\":"+nUrl;
				var wp = window.open("http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent(mTitle) + "&new_post[tags]=webnics", 'me2Day', '');
				if(wp) wp.focus();
			break;
			case "fa":
				var wp = window.open("http://www.facebook.com/sharer.php?u=" + nUrl + "&t=" + encodeURIComponent(nTitle), 'facebook', 'width=600px,height=420px');
				if(wp) wp.focus();
			break;
			case "yo":
				var href = "http://yozm.daum.net/api/popup/prePost?link=" + encodeURIComponent(nUrl) + "&prefix=" + encodeURIComponent(nTitle) + "&parameter=" + encodeURIComponent(option);
				var a = window.open(href, 'yozmSend', 'width=466, height=356');
				if(a) a.focus();
			break;
			case "pr":
				window.open('print.php?<?=$_SERVER[QUERY_STRING];?>',"contentsPrint","width=800, height=800");
			break;
		}
	}
</script>

	<!-- 게시판 뷰 테이블 기본스킨 -->
	<div class="photo_view">
		<table summary="게시물 정보 열람">
			<caption>게시물 정보 열람</caption>
			<colgroup>
				<col width="15%" />
				<col width="85%" />
			</colgroup>
			<tbody>
				<tr>
					<th>글 제 목</th>
					<td><strong><?=$view[subject];?></strong></td>
				</tr>
				<tr>
					<th>작 성 자</th>
					<td><span class="tblLeft"><?=$v_names." ".$o_email." ".$o_homepage;?></span><span class="tblRight"><?=$signdate;?></span></td>
				</tr>
				<tr>
					<td colspan="2">
						<!-- <div class="photoCnt"><?if($o_img_photo) echo $o_img_photo;?></div> -->
						<div class="contentsPrint"><?=$view[ucontents];?></div>
					</td>
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

	<div id="boardTail" style="width:100%;">
		<div class="tblRight">
			<ul>
			<?
				$basicLevel=count($mLevel);
				if($_SESSION[my_level]=='') $_SESSION[my_level]=$basicLevel;
				if($board_info[list_level] >= $_SESSION[my_level] || ($board_info[list_level]==$basicLevel)) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('list','$_GET[code]','$_GET[page]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]','$_GET[idx]');\" title=\"전체목록\"><span class=\"boardBtn\">전체목록</span></a></li>";
				}
				if(($board_info[write_level] >= $_SESSION[my_level] || $board_info[write_level]==$basicLevel)) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('write','$_GET[code]');\" title=\"새글쓰기\" /><span class=\"boardBtn\">새글쓰기</span></a></li>";
				}
				if(($board_info[noreplebtn]<=0)&&($board_info[reply_level] >= $_SESSION[my_level]) || ($board_info[reply_level]==$basicLevel)) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('reple','$_GET[code]','$_GET[page]','$_GET[idx]');\" title=\"답변글\"><span class=\"boardBtn\">답변글</span></a></li>";
				}
				//== 글수정 : 관리자와 회원게시판(회원자신이 작성한글)은 비번입력생략(수정폼으로 스킵)
				if(member_session(1) == true || (login_session() == true && (!strcmp($view[mem_id],$_SESSION[my_id]) || !strcmp($board_info[adminid], $_SESSION[my_id])))) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('edit','$_GET[code]','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]');\" title=\"글수정\"><span class=\"boardBtn\">글수정</span></a></li>";
				}else {
					if($board_info[edit_level] >= $_SESSION[my_level] || ($board_info[edit_level]==$basicLevel)) {
						$Board_Menu .= "<li><a href=\"javascript:formShow('aeForm');\" title=\"글수정\"><span class=\"boardBtn\">글수정</span></a></li>";
					}
				}
				//== 글삭제 : 관리자와 회원게시판(회원자신이 작성한글)은 비번입력생략(삭제폼으로 스킵)
				if(member_session(1) == true || (login_session() == true && (!strcmp($view[mem_id],$_SESSION[my_id]) || !strcmp($board_info[adminid], $_SESSION[my_id])))) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('delete','$_GET[code]','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]');\" title=\"글삭제\"><span class=\"boardBtn\">글삭제</span></a></li>";
				}else {
					if($board_info[delete_level] >= $_SESSION[my_level] || ($board_info[delete_level]==$basicLevel)) {
						$Board_Menu .= "<li><a href=\"javascript:formShow('adForm');\" title=\"글삭제\"><span class=\"boardBtn\">글삭제</span></a></li>";
					}
				}
				echo $Board_Menu;
			?>
			</ul>
		</div>
	</div>

	<div id="aeForm" class="passForm" style="display:none;">
		<form name="aeForm" onsubmit="passCheck(this,'check',''); return false;">
			<div><input type="password" name="passwd" size="15" maxlength="12" class="passbox" style="background:url(/nwebnics/img/pass.gif) no-repeat;" onfocus="this.style.backgroundImage=''" onblur="if (this.value=='') this.style.backgroundImage='url(/nwebnics/img/pass.gif)';" /><input type="hidden" name="mode" value="" /><input type="hidden" name="fid" value="<?=$view[fid];?>" /><input type="hidden" name="thread" value="<?=$view[thread];?>" /><input type="hidden" name="mem_id" value="<?=$view[mem_id];?>" /> <input type="image" src="skin/<?=$board_info[skin];?>/img/delete(btn).gif" width="30" height="20" style="vertical-align:middle;" /> <a href="#endLayer" onclick="formShow('aeForm');"><input type="image" src="skin/<?=$board_info[skin];?>/img/close(btn).gif" style="vertical-align:middle;" /></a></div>
		</form>
	</div>

	<div id="adForm" class="passForm" style="display:none;">
		<form name="adForm" onsubmit="passCheck(this,'delete',''); return false;">
			<div><input type="password" name="passwd" size="15" maxlength="12" class="passbox" style="background:url(/nwebnics/img/pass.gif) no-repeat;" onfocus="this.style.backgroundImage=''" onblur="if (this.value=='') this.style.backgroundImage='url(/nwebnics/img/pass.gif)';" /><input type="hidden" name="mode" value="" /><input type="hidden" name="fid" value="<?=$view[fid];?>" /><input type="hidden" name="thread" value="<?=$view[thread];?>" /><input type="hidden" name="mem_id" value="<?=$view[mem_id];?>" /> <input type="image" src="skin/<?=$board_info[skin];?>/img/delete(btn).gif" width="30" height="20" style="vertical-align:middle;" /> <a href="#endLayer" onclick="formShow('adForm');"><input type="image" src="skin/<?=$board_info[skin];?>/img/close(btn).gif" style="vertical-align:middle;" /></a></div>
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