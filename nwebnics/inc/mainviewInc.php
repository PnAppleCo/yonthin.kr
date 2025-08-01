<?php
//===========================
//== 메인페이지 공지 출력 함수
//===========================

function indexSlide($type) {
	global $db, $popupDir, $imgArray;
	$rstslide = '';
	$rstslide_m = '';
		$sqlStr = "SELECT * FROM wPopup WHERE popupType='3' AND (date_format(now(), '%Y%m%d') BETWEEN date_format(startdate, '%Y%m%d') AND date_format(stopdate, '%Y%m%d')) ORDER BY idx DESC";
		//echo $sqlStr;
		//exit;
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vTitlle = stripslashes($view['popupTitle']);
			$vTitlle = htmlspecialchars($vTitlle);
			if(mb_strlen($vTitlle, 'UTF-8') > 38) $vTitlle = han_cut($vTitlle, 38, "…");
			if(mb_strlen($view['aSummary'], 'UTF-8') > 54) $view['aSummary'] = han_cut($view['aSummary'], 56, "…");

			//== 이미지 추출
			$upFile=explode(".",$view['filename0']);
			if($view['filename0'] && in_array($upFile[1],$imgArray)) {
				$vImg = $popupDir.$view['filename0'];
			}else {
				$vImg = "";
			}

			$upFile=explode(".",$view['filename1']);
			if($view['filename1'] && in_array($upFile[1],$imgArray)) {
				$vImg_m = $popupDir.$view['filename1'];
			}else {
				$vImg_m = "";
			}

			if($view['linkTarget']=='2') $targetType="_blank"; else $targetType="_self";
			//$rstslide .="<div class=\"items idxA".$i."\" style=\"background-image: url(".$vImg.");\">".$view[uContents]."</div>";
			$rstslide .="<div class=\"items idxA".$i."\"><a href=\"".$view['linkUrl']."\"><img src=\"".$vImg."\" alt=\"".$view['popupTitle']."\"></a></div>";
			//$rstslide_m .="<div class=\"items idxA_m".$i."\"><a href=\"".$view[linkUrl]."\"><img src=\"".$vImg_m."\" /></a></div>";
			$rstslide_m .="<div class=\"items idxA_m".$i."\"><a href=\"".$view['linkUrl']."\"><img src=\"".$vImg_m."\" alt=\"".$view['popupTitle']."\" /></a></div>";
			$i++;
		}
		if($type==1) return $rstslide; else return $rstslide_m;
}

function photoList01($link,$db_table, $code, $view_count, $cutting) {
	global $db;
	$rstcontents = '';
		$sqlStr = "SELECT * FROM $db_table WHERE code='$code' ORDER BY signdate DESC, signtime DESC LIMIT $view_count";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vTitlle = stripslashes($view['subject']);
			$vTitlle = htmlspecialchars($vTitlle);
			if(mb_strlen($vTitlle, 'UTF-8') > $cutting) $vTitlle = han_cut($vTitlle, $cutting, "…");
			$vContents = stripslashes($view['ucontents']);
			//$vContents = htmlspecialchars($vContents);
			$vContents = strip_tags($vContents);
			if(mb_strlen($vContents, 'UTF-8') > 30) $vContents = han_cut($vContents, 30, "…");

			//== 이미지 추출
			if($view['filename0']) {
				$vImg = "/nwebnics/wBoard/files/".$code."/thumbnail/".$view['filename0'];
			}else {
				$vImg = "http://placehold.it/340x225";
			}
			$linkurl=$link."view.php?code=".$code."&idx=".$view['idx'];
			$rstcontents .="
	              <li>
	                <a href=\"".$linkurl."\">
	                  <img src=\"".$vImg."\" />
	                  <i>".$view['b_class']."</i>
	                  <p>".$vTitlle."</p>
	                </a>
	              </li>";
			$i++;
		}
		return $rstcontents;
}

