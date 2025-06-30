<?php
//========================================================================//
//======================= 루트 환경설정파일 로드 =========================//
//========================================================================//
include $_SERVER["DOCUMENT_ROOT"]."/nwebnics/inc/configInc.php";

//== 기본환경설정 테이블(환경설정, 게시판, 코멘트)
$b_cfg_tb = array('wboardConfig', 'wBoard', 'wComment');

//== 게시판 code 체크
//if(!$_GET[code] && !$_POST[code]) js_action(1, "게시판의 CODE를 찾을수 없습니다.", "", -1);
if($_GET['code']) $v_code=$_GET['code']; else if($_POST['code']) $v_code=$_POST['code']; else js_action(1, "게시판의 CODE를 찾을수 없습니다.", "", -1);

//== 게시판 등록 여부 체크
if(Board_Regist_Check($v_code,$b_cfg_tb[0])==false) js_action(1, "등록되지 않은 게시판 입니다.","",-1);

//== 기본환경정보 로드
$sql_str="SELECT * FROM ".$b_cfg_tb[0]." WHERE code='".$v_code."'";
$board_info = $db->getRow($sql_str,DB_FETCHMODE_ASSOC);
if(DB::isError($board_info)) die($board_info->getMessage());
$board_info['board_head'] = stripslashes($board_info['board_head']);
$board_info['board_tail'] = stripslashes($board_info['board_tail']);
$board_info['title_bar_text'] = stripslashes($board_info['title_bar_text']);
$board_info['meta_keyword'] = stripslashes($board_info['meta_keyword']);

//== 접근권한 설정 [삭제필요]
// $allow_ip = array("109.2.91.135");
// if(!in_array($_SERVER["REMOTE_ADDR"], $allow_ip)) { header('Location:/main.htm'); exit; }

//=========================================================================//
//============================== 게시판 관련 함수 ===========================//
//=========================================================================//

//== 게시판 등록 여부 체크
function Board_Regist_Check($code,$cfg_tb) {
	global $db;
	$sql_str = "SELECT COUNT(idx) FROM ".$cfg_tb." WHERE code='".$code."'";
	$N_Board = $db->getOne($sql_str);
	if(DB::isError($N_Board)) die($N_Board->getMessage());
	if($N_Board>0) return true; else return false;
}

function comment_count($idx, $cfg_tb) {
	global $db;
	//== 코멘트 카운트 쿼리
	$sql_str = "SELECT COUNT(idx) FROM ".$cfg_tb." WHERE board_idx='".$idx."'";
	$c_count = $db->getOne($sql_str);
	if(DB::isError($c_count)) die($c_count->getMessage());
	return $c_count;
}

