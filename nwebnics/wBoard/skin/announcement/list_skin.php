<?
if($align_type==="DESC") $v_align_type="ASC"; else if($align_type==="ASC") $v_align_type="DESC"; else $v_align_type="DESC";
if($rst_print) $rst_print="<tr><td colspan=\"5\">".$rst_print."</td></tr>";
?>

	<!-- 게시판 테이블 기본스킨-->
	<div id="boardHead">
		<span><?=$page_state;?></span>
		<span>
			<ul>
			<?
				$basicLevel=count($mLevel);
				if($_SESSION[my_level]=='') $_SESSION[my_level]=$basicLevel;
				if((($board_info[write_level] >= $_SESSION[my_level])) || ($board_info[write_level]==$basicLevel)) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('write','$_GET[code]');\" title=\"쓰기\"><span class=\"boardBtn\">쓰기</span></a></li>";
				}
				if($Board_Menu) echo $Board_Menu;
			?>
			</ul>
		</span>
	</div>

	<!-- 게시판 리스트 출력 스킨 -->
	<div class="tblList">
		<table class="boardList" summary="게시판 목록">
			<caption>게시판 목록</caption>
			<colgroup>
			<?if($board_info[ps_center]>0) {?>
				<col width="8%" />
				<col width="50%" />
				<col width="15%" />
				<col width="15%" />
				<col width="12%" />
			<?}else {?>
				<col width="8%" />
				<col width="8%" />
				<col width="46%" />
				<col width="20%" />
				<col width="15%" />
			<?}?>
			</colgroup>
			<thead>
				<tr>
					<th scope="col"><a href="<?=$_SEVER[PHP_SELF];?>?align_record=idx&align_type=<?=$v_align_type;?>&<?=get_value(1, 'align_record/align_type');?>" class="tbold" title="번호순 정렬">번호</a></th>
					<th scope="col"><a href="<?=$_SEVER[PHP_SELF];?>?align_record=idx&align_type=<?=$v_align_type;?>&<?=get_value(1, 'align_record/align_type');?>" class="tbold" title="번호순 정렬">분류</a></th>
					<th scope="col"><a href="<?=$_SEVER[PHP_SELF];?>?align_record=subject&align_type=<?=$v_align_type;?>&<?=get_value(1, 'align_record/align_type');?>" class="tbold" title="글제목순 정렬">제 목</a></th>
					<th scope="col"><a href="<?=$_SEVER[PHP_SELF];?>?align_record=name&align_type=<?=$v_align_type;?>&<?=get_value(1, 'align_record/align_type');?>" class="tbold" title="작성자순 정렬">공고기간</a></th>
					<th scope="col"><a href="<?=$_SEVER[PHP_SELF];?>?align_record=signdate&align_type=<?=$v_align_type;?>&<?=get_value(1, 'align_record/align_type');?>" class="tbold" title="등록일순 정렬">등록일</a></th>
					<?if($board_info[ps_center]>0) {?>
					<th scope="col">답변상태</th>
					<?}?>
				</tr>
			</thead>
			<tbody>
				<?
				echo noticeNotice($b_cfg_tb[1],$_GET[code], 5);												//== 게시물 공지글 출력
				echo $rst_print;																																					//== 게시물 등록/검색정보출력
				for($i = $first; $i <= $last; $i++) {
					//== 리스트의 마지막 라인 스타일 변경
					if(!$view[$i][subject]) $view[$i][subject]="제목이 입력되지 안았습니다.";
					$subject = stripslashes($view[$i][subject]);															//== 문자열 복구
					$c_count=comment_count($view[$i][idx],$b_cfg_tb[2]);							//== 코멘트 개수 추출
					//$subject = htmlspecialchars($subject);																			//== 제목에 태그불허(검색어가 없을경우)
					$titleBars=$subject;																																	//== 태그없는 title
					//== 내용의 총라인수
					$line = explode("\n",$view[$i][ucontents]);
					$line_of_ucontents = sizeof($line);
					//== 제목의 길이 제어(들여쓰기에 따라 자르는 크기 지정)
					if(mb_strlen($subject, 'UTF-8') > $board_info[subject_cut]) {
						if($thread==="A") {
							$subject = han_cut($subject, $board_info[subject_cut], "..");
						}else {
							$cutsize=(strlen($view[$i][thread])*2);
							$subject = han_cut($subject, $board_info[subject_cut] - $cutsize, "..");
						}
					}
					//== 검색시 글자색상 지정
					if($_GET[keyword]) $subject = eregi_replace($_GET[keyword], "<span style=\"color:#FF6633;\"><strong>$_GET[keyword]</strong></span>", $subject);
					$name = han_cut($view[$i][name], 12, "");//== 작성자 길이 줄임
					$signdate=strtr($view[$i][signdate],"-",".");

					//== 답변글 들여쓰기 제어
					$spacer = strlen($view[$i][thread])-1;
					if($spacer > $board_info[reply_indent]) $spacer = $board_info[reply_indent];
					for($j = 0; $j < $spacer; $j++) $rTitle .= "&nbsp;&nbsp;";
					if($spacer) $rTitle .= "&nbsp;<img src=\"/img/board/reply_img.gif\" align=\"absmiddle\">";

					// 작성시간 기준 만 24시 동안 새글 설정
					$today=time();
					$arr_day=explode("-",$view[$i][signdate]);
					$arr_time=explode(":",$view[$i][signtime]);
					$write_day = mktime ($arr_time[0],$arr_time[1],$arr_time[2], $arr_day[1] , $arr_day[2], $arr_day[0]);
					$view_day = $write_day+((60*60)*24);
					if($today < $view_day) $newIcon = " <img src=\"/img/board/new.gif\" align=\"absmiddle\">"; else $newIcon="";


					//== 비밀글 비번 입력레이어
					if($view[$i][secret]>0) {
						if((member_session(1) == true) || (login_session()==true && !strcmp($view[$i][mem_id],$_SESSION[my_id]))) {
							$link="javascript:url_move('view', '".$_GET[code]."', '".$_GET[page]."', '".$view[$i][idx]."', '".$_GET[keyword]."', '".$_GET[s_1]."', '".$_GET[s_2]."', '".$_GET[s_3]."', '1');";										//== 관리자나 글등록자인 경우
							$Pass_Form_Link="";
						}else {																																																//== 비번 요청
							$link="javascript:formShow('acForm$i');";
							$Pass_Form_Link="";
						}
					}else {
						$link="javascript:url_move('view', '".$_GET[code]."', '".$_GET[page]."', '".$view[$i][idx]."', '".$_GET[keyword]."', '".$_GET[s_1]."', '".$_GET[s_2]."', '".$_GET[s_3]."', '0');";											//== 일반열람자인 경우
						$Pass_Form_Link="";
					}

					//== 등록자료 이미지 출력
					if($board_info[pds_view]>0) $pds_icon=file_view(1,"./files/".$_GET[code],$view[$i][filename0])." "; else $pds_icon="";

					//== 레이어 미리보기 설정
					if($board_info[overview] == 1 && $view[$i][secret] != 1) {
						if(strlen($view[$i][ucontents])>300) $ucontents=han_cut($view[$i][ucontents], 300, "..."); else $ucontents=$view[$i][ucontents];
						$ucontents = stripslashes($ucontents);																											//== 공백과 따움표 제거(레이어)
						if($view[$i][auto_enter]>0) $ucontents = nl2br($ucontents);
						$ucontents = ereg_replace("[[:space:]]+", " ", $ucontents);
						$ucontents = ereg_replace("\"", " ", $ucontents);
						$ucontents = str_replace("\r\n","",$ucontents);
						$ucontents = str_replace("'","",$ucontents);
						$rTitle .= $pds_icon."<a href=\"".$link."\" onMouseOver=\"writetxt('".$ucontents."')\" onMouseOut=\"writetxt(0)\" title=\"".$titleBars."\">".$subject."</a>".$newIcon."";
					}else {
						$rTitle .= $pds_icon."<a href=\"".$link."\"".$Pass_Form_Link." title=\"".$titleBars."\">".$subject."</a>".$newIcon."";
					}
					if($c_count>0) $rTitle .= " <span style=\"color:#F97501;\">[".$c_count."]</span>";
					//if($view[$i][click] > $board_info[hotclick]) $rTitle .= " <img src=\"/img/board/hot.gif\" align=\"absmiddle\">";
					//== 비밀글(비공개글)
					if($view[$i][secret]>0) $rTitle .= " <img src=\"/img/board/secret_img.gif\" align=\"absmiddle\">";
					//== 작성자의 성명이 길면 줄이고 이메일에 링크
					if($view[$i][email]) $real_email = "<a href=\"./sendmail.php?email=".base64_encode($view[$i][email])."\" target=\"mail_cipher\" title=\"등록자에게 메일발송\">".$name."</a>"; else $real_email = $name;

					//== 썸네일 이미지
					if($view[$i][filename0] && $board_info[only_img]>0) {
						$image_dir = 'files/'.$_GET[code].'/'.$view[$i][filename0];
						$img_size = @getimagesize($image_dir);
							if($img_size[0]>1024) $pop_width=1024+6; else $pop_width=$img_size[0]+6;
							if($img_size[1]>768) $pop_height=768+6; else $pop_height=$img_size[1]+6;
							if($img_size[0]>590) $img_width=590; else $img_width=$img_size[0];
							$img_height=$img_size[1];
						$thumbneil_img = 'files/'.$_GET[code].'/thumbnail/'.$view[$i][filename0];
					}

					//== 승인형 게시판
					if($board_info[approve]>0 && $view[$i][approve]<1 && member_session(1)==true) $rTitle .= " <span style=\"color:#FF6633;\"><strong>[미승인]</strong></span>";
				?>
					<tr>
						<td><?=$article_num;?>.</td>
						<td><?=$view[$i][b_class];?></td>
						<td class="ListAlign"><?=$rTitle;?>
						<?if($view[$i][secret]>0) {?>
						<div id="acForm<?=$i;?>" class="passForm_List" style="width:125px; display:none;">
							<form name="acForm<?=$i;?>" onsubmit="passCheck(this,'secret','<?=$view[$i][idx];?>'); return false;">
							<div><input type="password" name="passwd" size="8" maxlength="12" class="passbox" title="비밀번호입력" /><input type="hidden" name="mode" value="" /><input type="hidden" name="fid" value="<?=$view[fid];?>" /><input type="hidden" name="thread" value="<?=$view[thread];?>" /><input type="hidden" name="mem_id" value="<?=$view[mem_id];?>" /> <input type="image" src="/img/board/delete(btn).gif" width="30" height="20" style="vertical-align:middle;" title="확인" /> <a href="#endLayer" onclick="formShow('acForm<?=$i;?>');" title="닫기"><img src="/img/board/close(btn).gif" width="30" height="20" style="vertical-align:middle;" alt="닫기" /></a>
							</div>
							</form>
						</span>
						<?}?>
						</td>
						<td><?=strtr($view[$i][etc01],"-",".")."~".strtr($view[$i][etc02],"-",".");?></td>
						<td><?=$signdate;?></td>
					</tr>
				<?
				$rTitle = "";//== 제목변수 초기화
				$article_num--; }
				?>
			</tbody>
		</table>
	</div>

	<div id="boardTail">
		<span class="tblLeft"><?=$view_paging;?></span>
		<span class="tblRight">
			<form name="searchForm" method="get" action="<?=$PHP_SELF;?>">
			<fieldset >
				<legend>게시판 검색</legend>
				<select name="b_class" class="selectbox">
					<option value="">공고구분</option>
					<option value="공고"<?if($_GET[b_class]=='공고') echo " selected";?>>공고</option>
					<option value="재공고""<?if($_GET[b_class]=='재공고') echo " selected";?>>재공고</option>
					<option value="추가공고""<?if($_GET[b_class]=='추가공고') echo " selected";?>>추가공고</option>
				</select>
				<input type="hidden" name="code" value="<?=$_GET[code];?>">
				<input type="checkbox" name="s_1" value="subject" checked style="vertical-align:-2px;" title="제목으로 검색" />제목
				<input type="checkbox" name="s_2" value="ucontents" style="vertical-align:-2px;" title="내용으로 검색" />내용
				<input type="checkbox" name="s_3" value="name" style="vertical-align:-2px;" title="이름으로 검색" />이름
				<input type="text" name="keyword" size="15" maxlength="20" class="textbox" title="검색 키워드 입력" />
				<input type="button" class="searchBtn" onClick="this.form.submit();" value="검색"/>
			</fieldset>
		</form>
		</span>
	</div>

	<?if($board_info[board_tail]) echo $board_info[board_tail];?>
	<?//== 비밀번호 입력 레이어 폼;
	require ('inc/passForm.htm');
	?>
	<iframe name="mail_cipher" src="./sendmail.php" style="display:none;" ></iframe>