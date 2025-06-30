<?php 
//==================================================================
//== webnics board  http://www.webnicsoft.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin' and 'sneil'
//== last modify date : 2012. 12. 30
//==================================================================
//== 게시판 기본정보 로드
include ("inc/configInc.php");

set_time_limit(0);

//== 메일발송기본정보
if($_POST['senderName']) $senderName=$_POST['senderName']; else $senderName="애니타로";
if($_POST['senderMail']) $senderMail=$_POST['senderMail']; else $senderMail="anytarot@anytarot.co.kr";

//== 메일 발송회원 구분
switch ($_POST['mailType']) {
	case 1 :
		$sqlStr = "SELECT name,email FROM cs_member WHERE userid='".$_POST['receiverId']."' AND name='".$_POST['receiverName']."' AND email='".$_POST['receiverEmail']."'";
		$sqlCount = "SELECT COUNT(idx) FROM cs_member";
	break;
	case 2 :
		$sqlStr = "SELECT name,email FROM cs_member";
		$sqlCount = "SELECT COUNT(idx) FROM cs_member";
	break;
	case 3 :
		$sqlStr = "SELECT name,email FROM cs_member WHERE mailing=1";
		$sqlCount = "SELECT COUNT(idx) FROM cs_member WHERE mailing=1";
	break;
	case 4 :
		$sqlStr = "SELECT name,email FROM cs_member WHERE";
		$sqlCount = "SELECT COUNT(idx) FROM cs_member WHERE";
		if($_POST['sex']) $addStr .= " sex=".$_POST['sex']." AND";
		if($_POST['level']) $addStr .= " level=".$_POST['level']." AND";
		if($_POST['birthDay']) $addStr .= " SUBSTRING(jumin1,3,2)=DATE_FORMAT(CURDATE(),'%m') AND";
		if($_POST['joinDay']) $addStr .= " DATE_FORMAT(signdate,'%m')=DATE_FORMAT(CURDATE(),'%m') AND";
		if($addStr) { $sqlStr .= substr($addStr,0,-3); $sqlCount .= substr($addStr,0,-3); }
	break;
}
$sqlStr .= " ORDER BY idx ASC";
//== 메일발송 전체회원
$vTotal = $db->getOne($sqlCount);
if(DB::isError($vTotal)) die($vTotal->getMessage());
if($vTotal<=0) {
	echo "<table>
						<tr><td style=\"text-align:center; font-size:9pt; font-family:굴림; color:#333333; text-decoration:none\">조건에 맞는 회원이 없습니다.<br><br><a href=\"javascript:hisgory.back();\"></td></tr>
					</table>";
	exit;
}

	$rst = $db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
	$sendTotal=1;
	while($mem_info = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		send_mail(1, $mem_info['email'], $mem_info['name'], $senderMail, $senderName, $_POST['mailSubject'], $_POST['mailContents'], "", "");
		$sendTotal++;
		if(($sendTotal % 100) == 0) sleep(3);
	}
	echo "총".$Send_Total."명에게 메일을 발송하였습니다.";

/*
//== 메일 발송
if($_POST[mailType]==1) {									//== 하나 발송
	send_mail(1, $_POST[receiverMail], $_POST[receiverName], $senderMail, $senderName, $_POST[mailSubject], $_POST[mailContents], "", "");
}else {																						//== 여러개 발송
	$rst = $db->query($sqlStr);
	if(DB::isError($rst)) die($rst->getMessage());
	$sendTotal=1;
	while($mem_info = $rst->fetchRow(DB_FETCHMODE_ASSOC)) {
		send_mail(1, $mem_info[email], $mem_info[name], $senderMail, $senderName, $_POST[mailSubject], $_POST[mailContents], "", "");
		$sendTotal++;
		if(($sendTotal % 100) == 0) sleep(3);
	}
	echo "총".$Send_Total."명에게 메일을 발송하였습니다.";
}
*/
redirect(1, "/nwebnics/wTools/mailForm.php", "총".$sendTotal."명에게 메일을 발송중입니다. 잠시 기달려 주십시오.", 5);
?>