<?
//==================================================== 월별 통계 =============================================================================
function monthStatics($vYear, $vMonth, $vDay) {
	global $db;

	if(empty($vYear)) $vYear=date('Y'); else $vYear=sprintf('%02d',$vYear);
	if(empty($vMonth)) $vMonth="00"; else $vMonth=sprintf('%02d',$vMonth);
	if(empty($vDay)) $vDay="00"; else $vDay=sprintf('%02d',$vDay);
	//== 최대 접속 질의
	$sDate=$vYear.'-'.$vMonth.'-'.$vDay;
	$maxStr = "SELECT DATE_FORMAT(visitDate,'%m') AS vMonth, COUNT(DISTINCT(user_ip)) AS vCount FROM wStatics WHERE DATE_FORMAT(visitDate,'%Y') = DATE_FORMAT('$sDate','%Y') GROUP BY DATE_FORMAT(visitDate,'%m') ORDER BY vCount DESC";
	$maxMonth = $db->getRow($maxStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($maxMonth)) die($maxMonth->getMessage());
	$vTitle="<div><strong class=\"staticsTitle\">".$vYear."년 월별 로그</strong></div>";
	$vChart = $vTitle."<table class=\"offLineTbl\">\n<caption>".$vYear."년 월별 로그</caption>\n<tbody><tr>\n";

	for($i=1; $i<=12; $i++) {
		$vDate=$vYear.'-'.sprintf('%02d',$i).'-'.$vDay;
		unset($addSql, $sqlStr);
		$sqlStr = "SELECT COUNT(DISTINCT(user_ip)) FROM wStatics";
		//if($startDate && $stopDate) $addSql .= " DATE_FORMAT(visitDate,'%Y-%m-%d') BETWEEN DATE_FORMAT('$startDate','%Y-%m-%d') AND DATE_FORMAT('$stopDate','%Y-%m-%d') AND";
		if($vDate) $addSql .= " DATE_FORMAT(visitDate,'%Y-%m') = DATE_FORMAT('$vDate','%Y-%m') AND";
		if($addSql) $sqlStr .= " WHERE".substr($addSql,0,-3);
//		echo $sqlStr.'<br>';
		$monthRet = $db->getOne($sqlStr);
		if(DB::isError($monthRet)) die($monthRet->getMessage());
		if($maxMonth[vCount]==$monthRet) $v_image="./img/red.gif"; else if($vMonth==sprintf('%02d',$i)) $v_image="./img/green.gif"; else $v_image="./img/blue.gif";
		$vChart .= "<td style=\"vertical-align:bottom;\"><div>".$monthRet."</div><div><img src=\"".$v_image."\" width=\"30\" height=\"".$monthRet."\" /></div><div class=\"staticsSub\">".$i."월</div></td>";
	}
	$vChart .= "</tr>\n</tbody>\n</table>\n<br />\n";
	return $vChart;
}

