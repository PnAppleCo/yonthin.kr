<div class="board_typeB_view">
	<div class="title">
		<p>
			<span class="category"><?=$view[b_class];?></span>
			<?=$view[subject];?>
		</p>
		<span class="date"><?=$signdate;?></span>
		<span class="view"><?=number_format($view[click]);?></span>
		<input type="button" class="url" id="urlCopy" value="링크 복사" />
		<input type="button" class="facebook" value="페이스북 공유" onClick="snsModule.share_fb();" />
		<input type="hidden" id="clip_url" value="<?=$_SERVER["HTTP_HOST"].$_SERVER[PHP_SELF]?>?<?=$_SERVER[QUERY_STRING]?>" />
	</div>
	<div class="contents">
		<div><?if($o_img_photo) echo $o_img_photo;?></div>
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