function photoList02($link,$db_table, $code, $view_count, $cutting) {
	global $db;
	$rstcontents = '';
		$sqlStr = "SELECT * FROM $db_table WHERE code='$code' ORDER BY idx DESC LIMIT $view_count";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$vTitlle = stripslashes($view['subject']);
			$vTitlle = htmlspecialchars($vTitlle);
			if(mb_strlen($vTitlle, 'UTF-8') > $cutting) $vTitlle = han_cut($vTitlle, $cutting, "…");
			$vContents = stripslashes($view['ucontents']);
			//$vContents = htmlspecialchars($vContents);
			$vContents = strip_tags($vContents);
			if(mb_strlen($vContents, 'UTF-8') > 30) $vContents = han_cut($vContents, 30, "…");

			//== 신청 가능 시간 확인
			$startArr=explode("-",$view['etc01']);
			$stopArr=explode("-",$view['etc02']);
			$startTime = @mktime (0,0,0, $startArr[1] , $startArr[2], $startArr[0]);
			$stopTime = @mktime (0,0,0, $stopArr[1] , $stopArr[2], $stopArr[0]);
			$todayTime = @mktime (0,0,0, date('m') , date('d'), date('Y'));
			if($startTime <= $todayTime && $stopTime >= $todayTime) {																																//== 신청중
				$appStatus="진행중";
			}else {
				if($startTime>$todayTime) {																																																					//== 신청예정
					$appStatus="진행예정";
				}else {																																																																			//== 신청마감
					$appStatus="진행마감";
				}
			}
			if($view['etc02']) $endDates =  "~".strtr($view['etc02'],"-",".");

			//== 이미지 추출
			if($view['filename0']) {
				$vImg = "/nwebnics/wBoard/files/".$code."/thumbnail/".$view['filename0'];
			}else {
				$vImg = "/img/board/340X185.jpg";
			}
			$linkurl=$link."view.php?code=".$code."&idx=".$view['idx'];
			$rstcontents .="
	                <li>
	                  <a href=\"".$linkurl."\">
	                    <img src=\"".$vImg."\" />
	                    <i class=\"on\">".$appStatus."</i>
	                    <strong>".$vTitlle."</strong>
	                    <span>일시 :".strtr($view['signdate'],"-",".").$endDates."</span>
	                  </a>
	                </li>";
			$i++;
		}
		return $rstcontents;
}

// 2025.6.24 쿼리수정 : $bClass 삭제
// function boardList($link, $db_table, $code, $view_count, $cutting,$bClass) {
function boardList($link, $db_table, $code, $view_count, $cutting) {
	global $db;
	$rstcontents = '';
		//if($bClass) $addSql=" AND b_class='".$bClass."'";
		// $sqlStr = "SELECT * FROM $db_table WHERE code='$code'".$addSql." ORDER BY signdate DESC, signtime DESC LIMIT $view_count";
		$sqlStr = "SELECT * FROM $db_table WHERE code='$code' ORDER BY signdate DESC, signtime DESC LIMIT $view_count";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$subject = stripslashes($view['subject']);
			//$subject = htmlspecialchars(str_replace("\"","", $subject));
			if(isMobile()) {
				if(mb_strlen($subject, 'UTF-8') > 20) $subject = han_cut($subject, 20, "..");
			}else {
				if(mb_strlen($subject, 'UTF-8') > $cutting) $subject = han_cut($subject, $cutting, "..");
			}
			$linkUrl="<li><a href=\"".$link."view.php?code=".$code."&idx=".$view['idx']."&secret=".$view['secret']."\">".$subject."</a><span>".strtr($view['signdate'],"-",".")."</span></li>";
			
			$rstcontents .= $linkUrl."\n";
		}
		return $rstcontents;
}

