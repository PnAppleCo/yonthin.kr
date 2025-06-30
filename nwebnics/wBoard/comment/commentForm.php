<?php
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2011. 03. 02
//==================================================================
//== 코멘트 모드[0:비회원, 1:회원]
$ucontents_info['boardprivate']=0;
if(($ucontents_info['boardprivate']>0) && (login_session()==false)) {
	$submit_event="javascript:alert('로그인후 작성하실 수 있습니다.');";
	$box_status=" disabled";
} else {
	$submit_event="ucontents_Check(document.signform,'$_GET[code]','$_GET[idx]','board','$board_info[private_board]'); return false;";
	$box_status="";
}

$sql_str = "SELECT * FROM ".$b_cfg_tb[2]." WHERE board_idx='$_GET[idx]' AND code='$_GET[code]' ORDER BY idx DESC";
$rst = $db->query($sql_str);
if(DB::isError($rst)) die ($rst->getMessage());
	$k = 0;
	while($c_view = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		//== 내용 복구
		$c_view['ucontents'] = stripslashes($c_view['ucontents']);
		//== 내용에 태그를 허용하지 않을 경우
		if(!$c_view['html'] >0) $c_view['ucontents'] = htmlspecialchars($c_view['ucontents']);
		//== 내용 개행처리
		$c_view['ucontents'] = nl2br($c_view['ucontents']);
		//== 등록일
		$old_date=explode(" ", $c_view['signdate']);
		$v_time=explode(":", $old_date[1]);
		$v_date=strtr($old_date[0],"-",".")." ".$v_time[0].":".$v_time[1];
		$v_char_img="char".$c_view['char_img'].".gif";
		$t_user_ip=explode('.',$c_view['user_ip']);
		$v_user_ip=$t_user_ip[0].'.'.$t_user_ip[1].'.xx.'.$t_user_ip[3];
		//== 코멘트삭제 : 관리자와 회원 코멘트(회원자신이 작성한코멘트)는 비번입력생략
		if(member_session(1,1) == true || (login_session() == true && !strcmp($c_view['mem_id'],$_SESSION['my_id']))) {
			$v_link="<a href=\"javascript:url_move('ucontents', '".$_GET['code']."', '".$view['idx']."', '".$c_view['idx']."', '".$c_view['mem_id']."');\"><img src=\"/img/board/i_delete.gif\"></a>";
		}else {
			$v_link="<a href=\"javascript:formShow('cDForm$k');\"><img src=\"/img/board/i_delete.gif\" title=\"코멘트 삭제\"></a>";
		}
		$o_ucontents .= "
				<tr>
					<td style=\"text-align:left;\"><img src=\"img/comment.png\"> $c_view[name]</td>
					<td style=\"text-align:right;\">".$v_user_ip."&nbsp;&nbsp;".$v_date." ".$v_link."
						<div id=\"cDForm$k\" class=\"passForm\" style=\"display:none;\">
							<form name=\"PassForm$k\" onsubmit=\"passCheck(this,'ucontents','$c_view[idx]'); return false;\"><input type=\"password\" name=\"passwd\" size=\"15\" maxlength=\"12\" class=\"passbox\" title=\"비밀번호 입력\" style=\"background:url(/nwebnics/img/pass.gif) no-repeat;\" onfocus=\"this.style.backgroundImage=''\" onblur=\"if (this.value=='') this.style.backgroundImage='url(/nwebnics/img/pass.gif)';\" /><input type=\"hidden\" name=\"mode\" value=\"\" /><input type=\"hidden\" name=\"fid\" value=\"$view[fid]\" /><input type=\"hidden\" name=\"thread\" value=\"$view[thread]\" /><input type=\"hidden\" name=\"mem_id\" value=\"$view[mem_id]\" /> <input type=\"image\" src=\"./skin/$board_info[skin]/img/delete(btn).gif\" width=\"30\" height=\"20\" style=\"vertical-align:middle; \" title=\"확인\" />[<a href=\"#endLayer\" onclick=\"formShow('cDForm$k');\" title=\"닫기\">닫기</a>]
							</form>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"text-align:left;\"><div style=\"margin-left:15px; padding:3px; background-color:#F7F7F7;\">".$c_view['ucontents']."</div></td>
				</tr>";
		$k++;
	}

//== 글입력 제한 구성
if($board_info['comment_limit']>0) {
	$V_Span = "<span id=\"v_byte\">0</span>/".$board_info['comment_limit']."까지";
	$V_OnKeyup = " onKeyUp=\"check_msglen('reple','v_byte',$board_info[comment_limit]);\"";
}else {
	$V_Span = "글자수제한없음";
	$V_OnKeyup = "";
}

