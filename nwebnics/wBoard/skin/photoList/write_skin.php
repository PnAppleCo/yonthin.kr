<?
$now_path = now_filename($_SERVER["PHP_SELF"]);
if($board_info['only_img']==1) echo "<div id=\"navtxt\" class=\"navtext\" style=\"position:absolute; top:-100px; left:0px; visibility:hidden\"></div>\n";

//== 파일 크기 지정
$file_size=array("Bytes", "Kb", "Mb", "Gb");
for($i=0; $board_info['upload_max_size']>=1024; $board_info['upload_max_size']=$board_info['upload_max_size']/1024, $i++);
$v_file_size= sprintf("%0.{$i}f$file_size[$i]", $board_info['upload_max_size']);

for($i=0; $i<$board_info['upload_count']; $i++) {
	if(!strcmp($now_path, "edit.php")) {
		//== 등록된 파일이 이미지인경우 미리보기 설정
		if($view['filename'.$i]) {
			if($board_info['only_img']==1) {
				${"Pre_Img_".$i} = "<img src=./files/".$_GET['code']."/".$view['filename'.$i]." width=120 height=90>";
				${"Link_".$i} = "<a href=\"#\" onMouseOver=\"writetxt('".${"Pre_Img_".$i}."')\" onMouseOut=\"writetxt(0)\"><font color=\"#ff6200\">".$view['filename'.$i]."</font></a> <input type=\"checkbox\" name=\"deletefile".$i."\" value=\"".$view['filename'.$i]."\">삭제";
			}else {
				${"Link_".$i} = "<span><font color=\"#ff6200\">".$view['filename'.$i]."</font> <input type=\"checkbox\" name=\"deletefile".$i."\" value=\"".$view['filename'.$i]."\">삭제</span>";
			}
		}else {
			${"Link_".$i} = "<span><font color=\"#ff6200\">미등록</font></span>";
		}
	}
	//== 업로드 텍스트박스
	$upload_textbox .= "<div><input type=\"file\" name=\"filename[]\" size=\"20\" maxlength=\"255\" class=\"textbox\" style=\"padding:0;\" /> <span>".${"Link_".$i}."</span></div>\n";
	//== 수정/삭제 파일 히든처리
	$f_name="up_file".$i;
	$f_value=$view['filename'.$i];
	$hidden_file .= "<input type=\"hidden\" name=\"$f_name\" value=\"$f_value\">\n";
}
//== 옵션 체크
if($board_info['html_editor']>0 || $view['html']>0) $html_check = " checked"; else $html_check = "";
if($board_info['html_editor']==0 || $view['auto_enter']>0) $auto_enter_check = " checked"; else $auto_enter_check = "";
if($view['secret']>0) $secret_check = " checked"; else $secret_check = "";
if($view['re_email']>0) $re_email_check = " checked"; else $re_email_check = "";
if($view['notice']>0) $notice_check = " checked"; else $notice_check = "";
if($view['approve']>0) $approve_check = " checked"; else $approve_check = "";


if(!strcmp($now_path, "write.php")) {
	$writeBtn = "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','write','','','','','','','$board_info[html_editor]');\" title=\"등록\" class=\"bt3\" style=\"background: #00a0df;\">등록</a>";
}else if(!strcmp($now_path, "reple.php")) {
	$writeBtn = "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','reple','$_GET[page]','','','','','','$board_info[html_editor]');\" title=\"등록\" class=\"bt3\" style=\"background: #00a0df;\">등록</a>";
}else if(!strcmp($now_path, "edit.php")) {
	$writeBtn =  "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','edit','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]','$board_info[html_editor]');\" title=\"수정\" class=\"bt3\" style=\"background: #00a0df;\">수정</a>";
}else { js_action(1, "작업방법을 찾을수 없습니다.", "", -1); }

//== 게시물 카테고리
if($board_info['board_class']) {
	$class_item=explode(",",$board_info['board_class']);
	for($i=0; $i<count($class_item); $i++) {
		if($view['b_class'] == $class_item[$i]) $class_selected=" selected"; else $class_selected="";
		$p_class .= "<option value=\"".$class_item[$i]."\"".$class_selected.">".$class_item[$i]."</option>\n";
	}
}
?>

	<!-- 글 쓰기, 수정, 답변글 기본스킨 -->
	<div class="boardForm">
		<form name="signForm" id="signForm" ENCTYPE="multipart/form-data">
			<fieldset>
				<legend>게시물 데이터 입력</legend>
					<div class="tit1">
						<strong>글쓰기</strong>
					</div>
					<div class="board_typeB_write">
						<div class="title">
							<?if($board_info['board_class']) {?>
							<dl>
								<dt>카테고리</dt>
								<dd>
									<select name="b_class">
										<option value="">카테고리</option>
										<?=$p_class;?>
									</select>
								</dd>
							</dl>
							<?}?>
							<dl>
								<dt>제목</dt>
								<dd><input type="text" name="subject" value="<?=$view['subject'];?>" placeholder="제목을 입력하세요." /></dd>
							</dl>
							<dl>
								<dt>첨부파일</dt>
								<dd>
									<?=$upload_textbox;?>
									<!-- 삭제수정시 원본파일 추출 -->
									<?=$hidden_file;?>
									<!-- 업로드파일 용량 제한 -->
									<input type="hidden" name="MAX_FILE_SIZE" value="<?=$board_info['upload_max_size'];?>" />
								</dd>
							</dl>
						</div>
						<div class="contents">
							<script type="text/javascript" src="/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js" charset="utf-8"></script>
							<textarea id="ucontents" name="ucontents" rows="10" cols="100" style="width:100%; height:412px; min-width:610px; display:none;"><?=$view['ucontents'];?></textarea>
							<?include ("inc/smarteditor.php");?>
						</div>
					</div>
					<div class="bt_wrap">
						<!-- <a href="#" class="bt3" style="background: #00a0df;">등록</a> -->
						<?=$writeBtn;?>
						<a href="javascript:history.go(-1);" class="bt3">취소</a>
					</div>

					<input type="hidden" name="fid" value="<?=$view['fid'];?>">
					<input type="hidden" name="thread" value="<?=$view['thread'];?>">
					<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>">
					<input type="hidden" name="mode" value="">

			<fieldset>
		</form>
	</div>