//== POLL
function pollList($code, $mnv) {
	global $db, $_SERVER, $_SESSION;
	$returnPoll = '';
	$onLink = '';
	$sqlStr="SELECT *, (vote0 + vote1 + vote2 + vote3 + vote4 + vote5 + vote6 + vote7 + vote8 + vote9) AS tcnt FROM wPoll WHERE code='".$code."' ORDER BY idx DESC LIMIT 1";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	$sContents=stripslashes($view['sContents']);

	$jsLink="'$view[code]','$view[idx]', '$mnv'";

	//== 투표가능 시간 및 유무 확인
	if(login_session()) {
		if($_COOKIE['voteok'] == $_SESSION['my_id'].$view['idx']) {
			$btnName="<img src=\"/img/comm/poll_app_finish_index.gif\" alt=\"투표완료\" onClick=\"alert('".$_SESSION['my_name']."님은 이미 투표하셨습니다.');\" />";
		}else if(strtotime("today")>strtotime($view['endDate'])) {
			$btnName="<img src=\"/img/comm/poll_app_end_index.gif\" alt=\"투표종료\" onClick=\"alert('투표가 종료되었습니다.');\" />";
		}else {
			$btnName="<img src=\"/img/comm/poll_app_index.gif\" alt=\"투표하기\" onClick=\"addChk(document.pollForm,'vote',".$jsLink."); return false;\" />";
		}
	}else {
		$btnName="<img src=\"/img/comm/poll_app_login.gif\" alt=\"로그인후 투표하세요.\" onClick=\"alert('로그인후 투표하세요.');\" />";
	}
	$returnPoll .= "<form name=\"pollForm\">";
	$returnPoll .= "<div><img src=\"/img/comm/poll_q.gif\" /><span style=\"font-weight:bold; font-size:13px; color:#484848;\">".$view['sSubject']."</span></div>";
	$returnPoll .= "<table class=\"offLineTbl\">
						<caption>통계</caption>
						<colgroup>
							<col width=\"5%\" />
							<col width=\"40%\" />
							<col width=\"55%\" />
						</colgroup>
						<tbody>";
							for($i=0; $i<10; $i++) {
								if($view['censitem'.$i]) {
									$returnPoll .= "<tr><td><input type=\"radio\" name=\"vote\" value=\"".$i."\" style=\"vertical-align:middle;\" /></td><td>".$view['censitem'.$i]."</td>";
									$returnPoll .= "<td><img src=\"/img/comm/bar.gif\" width=\"".totalAve(1, $view['tcnt'],$view['vote'.$i])."\" height=\"10\" /> 총".$view['vote'.$i]."명 [".number_format(totalAve(1, $view['tcnt'],$view['vote'.$i]))."%]</td></tr>";
								}
							}
	$returnPoll .= "</tbody>\n</table>";
	$returnPoll .= "<div style=\"padding-top:10px; text-align:center;\"><a href=\"javascript:".$onLink."\">".$btnName."</a></div>";
	$returnPoll .= "\n</form>";
	return $returnPoll;
}

//== 팝업존 그룹
function poupZone($rType) {
	global $db;
	$returnNo = '';
		$sqlStr = "SELECT * FROM wPopup WHERE popupType='3' AND (date_format(now(), '%Y%m%d') BETWEEN date_format(startdate, '%Y%m%d') AND date_format(stopdate, '%Y%m%d')) ORDER BY idx DESC";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$returnData="";
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			if($i==1) {
				$popidStatus="block";
				$popnoStatus="on";
			}else {
				$popidStatus="none";
				$popnoStatus="off";
			}
			if($view['filename0']) $popContents="<img src=\"/nwebnics/wTools/popupFiles/".$view['filename0']."\" alt=\"".$view['popup_title']."\" />"; else $popContents=$view['uContents'];
			$returnNo .= "<li><a href=\"#pop".$i."\" onclick=\"popup_display('".$i."', '');\" title=\"".$i."번 팝업\"><img id=\"popupzoneNumber".$i."\" src=\"/img/popupzone".$i."_".$popnoStatus.".gif\" alt=\"".$i."번 팝업\" /></a></li>\n";
			$returnData .= "<li id=\"popupzoneImage".$i."\" style=\"display:".$popidStatus."\"><a href=\"".urldecode($view['linkurl'])."\" target=\"".$view['linkTarget']."\" title=\"".$view['popup_title']."\">".$popContents."</a></li>\n";
			$i++;
		}
		if($rType==1) return $returnNo; else if($rType==2) return $returnData; else if ($rType==3) return ($i-1);
}

//== 팝업존 그룹
// function poupZones() {
// 	global $db, $popupDir;
// 	$rdata = '';
// 		$sqlStr = "SELECT * FROM wPopup WHERE popupType='3' AND (DATE_FORMAT(now(), '%Y%m%d') BETWEEN DATE_FORMAT(startDate, '%Y%m%d') AND DATE_FORMAT(stopDate, '%Y%m%d')) ORDER BY idx DESC";
// 		$result = $db->query($sqlStr);
// 		if(DB::isError($result)) die($result->getMessage());
// 		$returnData="";
// 		$i=1;
// 		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
// 			$rdata .= "
// 								<div class=\"item\">
// 									<a href=\"".$view['linkUrl']."\" target=\"".$view['linkTarget']."\"><img src=\"".$popupDir.$view['filename0']."\" alt=\"".$view['popup_title']."\" /></a>
// 								</div>";
// 		}
// 		return $rdata;
// }
function poupZones(): string {
    global $db, $popupDir;

    $rdata = '';

    $sqlStr = "
        SELECT * 
        FROM wPopup 
        WHERE popupType = '3' 
          AND (DATE_FORMAT(NOW(), '%Y%m%d') BETWEEN DATE_FORMAT(startDate, '%Y%m%d') AND DATE_FORMAT(stopDate, '%Y%m%d'))
        ORDER BY idx DESC
    ";

    $result = $db->query($sqlStr);

    if (DB::isError($result)) {
		return $$result;
        die($result->getMessage()); // 더 나은 방식: 로깅 후 사용자에게는 일반 오류 메시지
    }

    while ($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
        // 안전한 출력 처리
        $linkUrl     = htmlspecialchars($view['linkUrl'] ?? '#');
        $linkTarget  = htmlspecialchars($view['linkTarget'] ?? '_blank');
        $popupTitle  = htmlspecialchars($view['popup_title'] ?? '');
        $imagePath   = htmlspecialchars($popupDir . ($view['filename0'] ?? ''));

        $rdata .= <<<HTML
            <div class="item">
                <a href="{$linkUrl}" target="{$linkTarget}">
                    <img src="{$imagePath}" alt="{$popupTitle}" />
                </a>
            </div>
        HTML;
    }

    return $rdata;
}

