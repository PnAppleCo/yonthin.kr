<?
//== 만족도 평가 라디오박스
for($i=1; $i<count($Arr_psc_grade); $i++) {
	if($view[svc_grade]==$i) $checked_svc_grade=" checked"; else $checked_svc_grade="";
	$v_grade .= "<input type=\"radio\" name=\"svc_grade\" value=\"$i\"".$checked_svc_grade." class=\"align_left_middle\">".$Arr_psc_grade[$i];
}
//== 답변글 내용
if($view[svc_reply]) $repleStatus="<strong>".$Arr_psc_status[$view[svc_status]]."</strong>&nbsp;[담당부서:".$view[svc_name]."]";
?>
<div id="psCenter">
	<form name="gradeForm">
		<table summary="민원글 처리">
			<caption>민원글 처리</caption>
			<colgroup>
				<col width="15%" />
				<col width="85%" />
			</colgroup>
			<tbody>
				<!-- 답변이 완료 -->
				<?if($view[svc_reply]) {?>
				<tr>
					<th>답변내용</th>
					<td>
						<div id="repleStatus">
							<span><?=$repleStatus;?></span>
							<!-- 답변 평가 -->
							<span><?=strtr($view[svc_date], "-", ".");?></span>
						</div>
						<div class="contentsPrint"><?=$view[svc_reply];?></div>
					</td>
				</tr>
				<!-- 답변 미완료 -->
				<?}else {?>
				<tr>
					<td colspan="2" style="text-align:center;">현재 <strong>"<?=$Arr_psc_status[$view[svc_status]];?>"</strong>입니다</span></td>
				</tr>
				<?}?>
			</tbody>
		</table>
	</form>
</div>