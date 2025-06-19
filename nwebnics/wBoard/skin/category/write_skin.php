<?
$now_path = now_filename($_SERVER["PHP_SELF"]);
if($board_info[only_img]==1) echo "<div id=\"navtxt\" class=\"navtext\" style=\"position:absolute; top:-100px; left:0px; visibility:hidden\"></div>\n";

//== 파일 크기 지정
$file_size=array("Bytes", "Kb", "Mb", "Gb");
for($i=0; $board_info[upload_max_size]>=1024; $board_info[upload_max_size]=$board_info[upload_max_size]/1024, $i++);
$v_file_size= sprintf("%0.{$i}f$file_size[$i]", $board_info[upload_max_size]);

for($i=0; $i<$board_info[upload_count]; $i++) {
	if(!strcmp($now_path, "edit.php")) {
		//== 등록된 파일이 이미지인경우 미리보기 설정
		if($view[filename.$i]) {
			if($board_info[only_img]==1) {
				${"Pre_Img_".$i} = "<img src=./files/".$_GET[code]."/".$view[filename.$i]." width=120 height=90>";
				${"Link_".$i} = "<a href=\"#\" onMouseOver=\"writetxt('".${"Pre_Img_".$i}."')\" onMouseOut=\"writetxt(0)\"><font color=\"#ff6200\">".$view[filename.$i]."</font></a> <input type=\"checkbox\" name=\"deletefile".$i."\" value=\"".$view[filename.$i]."\">삭제";
			}else {
				${"Link_".$i} = "<span><font color=\"#ff6200\">".$view[filename.$i]."</font> <input type=\"checkbox\" name=\"deletefile".$i."\" value=\"".$view[filename.$i]."\">삭제</span>";
			}
		}else {
			${"Link_".$i} = "<span><font color=\"#ff6200\">미등록</font></span>";
		}
	}
	//== 업로드 텍스트박스
	$upload_textbox .= "<div><input type=\"file\" name=\"filename[]\" size=\"20\" maxlength=\"255\" class=\"textbox\" style=\"padding:0;\" /> <span>".${"Link_".$i}."</span></div>\n";
	//== 수정/삭제 파일 히든처리
	$f_name="up_file".$i;
	$f_value=$view[filename.$i];
	$hidden_file .= "<input type=\"hidden\" name=\"$f_name\" value=\"$f_value\">\n";
}
//== 옵션 체크
if($board_info[html_editor]>0 || $view[html]>0) $html_check = " checked"; else $html_check = "";
if($board_info[html_editor]==0 || $view[auto_enter]>0) $auto_enter_check = " checked"; else $auto_enter_check = "";
if($view[secret]>0) $secret_check = " checked"; else $secret_check = "";
if($view[re_email]>0) $re_email_check = " checked"; else $re_email_check = "";
if($view[notice]>0) $notice_check = " checked"; else $notice_check = "";
if($view[approve]>0) $approve_check = " checked"; else $approve_check = "";
?>

	<!-- 글 쓰기, 수정, 답변글 기본스킨 -->
	<div class="boardForm">
		<form name="signForm" id="signForm" ENCTYPE="multipart/form-data">
			<fieldset>
				<legend>게시물 데이터 입력</legend>
				<div id="formWrap">
					<dl class="formDl">
						<dt>글 제 목</dt>
						<dd><input type="text" name="subject" size="55" maxlength="100" class="textbox textBoxclass" value="<? if(!strcmp($now_path, "reple.php") || !strcmp($now_path, "edit.php")) echo $view[subject];?>" placeholder="글제목입력(필수)" title="글제목입력" /></dd>
					</dl>
					<dl class="formDl">
						<dt>작 성 자</dt>
						<dd><input type="text" name="name" size="15" maxlength="12" class="textbox textBoxclass" value="<?if(!strcmp($now_path, "edit.php")) echo $view[name]; else echo $_SESSION[my_name];?>" title="작성자입력" placeholder="작성자입력(필수)" /></dd>
					</dl>
					<?if(strcmp($now_path, "edit.php")) {?>
					<dl class="formDl">
						<dt>비밀번호</dt>
						<dd><input type="password" name="passwd" size="15" maxlength="12" class="textbox textBoxclass" placeholder="비밀번호입력(필수)" title="비밀번호입력" /></dd>
					</dl>
					<?}?>
					<dl class="formDl">
						<dt>전자우편</dt>
						<dd><input type="text" name="email" size="30" maxlength="100" class="textbox textBoxclass" value="<?if(!strcmp($now_path, "reple.php") || !strcmp($now_path, "edit.php")) echo $view[email]; else echo $_COOKIE[cuk_mg_email];?>" placeholder="전자우편입력" title="전자우편입력" /></dd>
					</dl>
					<dl class="formDl">
						<dt>홈페이지</dt>
						<dd><input type="text" name="homepage" size="30" maxlength="100" class="textbox textBoxclass" value="<? if(!strcmp($now_path, "reple.php") || !strcmp($now_path, "edit.php")) echo $view[homepage]; else echo $_COOKIE[cuk_mg_home];?>" placeholder="홈페이지입력" title="홈페이지입력" /></dd>
					</dl>
					<dl class="formDl">
						<dt>행사구분</dt>
						<dd>
						<?
							//== 게시물 카테고리
							if($board_info[board_class]) {
								$class_item=explode(",",$board_info[board_class]);
								for($i=0; $i<count($class_item); $i++) {
									if($view[b_class] == $class_item[$i]) $class_selected=" selected"; else $class_selected="";
									$p_class .= "<option value=\"".$class_item[$i]."\"".$class_selected.">".$class_item[$i]."</option>\n";
								}
								echo "<select name=\"b_class\" class=\"selectbox\">\n<option value=\"\">카테고리</option>\n".$p_class."</select>";
							}
						?>
						</dd>
					</dl>
					<?if(member_session(1)) {?>
					<dl class="formDl">
						<dt>입력시간</dt>
						<dd><input type="text" name="signdate" size="20" maxlength="20" class="textbox textBoxclass" value="<?=$view[signdate];?>" placeholder="입력일자" title="입력일자" /></dd>
					</dl>
					<?}?>
					<dl class="formDl">
						<dt>특수기능</dt>
						<dd>
								<input type="checkbox" name="html" value="1"<?=$html_check;?> class="align_left_middle" /><span>태그사용</span>
								<input type="checkbox" name="auto_enter" value="1"<?=$auto_enter_check;?> class="align_left_middle" /><span>자동개행</span>
								<input type="checkbox" name="secret" value="1"<?=$secret_check;?> class="align_left_middle" /><span>비공개글</span>
								<input type="checkbox" name="re_email" value="1"<?=$re_email_check;?> class="align_left_middle" /><span>답변메일</span>
								<?
									//== 공지사항 체크
									if((member_session(1) == true) || ($board_info[adminid] === $_SESSION[my_id])) {
										echo "<input type=\"checkbox\" name=\"notice\" value=\"1\"".$notice_check." class=\"align_left_middle\" /><span>공지사항</span>";
									}
									if((member_session(1) == true || $board_info[adminid] === $_SESSION[my_id]) && $view[idx]>0) {
										//== 게시물 승인
										if($board_info[approve]>0) echo " <input type=\"checkbox\" name=\"approve\" value=\"1\"".$approve_check." class=\"align_left_middle\" />게시물승인";
										//== 게시물 이동
										$sql_str = "SELECT code, board_summary FROM ".$b_cfg_tb[0]." ORDER BY idx DESC";
										$result = $db->query($sql_str);
										if(DB::isError($result)) die($result->getMessage());
										while($v_code = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
											$p_code .= "<option value=\"".$v_code[code]."\">".$v_code[board_summary]."</option>\n";
										}
										echo "<select name=\"move_code\" class=\"selectbox\">\n<option value=\"\">게시물이동</option>\n".$p_code."</select>";
									}
								?>
						</dd>
					</dl>
					<?if($board_info[upload_count]>0) {
							switch ($board_info[upload_count]) {
								case "1" :
									$cssHeight="30";
								break;
								case "3" :
									$cssHeight="75";
								break;
								case "5" :
									$cssHeight="125";
								break;
								case "8" :
									$cssHeight="180";
								break;
								default :
									$cssHeight="30";
							}
					?>
					<dl class="formDl">
						<dt style="height:<?=$cssHeight;?>px;">파일등록</dt>
						<dd style="height:<?=$cssHeight;?>px;">
							<?=$upload_textbox;?>
							<!-- 삭제수정시 원본파일 추출 -->
							<?=$hidden_file;?>
							<!-- 업로드파일 용량 제한 -->
							<input type="hidden" name="MAX_FILE_SIZE" value="<?=$board_info[upload_max_size];?>" />
						</dd>
					</dl>
					<?}?>
					<?if($board_info[ps_center]>0) {?>
					<dl class="formDl">
						<dt>전화번호</dt>
						<dd><input type="text" name="svc_tel" size="20" maxlength="100" class="textbox" <? if(!strcmp($now_path, "reple.php") || !strcmp($now_path, "edit.php")) echo "value=\"$view[svc_tel]\""; else echo "value=\"$_COOKIE[cuk_mg_svc_tel]\"";?> title="전화번호 입력" /> 예)02-1234-5678
						</dd>
					</dl>
					<?}?>
					<dl class="formDl">
						<dt>검색태그</dt>
						<dd><input type="text" name="keytag" size="45" maxlength="255" class="textbox" value="<?=$view[keytag];?>" title="검색 태그 입력" /> ※ 다수 입력시 ','(쉼표)로 분리</dd>
					</dl>
					<div class="clear_both">
						<?
							//== HTML 편집기 삽입 시작 ====================================================================
							if(isMobile()==false) {
								switch ($board_info[html_editor]) {
									case 1 :
										include("../htmlEditor/fckeditor1/fckeditor.php");
										$oFCKeditor = new FCKeditor('ucontents');
										$oFCKeditor->BasePath='../htmlEditor/fckeditor1/';
										$oFCKeditor->Height=300;
										$oFCKeditor->ToolbarSet='webnics';
										$oFCKeditor->Value=$view[ucontents];
										$oFCKeditor->Create();
									break;
									case 2 :
										include("../htmlEditor/fckeditor2/fckeditor.php");
										$oFCKeditor = new FCKeditor('ucontents');
										$oFCKeditor->BasePath='../htmlEditor/fckeditor2/';
										$oFCKeditor->Height=300;
										$oFCKeditor->ToolbarSet='webnics';
										$oFCKeditor->Value=$view[ucontents];
										$oFCKeditor->Create();
									break;
									case 3 :
										echo "<script type=\"text/javascript\" src=\"../htmlEditor/fckeditor3/ckeditor.js\"></script>";
										echo "<script src=\"../htmlEditor/fckeditor3/_samples/sample.js\" type=\"text/javascript\"></script>";
										//echo "<link href=\"../htmlEditor/fckeditor3/_samples/sample.css\" rel=\"stylesheet\" type=\"text/css\" />";
										echo "<textarea name=\"ucontents\" cols=\"50\" rows=\"17\" class=\"ckeditor\">".$view[ucontents]."</textarea>";
									break;
									case 4 :
										include("../htmlEditor/daumeditor-5.4.0/webnics.htm");
									break;
									case 5 :
										//echo "<link rel=\"stylesheet\" href=\"../htmlEditor/fckeditor4/samples/sample.css\">";
										echo "<script src=\"../htmlEditor/fckeditor4/ckeditor.js\"></script>";
										echo "<textarea name=\"ucontents\" cols=\"50\" rows=\"17\" class=\"textarea\">".$view[ucontents]."</textarea>";
										echo "<script>CKEDITOR.replace( 'ucontents' );</script>";
									break;
									case 6 :
										echo "<script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/cheditor/cheditor.js\"></script>";
										echo "<textarea id=\"ucontents\" name=\"ucontents\" >".$view[ucontents]."</textarea>";
										echo "<script type=\"text/javascript\">\n
										var myeditor = new cheditor();\n
										myeditor.config.editorHeight = '300px';\n
										myeditor.config.editorWidth = '100%';\n
										myeditor.inputForm = 'ucontents';\n
										myeditor.run();
										</script>";
									break;
									case 7 :
										echo "<div style=\"clear:both;\"><script type=\"text/javascript\" src=\"/nwebnics/htmlEditor/SE2.3.10/js/HuskyEZCreator.js\" charset=\"utf-8\"></script>";
										echo "<textarea id=\"ucontents\" name=\"ucontents\" rows=\"10\" cols=\"100\" style=\"width:100%; height:412px; min-width:610px; display:none;\">".$view[ucontents]."</textarea></div>";
										include ("inc/smarteditor.php");
									break;
									default :
										echo "<textarea name=\"ucontents\" cols=\"50\" rows=\"17\" class=\"textarea\" placeholder=\"내용을 입력하세요\">".$view[ucontents]."</textarea>";
								}
							}else {
								echo "<textarea name=\"ucontents\" cols=\"50\" rows=\"17\" class=\"textarea\" placeholder=\"내용을 입력하세요\">".$view[ucontents]."</textarea>";
								$board_info[html_editor]=0;
							}
							//== HTML 편집기 삽입 종료 ====================================================================
						?>
						<input type="hidden" name="fid" value="<?=$view[fid];?>">
						<input type="hidden" name="thread" value="<?=$view[thread];?>">
						<input type="hidden" name="tcode" id="tcode" value="<?=$imgFolder;?>">
						<input type="hidden" name="mode" value="">
					</div>
					<?if(member_session(1)==true && !strcmp($now_path, "edit.php") && $board_info[ps_center]>0) {?>
					<dl class="formDl">
						<dt>답변처리</dt>
						<dd>
							<select name="svc_status" class="selectbox">
								<option value="" selected >-- 선택 --</option>
								<?
									for($i=0; $i<count($Arr_psc_status); $i++) {
										if($view[svc_status]==$i) $selected_svc_status=" selected"; else $selected_svc_status="";
										echo "<option value=\"".$i."\"".$selected_svc_status.">".$Arr_psc_status[$i]."</option>";
									}
								?>
							</select>
							<input type="text" name="svc_name" size="20" maxlength="50" class="textbox" placeholder="담당부서 입력" value="<? if(!strcmp($now_path, "reple.php") || !strcmp($now_path, "edit.php")) echo $view[svc_name]; else echo $_COOKIE[cuk_mg_svc_name];?>" title="담당부서 입력" />
						</dd>
					</dl>
					<div class="clear_both">
						<textarea name="svc_reply" style="width:100%;" rows="5" class="textarea" placeholder="답변내용 입력"><?=$view[svc_reply];?></textarea>
					</div>
					<?}?>
				</div>
			<fieldset>
		</form>
	</div>

	<div id="boardTail" style="text-align:center; margin-top:2em;">
		<?
			if(!strcmp($now_path, "write.php")) {
				echo "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','write','','','','','','','$board_info[html_editor]');\" title=\"등록\"><span class=\"boardBtn\">등록</span></a>";
			}else if(!strcmp($now_path, "reple.php")) {
				echo "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','reple','$_GET[page]','','','','','','$board_info[html_editor]');\" title=\"등록\"><span class=\"boardBtn\">등록</span></a>";
			}else if(!strcmp($now_path, "edit.php")) {
				echo "<a href=\"javascript:checkSubmit(document.signForm,'$_GET[code]','edit','$_GET[page]','$_GET[idx]','$_GET[keyword]','$_GET[s_1]','$_GET[s_2]','$_GET[s_3]','$board_info[html_editor]');\" title=\"수정\"><span class=\"boardBtn\">수정</span></a>";
			}else { js_action(1, "작업방법을 찾을수 없습니다.", "", -1); }
		?> <a href="javascript:history.go(-1);" title="취소하기"><span class="boardBtn">취소</span></a>
	</div>
	<?if($board_info[board_tail]) echo $board_info[board_tail];?>