//== 팝업존 그룹
function poupLayer() {
	global $db, $popupDir;
	$rdata = '';
		$sqlStr = "SELECT * FROM wPopup WHERE popupType='2' AND (DATE_FORMAT(now(), '%Y%m%d') BETWEEN DATE_FORMAT(startDate, '%Y%m%d') AND DATE_FORMAT(stopDate, '%Y%m%d')) ORDER BY idx DESC LIMIT 1";
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$returnData="";
		$i=1;
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$rdata .= "
			<div class=\"cont\">
				<a href=\"".$view['linkUrl']."\" target=\"".$view['linkTarget']."\"><img src=\"".$popupDir.$view['filename0']."\" class=\"responImg\" alt=\"".$view['popup_title']."\" /></a>
			</div>";
		}
		return $rdata;
}

//== 게시판정보
function boardInfo($code) {
	global $db;
	$sqlStr = "SELECT * FROM wboardConfig WHERE code='".$code."'";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	return $view['board_summary'];
}

//== 나의정보
function myInfomation() {
	global $db, $_SESSION;
	$sqlStr = "SELECT * FROM wMember WHERE mId='".$_SESSION['my_id']."'";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	return $view;
}

//== 회사정보 로드
function companyInfo() {
	global $db;
	//== 오늘 등록된 게시물
	$sqlStr = "SELECT * FROM infoTbl WHERE idx=1";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	return $view;
}

//== 프로그램 신청자수(전체)
function appCount($widx) {
	global $db;
	$sqlStr = "SELECT SUM(appCnt) FROM appList WHERE appIdx='".$widx."' AND pStatus<3";
	$cCount = $db->getOne($sqlStr);
	if(DB::isError($cCount)) die($cCount->getMessage());
	return $cCount;
}

//== 프로그램 신청자수 확인(회차별)
function appCounts($widx, $eDate, $eTime="") {
	global $db;
	$sqlStr = "SELECT SUM(appCnt) FROM appList WHERE appIdx='".$widx."' AND eduDate='".$eDate."'";
	if($eTime) $sqlStr .= " AND eduTime='".$eTime."'";
	$cCount = $db->getOne($sqlStr);
	if(DB::isError($cCount)) die($cCount->getMessage());
	return $cCount;
}

//== 프로그램별 총 인원
function allCount($widx) {
	global $db;
	$sqlStr = "SELECT * FROM eduList WHERE idx=".$widx."";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());

	//== 전체진행날짜 카운팅
	if($view['byDate']>0) {												//== 날짜별 미진행
		return $view['recruitCnt'];
	}else {																						//== 날짜별 진행
		$newDate = date("Y-m-d", strtotime("-1 day", strtotime($view['estartDate'])));
		$tDates = 0;
		while(true) {
			$newDate = date("Y-m-d", strtotime("+1 day", strtotime($newDate)));
			$tDates++;
			if($newDate == $view['estopDate']) break;
		}
		//== 전체진행시간 카운팅
		$appTimes=count(explode(",",$view['eduDate']));
		return $tDates*$appTimes*$view['recruitCnt'];
	}
}

//== 교육정보
function eduInfo($idx) {
	global $db;
	$sqlStr = "SELECT * FROM eduList WHERE idx=".$idx."";
	$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($view)) die($view->getMessage());
	return $view;
}

