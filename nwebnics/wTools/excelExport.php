<?php
//========================================================
//== webnics board  http://www.webnics.co.kr
//== made by webnicsoft member's 'gangster' and 'freekevin'
//== last modify date : 2017. 05. 12
//========================================================
//== 기본설정파일
include "./inc/configInc.php";

if(!member_session(1)) error_view(999, "죄송합니다. 관리자가 아니거나, 로그정보를 찾을수 없습니다.","로그인후 이용하여 보시기 바랍니다.");

$today = date("Y-m-d");

if($_GET['mode']==1) {																																	//========================== 회원목록 ===========================================
	$fileName="memberList".$today;
	$listHead = "<tr align=\"center\" bgcolor=\"#F4F4F4\"><td>아이디</td><td>성명</td><td>전자우편</td><td>우편번호</td><td>주소</td><td>일반전화</td><td>휴대전화</td><td>로그인</td><td>회원권한</td><td>관리자메모</td><td>마지막로그인</td><td>가입일</td></tr>";

}else if($_GET['mode']==2) {																													//========================== 프로그램 신청자==========================================
	//== 프로그램 정보
	$sqlSstr = "SELECT eduName FROM eduList WHERE idx=".$_GET['appIdx'];
	$tTitle = $db->getOne($sqlSstr);
	if(DB::isError($tTitle)) die($tTitle->getMessage());
	$fileName=$tTitle."_".$today;
	$listHead .= "<tr align=\"center\"><td colspan=\"9\">".$tTitle." 신청자 목록(".$today.")</td></tr>";
	$listHead .= "<tr align=\"center\"><td bgcolor=\"#F4F4F4\">성명</td><td bgcolor=\"#F4F4F4\">신청일자</td><td bgcolor=\"#F4F4F4\">신청시간</td><td bgcolor=\"#F4F4F4\">신청인원</td><td bgcolor=\"#F4F4F4\">휴대전화</td><td bgcolor=\"#F4F4F4\">일반전화</td><td bgcolor=\"#F4F4F4\">신청일자</td><td bgcolor=\"#F4F4F4\">신청상태</td><td bgcolor=\"#F4F4F4\">비 고</td></tr>";

}else if($_GET['mode']==3) {																													//========================== 아이누리 신청자==========================================
	if($_GET['rType']>1) {																																	//== 개인
		$fileName="아이누리_개인_".$today;
		$listHead .= "<tr align=\"center\"><td colspan=\"6\">군산시육아종합지원센터 실내놀이터 접수대장(개인)</td></tr>";
		$listHead .= "<tr align=\"center\"><td bgcolor=\"#F4F4F4\">순번</td><td bgcolor=\"#F4F4F4\">예약자성명</td><td bgcolor=\"#F4F4F4\">부모및인솔자</td><td bgcolor=\"#F4F4F4\">아동성명</td><td bgcolor=\"#F4F4F4\">거주지</td><td bgcolor=\"#F4F4F4\">연락처</td></tr>";
	}else {																																										//== 기관
		$fileName="아이누리_기관_".$today;
		$listHead .= "<tr align=\"center\"><td colspan=\"6\">군산시육아종합지원센터 실내놀이터 접수대장(기관)</td></tr>";
		$listHead .= "<tr align=\"center\"><td bgcolor=\"#F4F4F4\">순번</td><td bgcolor=\"#F4F4F4\">예약일자</td><td bgcolor=\"#F4F4F4\">기관명</td><td bgcolor=\"#F4F4F4\">원장성명</td><td bgcolor=\"#F4F4F4\">거주지</td><td bgcolor=\"#F4F4F4\">연락처</td></tr>";
	}
}else if($_GET['mode']==4) {																													//========================== 종전평화 서명자 목록 ==========================================
	$fileName="종전평화 서명자_목록_".$today;
	$listHead .= "<tr align=\"center\"><td colspan=\"4\">종전평화 서명자 목록</td></tr>";
	$listHead .= "<tr align=\"center\"><td bgcolor=\"#F4F4F4\">성명</td><td bgcolor=\"#F4F4F4\">주소</td><td bgcolor=\"#F4F4F4\">응원한마디</td><td bgcolor=\"#F4F4F4\">서명일자</td></tr>";
}