//== 공지글 상위 출력
function noticeNotice($dbtable,$code, $tdCount) {
		global $db,$board_info;
		$rtn_rst = '';
		$notice_sql_str = "SELECT * FROM $dbtable WHERE code='$code' AND notice = 1 ORDER BY idx DESC";
		$notice_rst = $db->query($notice_sql_str);
		if(DB::isError($notice_rst)) die($notice_rst->getMessage());

		$Total_Count=0;
		while($notice_view = $notice_rst->fetchRow(DB_FETCHMODE_ASSOC)) {
			$link_url="javascript:url_move('view', '".$_GET['code']."', '".$_GET['page']."', '".$notice_view['idx']."', '".$_GET['keyword']."', '".$_GET['s_1']."', '".$_GET['s_2']."', '".$_GET['s_3']."', '".$notice_view['secret']."');";																//== 링크 설정
			$link_url1="<a href=\"$link_url\" class=\"basic\">";
			$link_url2="</a>";
			$notice_view['subject'] = stripslashes($notice_view['subject']);//== 문자열 복구
			$subject = han_cut($notice_view['subject'], $board_info['subject_cut'], "..");										//== 제목 줄임
			$name = han_cut($notice_view['name'], 8, "");																														//== 작성자 길이 줄임
			if($notice_view['email']) $rEmail = "<a href=\"./sendmail.php?email=".base64_encode($notice_view['email'])."\" target=\"mail_cipher\" class=\"basic\">".$name."</a>"; else $rEmail =  $name;																					//== 전자우편
			$signdate=strtr($notice_view['signdate'],"-",".");																													//== 작성일
			if($tdCount==4) {
				$rtn_rst .= "<tr style=\"background-color:#FFEFE3;\">\n
									<td><strong>Notice</strong></td>\n
									<td class=\"ListAlign\">".$link_url1.$subject.$link_url2."</td>\n
									<td>".$rEmail."</td>\n
									<td>".$signdate."</td>\n";
				$rtn_rst .=  "</tr>";
			}else if($tdCount==5) {
				$rtn_rst .= "<tr style=\"background-color:#FFEFE3;\">\n
									<td><strong>Notice</strong></td>\n
									<td class=\"ListAlign\">".$link_url1.$subject.$link_url2."</td>\n
									<td>".$rEmail."</td>\n
									<td>".$signdate."</td>\n
									<td>".$notice_view['click']."</td>\n";
									if($board_info['recommend'] == 1) $rtn_rst .= "<td>".$notice_view['recommend']."</td>\n";
									if($board_info['twinread'] == 1) $rtn_rst .= "<td><input type=\"checkbox\" name=\"check[]\" value=\"".$notice_view['idx']."\"></td>\n";
									if($board_info['ps_center']>0) $rtn_rst .= "<td>&nbsp;</td>\n";
				$rtn_rst .=  "</tr>";
			}
			$Total_Count++;
			unset($name);
		}
	if($Total_Count>0) return $rtn_rst; else return "";
}

//== 입력된 비밀번호와 게시물 비밀번호 비교 함수
function compare_pass($table, $code, $idx, $pass) {
	global $db;
	//== 사용자가 입력한 암호를 암호화
	$user_pass = $db->getOne("SELECT PASSWORD('$pass')");
	if(DB::isError($user_pass)) die($user_pass->getMessage());

	//== 선택된 게시물의 암호 추출
	$real_pass = $db->getOne("SELECT passwd FROM $table WHERE code='$code' AND idx=$idx");
	if(DB::isError($real_pass)) die($real_pass->getMessage());
	//== 게시물 암호와 입력된 암호를 비교후 결과값 리턴
	if(!strcmp($real_pass,$user_pass)) return true; else return false;
}