//== 통계 비율
function totalAve($type,$total,$user) {
	if($type==1) {
		$temp1 = @round(($user/$total)*100,1);
		return substr($temp1,0,5);
	}else if($type==2) {
		$temp2 = @round(($user/57)*100,1);
		return $temp2;
	}
}

//== 팝업(2016.06.12)
function poupOpen($mode=1) {
	global $db, $popupDir, $_COOKIE;
	$main_view = '';
		$sqlStr = "SELECT * FROM wPopup WHERE popupType='$mode' AND (DATE_FORMAT(startDate,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') AND DATE_FORMAT(stopDate,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d')) ORDER BY idx DESC";
		//echo $sqlStr;
		$result = $db->query($sqlStr);
		if(DB::isError($result)) die($result->getMessage());
		$i=0;
		$win_popup = "function openPop() { \n";
		while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			//== 공통사용
			if($view['linkTarget']=="1") $targets="_self"; else if($view['linkTarget']=="2") $targets="_blank";
			if($view['linkurl']) $view['linkurl']=$view['linkurl']; else $view['linkurl']="#";

			if($mode==1) {																																			//== 팝업창 오픈
				$cookie_name="wpo".$view['idx'];
				$popup_name="wpo".$view['idx'];
				$win_popup .= "if(getCookie('".$cookie_name."') != 'no' ) {\n".$cookie_name."=window.open('/popup.htm?idx=".$view['idx']."','".$popup_name."',' top=".$view['locationTop'].",left=".$view['locationLeft'].",width=".$view['popWidth'].",height=".$view['popHeight'].",status=no,scrollbars=".$view['scrollbar'].",resizable=yes');\n".$cookie_name.".opener = self;\n } \n";

			}else if($mode==2) {																															//== 레이어 오픈
				$cookie_name="ipop".$view['idx'];
				$popup_name="ipop".$view['idx'];
				if($view['filename0']) {
					$e_name=explode('.',$view['filename0']);
					if($e_name[1]=='JPG' || $e_name[1]=='jpg' || $e_name[1]=='jpeg' || $e_name[1]=='gif' || $e_name[1]=='png' || $e_name[1]=='PNG') {
						$img_path = $popupDir.$view['filename0'];
						$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$img_path);
						$main_view .= "<img src=\"".$img_path."\" width=\"".$img_size[0]."\" height=\"".$img_size[1]."\" />";
					}else if($e_name[1]=='html' || $e_name[1]=='htm') {
						require $popupDir.$view['filename0'];
					}
				}
				$printText=preg_replace("/\s+/", "", $view['uContents']);		//== 공백제거
				if($printText) {
					$o_uContents=stripslashes($view['uContents']);
					//if($view[html]==0) $o_uContents = htmlspecialchars($o_uContents);
					$main_view .= $o_uContents;
				}
				if($view['ingTime']>0) $popAdd="<span class=\"float_left\"><input type=\"checkbox\" name=\"ipop".$i."\" onClick=\"closeiPop('$popup_name', '$cookie_name');\" class=\"align_left_middle\" />하루만 보기</span>";
				if($view['locationLeft']>0 OR $view['locationTop']>0) {					//== 절대값 임의영역 고정
					$layerStyle=" style=\"position:absolute; width:$view[popWidth]px; height:$view[popHeight]px; left:$view[locationLeft]px; top:$view[locationTop]px; z-index:9999; display:none; overflow:hidden; background:#fff; border:1px solid #CCCCCC;\"";
				}else {																																			//== 상대값 상단 고정
					$layerStyle=" style=\"position:relative; width:$view[popWidth]px; height:$view[popHeight]px; margin:0 auto; z-index:9999999; display:none; overflow:hidden; background:#fff;  border:1px solid #CCCCCC;\"";
				}

				$layer_popup .= "
				<div id=\"$popup_name\"".$layerStyle.">
					<div id=\"popupContents\"><a href=\"".$view['linkurl']."\" target=\"".$targets."\">".$main_view."</a></div>
					<div style=\"text-align:right;\">".$popAdd."<span class=\"float_right\">[<a href=\"#\" onClick=\"closeiPop('$popup_name', '');\">닫기</a>]</span></div>
				</div>";
				$java_start .= "
				<script language=\"javascript\">
					if(getCookie('".$cookie_name."') !='no') { document.getElementById('".$popup_name."').style.display='block'; }
				</script>";

			}else if($mode==3) {																															//== 팝업존
				//$returnDiv .= "<div class=\"jqb_slide\" title=\"".$view[popupTitle]."\" ><a href=\"".$view[linkUrl]."\" target=\"".$targets."\"><img src=\"".$popupDir.$view[filename0]."\" title=\"".$view[popupTitle]."\" alt=\"팝업존0".$i."\"/></a></div>\n";
				if($view['linkTarget']=='2') $targetType="_blank"; else $targetType="_self";
				//== 모바일 체크
				//if(isMobile()) $chkFiles=$view[filename1]; else $chkFiles=$view[filename0];
				$chkFiles=$view['filename0'];
				if($chkFiles) {
					$e_name=explode('.',$chkFiles);
					if($e_name[1]=='JPG' || $e_name[1]=='jpg' || $e_name[1]=='jpeg' || $e_name[1]=='gif' || $e_name[1]=='png' || $e_name[1]=='PNG') {
						$img_path = $popupDir.$chkFiles;
						$img_size = @getimagesize($_SERVER["DOCUMENT_ROOT"].$img_path);
						$img_view = "<a href=\"".$view['linkUrl']."\" target=\"".$targetType."\"><img src=\"".$img_path."\" alt=\"".$view['popupTitle']."\" /></a>";
					}
				}else {
					$img_view = "<a href=\"".$view['linkUrl']."\" target=\"".$targetType."\">".$view['popupTitle']."</a>";
				}
				$returnDiv .= "<li>".$img_view."</li>";
			}

			unset($main_view);
			$i++;
		}
		if($mode==1) {
			if($i>0) return $win_popup."}\n openPop();\n";
		}else if($mode==2) {
			if($i>0) return $layer_popup.$java_start;
		}else if($mode==3) {
			return $returnDiv;
		}
}

