<?
//==================================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2015. 01. 02
//==================================================================

//=============================================== 댓글 목록 질의 ==================================================//

$sqlStr = "SELECT * FROM mzCommentTbl WHERE b_idx='".$_GET[idx]."' AND code='".$_GET[code]."' ORDER BY app DESC, opp ASC";
$rst = $db->query($sqlStr);
if(DB::isError($rst)) die ($rst->getMessage());
$k = 0;
while($cView = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
	//== 내용 복구
	$cView[ucontents] = stripslashes($cView[ucontents]);
	//== 내용에 태그를 허용하지 않을 경우
	if(!$cView[html] >0) $cView[ucontents] = htmlspecialchars($cView[ucontents]);
	//== 내용 개행처리
	$cView[ucontents] = nl2br($cView[ucontents]);
	//== 등록일
	$old_date=explode(" ", $cView[signdate]);
	$v_time=explode(":", $old_date[1]);
	$v_date=strtr($old_date[0],"-",".")." ".$v_time[0].":".$v_time[1];
	$v_char_img="char".$cView[char_img].".gif";

	$v_date=strtr($cView[signdate],"-",".")." ".$cView[signtime];
	$t_user_ip=explode('.',$cView[user_ip]);
	$v_user_ip=$t_user_ip[0].'.'.$t_user_ip[1].'.xx.'.$t_user_ip[3];

	$v_link .= "<span><a href=\"/nwebnics/comments/commentExe.php?mode=rec&field=app&idx=".$cView[idx]."&b_idx=".$view[idx]."\" onClick=\"return confirm('추천 하시겠습니까?');\"><img src=\"/img/comm/icon_rec_20.png\" alt=\"rec\" /></a>(".$cView[app].")</span> <span><a href=\"/nwebnics/comments/commentExe.php?mode=rec&field=opp&idx=".$cView[idx]."&b_idx=".$view[idx]."\" onClick=\"return confirm('비추천 하시겠습니까?');\"><img src=\"/img/comm/icon_nrec_20.png\" alt=\"nrec\" /></a>(".$cView[opp].")</span>";
	//== 코멘트삭제 : 관리자와 회원 코멘트(회원자신이 작성한코멘트)는 비번입력생략
	if(member_session(1,1) == true || (login_session() == true && !strcmp($cView[m_id],$_SESSION[my_id]))) {
		$v_link .= " <span><a href=\"/nwebnics/comments/commentExe.php?mode=del&idx=".$cView[idx]."&b_idx=".$view[idx]."\" onClick=\"return confirm('삭제 하시겠습니까?');\"><img src=\"/img/comm/icon_del_20.png\" alt=\"삭제\" /></a></span>";
	}

	$o_ucontents .= "
			<tr>
				<td style=\"background:#F6FAFD; padding-left:.5em;\"><span style=\"color:#1363AA; font-weight:bold;\">".$cView[m_name]."</span> (".$v_date.")</td>
				<td style=\"text-align:right; background:#F6FAFD;  padding-right:.5em;\">".$v_link."</td>
			</tr>
			<tr>
				<td colspan=\"2\" style=\"border-bottom:1px solid #E9E8E8;\"><div style=\"word-break: break-all; padding:.5em;\">".$cView[ucontents]."</div></td>
			</tr>";
	$k++;
	unset($v_link);
	if(!login_session()) $vtext=" placeholder=\"로그인후 댓글을 달아주세요\" disabled";
}
?>
<script type="text/javascript">
	function fCheck(mode, idx) {
		//if(!document.cForm.m_name.value) { alert('닉네임을 입력하세요.'); document.cForm.m_name.focus(); return false; }
		//if(!document.cForm.passwd.value) { alert('비밀번호를 입력하세요.'); document.cForm.passwd.focus(); return false; }
		if(!document.cForm.ucontents.value) { alert('댓글내용을 입력하세요.'); document.cForm.ucontents.focus(); return false; }
		document.cForm.action = '/nwebnics/comments/commentExe.php?mode='+mode+'&b_idx='+idx;
		document.cForm.method = 'POST';
		document.cForm.submit();
	}
	$(document).ready(function(){
		var site_visible = 1;
		$("#open_btn").click(function(){
			$("#cSystemList").slideToggle(400);
			if(site_visible == 0) {
				$("#open_btn").text("▼ 닫기");
				site_visible = 1;
			}else {
					$("#open_btn").text("▲ 열기");
				site_visible = 0;
			}
		});
/*
		$(".nickname").focus(function() {
			$(this).val('');
		}).blur(function() {
			if($(this).val() == "") { $(this).val("닉네임"); }
		});
		$(".paswds").focus(function() {
			$(this).val('');
		}).blur(function() {
			if($(this).val() == "") { $(this).val("비밀번호"); }
		});
*/
		$('input[type="text"]').each(function(){
				this.value = $(this).attr('title');
				$(this).addClass('text-label');
				$(this).focus(function(){
						if(this.value == $(this).attr('title')) {
								this.value = '';
								$(this).removeClass('text-label');
						}
				});
				$(this).blur(function(){
						if(this.value == '') {
								this.value = $(this).attr('title');
								$(this).addClass('text-label');
						}
				});
		});
	});
</script>

<div id="cSystemHead"><span class="tblLeft">총 <font style="font-weight:bold; color:#F4710E;"><?=$k;?></font>개의 댓글이 있습니다.</span><span id="open_btn" class="tblRight">▼ 닫기</span></div>

<div id="cSystemList">
	<table summary="댓글 목록">
		<caption>댓글 목록</caption>
		<colgroup>
			<col width="70%" />
			<col width="30%" />
		</colgroup>
		<tbody><?=$o_ucontents;?></tbody>
	</table>
</div>

<div id="cSystemForm">
	<form name="cForm">
		<table summary="댓글 등록">
			<caption>댓글 등록</caption>
			<colgroup>
				<col width="50%" />
				<col width="50%" />
			</colgroup>
			<tbody>
<!-- 				<tr>
					<td><input type="text" name="m_name" title="닉네임" class="textbox nickname" /></td>
					<td><input type="text" name="passwd" title="비밀번호" class="textbox paswds" /></td>
				</tr> -->
				<tr>
					<td colspan="2"><textarea name="ucontents" id="ucontents" class="textarea" style="width:100%; max-width:100%; height:5em;"<?=$vtext;?>></textarea></td>
				</tr>
			</tbody>
		</table>
		<p style="text-align:center;"><span class="button01" onClick="fCheck('add','<?=$_GET[idx];?>');">등록</span></p>
	</form>
</div>