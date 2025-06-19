<?
if($align_type==="DESC") $v_align_type="ASC"; else if($align_type==="ASC") $v_align_type="DESC"; else $v_align_type="DESC";
if($rst_print) $rst_print="<tr><td colspan=\"5\">".$rst_print."</td></tr>";
if($board_info[board_class]) {
	$class_item=explode(",",$board_info[board_class]);
	for($i=0; $i<count($class_item); $i++) {
		if($_GET[b_class] == $class_item[$i]) $classSelected="font-weight:600; border-color: #81C36C #81C36C #fff #81C36C;"; else $classSelected="";
		$p_class .= "<li style=\"".$classSelected."\"><a href=\"list.php?code=".$_GET[code]."&b_class=".$class_item[$i]."\">".$class_item[$i]."</a></li>\n";
	}
	if(!$_GET[b_class]) $cSelected="font-weight:600; border-color: #81C36C #81C36C #fff #81C36C;"; else $cSelected="";
	$pClass = "<ul class=\"humanlabs\">\n<li style=\"".$cSelected."\"><a href=\"list.php?code=".$_GET[code]."\">전체</a></li>\n".$p_class."</ul>";
}
?>

	<!-- 게시판 테이블 기본스킨-->

	<div id="" style="width:auto; height:22px; padding:.5em 1em; border-top:1px solid #A9A9A9; background:#FAFAFA; ">
		<span class="tblLeft">
		<form name="searchForm" method="get" action="<?=$PHP_SELF;?>">
			<fieldset >
				<legend>게시판 검색</legend>
				<input type="hidden" name="code" value="<?=$_GET[code];?>">
				<input type="checkbox" name="s_1" value="subject" checked style="vertical-align:-2px;" title="제목으로 검색" />제목
				<input type="checkbox" name="s_2" value="ucontents" style="vertical-align:-2px;" title="내용으로 검색" />내용
				<input type="checkbox" name="s_3" value="name" style="vertical-align:-2px;" title="이름으로 검색" />이름
				<input type="text" name="keyword" size="15" maxlength="20" class="textbox" title="검색 키워드 입력" />
				<input type="button" class="searchBtn" onClick="this.form.submit();" value="검색"/>
			</fieldset>
		</form>
		</span>
		<span class="tblRight">
			<ul>
			<?
				$basicLevel=count($mLevel);
				if($_SESSION[my_level]=='') $_SESSION[my_level]=$basicLevel;
				if((($board_info[write_level] >= $_SESSION[my_level])) || ($board_info[write_level]==$basicLevel)) {
					$Board_Menu .= "<li><a href=\"javascript:url_move('write','$_GET[code]');\" title=\"새글쓰기\"><span class=\"boardBtn\">새글쓰기</span></a></li>";
				}
				if(login_session()) {
					//$Board_Menu .= "<li><a href=\"../wMembers/logout.php?code=".$_GET[code]."&mode=board\" title=\"로그아웃\"><span class=\"boardBtn\">로그아웃</span></a></li>";
				}else {
					//$Board_Menu .= "<li><span class=\"boardBtn\"><a href=\"#:;\" id=\"adminBtn\" title=\"로그인폼 레이어 열기\">로그인</span></a></li>";
				}
				if($Board_Menu) echo $Board_Menu;
			?>
			</ul>
		</span>
	</div>

	<div id="boardHead">
		<?=$pClass;?>
	</div>

	<!-- 게시판 리스트 출력 스킨 -->
	<div class="tblList">
		<div class="faqList">
				<?
				echo noticeNotice($b_cfg_tb[1],$_GET[code], 5);												//== 게시물 공지글 출력
				echo $rst_print;																																					//== 게시물 등록/검색정보출력
				for($i = $first; $i <= $last; $i++) {
					//== 리스트의 마지막 라인 스타일 변경
					if(!$view[$i][subject]) $view[$i][subject]="제목이 입력되지 안았습니다.";
					$subject = stripslashes($view[$i][subject]);															//== 문자열 복구
					$subject = htmlspecialchars($subject);																			//== 제목에 태그불허(검색어가 없을경우)
					$titleBars=$subject;																																	//== 태그없는 title
					//== 내용의 총라인수
					$line = explode("\n",$view[$i][ucontents]);
					$line_of_ucontents = sizeof($line);
					//== 제목의 길이 제어(들여쓰기에 따라 자르는 크기 지정)
					if(strlen($subject) > $board_info[subject_cut]) {
						if($thread==="A") {
							$subject = han_cut($subject, $board_info[subject_cut], "..");
						}else {
							$cutsize=(strlen($view[$i][thread])*2);
							$subject = han_cut($subject, $board_info[subject_cut] - $cutsize, "..");
						}
					}
					//== 검색시 글자색상 지정
					if($_GET[keyword]) $subject = eregi_replace($_GET[keyword], "<span style=\"color:#FF6633;\"><strong>$_GET[keyword]</strong></span>", $subject);
					$name = han_cut($view[$i][name], 8, "");//== 작성자 길이 줄임
					$signdate=strtr($view[$i][signdate],"-",".");

					// 작성시간 기준 만 24시 동안 새글 설정
					$today=time();
					$arr_day=explode("-",$view[$i][signdate]);
					$arr_time=explode(":",$view[$i][signtime]);
					$write_day = mktime ($arr_time[0],$arr_time[1],$arr_time[2], $arr_day[1] , $arr_day[2], $arr_day[0]);
					$view_day = $write_day+((60*60)*24);
					if($today < $view_day) $newIcon = " <img src=\"/img/board/new.gif\" align=\"absmiddle\">"; else $newIcon="";

					$vUcontents = stripslashes($view[$i][ucontents]);

				?>
				<ul class="faqBody">
					<li class="article" id="a<?=$article_num;?>">
						<div class="q"><a href="#a<?=$article_num;?>"><span class="arialQ">Q</span> <?=$subject;?></a></div>
						<div class="a"><span class="arialA">A</span><div style="word-break:break-all;"><?=$vUcontents;?><?if(member_session(1)) {?><a href="edit.php?code=<?=$_GET[code];?>&idx=<?=$view[$i][idx];?>" class="boardBtn">수정</a><?}?></div></div>
					</li>
				</ul>
				<?$article_num--; }?>
		</div>
	</div>

	<div id="boardTail" style="text-align:center;">
		<span><?=$view_paging;?></span>
	</div>