//== 달력 출력 함수(2016.07.14)
function wCalendar($nowYear, $nowMonth, $cType="", $dType="") {
	global $_GET;
	$iNames = '';
	//== 주어진 날짜가 없을경우 오늘날짜 지정
	//== 시작주와 마지막 날짜 계산
	$first_week = date('w', mktime(0,0,0,$nowMonth,1,$nowYear));
	$last_day = date('t', mktime(0,0,0, $nowMonth,1,$nowYear));
	//== 전체일수를 계산
	$total_days=get_total_days($nowYear,$nowMonth);

	//== 이전의 년/월 구하기
	$month_p = $nowMonth-1;
	if($month_p < 1) {
		$month_p=12;
		$year_p=$nowYear-1;
	}else {
		$year_p=$nowYear;
	}
	//== 이후의 년/월 구하기
	$month_n = $nowMonth + 1;
	if($month_n > 12) {
		$month_n=1;
		$year_n=$nowYear+1;
	}else {
		$year_n=$nowYear;
	}
	foreach($_GET AS $key => $value) {
		if($value) $iNames .= $value.",";
	}
	$iNames=substr($iNames,0,-1);
	$addGet="&code=".$_GET['code']."&cType=".$_GET['cType']."&dType=".$_GET['dType']."&sWord=".urlencode($_GET['sWord']);
	$yprev_link="<a href=\"".$_SERVER['PHP_SELF']."?year=".($nowYear-1)."&month=".$nowMonth.$addGet."\">".($nowYear-1)."</a>";
	$ynext_link="<a href=\"".$_SERVER['PHP_SELF']."?year=".($nowYear+1)."&month=".$nowMonth.$addGet."\">".($nowYear+1)."</a>";

	$prev_link="<a href=\"".$_SERVER['PHP_SELF']."?year=".$year_p."&month=".$month_p.$addGet."\"><img src=\"/img/comm/allow_right.jpg\" alt=\"이전달\" /></a>";
	$next_link="<a href=\"".$_SERVER['PHP_SELF']."?year=".$year_n."&month=".$month_n.$addGet."\"><img src=\"/img/comm/allow_left.jpg\" alt=\"다음달\" /></a>";
	//== 달력의 기본 정보 출력
	echo "<div id=\"calendarHead\">
		<span class=\"monthPrev\">".$yprev_link.$prev_link."</span><span class=\"nowMonth\">".$nowYear."년 ".number_format($nowMonth)."월</span><span class=\"monthNext\">".$next_link.$ynext_link."</span>
	</div>";
	echo "<div id=\"calendarDiv\">
			<table id=\"calendarTbl\">
				<caption>카렌더</caption>
				<colgroup>
					<col width=\"14.2%\" />
					<col width=\"14.3%\" />
					<col width=\"14.3%\" />
					<col width=\"14.3%\" />
					<col width=\"14.3%\" />
					<col width=\"14.3%\" />
					<col width=\"14.3%\" />
				</colgroup>
				<thead>
				<tr>
					<th>일요일</th>
					<th>월요일</th>
					<th>화요일</th>
					<th>수요일</th>
					<th>목요일</th>
					<th>금요일</th>
					<th>토요일</th>
				</tr>
				</thead>
				<tbody>
				<tr>";
				//== 달의 1일이 나오기 전까지 공백 출력
				$col = 0;
				$m=sprintf('%02d', $nowMonth);
				for($i = 0; $i < $first_week; $i++) { echo "<td>&nbsp;</td>"; $col++; }
				//== 날짜 출력 시작
				for($j = 1; $j <= $total_days; $j++) {
					if($col === 6) $bgcolor="#336ad2"; else if($col === 0) $bgcolor="#ec3939"; else $bgcolor="#222";//== 토/일/평일 색상구분
					if(date('d') == $j && date('m') == $m) $bgcolor="#8eb40d";																																														//== 금일날짜 색상지정
					echo "<td style=\"color:$bgcolor\">";
					if($j<10) $k="0".$j; else $k=$j;																																																					//== 일(10보다 작은일 앞에 0삽입)
					$choice_date=$nowYear."-".$m."-".$k;																																														//== 선택한 날짜
					$dayContents=dayContents($choice_date, $cType, $dType);
					echo "<p><strong>".$j."</strong></p>".$dayContents."</td>";
					//== 일주일이 지나면 다음줄로
					$col++;
					if($col === 7) {
						echo "</tr> ";
						if($j != $total_days) echo "<tr>";
						$col = 0;
					}
				}
				//== 달의 마지막날 이후는 공백 출력
				while($col > 0 && $col < 7) { echo "<td>&nbsp;</td>"; $col++; }
				echo "</tr></tbody></table></td></tr></div>";
}

