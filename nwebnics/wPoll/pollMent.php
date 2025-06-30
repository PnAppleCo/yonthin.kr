<?php
//========================================================//
//== last modify date : 2012. 05. 26
//========================================================//

$sql_str = "SELECT * FROM wpollMent WHERE board_idx='$_GET[idx]' AND code='$_GET[code]' ORDER BY idx DESC";
$rst = $db->query($sql_str);
if(DB::isError($rst)) die ($rst->getMessage());
	$k = 0;
	while($c_view = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		//== 내용 복구
		$content_edit=stripslashes($c_view['ucontents']);
		$c_view['ucontents'] = stripslashes($c_view['ucontents']);
		//== 내용에 태그를 허용하지 않을 경우
		if(!$c_view['html'] >0) $c_view['ucontents'] = htmlspecialchars($c_view['ucontents']);
		$c_view['ucontents'] = str_replace(" ","&nbsp;",$c_view['ucontents']);
		//== 내용 개행처리
		$c_view['ucontents'] = nl2br($c_view['ucontents']);
		//== 등록일
		$old_date=explode(" ", $c_view['signdate']);
		$v_time=explode(":", $old_date[1]);
		$v_date=strtr($old_date[0],"-",".")." ".$v_time[0].":".$v_time[1];
		$v_char_img="char".$c_view['char_img'].".gif";
		$t_user_ip=explode('.',$c_view['user_ip']);
		$v_user_ip=$t_user_ip[0].'.'.$t_user_ip[1].'.xx.'.$t_user_ip[3];
		//== 코멘트삭제 : 관리자와 사원 코멘트(사원자신이 작성한코멘트)는 비번입력생략
		if(member_session(1) == true || (login_session() == true && !strcmp($c_view['mem_id'],$_SESSION['my_id']))) {
				$v_link="<img src=\"/img/comm/w_delete.gif\" onclick=\"mentChk(this.form,'del','$view[code]','$view[idx]','$c_view[idx]','$_GET[page]','$_GET[mnv]');\" alt=\"삭제\" style=\"cursor:pointer;\"> <a href=\"javascript:SwitchMenu('sub".$k."')\"\"><img src=\"/img/comm/w_edit.gif\" alt=\"수정\"></a>";
		}else {
			$v_link="";
		}
		$o_ucontents .= "
				<tr>
					<td style=\"text-align:left;\"><img src=\"/nwebnics/wBoard/img/comment.png\"> $c_view[name]</td>
					<td style=\"text-align:right;\">".$v_link." ".$v_date."</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"text-align:left;\"><div style=\"margin-left:15px; padding:3px; background-color:#F7F7F7;\">".$c_view['ucontents']."</div></td>
				</tr>
				<tr id=\"sub".$k."\" style=\"display: none;\">
					<td colspan=\"2\">
						<form name=\"ceditForm\">
							<textarea id=\"reple\" name=\"ucontents\" style=\"width:98%\" rows=\"5\" class=\"textarea\" title=\"코멘트 입력\" tabindex=\"3\">".$content_edit."</textarea>
							<input type=\"button\" value=\"답변수정\" onclick=\"mentChk(this.form,'edit','$view[code]','$view[idx]','$c_view[idx]','$_GET[page]','$_GET[mnv]'); return false;\" class=\"button\" style=\"margin-top:3px;\">
						</form>
					</td>
				</tr>";
		$k++;
	}
?>
<script language="javascript">
<!--
	//== 레이오 오픈
	function getucontentsVisible(obj) {
		var l = document.getElementById('ucontents_Layer');
		if(obj.src.indexOf('_show.gif') != -1) {
			obj.src = obj.src.replace('_show.gif', '_hide.gif');
			l.style.display = 'block';
		}else {
			obj.src = obj.src.replace('_hide.gif', '_show.gif');
			l.style.display = 'none';
		}
	}

	function mentChk(thisform,mode,code,m_idx,c_idx,v_page,mnv) {
		if(mode=='add' || mode=='edit') {
			//if(!thisform.name.value) { alert("작성자명을 입력하세요!"); thisform.name.focus(); return; }
			if(!thisform.ucontents.value) { alert("내용을 입력하세요!"); thisform.ucontents.focus(); return; }
		}
		if(mode=='edit') {
			thisform.action = 'pollmentExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&cidx='+c_idx+'&page='+v_page+'&mnv='+mnv;
			thisform.method = 'POST';
			thisform.submit();
		}else if(mode=='add') {
			thisform.action = 'pollmentExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&cidx='+c_idx+'&page='+v_page+'&mnv='+mnv;
			thisform.method = 'POST';
			thisform.submit();
		}else if(mode=='del') {
			if(!confirm("정말 삭제 하시겠습니까?")) { return false; }
			location = 'pollmentExe.php?mode='+mode+'&code='+code+'&idx='+m_idx+'&cidx='+c_idx+'&page='+v_page+'&mnv='+mnv;
		}else { alert('작업모드가 선택되지 않았습니다.!'); }
	}
//-->
</script>

<div style="padding-top:2px;"><span class="tblLeft">총 <font style="font-weight:bold; color:#F4710E;"><?=$k;?></font>개의 답변이 있습니다.</span><span class="tblRight"><img src="/img/comm/btn_comment_hide.gif" onclick="getucontentsVisible(this);" style="cursor:pointer;" alt="답변보기"></span></div>

<div style="clear:both; display:block" id="ucontents_Layer">

	<div id="masterdiv" style="clear:both;">
		<table class="commentTbl" summary="답변 목록" style="width:100%;">
			<caption>답변 목록</caption>
			<colgroup>
				<col width="70%" />
				<col width="30%" />
			</colgroup>
			<tbody><?=$o_ucontents;?></tbody>
		</table>
	</div>

<?php
if(!login_session()) {
	$nologinMent="로그인후 댓글을 작성하세요.";
	$nickName="회원전용 서비스입니다.";
	$lgnJs="alert('로그인후 작성하세요.');";
	$txtArea=" disabled";
}else {
	$nologinMent="";
	$nickName=$_SESSION['my_name'];
	$lgnJs="mentChk(document.cForm,'add','".$view['code']."','".$view['idx']."','','".$_GET['page']."','".$_GET['mnv']."'); return false;";
	$txtArea="";
}
?>

	<div style="clear:both;">
		<form name="cForm">
		<table class="commentTbl" summary="답변 등록">
			<caption>답변 등록</caption>
			<colgroup>
				<col width="90%" />
				<col width="10%" />
			</colgroup>
			<tbody>
				<tr>
					<td colspan="2" style="text-align:left;">닉네임 : <?=$nickName;?></td>
				</tr>
				<tr>
					<td><textarea id="reple" name="ucontents" rows="3" class="textarea" style="width:100%;" title="댓글 입력" tabindex="3"<?=$txtArea;?>><?=$nologinMent;?></textarea></td>
					<td><a href="#" onClick="<?=$lgnJs;?>"><img src="/img/comm/comment_write.gif" width="70" height="44" align="absmiddle" alt="댓글등록"></a><input type="hidden" name="name" value="<?=$_SESSION['my_name'];?>"></td>
				</tr>
			</tbody>
		</table>
		</form>
	</div>

</div>
<?php
//== 비밀번호 입력 레이어폼
require "inc/passForm.htm";
?>