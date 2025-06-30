<div class="board_view_wrap">
	<div class="board_view">
		<div class="top">
			<div class="title"><?=$view['subject'];?></div>
			<div class="writer">
				<dl>
					<dt>작성자</dt>
					<dd><?=$view['name'];?></dd>
				</dl>
			</div>
			<div class="date">
				<dl>
					<dt>작성일</dt>
					<dd><?=$signdate;?></dd>
				</dl>
			</div>
		</div>
		<div class="file">
			<a href="#">
				<?php if(!empty($o_data)) { echo "<img src=\"/img/comm/icon_file.png\" alt=\"icon\">".$o_data; }?>
			</a>
		</div>
		<div class="cont_wrap">
			<?=$view['ucontents'];?>
		</div>
<!--
		<div class="img_wrap">
			<img src="/img/temp/notice_img.png" alt="">
		</div>
 -->
	</div>
	<div class="bt_wrap">
		<a href="/nwebnics/wBoard/list.php?code=<?=$_GET['code'];?>" class="bt1 on">목록</a>
	</div>
</div>