//== 일정 확인
function dayContents($iDate, $cType, $dType) {
	global $db, $_SESSION;
	$addSql = '';
	$sqlStr = '';
	$rtn_rst = '';
	if($cType) $addSql .= " useBuilding='".$cType."'AND";
	if($dType) $addSql .= " useRoom='".$dType."'AND";
	if($addSql) $sqlStr .= " WHERE".substr($addSql,0,-3);
	$sqlStr = "SELECT * FROM appTbl WHERE ".$addSql."(DATE_FORMAT(startDate,'%Y-%m-%d')<='".$iDate."' AND DATE_FORMAT(stopDate,'%Y-%m-%d')>='".$iDate."') ORDER BY idx DESC;";
	//echo $sqlStr;
	$rst = $db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
	while($view = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		//$view[schTitle] = han_cut($view[schTitle], 14, "");
		$i_data=explode(" ",$view['startDate']);
		$rtn_rst .= "<li><a href=\"/contents.htm?code=3_3_3&mode=view&idx=".$view['idx']."\">".$view['appCorp']."</a></li>";
	}
	if($rtn_rst) return "<ul id=\"calendarUl\">\n".$rtn_rst."</ul>\n"; else return "";
}

// 청년독립지원청 미사용 함수
//== 주간일정(가나안)
// function weekSchedule() {
// 	global $weekArr, $_GET;
// 	if(!$Time) $Time = time();
// 	$week = date("w", $Time);
// 	if(!$_GET['sunday']) $_GET['sunday'] = mktime(0, 0, 0, date("m"), date("d") - $week, date("Y"));