//==================================================== 일별 통계 =============================================================================
function dayStatics($vYear, $vMonth, $vDay) {
	global $db;

	if(empty($vYear)) { $vYear=date('Y'); } else { $vYear=sprintf('%02d',$vYear); $formDate .= '%Y-'; }
	if(empty($vMonth)) { $vMonth='00'; } else { $vMonth=sprintf('%02d',$vMonth); $formDate .= '%m-'; }
	if(empty($vDay)) { $vDay='00'; $formDate .= '%d-'; } else { $vDay=sprintf('%02d',$vDay); $formDate .= '%d-'; }		//== 일별에서 Day 포멧은 필수
	$formDate=substr($formDate, 0, -1);

	//== 최대 접속 질의
	$sDate=$vYear.'-'.$vMonth.'-'.$vDay;
	$maxDate = '%Y-';
	if(empty($vMonth)) $maxDate .= '%m-';
	$maxDate=substr($maxDate, 0, -1);
	$maxStr = "SELECT DATE_FORMAT(visitDate,'%d') AS vDay, COUNT(DISTINCT(user_ip)) AS vCount FROM wStatics WHERE DATE_FORMAT(visitDate,'".$maxDate."') = DATE_FORMAT('$sDate','".$maxDate."') GROUP BY DATE_FORMAT(visitDate,'%d') ORDER BY vCount DESC";
	$maxDay = $db->getRow($maxStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($maxDay)) die($maxDay->getMessage());

	$vDTitle .= $vYear.'년';
	if($vMonth!="00") $vDTitle .= $vMonth.'월';
	$vTitle="<div><strong class=\"staticsTitle\">".$vDTitle." 일별 로그</strong></div>";
	$vChart = $vTitle."<table class=\"offLineTbl\">\n<caption>".$vDTitle." 일별 로그</caption>\n<tbody><tr>\n";

	//== 년도별과 월별 구분
	if($vMonth!="00") $thisMonth=get_total_days($vYear,$vMonth); else $thisMonth=31;

	for($i=1; $i<=$thisMonth; $i++) {
		$vDate=$vYear.'-'.$vMonth.'-'.sprintf('%02d',$i);
		unset($addSql, $sqlStr);
		$sqlStr = "SELECT COUNT(DISTINCT(user_ip)) FROM wStatics";
		if($vDate) $addSql .= " DATE_FORMAT(visitDate,'".$formDate."') = DATE_FORMAT('$vDate','".$formDate."') AND";
		if($addSql) $sqlStr .= " WHERE".substr($addSql,0,-3);
		//echo $sqlStr.'<br>';
		$dayRet = $db->getOne($sqlStr);
		if(DB::isError($dayRet)) die($dayRet->getMessage());
		if($maxDay[vCount]==$dayRet) $v_image="./img/red.gif"; else if($vDay==sprintf('%02d',$i)) $v_image="./img/green.gif"; else $v_image="./img/blue.gif";
		$vChart .= "<td style=\"vertical-align:bottom;\"><div>".$dayRet."</div><div><img src=\"".$v_image."\" width=\"20\" height=\"".$dayRet."\"></div><div class=\"staticsSub\">".$i."</div></td>";
	}
	$vChart .= "</tr>\n</tbody>\n</table>\n<br />\n";
	return $vChart;
}

//==================================================== 주별 통계 =============================================================================
function weekStatics($vYear, $vMonth, $vDay) {
	global $db;

	if(empty($vYear)) { $vYear = date('Y'); $formDate .= '%Y-'; } else { $formDate .= '%Y-'; }
	if(empty($vMonth)) { $vMonth .= '00'; } else { $formDate .= '%m-'; }
	if(empty($vDay)) $vDay .= '00';																											//== 주별통계에서 일자는 의미 없음
	$formDate=substr($formDate, 0, -1);
	//== 최대 접속 질의
	$sDate=$vYear.'-'.$vMonth.'-'.$vDay;
	$maxStr = "SELECT DAYOFWEEK(visitDate) AS vWeek, COUNT(DISTINCT(user_ip)) AS vCount FROM wStatics WHERE DATE_FORMAT(visitDate,'".$formDate."') = DATE_FORMAT('$sDate','".$formDate."') GROUP BY DAYOFWEEK(visitDate) ORDER BY vCount DESC";
	$maxWeek = $db->getRow($maxStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($maxWeek)) die($maxWeek->getMessage());

	$vDTitle .= $vYear.'년';
	if($vMonth!="00") $vDTitle .= $vMonth.'월';
	$vTitle="<div><strong class=\"staticsTitle\">".$vDTitle." 주별 로그</strong></div>";
	$vChart = $vTitle."<table class=\"offLineTbl\">\n<caption>".$vDTitle." 주별 로그</caption>\n<tbody><tr>\n";

	for($i=0; $i<7; $i++) {
		$vDate=$vYear.'-'.$vMonth.'-'.$vDay;
		unset($addSql, $sqlStr);
		$sqlStr = "SELECT COUNT(DISTINCT(user_ip)) FROM wStatics";
		if($vDate) $addSql .= " DAYOFWEEK(visitDate) = ($i+1) AND DATE_FORMAT(visitDate,'".$formDate."') = DATE_FORMAT('$vDate','".$formDate."') AND";
		if($addSql) $sqlStr .= " WHERE".substr($addSql,0,-3);
		//echo $sqlStr.'<br>';
		$weekRet = $db->getOne($sqlStr);
		if(DB::isError($weekRet)) die($weekRet->getMessage());
		switch ($i) {
			case (0) :
				$weekName="일";
			break;
			case (1) :
				$weekName="월";
			break;
			case (2) :
				$weekName="화";
			break;
			case (3) :
				$weekName="수";
			break;
			case (4) :
				$weekName="목";
			break;
			case (5) :
				$weekName="금";
			break;
			case (6) :
				$weekName="토";
			break;
		}
		if($maxWeek[vCount]==$weekRet) $v_image="./img/red.gif"; else $v_image="./img/blue.gif";									//== 최대값과 카운트가 일치하는경우
		$vChart .= "<td style=\"vertical-align:bottom;\"><div>".$weekRet."</div><div><img src=\"".$v_image."\" width=\"50\" height=\"".$weekRet."\"></div><div class=\"staticsSub\">".$weekName."요일</div></td>";
	}
	$vChart .= "</tr>\n</tbody>\n</table>\n<br />\n";
	return $vChart;
}