//== 베스트 이미지 추출
function Best_Img($link_url, $db_table, $code, $cut_subject, $popup, $css, $limit=5, $width=80, $height=60, $table_width=100, $view_type) {
	global $db,$Site_Root_Path,$board_info,$b_cfg_tb;
	//== 주별 월별 질의 설정
	if($view_type==="w") {
		$divi_sql="WEEK(signdate)=WEEK(now())";
		//== 금일이 몇번째 주인지 설정
		$Now_Date=Num_Handate (mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	}else if($view_type==="m") {
		$divi_sql="SUBSTRING(signdate,1,7)=DATE_FORMAT(now(),'%Y-%m')";
		//== 금일이 몇월인지 설정
		$Now_Date=date('Y')."년 ".date('m')."월";
	}else {
		error_view(999, "베스트이미지 뷰타입을 찾을수 없습니다.","관리자에게 문의하세요.");
	}
	$sql_str = "SELECT * FROM $db_table WHERE code='$code' and ".$divi_sql." ORDER BY recommend DESC, click DESC LIMIT $limit";
	$rst = $db->query($sql_str);
	if(DB::isError($rst)) die($rst->getMessage());
	//== 금일이 몇번째 주인지 설정
	$Now_Date=Num_Handate (mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr><td>";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#CCCCCC\"><tr><td>";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\" bgcolor=\"#FFFFFF\"><tr><td colspan=\"5\">";
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"3\" bgcolor=\"#FFF7EF\"><tr><td class=\"font_kr\">";
	echo $Now_Date." 베스트 이미지";
	echo "</td></tr></table>";
	echo "</td><tr>";
	$i=1;
	while($view = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		$subject = stripslashes($view['subject']);
		$subject = htmlspecialchars($subject);
		$subject = han_cut($subject, $cut_subject, "..");
		//== 링크설정
		if($popup === "Y") {
			$link_1 = "<a href=\"javascript:win_pop('".$link_url."popupview.php?code=".$code."&idx=".$view['idx']."&secret=".$view['secret']."','notice_win',500,400,0,0,1,50,50)\" class=\"".$css."\">";
		}else {
			$link_1 = "<a href=\"".$link_url."view.php?code=".$code."&idx=".$view['idx']."&secret=".$view['secret']."\" class=\"".$css."\">";
		}
		$link_2 = "</a>";
		//== 이미지 추출
		if($view['filename0']) {
			$img_dir = $Site_Root_Path.$link_url."files/".$code."/".$view['filename0'];
			$now_dir = $link_url."files/".$code."/thumbnail/".$view['filename0'];
			$img_size = getimagesize($img_dir);
				if($img_size[0]>$width) $img_width=$width; else $img_width=$img_size[0];
				if($img_size[1]>$height) $img_height=$height; else $img_height=$img_size[1];
				$o_img="<table width=\"$img_width\" height=\"$img_height\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table_outline\"><tr><td align=\"center\">$link_1<img src=\"$now_dir\" width=\"$img_width\" border=\"0\" height=\"$img_height\" align=\"center\">$link_2</td></tr></table>";
		}else {
			$o_img="<table width=\"$width\" height=\"$height\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table_outline\"><tr><td align=\"center\">$link_1<img src=\"./skin/$board_info[skin]/img/not_thumbnail.gif\" width=\"$width\" border=\"0\" height=\"$height\" align=\"center\">$link_2</td></tr></table>";
		}
		echo "<td width=\"\" align=\"left\" valign=\"top\">
			<table width=\"$table_width\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr><td align=\"center\" class=\"font_kr\"><font color=\"#FF7E00\"><b>".$i."</b>위</font>&nbsp;<font color=\"#2D8F26\"><b>".$view['recommend']."</b>점</font></td></tr>
				<tr><td width=\"\" height=\"\" align=\"center\" valign=\"top\">".$o_img."</td></tr>
				<tr><td align=\"center\">".$link_1.$subject.$link_2."</td></tr>
				<tr><td align=\"center\">".$link_1.$view['name'].$link_2."</td></tr>
			</table></td>";
		$i++;
	}
	//== 검색결과가 없을시
	if($i<=1) echo "<td align=\"center\" class=\"font_kr\">".$Now_Date."에 등록된 이미지가 없습니다.</td>";
	echo "</tr></table>";
	echo "</td></tr></table>";
	echo "</td></tr></table>";
}

//== 선택된 글 전체삭제
function aDelete($itemidx) {
	global $db, $_GET, $_POST, $b_cfg_tb;
	//== 등록된 이미지 삭제
	$sqlStr = "SELECT filename0,filename1,filename3,filename4,filename5,filename6,filename7,filename8 FROM $b_cfg_tb[1] WHERE idx='$itemidx'";
	$dels = $db->getRow($sqlStr,DB_FETCHMODE_ASSOC);
	if(DB::isError($dels)) die($dels->getMessage());
		for($i=0; $i<count($dels); $i++) {
			if($dels['filename'.$i]) {
				$delete_file = "files/".$_GET['code']."/".$dels['filename'.$i];
				if(is_file($delete_file)) unlink($delete_file);
				$delete_file_thumbnail = "files/".$_GET['code']."/thumbnail/".$dels['filename'.$i];
				if(is_file($delete_file_thumbnail)) unlink($delete_file_thumbnail);
			}
		}

	//== 에디터 파일 삭제
	$imgFolder="b_".$_GET['code']."_".$itemidx;
	$delPath=$_SERVER["DOCUMENT_ROOT"]."/eUpload/".$imgFolder;
	removeDir($delPath);

	//== 글삭제
	$msqlStr= "DELETE FROM $b_cfg_tb[1] WHERE idx='$itemidx'";
	$rst=$db->query($msqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
	//== 코멘트 삭제
	$sqlStr = "DELETE FROM $b_cfg_tb[2] WHERE board_idx='$itemidx' and code='$_GET[code]'";
	$rst=$db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
}
?>