<script type="text/javascript">
	jQuery(function($){
		// Frequently Asked Question
		var article = $('.faqList>.faqBody>.article');
		article.addClass('hide');
		article.find('.a').hide();
		article.eq(0).removeClass('hide');
		article.eq(0).find('.a').show();
		$('.faqList>.faqBody>.article>.q>a').click(function(){
			var myArticle = $(this).parents('.article:first');
			if(myArticle.hasClass('hide')){
				article.addClass('hide').removeClass('show');
				article.find('.a').slideUp(100);
				myArticle.removeClass('hide').addClass('show');
				myArticle.find('.a').slideDown(100);
			} else {
				myArticle.removeClass('show').addClass('hide');
				myArticle.find('.a').slideUp(100);
			}
			return false;
		});
		$('.faqList>.faqHeader>.showAll').click(function(){
			var hidden = $('.faqList>.faqBody>.article.hide').length;
			if(hidden > 0){
				$(".showAll").html("<span class='faqon'>▲</span> 모두닫기");
				article.removeClass('hide').addClass('show');
				article.find('.a').slideDown(100);
			} else {
				$(".showAll").html("<span class='faqoff'>▼</span> 모두보기");
				article.removeClass('show').addClass('hide');
				article.find('.a').slideUp(100);
			}
		});
	});
</script>
<style>
	/* FAQ */
	.faqList { border-bottom:1px solid #ddd; margin:1em 0; }
	.faqList .faqHeader { position:relative;zoom:1; }
	.faqList .faqHeader .showAll { position:absolute; bottom:0; right:0; border:0; padding:0; overflow:visible; background:none; cursor:pointer; }
	.faqList .faqBody{ margin:0; padding:0; }
	.faqList .q { position:relative; margin:0; padding:0 .5em; border-top:1px solid #ddd; }
	.faqList .q a { display:block; padding:1em 0; text-align:left; font-size:1.1em; background:url('/img/board/arrowDown.png') no-repeat right 17px; color:#000; text-decoration:none !important; }
	.faqList .q a:hover, .faqList .q a:active, .faqList .q a:focus{ color:#3C9CC6; background:url('/img/board/arrowUp.png') no-repeat right 17px; }
	.faqList .a { margin:0; padding:.5em; line-height:150%; font-size:1em; color:#5C5959; background:#F4F4F4; }
	.arialQ { font-family: Arial, sans-serif !important; font-weight:bold; font-size:1.2em; color:#57453C; }
	.arialA { font-family: Arial, sans-serif !important; font-weight:bold; font-size:1.2em; color:#B8B8B8; }
	.faqon { font-family: '맑은 고딕', 'Malgun Gothic', "돋움", Dotum, AppleGothic, sans-serif !important; font-weight:bold; font-size:1.2em; color:#FFA500; }
	.faqoff { font-family: '맑은 고딕', 'Malgun Gothic', "돋움", Dotum, AppleGothic, sans-serif !important; font-weight:bold; font-size:1.2em; color:#FFA500; }
</style>