//==================================================== 시간별 통계 ==========================================================================
function timeStatics($vYear, $vMonth, $vDay) {
	global $db;

	if(empty($vYear)) { $vYear = date('Y'); $formDate .= '%Y-'; } else { $vYear = $vYear; $formDate .= '%Y-'; }
	if(empty($vMonth)) { $vMonth = '00'; } else { $vMonth = $vMonth; $formDate .= '%m-'; }
	if(empty($vDay)) { $vDay .= '00'; } else { $vDay = $vDay; $formDate .= '%d-'; }
	//== 최대 접속 질의
	$vDate=$vYear.'-'.$vMonth.'-'.$vDay;
	$formDate=substr($formDate, 0, -1);
	$maxStr = "SELECT TIME_FORMAT(visitTime,'%H') AS vHour, COUNT(DISTINCT(user_ip)) AS vCount FROM wStatics WHERE DATE_FORMAT(visitDate,'".$formDate."') = DATE_FORMAT('$vDate','".$formDate."') GROUP BY TIME_FORMAT(visitTime,'%H') ORDER BY vCount DESC";
	$maxHour = $db->getRow($maxStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($maxHour)) die($maxHour->getMessage());

	$vDTitle .= $vYear.'년';
	if($vMonth!="00") $vDTitle .= $vMonth.'월';
	if($vDay!="00") $vDTitle .= $vDay.'일';

	if($vMonth!="00") $vDTitle .= $vMonth.'월';
	$vTitle="<div><strong class=\"staticsTitle\">".$vDTitle." 시간별 로그</strong></div>";
	$vChart = $vTitle."<table class=\"offLineTbl\">\n<caption>".$vDTitle." 시간별 로그</caption>\n<tbody><tr>\n";

	for($i=0; $i<24; $i++) {
		$vTime=sprintf('%02d',$i).':00:00';																																																	//== 시간별 형식 조합
		unset($addSql, $sqlStr);
		$sqlStr = "SELECT COUNT(DISTINCT(user_ip)) FROM wStatics";
		if($vDate) $addSql .= " DATE_FORMAT(visitDate,'".$formDate."') = DATE_FORMAT('$vDate','".$formDate."') AND TIME_FORMAT(visitTime,'%H') = TIME_FORMAT('$vTime','%H') GROUP BY TIME_FORMAT(visitTime,'%H') AND";
		if($addSql) $sqlStr .= " WHERE".substr($addSql,0,-3);
		//echo $sqlStr.'<br>';
		$hourRet = $db->getOne($sqlStr);
		if(DB::isError($hourRet)) die($hourRet->getMessage());
		if(!$hourRet) $hourRet=0;
		if($maxHour[vCount]==$hourRet) $v_image="./img/red.gif"; else $v_image="./img/blue.gif";									//== 최대값과 카운트가 일치하는경우
		$vChart .= "<td style=\"vertical-align:bottom;\"><div>".$hourRet."</div><div><img src=\"".$v_image."\" width=\"20\" height=\"".$hourRet."\"></div><div class=\"staticsSub\">".sprintf('%02d', $i)."</div></td>";
	}
	$vChart .= "</tr>\n</tbody>\n</table>\n<br />\n";
	return $vChart;
}
?>