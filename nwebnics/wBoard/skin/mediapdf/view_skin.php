<?

if($view[idx]<=401) $o_img_photo = photoLoad("photo", $view[etc01]);

function photoLoad($code, $idx) {
	global $db, $board_info;
		$sql_str = "SELECT * FROM g4_board_file WHERE bo_table='$code' AND wr_id='$idx' ORDER BY bf_no DESC";
		$result = $db->query($sql_str);
		if(DB::isError($result)) die($result->getMessage());
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				//== 이미지 기본정보 처리

				$savedir=$_SERVER['DOCUMENT_ROOT']."/data/file/".$code;
				$o_data .= "<a href=\"./down.php?code=".$_GET[code]."&save_dir=".$savedir."&filename=".$view[filename.$i]."\">".file_view("",$savedir,$view[filename.$i])."</a> ";
				//== 확장자 추출
				$upfile=explode(".",$view[bf_file]);
				switch ($upfile[1]) {
					case ("wmv") : case ("asf") : case ("mpg") : case ("mpeg") :
							//== 동영상보기
						$movie_path="files/".$_GET[code]."/".$view[filename.$i];
						$o_img_news .= "<div><embed src=\"".$movie_path."\" type=\"application/x-mplayer2\" width=\"280\" height=\"240\" autostart=\"false\" loop=\"false\"></embed></div><br>";
					break;
					case ("gif") : case ("GIF") : case ("jpg") : case ("JPG") : case ("jpeg") : case ("bmp") :
						$img_dir = $savedir."/".$view[bf_file];
							//== 팝업 창크기설정
							$pop_height=700;
							$scroll_status="yes";

							$img_size[0]=800;
							$img_size[1]=600;
							//== 이미지크기설정(비율에 맞게 조정)
							if($img_size[0]>$board_info[img_view_size]) {
								$img_width=$board_info[img_view_size];																										//== 가로 비율
								$img_height=(($board_info[img_view_size]*$img_size[1])/$img_size[0]);			//== 세로 비율
							}else {
								$img_width=$img_size[0];
								$img_height=$img_size[1];
							}
							$v_img_dir="/data/file/".$code."/".$view[bf_file];

							//== 이미지 넓이가 적은경우 이미지 좌측 배열설정
							if($img_width!=0 && $img_width<=350) $v_width_style=" style=\"float:left\""; else $v_width_style="";
							$alt_img_size = "[".$img_size[0]." ×".$img_size[1]."]";
							//== 포토게시판 이미지보기
							$o_img_photo .= "<a href=\"javascript:void(window.open('./zoom.php?image=".urlencode($img_dir)."','photo_zoom','width=$pop_width,height=$pop_height,status=no,resizable=0,scrollbars=$scroll_status,1,1'));\"><img src=\"$v_img_dir\" align=\"center\"".$v_width_style." alt=\"$alt_img_size\"></a><br><br>";
					break;
					default :
						$o_data .= "";
				}

			$i++;
		}
		return $o_img_photo;
}
?>

<div class="board_typeB_view">
	<div class="title">
		<p>
			<?=$view[subject];?>
		</p>
		<span class="date"><?=$signdate;?></span>
		<span class="view"><?=number_format($view[click]);?></span>
		<input type="button" class="url" id="urlCopy" value="링크 복사" />
		<input type="button" class="facebook" value="페이스북 공유" onClick="snsModule.share_fb();" />
		<input type="hidden" id="clip_url" value="<?=$_SERVER["HTTP_HOST"].$_SERVER[PHP_SELF]?>?<?=$_SERVER[QUERY_STRING]?>" />
	</div>
	<div class="contents">
		<?if($view[youtube]) {?>
		<div id="youtubePlayer"></div>
		<?}?>
		<div class="responseCss"><?if($o_img_photo) echo $o_img_photo;?></div>
		<?=$view[ucontents];?>
	</div>
	<div style="border-top:1px solid #e0e0e0; padding:1em 0;">
		<p style="font-size:1.1em; padding:.5em 1em;">첨부파일</p>
		<?=$o_data;?>
	</div>
	<?if($board_info[relation_text]>0) require ('inc/relationInc.php');?>
</div>
<div class="bt_wrap">
	<a href="list.php?code=<?=$_GET[code];?>&page=<?=$_GET[page];?>" class="bt3">목록</a>
	<?if(member_session(1) == true) {?>
	<a href="javascript:url_move('edit','<?=$_GET[code];?>','<?=$_GET[page];?>','<?=$_GET[idx];?>','<?=$_GET[keyword];?>','<?=$_GET[s_1];?>','<?=$_GET[s_2];?>','<?=$_GET[s_3];?>');" class="bt3">수정</a>
	<a href="javascript:url_move('delete','<?=$_GET[code];?>','<?=$_GET[page];?>','<?=$_GET[idx];?>','<?=$_GET[keyword];?>','<?=$_GET[s_1];?>','<?=$_GET[s_2];?>','<?=$_GET[s_3];?>');" class="bt3">삭제</a>
	<?}?>
	<form name="Del_Form">
		<input type="hidden" name="mode" value="delete">
		<input type="hidden" name="fid" value="<?=$view[fid];?>">
		<input type="hidden" name="thread" value="<?=$view[thread];?>">
		<input type="hidden" name="mem_id" value="<?=$view[mem_id];?>">
	</form>
</div>

<script>
	// 2. This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');

	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	// 3. This function creates an <iframe> (and YouTube player)
	//    after the API code downloads.
	var player;
	function onYouTubeIframeAPIReady() {
		player = new YT.Player('youtubePlayer', {
			height: '100%',
			width: '100%',
			videoId: '<?=$view[youtube];?>',
			rel : 0, //0으로 해놓아야 재생 후 관련 영상이 안뜸
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange
			}
		});
	}

	// 4. The API will call this function when the video player is ready.
	function onPlayerReady(event) {
		//event.target.playVideo();// 플레이어 자동실행 (주의: 모바일에서는 자동실행되지 않음)
	}

	// 5. The API calls this function when the player's state changes.
	//    The function indicates that when playing a video (state=1),
	//    the player should play for six seconds and then stop.
	var done = false;
	function onPlayerStateChange(event) {
		if (event.data == YT.PlayerState.PLAYING && !done) {
			setTimeout(stopVideo, 6000);
			done = true;
		}
	}
	function stopVideo() {
		//alert('미션 수행 완료!');
		//player.stopVideo();
	}
</script>
<style>
  #youtubePlayer { position: relative; width: 100%; height: 0; padding-bottom: 56.25%; }
  #youtubePlayer iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
  .contents iframe { max-width: 100%; max-height: 100%; }
</style>