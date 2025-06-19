<?
if($align_type==="DESC") $v_align_type="ASC"; else if($align_type==="ASC") $v_align_type="DESC"; else $v_align_type="DESC";
if($rst_print) $rst_print="<li style=\"padding:1em 0; font-size:1.1em !important; text-align:center !important;\">".$rst_print."</li>";
switch ($_GET[b_class]) {
	case "대북사업" :
	$cssOn01="on";
	break;
	case "교육·행사" :
	$cssOn02="on";
	break;
	case "청소년" :
	$cssOn03="on";
	break;
	case "대학생" :
	$cssOn04="on";
	break;
	default :
	$cssOn00="on";
}
?>
<div style="background: #f1f1f1;">
	<div class="tit1">
		<strong>활동소식</strong>
	</div>
	<div class="board">
		<div class="board_tab tab6">
			<a href="<?=$_SERVER["PHP_SELF"];?>?code=<?=$_GET[code];?>&b_class=" class="<?=$cssOn00;?>">전체</a>
			<a href="<?=$_SERVER["PHP_SELF"];?>?code=<?=$_GET[code];?>&b_class=대북사업" class="<?=$cssOn01;?>">대북사업</a>
			<a href="<?=$_SERVER["PHP_SELF"];?>?code=<?=$_GET[code];?>&b_class=교육·행사" class="<?=$cssOn02;?>">교육·행사</a>
			<a href="<?=$_SERVER["PHP_SELF"];?>?code=<?=$_GET[code];?>&b_class=청소년" class="<?=$cssOn03;?>">청소년</a>
			<a href="<?=$_SERVER["PHP_SELF"];?>?code=<?=$_GET[code];?>&b_class=대학생" class="<?=$cssOn04;?>">대학생</a>
		</div>
		<div class="board_typeB_list">
			<ul>
				<?
				echo noticeNotice($b_cfg_tb[1],$_GET[code], 5);												//== 게시물 공지글 출력
				echo $rst_print;																																					//== 게시물 등록/검색정보출력
				for($i = $first; $i <= $last; $i++) {
					//== 리스트의 마지막 라인 스타일 변경
					if(!$view[$i][subject]) $view[$i][subject]="제목이 입력되지 않았습니다.";
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
					//if($today < $view_day) $newIcon = " <img src=\"/img/board/new.gif\" align=\"absmiddle\">"; else $newIcon="";


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
					}else {
						$thumbneil_img = "/img/board/400X300.jpg";
					}

					//== 승인형 게시판
					if($board_info[approve]>0 && $view[$i][approve]<1 && member_session(1)==true) $rTitle .= " <span style=\"color:#FF6633;\"><strong>[미승인]</strong></span>";
				?>
				<li>
					<a href="<?=$link;?>">
						<img src="<?=$thumbneil_img;?>" />
						<span class="category"><?=$view[$i][b_class]?></span>
						<p><?=$subject;?></p>
						<span class="date"><?=$signdate;?></span>
						<span class="view"><?=$view[$i][click]?></span>
					</a>
				</li>
				<?
				$rTitle = "";//== 제목변수 초기화
				$article_num--; }
				?>
			</ul>
		</div>
		<div class="paging">
			<?=$view_paging;?>
		</div>
		<div class="board_search">
			<form name="searchForm" method="get" action="<?=$PHP_SELF;?>">
				<input type="text" name="keyword" maxlength="20" placeholder="검색어를 입력해주세요." />
				<input type="button" value="검색" onClick="this.form.submit();" />
				<input type="hidden" name="code" value="<?=$_GET[code];?>">
				<input type="hidden" name="s_1" value="subject" />
			</form>
		</div>
	</div>
	<?if(member_session(1) == true) {?>
	<p style="padding:10px; text-align:center;"><a href="javascript:url_move('write','<?=$_GET[code];?>');">쓰기</a></p>
	<?}?>
</div>