//== 글등록 정보
$No_Member_Info_Form="
			<tr>
				<td><input type=\"text\" name=\"name\" style=\"width:95%; height:12px;\" maxlength=\"12\" class=\"textbox\"".$box_status." title=\"작성자명 입력\" tabindex=\"1\" value=\"작성자\" onfocus=\"this.value==this.defaultValue?this.value='':null\"></td>
				<td rowspan=\"2\"><textarea id=\"reple\" name=\"ucontents\" style=\"width:95%; height:45px;\" class=\"textarea\"".$box_status." title=\"코멘트 입력\"  tabindex=\"3\"></textarea></td>
				<td rowspan=\"2\">
					<button onClick=\"".$submit_event."\" class=\"commentBtn\">등 록</button>
					<input type=\"hidden\" name=\"mode\" value=\"write\">
					<input type=\"hidden\" name=\"char_img\" value=\"1\"></td>
			</tr>
			<tr>
				<td><input type=\"password\" name=\"passwd\" style=\"width:95%; height:12px;\" maxlength=\"16\" class=\"textbox\"".$box_status." title=\"비밀번호 입력\"  tabindex=\"2\" value=\"비밀번호\" onfocus=\"this.value==this.defaultValue?this.value='':null\"></td>
			</tr>";
?>
<script language="javascript">
<!--
	//==코멘트필드 리사이즈
	function TextArea_Resize(mode) {
		var area = document.getElementById('reple');
		var heit = parseInt(area.style.height.replace('px',''));
		if(mode == '-') {
			if(heit - 50 >= 50) area.style.height = heit - 50;
		}else if(mode == '+') {
			if(heit + 50 <= 500) area.style.height = heit + 50;
		}else {
			area.style.height = 50;
		}
	}
	//== 글제수 제한(다음 추후 수정할것)
	function check_msglen(frm,id,lenStr) {
		var length = calculate_msglen(document.getElementById(frm).value);
		document.getElementById(id).innerHTML = length;
		if (length > lenStr) {
			alert("최대 "+lenStr+" 바이트까지 남기실 수 있습니다.\r\n초과된 " + (length - lenStr) + "바이트는 자동으로 삭제됩니다.");
			document.getElementById(frm).value = assert_msglen(document.getElementById(frm).value, lenStr, id);
		}
	}
	function calculate_msglen(message) {
		var nbytes = 0;
		for (i=0; i<message.length; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {
				nbytes += 2;
			} else if (ch != "\r") {
				nbytes++;
			}
		}
		return nbytes;
	}
	function assert_msglen(message, maximum, id) {
		var inc = 0;
		var nbytes = 0;
		var msg = "";
		var msglen = message.length;
		for (i=0; i<msglen; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {
				inc = 2;
			}else if (ch != "\r") {
				inc = 1;
			}
			if((nbytes + inc) > maximum) {
				break;
			}
			nbytes += inc;
			msg += ch;
		}
		document.getElementById(id).innerHTML = nbytes;
		return msg;
	}
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
//-->
</script>
<?php if($k>0) {?>
<div style="padding-top:2px;"><span class="tblLeft">총 <font style="font-weight:bold; color:#F4710E;"><?=$k;?></font>개의 댓글이 있습니다.</span><span class="tblRight"><img src="/img/board/btn_comment_hide.gif" onclick="getucontentsVisible(this);" style="cursor:pointer;" title="댓글보기"></span></div>

<div style="clear:both;" id="ucontents_Layer">
	<table class="commentTbl" summary="댓글 목록" style="width:100%;">
		<caption>댓글 목록</caption>
		<colgroup>
			<col width="70%" />
			<col width="30%" />
		</colgroup>
		<tbody><?=$o_ucontents;?></tbody>
	</table>
</div>
<?php }?>
<div style="clear:both;">
	<form name="signform">
	<table class="commentTbl" summary="댓글 등록">
		<caption>댓글 등록</caption>
		<colgroup>
			<col width="15%" />
			<col width="75%" />
			<col width="10%" />
		</colgroup>
		<tbody>
			<?php if($board_info['private_board']>0) echo ""; else echo $No_Member_Info_Form;?>
		</tbody>
	</table>
	</form>
</div>

<!-- 코멘트 삭제 기본정보 -->
<form name="ucontents_Del_Form">
	<input type="hidden" name="mode" value="delete">
</form>
</table>