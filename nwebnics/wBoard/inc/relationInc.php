<?
		//================================>>이전이후 관련글 시작<<=====================================//
		//== fid가 자신하고 같고, 쓰레드가 자신보도 큰것의 카운트를 구해 0보다 크면 자식쓰레드중에서 맥스값을 구하고
		//== 카운트가 0보다 작은경우 fid가 자기 자신보다 작은것중 맥스, 쓰레드가 A인것을 desc한다.
		$sql_str_mode = "SELECT COUNT(idx) FROM ".$b_cfg_tb[1]." WHERE code='".$_GET[code]."' AND fid=".$view[fid]." AND thread > '".$view[thread]."'";
		$fid_count = $db->getOne($sql_str_mode);
		if(DB::isError($fid_count)) die($fid_count->getMessage());
		//== 자식글이 있을경우 이전글을 자식글에서 탐색하고 없을경우 자신의 idx보다 하나 작은것을 찾음(단 쓰레드가 A인것)
		if($fid_count>0) {
			$sql_str_up = "SELECT * FROM ".$b_cfg_tb[1]." WHERE code='".$_GET[code]."' AND fid=".$view[fid]." AND idx > ".$_GET[idx]." ORDER BY idx ASC";
		}else {
			$sql_str_up = "SELECT * FROM ".$b_cfg_tb[1]." WHERE code='".$_GET[code]."' AND idx < ".$_GET[idx]." AND fid < ".$view[fid]." AND thread = 'A' ORDER BY idx DESC";
		}
			$rst_up = $db->getRow($sql_str_up,DB_FETCHMODE_ASSOC);
			if(DB::isError($rst_up)) die($$rst_up->getMessage());

		//======================================= 이후글 추출 ============================================
		//== 자신의 쓰레드가 A이다면 idx가 지신보다 큰것중 쓰레드가 A인것을 구하고
		//== 쓰레드가 A가 아니라면 fid가 자신이고, 쓰레드가 자신보다 큰것중 맥스값을 구하면 된다.
		if($view[thread] === "A") {
			$sql_str_down = "SELECT * FROM ".$b_cfg_tb[1]." WHERE code='".$_GET[code]."' AND idx > ".$_GET[idx]." AND fid > ".$view[fid]." ORDER BY idx ASC";
		}else {
			$sql_str_down = "SELECT * FROM ".$b_cfg_tb[1]." WHERE code='".$_GET[code]."' AND fid = ".$view[fid]." AND thread < '".$view[thread]."' ORDER BY idx DESC";
		}
			$rst_down = $db->getRow($sql_str_down,DB_FETCHMODE_ASSOC);
			if(DB::isError($rst_down)) die($$rst_down->getMessage());

		//======================================= 이전이후 링크 설정 ============================================
		if($rst_up[secret]>0) {																										//== 이전글
			$prevLink="# onClick=\"return enlarge('back',event,'secret','".$rst_up[idx]."');\"";
		}else {
			$prevLink="javascript:url_move('view', '$_GET[code]', '$_GET[page]', '$rst_up[idx]', '$_GET[keyword]', '$_GET[s_1]', '$_GET[s_2]', '$_GET[s_3]', '$rst_up[secret]');";
		}
		if($rst_down[secret]>0) {																							//== 이후글
			$nexiLink="# onClick=\"return enlarge('back',event,'secret','".$rst_down[idx]."');\"";
		}else {
			$nexiLink="javascript:url_move('view', '$_GET[code]', '$_GET[page]', '$rst_down[idx]', '$_GET[keyword]', '$_GET[s_1]', '$_GET[s_2]', '$_GET[s_3]', '$rst_down[secret]');";
		}
?>

<!-- 이전글 출력 -->
<?if($rst_up) {?>
	<div class="list">
		<a href="<?=$prevLink;?>">
			<span>이전</span>
			<?=$rst_up[subject];?>
		</a>
	</div>
<?}?>
<!-- 이후글 출력 -->
<?if($rst_down) {?>
	<div class="list">
		<a href="<?=$nexiLink;?>">
			<span>다음</span>
			<?=$rst_down[subject];?>
		</a>
	</div>
<?}?>