// 	$Prev_Tme = $_GET['sunday'] - 604800;
// 	$Next_Time = $_GET['sunday'] + 604800;
// 	$prev_link="<a href=\"$_SERVER[PHP_SELF]?code=$_GET[code]&sunday=$Prev_Tme\" class=\"calBtn\">이전주</a>";
// 	$next_link="<a href=\"$_SERVER[PHP_SELF]?code=$_GET[code]&sunday=$Next_Time\" class=\"calBtn\">다음주</a>";
// 	$date_view="<font style=\"font: bold 13px 'Verdana'; color: #333333;\">".date("Y.m.d", $_GET['sunday'])."~".date("Y.m.d", $_GET['sunday']+518400)."</font>";
// 	echo "<div style=\"text-align:center; padding:1em 0;\">".$prev_link." ".$date_view." ".$next_link."</div>";
// 	echo "
// 		<div class=\"dietList\">
// 			<table surmmary=\"주간식단\">
// 				<caption>주간식단</caption>
// 				<colgroup>
// 					<col width=\"3%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 					<col width=\"13.9%\" />
// 				</colgroup>
// 				<tbody>
// 					<tr>
// 						<td valign=\"top\" style=\"padding:0;\">
// 							<table surmmary=\"식단\">
// 							<caption>식단</caption>
// 								<tbody>
// 									<tr><td style=\"background:#FEF5E8; height:22px; font-weight:bold;\">구 분</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:135px; vertical-align:middle; font-weight:bold;\">조&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold;\">죽&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold; letter-spacing:-2px;\">원산지</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:135px; vertical-align:middle; font-weight:bold;\">중&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold;\">죽&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold; letter-spacing:-2px;\">원산지</td></tr>
// 									<tr><td style=\"background:#E8F4FE; height:45px; vertical-align:middle; font-weight:bold;\">간&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:135px; vertical-align:middle; font-weight:bold;\">석&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold;\">죽&nbsp;&nbsp;식</td></tr>
// 									<tr><td style=\"background:#FEFBF8; height:45px; vertical-align:middle; font-weight:bold;letter-spacing:-2px;\">원산지</td></tr>
// 								</tbody>
// 							</table>
// 						</td>";
// 	for($i = 0, $day = $_GET['sunday']; $i < 7; $i++, $day+= 86400) {
// 		if(($i%2)==0) $line_bgcolor="#FFFFFF"; else $line_bgcolor="#F6F7F8";
// 		$choice_date=date('Y-m-d', $day);																												//== 선택한 날짜
// 		$deitView=explode("|",vDiets(1, $choice_date));																	//== 식단
// 		$sView=explode("|",vDiets(2, $choice_date));																				//== 원산지
// 		$pView=explode("|",vDiets(3, $choice_date));																				//== 죽식

// 		if($deitView[0]) $aPrint=$deitView[0]; else $aPrint="&nbsp;";
// 		if($pView[0]) $bPrint=$pView[0]; else $bPrint="&nbsp;";
// 		if($sView[0]) $cPrint=$sView[0]; else $cPrint="&nbsp;";
// 		if($deitView[1]) $dPrint=$deitView[1]; else $dPrint="&nbsp;";
// 		if($pView[1]) $ePrint=$pView[1]; else $ePrint="&nbsp;";
// 		if($sView[1]) $fPrint=$sView[1]; else $fPrint="&nbsp;";
// 		if($deitView[3]) $gPrint=$deitView[3]; else $gPrint="&nbsp;";
// 		if($deitView[2]) $hPrint=$deitView[2]; else $hPrint="&nbsp;";
// 		if($pView[2]) $iPrint=$pView[2]; else $iPrint="&nbsp;";
// 		if($sView[2]) $kPrint=$sView[2]; else $kPrint="&nbsp;";

// 		echo "<td valign=\"top\" style=\"border-left:1px solid #ccc; padding:0;\">
// 				<table surmmary=\"식단\">
// 				<caption>식단</caption>
// 					<thead>
// 						<tr><th style=\"height:22px;\">".$weekArr[date("w", $day)]."(".date("d일", $day).")</th></tr>
// 					</thead>
// 					<tbody>
// 						<tr><td style=\"height:135px; letter-spacing:-1px;\">".str_replace(',','<br />',$aPrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($bPrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($cPrint)."</td></tr>
// 						<tr><td style=\"height:135px; letter-spacing:-1px;\">".str_replace(',','<br />',$dPrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($ePrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($fPrint)."</td></tr>
// 						<tr><td style=\"height:45px; background:#E8F4FE; vertical-align:middle;\">".nl2br($gPrint)."</td></tr>
// 						<tr><td style=\"height:135px; letter-spacing:-1px;\">".str_replace(',','<br />',$hPrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($iPrint)."</td></tr>
// 						<tr><td style=\"height:45px; vertical-align:middle;\">".nl2br($kPrint)."</td></tr>
// 					</tbody>
// 				</table>
// 			</td>";
// 	}
// 	echo "</tr></tbody>\n</table>";
// }

//== 콘텐츠 로드
function loadContent($code) {
	global $db;
		$sqlStr = "SELECT * FROM cmsList WHERE cStatus>1 AND cmsCode='$code' LIMIT 1";
		$view = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
		if(DB::isError($view)) die($view->getMessage());
		if(!$view)  js_action(1,"중요정보를 찾을 수 없습니다. 관리자에게 문의하세요.","",-1);
		$pContents=stripslashes($view['uContents']);
		echo $pContents;
}
?>