//========================== 회원 목록 ===========================================
if($_GET['mode']==1) {
	$sql = urldecode($_GET['sqlStr']);
	$result = $db->query($sql);
	if(DB::isError($result)) die($result->getMessage());
	$excelRst = "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\">";
	$excelRst .= $listHead;
	while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$excelRst .= "<tr align=\"center\"><td>".$view['mId']."</td><td>".$view['mName']."</td><td>".$view['email']."</td><td>".$view['zipcode']."</td><td>".$view['haddress1']." ".$view['haddress2']."</td><td>".$view['telNum']."</td><td>".$view['selNum']."</td><td>".$view['login']."</td><td>".$mLevel[$view['ulevel']]."</td><td>".$view['adminMemo']."</td><td>".$view['lastLogin']."</td><td>".$view['signDate']."</td></tr>";
	}
	$excelRst .= "</table>";
}

//========================== 프로그램 신청자 목록 ===========================================
if($_GET['mode']==2) {
	$sqlStr = stripslashes(urldecode($_GET['sqlStr']));
	$result = $db->query($sqlStr);
	if(DB::isError($result)) die($result->getMessage());
	$excelRst = "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\">";
	$excelRst .= $listHead;
	while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
		$excelRst .= "<tr align=\"center\"><td>".$view['appName']."</td><td>".$view['eduDate']."</td><td>".$view['eduTime']."</td><td style=mso-number-format:'\@'>".$view['appCnt']."</td><td style=mso-number-format:'\@'>".$view['appSel']."</td><td style=mso-number-format:'\@'>".$view['appTel']."</td><td>".$view['signDate']."</td><td>".$statusArr[$view['pStatus']]."</td><td>".$view['uContents']."</td></tr>";
	}
	$excelRst .= "</table>";

//========================== 아이누리신청자 목록 ===========================================
}else if($_GET['mode']==3) {
	$sqlStr = stripslashes(urldecode($_GET['sqlStr']));
	$result = $db->query($sqlStr);
	if(DB::isError($result)) die($result->getMessage());

	$excelRst = "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\">";
	$excelRst .= $listHead;
	$i=1;
	while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

		if($_GET['rType']>1) {																																	//== 개인
			if($view['revCnt']>1) {
				$uNames=explode(",", $view['sevName']);
				for($j=0; $j<$view['revCnt']; $j++) {
					$excelRst .= "<tr align=\"center\"><td>".$i."</td><td>".$view['revName']."</td><td>".$view['leaderName']."</td><td>".$uNames[$j]."</td><td>".$view['rAddress']."</td><td style=mso-number-format:'\@'>".$view['revTel']."</td></tr>";
					$i++;
				}
			}else {
				$excelRst .= "<tr align=\"center\"><td>".$i."</td><td>".$view['revName']."</td><td>".$view['leaderName']."</td><td>".$view['sevName']."</td><td>".$view['rAddress']."</td><td style=mso-number-format:'\@'>".$view['revTel']."</td></tr>";
			}
		}else {																																										//== 기관
			$excelRst .= "<tr align=\"center\"><td>".$i."</td><td>".$view['rDate']."</td><td>".$view['sevName']."</td><td>".$view['revName']."</td><td>".$view['rAddress']."</td><td style=mso-number-format:'\@'>".$view['revTel']."</td></tr>";
		}
		$i++;
	}
	$excelRst .= "</table>";

//========================== 종전평화 서명자 목록 ===========================================
}else if($_GET['mode']==4) {
	$sql = urldecode(stripslashes($_GET['sqlStr']));
	$result = $db->query($sql);
	if(DB::isError($result)) die($result->getMessage());

	$excelRst = "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\">";
	$excelRst .= $listHead;
	while($view = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$excelRst .= "<tr align=\"center\"><td>".$view['sName']."</td><td>".$view['aDdress']."</td><td>".$view['wordSupport']."</td><td>".$view['signDate']."</td></tr>";
	}
	$excelRst .= "</table>";
}
$realname = iconv('utf-8', 'euc-kr', $fileName);
header('Content-Type: application/vnd.ms-excel;charset=utf-8');
header('Content-Type: application/x-msexcel;charset=utf-8');
header("Content-Disposition: attachment; filename=".$realname.".xls");
header('Cache-Control: max-age=0');
header("Content-Description: PHP4 Generated Data");
?>
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 10">
<?php
echo $excelRst;
$db->disconnect();
?>